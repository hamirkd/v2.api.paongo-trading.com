<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use Mail;

use Validator;

class PasswordResetController extends Controller
{
    /**
     * Create a new PasswordResetController instance.
     *
     * @return void
     */
    public function __construct() {

    }
    
    /**
     * Cette fonction permet de generer un token de reinitialisation
     * de mot de passe
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate_password_init_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        
        $email=$request->input('email');
        $user = User::all()->where('email',$email);
        if($user==null){
            return response()->json([
                'message' => 'User is not existed',
                'status' => 404
            ], 404);
        }
        $token = Str::random(99);
        PasswordReset::create(array_merge(
            $validator->validated(),
            ['token' => $token],
            ['created_at'=> new \DateTime('NOW')]
        ));
        // Email pour envoyer le lien de reinitialisation
        $data = [
            'subject' => "",
            'email' => $request->email,
            'content' => ""
          ];
        Mail::send('email-template', $data, function($message) use ($data) {
            $message->to($data['email'])
            ->subject($data['subject']);
          });
        return response()->json([
            'message' => 'A email will sending with the reinitilisation code',
            'token' => $token,
            'status' => 200
        ], 200);
    }
    
    /**
     * Verif if token is enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verif_token_enabled_to_init_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|between:2,100'
        ]);
        $token=$request->input('token');
        $passwordReset = PasswordReset::all()->where('token',$token)->first();
        if($passwordReset==null){
            return response()->json([
                "message"=>"Bad token",
                "status"=>400
            ],400);
        }
        
        return response()->json([
            'message' => 'Good token',
            'email' => $passwordReset->email,
            "status"=>200
        ], 200);
    }

     /**
     * Init user password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset_password_init(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::all()->where('email',$request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        $token=$request->input('token');
        PasswordReset::where('token',$request->token)->delete();
        // Envoie un mail pour informer que le mot de passe a été changé
        return response()->json([
            'message' => 'Password is updated',
            "status"=>201
        ], 201);
    }
}