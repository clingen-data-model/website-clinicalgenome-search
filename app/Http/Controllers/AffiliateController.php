<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Affiliate as AffiliateResource;

use Auth;

use App\Filter;

/**
*
* @category   Web
* @package    Search
* @author     P. Weller <pweller1@geisinger.edu>
* @author     S. Goehringer <scottg@creationproject.com>
* @copyright  2020 ClinGen
* @license    http://www.php.net/license/3_01.txt  PHP License 3.01
* @version    Release: @package_version@
* @link       http://pear.php.net/package/PackageName
* @see        NetOther, Net_Sample::Net_Sample()
* @since      Class available since Release 1.2.0
*
* */
class AffiliateController extends Controller
{
    private $api = '/api/affiliates';
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
    public function index(Request $request, $page = 1, $size = 50)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
            $$key = $value;

        // set display context for view
        $display_tabs = collect([
            'active' => "validity",
            'title' => "Gene Curation Expert Panels",
            'scrid' => Filter::SCREEN_VALIDITY_EPS,
			'display' => "Gene-Disease Validity"
        ]);

        if (Auth::guard('api')->check())
            $user = Auth::guard('api')->user();

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_VALIDITY_EPS)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_VALIDITY_EPS);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

        return view('affiliate.index', compact('display_tabs'))
                        ->with('apiurl', $this->api)
                        ->with('pagesize', $size)
                        ->with('page', $page)
                        ->with('user', $this->user)
                        ->with('display_list', $display_list)
						->with('bookmarks', $bookmarks)
                        ->with('currentbookmark', $filter);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $page = 1, $size = 50)
    {
        // set display context for view
        $display_tabs = collect([
            'active' => "validity",
            'title' => "ClinGen Expert Panel Curations",
            'scrid' => Filter::SCREEN_VALIDITY_EP_CURATIONS,
			'display' => "Expert Panel Curations"
        ]);

        // the affiliate id is expected to be numeric.
        if (!ctype_digit($id))
            $id = 0;

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_VALIDITY_EP_CURATIONS)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_VALIDITY_EP_CURATIONS);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

        return view('affiliate.show', compact('display_tabs'))
                        ->with('apiurl', $this->api . "/${id}")
                        ->with('pagesize', $size)
                        ->with('page', $page)
                        ->with('user', $this->user)
                        ->with('display_list', $display_list)
                        ->with('bookmarks', $bookmarks)
                        ->with('currentbookmark', $filter);
    }

}
