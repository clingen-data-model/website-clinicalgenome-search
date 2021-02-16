<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth;
use Session;

use App\Gene;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 201);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'This User does not exist, check your details'], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $context = $request->input('context');

        $stat = false;
        
        if (!empty($context))
        {
            $user = Auth::user();

            $gene=Gene::hgnc($context)->first();

            if ($gene !== null)
            {
                $user->genes()->sync([$gene->id], false);
                $stat = true;
            }
        }
        // return response()->json(['redirect' => $red, 'context' => $stat], 200);

        return response(['context' => $stat, 'access_token' => $accessToken])->withCookie(cookie('laravel_token',$accessToken, 0, null, null, false, false));
    }

    public function logout (Request $request)
    {
        $accessToken = auth()->user()->token();
        $token= $request->user()->tokens->find($accessToken);
        $token->revoke();
        Session::flush();
        //Auth::logout();
        return response(['status' => true, 'access_token' => null]);
    }
}