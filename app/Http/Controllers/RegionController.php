<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegionController extends Controller
{
    //
    /**
     * Display a listing of all gene validity assertions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 0, $size = 20)
    {
        // set display context for view
        $display_tabs = collect([
            'active' => "more",
            'title' => "ClinGen Regions"
        ]);

        return view('region.index', compact('display_tabs'));
    }
}
