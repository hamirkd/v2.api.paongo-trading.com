<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PasswordReset;
use Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','password-forget','password-init','password-verify']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'telephone' => 'required|string|between:4,18'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            "status"=>201
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // les valeurs qui peuvent etre modifier
        // die("DAO");
        //  $user = User::all()->where('email',Auth::user()->email);
         
         $user = Auth::user();
        
        // $user->update($request->all());
        // die(Auth::user()->name);
        $user->first_name=$request->input('first_name');
        $user->last_name=$request->input('last_name');
        $user->telephone=$request->input('telephone');
        if($request->input('occupation'))
        $user->occupation=$request->input('occupation');
        if($request->input('physical_address'))
        $user->physical_address=$request->input('physical_address');
        if($request->input('town'))
        $user->town=$request->input('town');
        if($request->input('country'))
        $user->country=$request->input('country');
        if($request->input('postal_code'))
        $user->postal_code=$request->input('postal_code');
        if($request->input('s_first_name'))
        $user->s_first_name=$request->input('s_first_name');
        if($request->input('s_last_name'))
        $user->s_last_name=$request->input('s_last_name');
        if($request->input('s_town'))
        $user->s_town=$request->input('s_town');
        if($request->input('s_country'))
        $user->s_country=$request->input('s_country');
        if($request->input('s_postal_code'))
        $user->s_postal_code=$request->input('s_postal_code');
        if($request->input('s_telephone'))
        $user->s_telephone=$request->input('s_telephone');
        if($request->input('description'))
        $user->description=$request->input('description');
        $user->save();
        return response()->json([
            'message' => 'User updated',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            "role"=>auth()->user()->role
        ]);
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
        PasswordReset::create(array_merge(
            $validator->validated(),
            ['token' => Str::random(99)]
        ));
        // Email pour envoyer le lien de reinitialisation
        
        return response()->json([
            'message' => 'A email will sending with the reinitilisation code',
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
        $passwordReset = PasswordReset::all()->where('token',$token);
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
        $user = User::all()->where('email',$request->email);
        $user->password = bcrypt($request->password);
        $user->save();
        $token=$request->input('token');
        PasswordReset::all()->where('token',$request->token)->delete();
        // Envoie un mail pour informer que le mot de passe a été changé
        return response()->json([
            'message' => 'Password is updated',
            "status"=>201
        ], 201);
    }

}