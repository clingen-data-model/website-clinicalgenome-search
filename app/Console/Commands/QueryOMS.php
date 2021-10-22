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

        try {

            $results = file_get_contents(base_path() . '/data/wg-ep-json.json');
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

        foreach ($data as $entry) {
            //dd($entry);
            //$panel = Panel::affiliate($entry['affiliate_id'])->first();

            //echo $entry['affiliation_id'] . "\n";
            Panel::updateOrCreate(
                ['affiliate_id' => $entry['affiliation_id']],
                [
                    'title' => $entry['title'],
                    'name' => $entry['title_short'] ?? "",
                    'title_short' => $entry['title_short'] ?? "",
                    'title_abbreviated' => $entry['title_abbreviated'] ?? "",
                    'summary' => $entry['summary'],
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

        echo "Update Complete\n";
    }
}
