<?php

namespace App\Http\Controllers\Api\v1;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\LoginFormRequest;
use App\Http\Requests\api\v1\RegisterFormRequest;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Team;

class AuthController extends Controller 
{
    public function register(RegisterFormRequest $request){
        $fields = $request->validated();
        
     
        $fields['password'] =  Hash::make($fields['password']);
        $team = Team::create([
            'name' => "Equipe de " . $request->name,
        ]);
        $fields['team_id'] = $team->id;
        $fields['role'] = "Admin";
        $user = User::create($fields);
        $user->load('team');
        $token = $user->createToken($request->name);
        return [
            'user' => $user, 
            'token' => $token->plainTextToken
        ];
    }

    public function login(LoginFormRequest $request){
        $fields = $request->validated();
        $userExists = User::where('email', $request->login)->orWhere('phone', $request->login)->first();

        if(!$userExists || !Hash::check($request->password, $userExists->password)){
            return ['message' => 'The provided credentials are incorrect.', "data" => $fields];
        }
    
        $token = $userExists->createToken($userExists->name);
        $userExists->load('team');
        return [
            'user' => $userExists, 
            'token' => $token->plainTextToken,
            
        ];
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return ['message' => 'you are logged out',
        'loggedOut' => true,
        
        ];

        //return "Ok";
        // "userTokens" => $request->user(),
        //session()->flush(); // removes all session data
        // $tokenString = $request->bearerToken(); // Just the token string, no "Bearer"
        // $tokenFromRequest = PersonalAccessToken::findToken($tokenString);
    }
}