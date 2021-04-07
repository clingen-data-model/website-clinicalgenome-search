<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

use Auth;
use App\Gene;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle any post-authentication tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
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

        if ($request->ajax()) {
			$red = '';
			/*switch (Auth::user()->role)
			{
				case 0:		// guest
					$red = '/';
					break;
				case 1:		// administrator
					$red = '/adm/home';
					break;
				case 2:		// curator
					$red = '/home/dashboard';
					break;
				case 9:		// Locked
					$red = '/';
					break;
				default:
					break;
			}*/
            return response()->json(['redirect' => $red, 'context' => $stat], 200);
        }
        
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }
    
    
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'error' => Lang::get('auth.failed')
            ], 401);
        }
        
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }
    
    protected function loggedOut(Request $request) {

        return redirect()->back();

    }
}
