<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;

use App\Panel;

class UpdateAffiliates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:affiliates';

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

        echo "Updating affiliation data from local file ...";

        $file = "/home/pweller/Projects/website-clinicalgenome-search/data/Affiliations.xlsx";

        $worksheets = (new Excel)->toArray($file);

        foreach ($worksheets[0] as $row)
        {
            if ($row[0] == "Affiliation Full Name")
                continue;

            if (!empty($row[9]) && is_numeric($row[9]))
            {
    
                $panel = Panel::affiliate((int) $row[9])->first();

                $id = (int) $row[1];
                if ($panel !== null)
                {
                    $panel->update(['alternate_id' => $id]);
                }
            }
            else if (!empty($row[7]) && is_numeric($row[7]))
            {
                $panel = Panel::affiliate((int) $row[7])->first();

                $id = (int) $row[1];

                if ($panel !== null)
                {
                    $panel->update(['alternate_id' => $id]);
                }
            }
        }

        echo "DONE\n";
    }

}
