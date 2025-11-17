<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Panel;
use Illuminate\Support\Facades\Artisan;

class PanelsController extends Controller
{
    //
    public function index()
    {
        $panels = Panel::orderBy('title')->get();
        return view('panels', compact('panels'));
    }

    public function syncGpm(Request $request)
    {
        // Run the console command: query:kafka gpm-general-events
        Artisan::call('query:kafka gpm-general-events');

        return redirect()
            ->route('panels.index')
            ->with('status', 'GPM sync started successfully.');
    }

    /**
     * Show details for a single Expert Panel.
     */
    public function show($id)
    {
        $panel = Panel::with([
            'members' => function ($q) {
                $q->orderBy('last_name')->orderBy('first_name');
            },
            'activities' => function ($q) {
                $q->orderBy('activity_date', 'desc');
            },
        ])->findOrFail($id);

        return view('panels.show', compact('panel'));
    }

    /**
     * Sync a single expert panel by affiliate_id.
     */
    public function sync($affiliateId)
    {
        $panel = Panel::where('affiliate_id', $affiliateId)->firstOrFail();

        // php artisan processwire:panels {affiliate_id}
        \Artisan::call('processwire:panels', [
            'panel_id' => $panel->affiliate_id,
        ]);

        return redirect()
            ->back()
            ->with('status', 'Panel ' . $panel->affiliate_id . ' synced successfully.');
    }
}
