<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AjoutMembreRequest;
use App\Models\Team;
use App\Mail\TeamInvitationMail;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Mail;

class EquipeController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->team;
        // dd($team->users());
        $authUser = $team->users()->where('user_id', $request->user()->id)->first();
        if (!$authUser) {
            $team->users()->attach($request->user()->id, [
                'role' => 'admin',
            ]);
        }
        $invitations = TeamInvitation::where('user_id', $request->user()->id)
            ->where('status', 'en attente')
            ->with('inviter')
            ->get();

        $mesEquipes = TeamUser::where('user_id', $request->user()->id)
            ->where('role', 'membre')
            ->with('team')
            ->get()
            ->pluck('team');
        
        return view('equipe.index', [
            'liste_membres' => $team->users,
            'invitations' => $invitations,
            'mesEquipes' => $mesEquipes,
        ]);
    }

    public function accepterInvitation(Request $request)
    {
        $validated = $request->validate([
            'inviter' => ['required', 'string', 'exists:users,id'],
            'accepterInvitation' => ['required', 'in:en attente,accepter,refuser']
        ]);
        $inviter = User::where('id', $validated['inviter'])->firstOrFail();
        $team = $inviter->team; // à adapter au nom de votre relation
        abort_unless($team, 404);

         if($inviter->id == $request->user()->id) {
            return back()->withErrors(['inviter' => 'Vous êtes déjà membre de cette équipe.']);
        }
        $invitation = TeamInvitation::where('team_id', $team->id)
            ->where('user_id', $request->user()->id)
            ->first();
        if ($validated['accepterInvitation'] === 'refuser') {
            if ($invitation) {
                $invitation->status = 'refuser';
                $invitation->save();
            }
            return back()->with('success', 'Vous avez refusé l\'invitation à rejoindre l\'équipe.');
        }else if ($validated['accepterInvitation'] === 'accepter') {
            if ($invitation) {
                $invitation->status = 'accepter';
                $invitation->save();
            }
        } else {
            return back()->withErrors(['accepterInvitation' => 'Valeur invalide pour accepter/refuser l\'invitation.']);
        }

        $team->users()->syncWithoutDetaching([
            $request->user()->id => ['role' => 'membre'],
        ]);
        return back()->with('success', 'Le membre a été ajouté à votre équipe.');
    }

    public function invite(Request $request)
    {
        $user1 = auth()->user();
        $team = $user1->team; // adaptez selon votre relation
        if(!$team) {
            return back()->withErrors(['team' => 'Vous n\'avez pas d\'équipe.']);
        }
        $user2 = User::where('email', $request->membre)->first();
        if (!$user2) {
            return back()->withErrors(['membre' => 'Utilisateur non trouvé.']);
        }

        // Exemple avec role stocké sur la table pivot
        abort_unless(
            $team->users()
                ->where('users.id', $user1->id)
                ->wherePivot('role', 'admin')
                ->exists(),
            403
        );

        if($team->users()->whereKey($user2->id)->exists()) {
            return back()->withErrors(['membre' => 'Déjà membre.']);
        }

        $invitation = TeamInvitation::updateOrCreate(
            ['team_id' => $team->id, 'user_id' => $user2->id],
            [
                'invited_by' => $user1->id,
                'status' => 'en attente',
                // 'expires_at' => now()->addDays(7),
            ]
        );

        // Mail::to($user2->email)->send(new TeamInvitationMail($invitation));

        return back()->with('success', 'Invitation envoyée.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $membre = User::find($request->membre);
        if (!$membre) {
            return Redirect::back()->withErrors(['membre' => 'Membre non trouvé.']);
        }
        if ($membre->id == $request->user()->id) {
            return Redirect::back()->withErrors(['membre' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }
        $team = $request->user()->team;
        $team->users()->detach($membre->id);
        return back()->with('success', 'Le membre a été supprimé de votre équipe.');
    }
}
