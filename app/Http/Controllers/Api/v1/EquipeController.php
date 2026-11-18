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
    public function index(Request $request)
    {
        $team = $request->user()->team;
        // dd($team->users());
        $authUser = $team->users()->where('user_id', $request->user()->id)->first();
        if (!$authUser) {
            $team->users()->attach($request->user()->id, [
                'role' => 'admin',
            ]);
        }
        $demandeEnvoye = TeamInvitation::where("invited_by", $request->user()->id)->where("status", "!=", "accepted")->get();
        $demandeRecu = TeamInvitation::where("user_id", $request->user()->id)->where("status", "en attente")->get();
        
        return[
            'liste_membres' => $team->users,
            'demandes_envoyes' => $demandeEnvoye,
            'demande_recus' => $demandeRecu,
        ];
    }

    public function accepterInvitation(Request $request)
    {
        $validated = $request->validate([
            'inviter' => ['required', 'string', 'exists:users,id'],
            'accepterInvitation' => ['required', 'in:en attente,accepter,refuser']
        ]);
        $inviter = User::where('id', $validated['inviter'])->firstOrFail();
        if(!$inviter){
            return ['message' => "Demandeur introuvable"];
        }
        
        $team = $inviter->team; // à adapter au nom de votre relation
        if(!$team){
            return ['message' => "Equipe introuvable"];
            }
            
            if($inviter->id == $request->user()->id) {
                return ['message' => 'Vous êtes déjà membre de votre équipe.'];
                }
                $invitation = TeamInvitation::where('team_id', $team->id)
                ->where('user_id', $request->user()->id)
                ->first();
        if ($invitation) {
            if ($validated['accepterInvitation'] === 'refuser') {
                $invitation->status = 'refuser';
                $invitation->save();
                return ['message' => 'Vous avez refusé l\'invitation à rejoindre l\'équipe de ' . $team->name];
            }else if ($validated['accepterInvitation'] === 'accepter') {
                    $invitation->status = 'accepter';
                    $invitation->save();
                    $team->users()->syncWithoutDetaching([
                        $request->user()->id => ['role' => 'membre'],
                    ]);
                    return ['message' => 'Vous avez accepter de rejoindre l\'equipe de ' . $team->name];
            } else {
                return ['message' => 'Valeur invalide pour accepter/refuser l\'invitation.'];
            }
        }
    }


    public function invite(Request $request)
    {
        $user1 = $request->user();
        $team = $user1->team; // adaptez selon votre relation
        if(!$team) {
            return back()->withErrors(['team' => 'Vous n\'avez pas d\'équipe.']);
            }
            $user2 = User::where('email', $request->user_email_or_phone)->orWhere('phone', $request->user_email_or_phone)->first();
        if (!$user2) {
            return ['message' => 'Utilisateur non trouvé.'];
        }
        
        // Exemple avec role stocké sur la table pivot
        if($team->users()
            ->where('users.id', $user1->id)
            ->wherePivot('role', 'admin')
            ->exists()){

            }else{
                return false;
            }

        $dejaMembre = TeamInvitation::where('invited_by', $request->user()->id)->where('user_id', $user2->id)->where('status', "accepter")->first();
        if($dejaMembre){
            return ["message" => "Déjà membre."];
        }
        $inv = '';
       try {
                $invitation = TeamInvitation::where('team_id', $team->id)
                    ->where('user_id', $user2->id)
                    ->where('invited_by',  $user1->id)
                    ->where('status', 'en attente')
                    ->first();

                if(!$invitation){
                    $inv = TeamInvitation::create(['team_id' => $team->id, 
                    'invited_by' =>  $user1->id, 
                    'user_id' => $user2->id, 
                    'status' => 'en attente']);
                    }

            } catch (\Throwable $e) {
                //Gérez l’erreur, par exemple :
                report($e);
                return ['message' => "1 Impossible d'envoyer l'invitation."];
            }
            if($inv){
                return [
                    'invitation' => $inv,
                    'message' =>'Invitation envoyée.',
                    'success' => true,
                ];
            }else{
                return [
                    'success' => false,
                    'message' => "Impossible d'envoyer l'invitation.",
                ];
            }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $membre = User::find($request->id);
        if (!$membre) {
            return ['message' => 'Membre non trouvé.'];
        }
        if ($membre->id == $request->user()->id) {
            return ['message' => 'Vous ne pouvez pas supprimer votre propre équipe.'];
            }
            $team = $request->user()->team;
            $invitation = '';
            try{
                $invitation = TeamInvitation::where('team_id', $team->id)
                ->where('user_id', $membre->id)
                ->where('invited_by', $request->user()->id)
                ->first();
            if($invitation){
                $invitation->forceDelete();
            }else{
                return ['message' => "Cette invitation n'existe pas"];
            }
                
        }catch(\Throwable $e){
            report($e);
        }
        $team->users()->detach($membre->id);
        $message = "Le membre a été supprimé de votre équipe.";
        return ['success' => true, "message" => $message ];
    }
}
