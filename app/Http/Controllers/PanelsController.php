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
	    $panels = Panel::get();
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
}
