<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Mail;

class EquipeController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->team;
        $authUser = $team->users()->where('user_id', $request->user()->id)->first();
        // dd($authUser);
        if (!$authUser) {
            $team->users()->attach($request->user()->id, [
                'role' => 'admin',
            ]);
        }
        return view('equipe.index', [
            'liste_membres' => $team->users,
        ]);
    }

    public function addMember(Request $request)
    {
        $validated = $request->validate([
            'membre' => ['required', 'email', 'exists:users,email'],
        ]);

        $team = $request->user()->team; // à adapter au nom de votre relation
        abort_unless($team, 404);

        $membre = User::where('email', $validated['membre'])->firstOrFail();
       
        
         if($membre->id == $request->user()->id) {
            return back()->withErrors(['membre' => 'Vous êtes déjà membre de cette équipe.']);
        }
        $team->users()->syncWithoutDetaching([
            $membre->id => ['role' => 'membre'],
        ]);

        return back()->with('success', 'Le membre a été ajouté à votre équipe.');
    }

    public function invite(User $user2)
    {
        $user1 = auth()->user();
        $team = $user1->team; // adaptez selon votre relation

        abort_unless($team, 404);

        // Exemple avec role stocké sur la table pivot
        abort_unless(
            $team->users()
                ->where('users.id', $user1->id)
                ->wherePivot('role', 'admin')
                ->exists(),
            403
        );

        abort_if($team->users()->whereKey($user2->id)->exists(), 409, 'Déjà membre.');

        $invitation = TeamInvitation::updateOrCreate(
            ['team_id' => $team->id, 'user_id' => $user2->id],
            [
                'invited_by' => $user1->id,
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]
        );

        Mail::to($user2->email)->send(new TeamInvitationMail($invitation));

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
