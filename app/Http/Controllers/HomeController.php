<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $display_tabs = collect([
                    'active'                            => "home",
                    'query'                             => "",
                    'counts'    => [
                        'dosage'                        => "1434",
                    'gene_disease'          => "500",
                    'actionability'         => "270",
                    'variant_path'          => "300"]
            ]);
        return view('home', compact('display_tabs'));
    }
}
