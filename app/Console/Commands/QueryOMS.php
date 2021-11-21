<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Panel;

class QueryOms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:oms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // for now, we're importing a json file

        foreach (['gceps', 'vceps', 'cwdgs', 'wgs'] as $target)
        {
            echo "Updating $target \n";

            try {

                //$results = file_get_contents(base_path() . '/data/wg-ep-json.json');
                $stream_opts = [
                    "ssl" => [
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ]
                ];

                $results = file_get_contents('https://clinicalgenome.org/data-pull/' . $target . '/',
                                false, stream_context_create($stream_opts));

            } catch (\Exception $e) {

                echo "\n(E001) Error retrieving panel data\n";
                return 0;
            }

            $data = json_decode($results, true);

            $typemap = [
                'gcep' => Panel::TYPE_GCEP,
                'vcep' => Panel::TYPE_VCEP,
                'working-group' => Panel::TYPE_WG
            ];

            /*
                https://clinicalgenome.org/data-pull/cwdgs/     This JSON file has all of the CDWGs with group information and all of their members
                https://clinicalgenome.org/data-pull/gceps/     This JSON file has all of the GCEPs with group information and all of their members
                https://clinicalgenome.org/data-pull/vceps/     This JSON file has all of the VCEPs with group information and all of their members
                https://clinicalgenome.org/data-pull/wgs/       This JSON file has all of the Working Groups with group information and all of their members
                https://clinicalgenome.org/data-pull/members/   This JSON is a list of all of the members in the website with their organization/institutions
                https://clinicalgenome.org/data-pull/organizations/     This JSON is a list of all of the organizations/institutions in the website

            */

            foreach ($data as $entry) {

                //$panel = Panel::affiliate($entry['affiliate_id'])->first();

                $alternate_id = (intval($entry['affiliation_id']) >= 40000 && intval($entry['affiliation_id']) < 50000) ?
                                        intval($entry['affiliation_id']) - 30000 : null;

                //echo $entry['affiliation_id'] . "\n";
                Panel::updateOrCreate(
                    ['affiliate_id' => $entry['affiliation_id']],
                    [
                        'alternate_id' => $alternate_id,
                        'title' => $entry['title'],
                        'name' => $entry['title_short'] ?? "",
                        'title_short' => $entry['title_short'] ?? "",
                        'title_abbreviated' => $entry['title_abbreviated'] ?? "",
                        'summary' => ($entry['summary'] != "" ? $entry['summary'] :
                                                $entry['description']),
                        'affiliate_type' => $entry['affiliate_type'],
                        'affiliate_status' => [
                            'gene' => $entry['affiliate_status_gene'],
                            'gene_date_step_1' => $entry['affiliate_status_gene_date_step_1'],
                            'gene_date_step_2' => $entry['affiliate_status_gene_date_step_2'],
                            'variant' => $entry['affiliate_status_variant'],
                            'variant_date_step_1' => $entry['affiliate_status_variant_date_step_1'],
                            'variant_date_step_2' => $entry['affiliate_status_variant_date_step_2'],
                            'variant_date_step_3' => $entry['affiliate_status_variant_date_step_3'],
                            'variant_date_step_4' => $entry['affiliate_status_variant_date_step_4']
                        ],
                        'cdwg_parent_name' => $entry['cdwg_parent_name'],
                        'member' => $entry['member'],
                        'status' => 1,
                        'type' => $typemap[$entry['affiliate_type']] ?? 0
                    ]
                );
            }

            echo "$target Update Complete\n";
        }
    }
}
