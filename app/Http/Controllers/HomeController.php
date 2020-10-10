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
}
