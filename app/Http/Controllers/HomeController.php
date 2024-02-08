<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

use Auth;
use Storage;
use Carbon\Carbon;

use App\Gene;
use App\Title;
use App\Report;
use App\Region;
use App\Panel;
use App\Genomeconnect;

use App\Imports\ExcelGC;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $message = null)
    {

        $display_tabs = collect([
            'active' => "more",
            'title' => "Dashboard"
        ]);

        $genes = collect();

        $diseases = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $diseases = $user->diseases;

            $notification = $user->notification;

            $reports = $user->titles;

        }
        else{

            return view('dashboard.logout', compact('display_tabs', 'message'));
        }


        // Add any followed groups
        foreach ($user->groups as $group)
        {
            switch ($group->name)
            {
                case '@AllGenes':
                    $a = ['dosage' => true, 'pharma' => true, 'varpath' => true, 'validity' => true, 'actionability' => true];
                    break;
                case '@AllDosage':
                    $a = ['dosage' => true, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => false];
                    break;
                case '@AllValidity':
                    $a = ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => true, 'actionability' => false];
                    break;
                case '@AllActionability':
                    $a = ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => true];
                    break;
                case '@AllVariant':
                    $a = ['dosage' => false, 'pharma' => false, 'varpath' => true, 'validity' => false, 'actionability' => false];
                    break;
                default:
                    $a = ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => false];
                    break;

            }

            $gene = new Gene(['name' => $group->display_name,
                                'hgnc_id' => $group->search_name,
                                'activity' => $a,
                                'date_last_curated' => ''
                            ]);

            $genes->prepend($gene);
        }

        // do a little self repair
        if ($user->profile === null)
            $user->update(['profile' => ['interests' => []]]);

        if (!isset($user->profile['interests']))
        {
            $p = $user->profile;
            $p['interests'] = [];
            $user->update(['profile' => $p]);
        }

        foreach ($user->panels as $panel)
        {
            $gene = new Gene(['name' => $panel->smart_title,
                                'hgnc_id' => '!' . $panel->ident,
                                'activity' => ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => false],
                                'type' => 4,
                                'date_last_curated' => ''
                            ]);

            $genes->prepend($gene);
        }

        $system_reports = $reports->where('type', Title::TYPE_SYSTEM_NOTIFICATIONS)->count();
        $user_reports = $reports->where('type', Title::TYPE_USER)->count();
        $shared_reports = $reports->where('type', Title::TYPE_SHARED)->count();

        // default to user reports
        $reports = $reports->where('type', Title::TYPE_USER);

        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) ($gene->activity === null ? 0 : in_array(true, $gene->activity));
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });

        $gceps = Panel::gcep()->blacklist(['40018', '40019', '40058'])->get()->sortBy('title_short', SORT_NATURAL | SORT_FLAG_CASE);
        $vceps = Panel::vcep()->blacklist(['4acafdd5-80f3-47f0-8522-f4bd04da175f'])->get()->sortBy('title_short', SORT_NATURAL | SORT_FLAG_CASE);


        $gcs = Genomeconnect::with('gene')->get();

        return view('home', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user',
                    'notification', 'reports', 'system_reports', 'user_reports', 'shared_reports',
                    'gceps', 'vceps', 'gcs', 'diseases'));
    }


    /**
     *
     * Show the ftp downloads page.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloads(Request $request)
    {
        $filelist = [
            'ClinGen recurrent CNV .aed file V1.1-hg19.aed',
            'ClinGen recurrent CNV .aed file V1.1-hg38.aed',
            'ClinGen recurrent CNV .bed file V1.1-hg19.bed',
            'ClinGen recurrent CNV .bed file V1.1-hg38.bed',
            'ClinGen_gene_curation_list_GRCh37.tsv',
            'ClinGen_gene_curation_list_GRCh38.tsv',
            'ClinGen_haploinsufficiency_gene_GRCh37.bed',
            'ClinGen_haploinsufficiency_gene_GRCh38.bed',
            'ClinGen_region_curation_list_GRCh37.tsv',
            'ClinGen_region_curation_list_GRCh38.tsv',
            'ClinGen_triplosensitivity_gene_GRCh37.bed',
            'ClinGen_triplosensitivity_gene_GRCh38.bed',
            'README'
        ];

        // set display context for view
        $display_tabs = collect([
            'active' => "downloads"
        ]);

        return view('downloads.index', compact('display_tabs'))
        ->with('filelist', $filelist)
            ->with('user', $this->user);
    }


    
    /**
    *
    * Show the ftp downloads page.
    *
    * @return \Illuminate\Http\Response
    */
    public function gc_upload(Request $request)
    {     
        if(!$request->hasFile('file'))
            dd("Null file upload");

        $file = $request->file('file');

        $filename = $file->getClientOriginalName();

        Storage::disk('local')->put('genomeconnect/'.$filename, file_get_contents($file));

        $worksheets = (new ExcelGC)->toArray("/home/pweller/Projects/website-clinicalgenome-search/data/GCTEST.xlsx");

        // process the first worksheet tab
        foreach ($worksheets[0] as $row)
        {
            $symbol = $row[0];

            if (empty($symbol))
                continue;

            $gene = Gene::name($symbol)->first();

            if ($gene === null) // check previous and alias
            {
                $gene = Gene::previous($symbol)->first();

                if ($gene === null)
                {
                    $gene = Gene::alias($symbol)->first();
                }
            }

            if ($gene === null)
            {
                // skip over comments of unknown genes
                continue;
            }

            $gc = $gene->genomeconnect;

            if ($gc == null)
            {
                $gc = new Genomeconnect( ['status' => Genomeconnect::STATUS_INITIALIZED]);
                $gene->genomeconnect()->save($gc);
            }
            
            //breturn redirect('/dashboard');
            return response()->json(['success' => 'true',
                                'status_code' => 200,
                                'message' => "File Processed"],
                                200);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function preferences()
    {

        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $notification = $user->notification;

        }

        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });

        return view('dashboard-preferences', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user', 'notification'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reports()
    {

        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $reports = $user->titles;

        }

        $system_reports = $reports->where('type', Title::TYPE_SYSTEM_NOTIFICATIONS)->count();
        $user_reports = $reports->where('type', Title::TYPE_USER)->count();
        $shared_reports = $reports->where('type', Title::TYPE_SHARED)->count();

        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });

        return view('dashboard.reports', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user', 'reports',
                            'system_reports', 'user_reports', 'shared_reports'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show_report(Request $request, $id = null)
    {

        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $records = new Collection;
        $params = [];

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $title = $user->titles->where('ident', $id)->first();

            if ($title === null)
            {
                return view('dashboard.noreport');
            }
            else
            {
                foreach($title->reports as $report)
                {
                    $changes = $report->run();

                    if ($changes->isNotEmpty())
                        foreach($changes as $change)
                        {
                            $records->push($change);
                        }

                        // it is eaaier to catch the parameters here than in the view
                    $params[] = ['start_date' => $report->start_date, 'stop_date' => $report->stop_date,
                                'genes' => $report->filters['gene_label']];
                }

                // update last run date
                $title->update(['last_run_date' => Carbon::now()]);

            }


        }

        $reports = $records;

        if (Auth::guard('api')->check())
        {
            $total = $genes->count();
            $curations = $genes->sum(function ($gene) {
                        return (int) ($gene->activity === null ? 0 : in_array(true, $gene->activity));
                    });
            $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });
        }
        else
        {
            $genes = null;
            $total = 0;
            $curations = 0;
            $recent = 0;
        }

        return view('dashboard.run', compact('display_tabs', 'genes', 'total', 'curations',
                        'recent', 'user', 'reports', 'title', 'params'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view(Request $request, $id = null)
    {

        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $records = new Collection;
        $params = [];

        $title = Title::where('ident', $id)->first();

        if ($title === null)
        {
            return view('dashboard.noreport');
        }
        else
        {
            foreach($title->reports as $report)
            {
                $changes = $report->run();

                if ($changes->isNotEmpty())
                    foreach($changes as $change)
                    {
                        $records->push($change);
                    }

                // it is eaaier to catch the parameters here than in the view

                $list = $report->filters['gene_label'];

                // do some dirty cleanup of genes list to remove region prefix
                $list = str_replace('%', '', $list);
                $list = str_replace('||1', '(GRCh37)', $list);
                $list = str_replace('||2', '(GRCh38)', $list);

                // replace ep ident with name
                foreach ($list as &$row)
                {
                    if (strpos($row, '!') === 0)
                    {
                        $panel = Panel::ident(substr($row, 1))->first();

                        if ($panel !== null)
                        {
                            $row = $panel->smart_title;
                        }
                    }
                }

                if (is_array($report->filters['gene_label']))
                    sort($list);

                $params[] = ['start_date' => $report->start_date, 'stop_date' => $report->stop_date,
                            'genes' => $list];
            }

            // update last run date
            $title->update(['last_run_date' => Carbon::now()]);

        }

        $reports = $records->unique(function ($item){
            return $item['element_id'].$item['element_type'].$item['change_date'].implode($item['description']);
        });

        $genes = null;
        $total = 0;
        $curations = 0;
        $recent = 0;
        $user = null;

        return view('dashboard.run', compact('display_tabs', 'genes', 'total', 'curations',
                        'recent', 'user', 'reports', 'title', 'params'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create_reports(Request $request)
    {

        $input = $request->only(['title', 'description', 'startdate', 'stopdate',
                                'genes', 'regions', 'ident', 'type']);

        if (!Auth::guard('api')->check())
        {
            die("error");
        }

        $user = Auth::guard('api')->user();

        // map any hgnc_id values to gene names;
        $genenames = explode(',', $input['genes']);
        $newgenes = [];

        foreach ($genenames as $genename)
        {
            if (strpos($genename, 'HGNC') === 0)
            {
                $gene = Gene::hgnc($genename)->first();
                if ($gene !== null)
                    $newgenes[] = $gene->name;
            }
            else
                $newgenes[] = $genename;
        }

        $regionnames = explode(';', $input['regions']);

        foreach ($regionnames as $regionname)
        {
            /*if (strpos($genename, 'HGNC') === 0)
            {
                $gene = Gene::hgnc($genename)->first();
                if ($gene !== null)
                    $newgenes[] = $gene->name;
            }
            else*/
            switch ($input['type'])
            {
                case 'GRCh37':
                    $type = '||' . Region::TYPE_REGION_GRCH37;
                    break;
                case 'GRCh38':
                    $type = '||' . Region::TYPE_REGION_GRCH38;
                    break;
                default:
                    $type = '';
            }
                $newgenes[] = '%' . $regionname . $type;
        }

        if (empty($input['ident']))
        {
            $title = new Title(['title' => $input['title'], 'description' => $input['description'],
                                'type' => Title::TYPE_USER, 'status' => 1]);

            $report = new Report(['start_date' => Carbon::parse($input['startdate']), 'stop_date' => Carbon::parse($input['stopdate'])->setTime(23, 59, 59),
                                    'type' => Title::TYPE_USER, 'status' => 1, 'user_id' => $user->id]);

            $report->filters = ['gene_label' => $newgenes];

            $user->titles()->save($title);
            $title->reports()->save($report);
        }
        else
        {
            $title = $user->titles()->ident($input['ident'])->first();
            $title->update(['title' => $input['title'], 'description' => $input['description']]);
            $report = $title->reports()->first();
            $report->update(['start_date' => Carbon::parse($input['startdate']),
                             'stop_date' => Carbon::parse($input['stopdate'])->setTime(23, 59, 59),
                             'filters' => ['gene_label' => $newgenes]
                             ]);
        }

        return redirect('/dashboard');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {

        $display_tabs = collect([
            'active'                            => "home",
            'title' => "titlehere"
        ]);


        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

        }


        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });

        return view('dashboard-profile', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user'));
    }

    /**
     * Providing legacy home query a landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('error.message-standard')
        ->with('title', 'Sorry, this page has moved...')
        ->with('message', 'Please use the search or navigation bar above.')
        ->with('back', url()->previous());

    }


    /**
     * Temporary update method for dashboard prototype.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $input = $request->only(['primary_email', 'secondary_email', 'frequency', 'summary', 'first']);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $notification = $user->notification;

        }

        // save the global since it is toggles elsewhere
        $global = $notification->frequency['global'];

        //update the notifications
        $notification->primary = ['email' => $input['primary_email']];
        $notification->secondary = ['email' => $input['secondary_email']];
        $notification->frequency = ['first' => $input['first'], 'frequency' => $input['frequency'],
                                    'summary' => $input['summary'], 'global' => $global];

        $notification->save();

        $total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });

        return view('dashboard-preferences', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user', 'notification'));

    }


    /**
     * Temporary update method for dashboard prototype.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_profile(Request $request)
    {
        $display_tabs = collect([
            'active' => "home",
            'title' => "Dashboard"
        ]);

        $input = $request->only(['name', 'firstname', 'lastname', 'organization', 'display_list',
                                'credentials', 'email', 'profile', 'preferences', 'avatar']);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            // some of these belong in preferences
            //$preferences = $user->preferences;
            //$preferences['display_list'] = $input["display_list"];
            //$user->preferences = $preferences;

            $user->update($input);

            //$genes = $user->genes;

        }

        return response()->json(['success' => 'truue',
                                'status_code' => 200,
                                'message' => "Request completed"],
                                200);

        //update the notifications

        /*$total = $genes->count();
        $curations = $genes->sum(function ($gene) {
                        return (int) in_array(true, $gene->activity);
                    });
        $recent = $genes->sum(function ($gene) {
                        if ($gene->date_last_curated === null)
                            return 0;

                        $last = new Carbon($gene->date_last_curated);
                        return (int)(Carbon::now()->diffInDays($last) <= 90);
                     });

        return view('dashboard-profile', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user'));*/

    }
}
