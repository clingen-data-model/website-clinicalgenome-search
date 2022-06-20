<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

use Auth;

use App\GeneLib;

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
* @deprecated
*
* */
class RegionController extends Controller
{
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
     * Display a listing of all gene validity assertions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $size = 50)
    {
        // set display context for view
        $display_tabs = collect([
            'active' => "more",
            'title' => "ClinGen Regions"
		]);

		$user = $this->user;

        return view('region.index', compact('display_tabs', 'user'));
    }


    /**
     * Display the results of a region search.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $type = '', $region = '', $page = 1, $size = 100)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction', 'region', 'type']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "gene",
            'title' => "Gene Curations"
        ]);

		// figure out what the search bar sent you

        $region = $search[3];

		$original = $region;

		// if the region is a cytoband, convert to chromosomal location
		if (strtoupper(substr($region, 0, 3)) != 'CHR')
		{
			$client = new Client([
				'base_uri' => 'https://www.ncbi.nlm.nih.gov/projects/ideogram/data/',
				'headers' => [
					'Content-Type' => 'text/csv'
				]
			]);

			try {

                $regions = explode('-', $region);

				$response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $regions[0]]);

				$cords = json_decode($response->getBody()->getContents());

				if (isset($cords->coords[0]->bp))
					$region = 'chr' . $cords->coords[0]->bp->chrom . ':'
								. $cords->coords[0]->bp->bp->from . '-';
				else
					$region = 'INVALID';

                if (isset($regions[1]))
                {
                    // allow user to drop the second chromosome
                    if (!(is_numeric($regions[1][0]) || $regions[1][0] == 'X'  || $regions[1][0] == 'Y'))
                        $regions[1] = $cords->coords[0]->bp->chrom . $regions[1];

                    $response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $regions[1]]);

                    $seccords = json_decode($response->getBody()->getContents());

                    if (isset($seccords->coords[0]->bp))
                        $region .=  $seccords->coords[0]->bp->bp->to;
                    else
                        $region = 'INVALID';
                }
                else
                {
                    $region .=  $cords->coords[0]->bp->bp->to;
                }

			} catch (ClientException $e) {
				$region = 'INVALID';
			}
        }

        $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);

		return view('region.search', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('type', $type)
						->with('original', $original)
						->with('region', $region)
						->with('apiurl', '/api/region/search/' . $type . '/' . $region)
						->with('pagesize', $size)
						->with('page', $page)
                        ->with('user', $this->user)
                        ->with('display_list', $display_list);
    }
}
