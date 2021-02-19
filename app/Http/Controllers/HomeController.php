<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

use Auth;
use Carbon\Carbon;

use App\Gene;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

        }


        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });
        
        return view('home', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function preferences()
    {

        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $notification = $user->notification;

        }

        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });
        
        return view('dashboard-preferences', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user', 'notification'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {

        $display_tabs = collect([
            'active'                            => "home",
            'title' => "titlehere"
        ]);

        //print_r($display_tabs);
        //exit();
        return view('dashboard-profile', compact('display_tabs'));
    }

    /**
     * Providing legacy home query a landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('error.message-standard')
        ->with('title', 'Sorry, this page has moved...')
        ->with('message', 'Please use the search or navigation bar above.')
        ->with('back', url()->previous());

    }


    /**
     * Temporary update method for dashboard prototype.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $input = $request->only(['primary_email', 'secondary_email', 'frequency', 'summary', 'first']);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $notification = $user->notification;

        }

        //update the notifications
        $notification->primary = ['email' => $input['primary_email']];
        $notification->secondary = ['email' => $input['secondary_email']];
        $notification->frequency = ['first' => $input['first'], 'frequency' => $input['frequency'], 'summary' => $input['summary']];

        $notification->save();

        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });
        
        return view('dashboard-preferences', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user', 'notification'));

    }
}
