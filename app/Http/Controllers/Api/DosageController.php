<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Dosage as DosageResource;
use App\Http\Resources\Region as RegionResource;
use App\Http\Resources\Acmg59 as Acmg59Resource;
use App\Http\Resources\Search as SearchResource;

use App\GeneLib;
use App\Gene;
use App\Omim;
class DosageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::dosageList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
                                        'curated' => true ]);
                                    
        if ($results === null)
            return GeneLib::getError();

        // query the regions
        $jresults = GeneLib::regionList(['page' =>  0,
                                        'pagesize' =>  "null",
                                        'sort' => 'GENE_LABEL',
                                        'direction' =>  'ASC',
                                        'search' =>  null,
                                        'curated' => false ]);

        if ($jresults === null)
            return GeneLib::getError();

        // combine the results

        return ['total' => $results->count + $jresults->count, 
                'totalNotFiltered' => $results->count + $jresults->count,
                'rows'=> DosageResource::collection($results->collection->concat($jresults->collection)),
                'ngenes' => $results->count,
                'nregions' => $jresults->count,
                'ncurations' => $results->ncurations + $jresults->ncurations];
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function region_search(ApiRequest $request, $type = '', $region = '')
    {
        $input = $request->only(['search', 'order', 'offset', 'limit', 'region', 'type']);

        $results = GeneLib::regionSearchList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
                                        'search' => $input['search'] ?? null,
                                        'type' => $type ?? '',
                                        'region' => $region ?? '',
										'curated' => true ]);

        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> SearchResource::collection($results->collection),
                'gene_count' => $results->gene_count,
                'region_count' => $results->region_count
                ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cnv(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::cnvList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
										'curated' => true ]);

        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> RegionResource::collection($results->collection),
                'nhaplo' => $results->nhaplo,
                'ntriplo' => $results->ntriplo];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function acmg59(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::acmg59List(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
										'curated' => true ]);
        
        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> Acmg59Resource::collection($results->collection),
                'nhaplo' => $results->nhaplo,
                'ntriplo' => $results->ntriplo];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expand(ApiRequest $request, $id = null)
    {

        // Check if identifier is a region...
        if (strpos($id, "ISCA-") === 0)
        {
            $region = GeneLib::dosageRegionDetail([ 'gene' => $id,
                                                    'curations' => true,
                                                    'action_scores' => true,
                                                    'validity' => true,
                                                    'dosage' => true
                                                    ]);
            if ($region === null)
            {}
//dd($region->label);
            // Jira has a lot of disease mapping options.  Deal with them.
            if (empty($region->loss_phenotype_name))   // Use name if especified
            {
                if (isset($region->loss_pheno_omim[0]))
                {
                    //$disease = Omim::omimid($region->loss_pheno_omim[0])->first();
                    $region->loss_phenotype_name = $region->loss_pheno_omim[0]['titles'];
                    $region->loss_omim = $region->loss_pheno_omim[0]['id'];
                }
            }

            if (empty($region->gain_phenotype_name))   // Use name if especified
            {
                if (isset($region->gain_pheno_omim[0]))
                {
                    $region->gain_phenotype_name = $region->gain_pheno_omim[0]['titles'];
                    $region->gain_omim = $region->gain_pheno_omim[0]['id'];
                }
            }

            return view('gene-dosage.region_expand')
                    ->with('region', $region);
        }

        

        // ...otherwise assume gene
        $gene = Gene::hgnc($id)->first();

        return view('gene-dosage.expand')
                    ->with('gene', $gene);
    }
}
