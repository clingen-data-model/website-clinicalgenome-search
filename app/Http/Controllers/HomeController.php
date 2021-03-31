<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

use Auth;
use Carbon\Carbon;

use App\Gene;
use App\Title;
use App\Report;

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
    public function index()
    {

        $display_tabs = collect([
            'active' => "more",
            'title' => "Dashboard"
        ]);

        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $notification = $user->notification;

            $reports = $user->titles;

        }
        else{
            return view('dashboard.logout', compact('display_tabs'));
        }

//dd($notification);

    // tack on group display.  For now, only All Genes is supported
    if (isset($user->notification->frequency['Groups']) && in_array('AllGenes', $user->notification->frequency['Groups']))
    {
        $group = new Gene(['name' => "All Genes",
                            'hgnc_id' => '*',
                           'activity' => ['dosage' => true, 'pharma' => true, 'varpath' => true, 'validity' => true, 'actionability' => true],
                           'date_last_curated' => Carbon::now()
                           ]);

        $genes->prepend($group);
    }
    if (isset($user->notification->frequency['Groups']) && in_array('AllActionability', $user->notification->frequency['Groups']))
    {
        $group = new Gene(['name' => "All Actionability",
                            'hgnc_id' => '@AllActionability',
                           'activity' => ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => true],
                           'date_last_curated' => Carbon::now()
                           ]);

        $genes->prepend($group);
    }
    if (isset($user->notification->frequency['Groups']) && in_array('AllValidity', $user->notification->frequency['Groups']))
    {
        $group = new Gene(['name' => "All Validity",
                            'hgnc_id' => '@AllValidity',
                           'activity' => ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => true, 'actionability' => false],
                           'date_last_curated' => Carbon::now()
                           ]);

        $genes->prepend($group);
    }
    if (isset($user->notification->frequency['Groups']) && in_array('AllDosage', $user->notification->frequency['Groups']))
    {
        $group = new Gene(['name' => "All Dosage",
                            'hgnc_id' => '@AllDosage',
                           'activity' => ['dosage' => true, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => false],
                           'date_last_curated' => Carbon::now()
                           ]);

        $genes->prepend($group);
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

        return view('home', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user',
                    'notification', 'reports', 'system_reports', 'user_reports', 'shared_reports'));
    }


    /**
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
            'active' => "more"
        ]);

        return view('downloads.index', compact('display_tabs'))
        ->with('filelist', $filelist)
            ->with('user', $this->user);
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

            if ($title !== null)
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

        if ($title !== null)
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

        $reports = $records;

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
                                'genes', 'ident']);

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
