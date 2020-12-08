<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

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
    //
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

        return view('region.index', compact('display_tabs'));
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

				$response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $region]);

				$cords = json_decode($response->getBody()->getContents());

				if (isset($cords->coords[0]->bp))
					$region = 'chr' . $cords->coords[0]->bp->chrom . ':'
								. $cords->coords[0]->bp->bp->from . '-' . $cords->coords[0]->bp->bp->to;
				else
					$region = 'INVALID';

			} catch (ClientException $e) {
				$region = 'INVALID';
			}
		}

		return view('region.search', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('type', $type)
						->with('original', $original)
						->with('region', $region)
						->with('apiurl', '/api/region/search/' . $type . '/' . $region)
						->with('pagesize', $size)
						->with('page', $page);
    }
}
