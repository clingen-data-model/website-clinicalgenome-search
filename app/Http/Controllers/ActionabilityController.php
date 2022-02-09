<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

require app_path() .  '/Helpers/helper.php';

use App\GeneLib;
use App\Filter;

/*
    +++++++++++++++++++++++++++++++++++++++++++++++
        Actionability will redirect to ACI pages and reports
    +++++++++++++++++++++++++++++++++++++++++++++++
*/

class ActionabilityController extends Controller
{
    private $api = '/api/actionability';
    private $user = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('api')->check())
                $this->user = Auth::guard('api')->user();
            return $next($request);
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // redirect to ACI
        $url = env('CG_URL_CURATIONS_ACTIONABILITY', 'http://www.clinicalgenome.org');

        return redirect()->away($url);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // redirect to ACI
        $url = env('CG_URL_CURATIONS_ACTIONABILITY', 'http://www.clinicalgenome.org');

        return redirect()->away($url);
    }


    /**
     * Display a listing of all clinical actionability assertions.
     *
     * @return \Illuminate\Http\Response
     */
    public function report_index(Request $request, $page = 1, $size = 50)
    {
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort','search', 'col_search', 'col_search_val', 'context']) as $key => $value)
			$$key = $value;


		// set display context for view
        $display_tabs = collect([
            'active' => "actionability",
            'title' => "ClinGen Clinical Actionability Curations",
            'scrid' => Filter::SCREEN_ACTIONABILITY_CURATIONS,
			'display' => "Clinical Actionability"
        ]);

        $col_search = collect([
            'col_search' => $request->col_search,
            'col_search_val' => $request->col_search_val,
        ]);

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_ACTIONABILITY_CURATIONS)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_ACTIONABILITY_CURATIONS);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

		return view('clinical-actionability.index', compact('display_tabs'))
						->with('apiurl', $this->api)
						->with('pagesize', $size)
						->with('page', $page)
                        ->with('col_search', $col_search)
                        ->with('user', $this->user)
                        ->with('display_list', $display_list)
						->with('bookmarks', $bookmarks)
                        ->with('context', $context ?? null)
                        ->with('currentbookmark', $filter);
    }


}
