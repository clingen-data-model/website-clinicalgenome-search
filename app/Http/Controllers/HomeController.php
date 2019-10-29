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
        $query = '
            MATCH (n:Gene) 
            RETURN n,
            n.iri as iri,
            n.symbol as symbol
            LIMIT 100';

       $items = Cypher::run($query);

       //dd($items);
       $collection = collect();
       
       //echo "<pre>";
        foreach($items->records() as $item) {

            $collect = (object)[
                    'iri'           => $item->value('iri'),
                    'symbol'        => $item->value('symbol')
            ];

            //$collect = $item
            //$collect = collect($collect);
            $collection->push($collect);
            //print_r($items);
            //print_r($item);
             //echo ."<br/>";
         }
         $collection->all();
         //print_r($collection);
         

            $display_tabs = collect([
                    'active'                            => "home",
                    'query'                             => "",
                    'counts'    => [
                        'dosage'                        => "1434",
                    'gene_disease'          => "500",
                    'actionability'         => "270",
                    'variant_path'          => "300"]
            ]);

         //print_r($display_tabs);
         //exit();
        return view('home', compact('display_tabs', 'collection', 'items'));
    }
}
