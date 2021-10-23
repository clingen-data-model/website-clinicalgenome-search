<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jira;
use App\Nodal;

class UpdateIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:index {report=all}';

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
        $report = $this->argument('report');

        switch ($report)
        {
            case 'gene':
                echo "Refreshing gene_isca.idx...";
                $this->gene();
                echo "DONE\n";
                return;
            case 'region':
                echo "Refreshing region(38).idx files...";
                $this->region(37);
                $this->region(38);
                echo "DONE\n";
                return;
            case 'none':
            default:
                break;
        }

		echo "Invalid report type\n";
	}


    /**
     * Create the gene_isca.idx map file
     *
     */
    public function gene()
    {

        $collection = collect();

        $start = 0;

        do {

            $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation"', $start);

            //echo "\n\nstartAt: " . $results->startAt;
            //echo "\nmaxResults: " . $results->maxResults;
            //echo "\ntotal: " . $results->total;

            foreach ($results->issues as $issue)
            {
                $record = (object) $issue->fields->customFields;

                // incomplete record
                if (!isset($record->customfield_10030) || !isset($record->customfield_10157))
                    continue;

                // duplicate
                if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == 'Duplicate')
                    continue;

                //echo "Processing Symbol " . $record->customfield_10030 . "\n";

                $node = [   'label' => $record->customfield_10030,
                            'id' => $issue->key
                        ];

                $collection->push($node);

            }

            $start += $results->maxResults;

        } while ($start < $results->total);

        // sort by gene symbol
        $sorted = $collection->sortBy('label');

        // write out the file
        $handle = fopen(base_path() . '/data/gene_isca.idx', 'w');

        //content
        foreach ($sorted as $item)
            fwrite($handle, implode("\t", $item) . PHP_EOL);

        fclose($handle);
    }


    /**
     * Create the region.idx and region38.idx map files
     *
     */
    public function region($build = 37)
    {
        switch ($build)
        {
            case 37:
                $build = '';
                $region = 'customfield_10160';
                break;
            case 38:
                $region = 'customfield_10532';
                break;
            default:
                "Error, invalid build $build \n";
                return;
        }

        $collection = collect();

        $start = 0;

        do {

            $results = Jira::getIssues('project = ISCA AND issuetype in ("ISCA Gene Curation", "ISCA Region Curation")', $start);

            foreach ($results->issues as $issue)
            {

                $record = (object) $issue->fields->customFields;

                // duplicate
                if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == 'Duplicate')
                    continue;

                // won't fix
                if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == "Won't Fix")
                    continue;

                 // we groom the status a bit
                 if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == "Complete")
                    $status = 'Complete';
                else if ($issue->fields->status->name == "Open")
                    $status =  'Awaiting Review';
                else
                    $status = $issue->fields->status->name;

                //echo "Processing Record " . $issue->key . "\n";

                if ($issue->fields->issuetype->name == "ISCA Gene Curation")
                {
                    // ignore withdrawn genes
                    if (isset($record->customfield_10156) && $record->customfield_10156 == 'withdrawn')
                        continue;

                    $node = [   'location' => $record->$region ?? '', 'id' => $issue->key,
                                'type' => $issue->fields->issuetype->name,
                                'status' => $status,
                                'label' => '', //$record->customfield_10030,
                                'ts' => ($status == 'Complete' ? ($record->customfield_10166->value ?? 'None') : 'N/A'),
                                'hs' => ($status == 'Complete' ? ($record->customfield_10165->value ?? 'None') : 'N/A'),
                                'pli' => $record->customfield_11635 ?? '',
                                'omim' => basename($record->customfield_10147 ?? '')

                            ];
                }
                else    // Region
                {
                    $node = [   'location' => $record->$region ?? '', 'id' => $issue->key,
                                'type' => $issue->fields->issuetype->name,
                                'status' => $status,
                                'label' => $record->customfield_10202,
                                'ts' => ($status == 'Complete' ? ($record->customfield_10166->value ?? 'None') : 'N/A'),
                                'hs' => ($status == 'Complete' ? ($record->customfield_10165->value ?? 'None') : 'N/A'),
                                'pli' => $record->customfield_11635 ?? '',
                                'omim' => basename($record->customfield_10147 ?? '')
                            ];
                }

                $collection->push($node);

            }

            $start += $results->maxResults;

        } while ($start < $results->total);

        // sort by isca issue in decending order
        $sorted = $collection->sortByDesc('id');

        // write out the file
        $handle = fopen(base_path() . '/data/region' . $build . '.idx', 'w');

        //content
        foreach ($sorted as $item)
            fwrite($handle, implode("\t", $item) . PHP_EOL);

        fclose($handle);

    }

}
