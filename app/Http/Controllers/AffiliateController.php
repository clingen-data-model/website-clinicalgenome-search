<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Affiliate as AffiliateResource;

use Auth;

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
            'title' => "Gene Curation Expert Panels"
        ]);

        if (Auth::guard('api')->check())
            $user = Auth::guard('api')->user();

        $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);

        return view('affiliate.index', compact('display_tabs'))
                        ->with('apiurl', $this->api)
                        ->with('pagesize', $size)
                        ->with('page', $page)
                        ->with('user', $this->user)
                        ->with('display_list', $display_list);

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
            'title' => "ClinGen Expert Panel Curations"
        ]);

        // the affiliate id is expected to be numeric.
        if (!ctype_digit($id))
            $id = 0;

        $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);

        return view('affiliate.show', compact('display_tabs'))
                        ->with('apiurl', $this->api . "/${id}")
                        ->with('pagesize', $size)
                        ->with('page', $page)
                        ->with('user', $this->user)
                        ->with('display_list', $display_list);
    }

}
