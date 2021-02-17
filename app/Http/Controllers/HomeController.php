<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

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
                'active'                            => "home",
                'title' => "titlehere"
            ]);

         //print_r($display_tabs);
         //exit();
        return view('home', compact('display_tabs'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function preferences()
    {

        $display_tabs = collect([
            'active'                            => "home",
            'title' => "titlehere"
        ]);

        //print_r($display_tabs);
        //exit();
        return view('dashboard-preferences', compact('display_tabs'));
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
}
