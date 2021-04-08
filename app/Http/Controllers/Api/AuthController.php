<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use App\Notifications\RegisterActivate;

use Illuminate\Support\Str;

use Auth;
use Session;
use Validator;
use Carbon\Carbon;

use App\Gene;
use App\User;
use App\Notification;

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


    /**
     * Register a new account.
     *
     * @return json
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|max:55',
            'lastname' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['name'] = $validatedData['firstname'] . ' ' . $validatedData['lastname'];
        $validatedData['organization'] = $request->input('organization');
        $validatedData['preferences'] = ['display_list' => '25'];
        $validatedData['password'] = Hash::make($request->password);
        $validatedData['activation_token'] = Str::random(60);
        $user = User::create($validatedData);

        // for now, keep this separate as the interersts feature evolve
        $user->profile = ['interests' => []];
        $user->save();
    
        //$accessToken = $user->createToken('authToken')->accessToken;

        // create a new notification recorrd for the user
        $notification = new Notification();
        $notification->primary = ['email' => $validatedData['email']];
        $user->notification()->save($notification);

        $context = $request->input('context');

        $stat = false;

        // send out email verification
        $user->notify(new RegisterActivate($user));
        
        // add the followed gene for this session
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

        /*if (empty($request->input('remember')))
            return response(['context' => $stat, 'access_token' => $accessToken, 'user' => $user->name])->withCookie(cookie('laravel_token',$accessToken, 0, null, null, false, false));
        else
            return response(['context' => $stat, 'access_token' => $accessToken, 'user' => $user->name])->withCookie(cookie('laravel_token',$accessToken));
        */

        return response(['message' => 'Email Confirmation Sent', 'context' => $stat, 'user' => $user->name], 201);
    }


    /**
     * Confirm an email and activate account.
     *
     * @return json
     */
    public function signupActivate(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->first();
        if ($user === null) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }

        if ($user->status == User::STATUS_ACTIVE)
        {
            return redirect('/dashboard/active');
        }

        if ($user->status != User::STATUS_INITIALIZED)
        {
            return redirect('/dashboard');
        }

        $user->status = User::STATUS_ACTIVE;
        //$user->activation_token = '';
        $user->save();

        $display_tabs = collect([
            'active' => "more",
            'title' => "Dashboard"
        ]);

        $show_message = true;

        return redirect('/dashboard/active');

        //return view('dashboard.logout', compact('display_tabs', 'show_message'));
    }


    /**
     * Login to account.
     *
     * @return json
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        $loginData = request(['email', 'password']);

        $loginData['status'] = User::STATUS_ACTIVE;
        $loginData['deleted_at'] = null;

        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'Your username or password is incorrect, or you have not verified your email address.'], 400);
        }

        $tokenResult = auth()->user()->createToken('authToken');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(4);

        $token->save();

        //$accessToken = $token->accessToken;

        //$accessToken = auth()->user()->createToken('authToken')->accessToken;

        $context = $request->input('context');

        $stat = false;

        $user = Auth::user();
        
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
        

        /*if (empty($request->input('remember')))
            return response(['context' => $stat, 'access_token' => $accessToken, 'user' => $user->name])->withCookie(cookie('laravel_token',$accessToken, 0, null, null, false, false));
        else
            return response(['context' => $stat, 'access_token' => $accessToken, 'user' => $user->name])->withCookie(cookie('laravel_token',$accessToken));
        */

        if ($request->remember_me)
            return response(['context' => $stat, 'access_token' => $tokenResult->accessToken, 'user' => $user->name,
                        'token_type' => 'Bearer',
                        'expires_at' => 7]);
        else
            return response(['context' => $stat, 'access_token' => $tokenResult->accessToken, 'user' => $user->name,
                        'token_type' => 'Bearer',
                         'expires_at' => 0]);

    }


    /**
     * Log out of an account
     * 
     * @return json
     */
    public function logout (Request $request)
    {
        $accessToken = auth()->user()->token();
        $token= $request->user()->tokens->find($accessToken);
        $token->revoke();
        Session::flush();
        //Auth::logout();
        return response(['status' => true, 'access_token' => null]);
    }


    /**
     * Forgot password
     * 
     * @return json
     */
    public function forgot(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email')); /*, function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });*/
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }


    /**
     * Change a password (not used)
     */
    public function change_password(Request $request)
    {
        /*$input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);*/
    }
}