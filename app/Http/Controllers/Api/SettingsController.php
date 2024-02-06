<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Reports as ReportsResource;
//use App\Http\Requests\ApiRequest;

use Auth;

use App\User;
use App\Title;
use App\Gene;
use App\Group;
use App\Notification;
use App\Region;
use App\Panel;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['name', 'value', 'ident']);

        if (empty($input['name']))
            return response()->json(['success' => 'false',
								 'status_code' => 1001,
							 	 'message' => "Invalid Name Attribute"],
                                  501);

        if (!Auth::guard('api')->check())
            return response()->json(['success' => 'false',
								 'status_code' => 1001,
							 	 'message' => "Permission Denied"],
                                  501);

        $user = Auth::guard('api')->user();

        $field = $input['name'];
        $value = $input['value'];

        switch ($input['name'])
        {
            case 'firstname':
            case 'lastname':
                $field = 'name';
            case 'name':
            case 'organization':
            case 'credentials':
            //case 'email':
                $user->update([$input['name'] => $input['value']]);
                break;
            case 'primary_email':
                $notification = $user->notification;
                $primary = $notification->primary;
                $primary['email'] = $input['value'];
                $notification->primary = $primary;
                $notification->save();
                break;
            case 'secondary_email':
                $notification = $user->notification;
                $secondary = $notification->secondary;
                $secondary['email'] = $input['value'];
                $notification->secondary = $secondary;
                $notification->save();
                break;
            case 'frequency':
                $notification = $user->notification;
                $frequency = $notification->frequency;
                $frequency['frequency'] = $input['value'];
                $notification->frequency = $frequency;
                $notification->save();
                break;
            case 'first':
                $notification = $user->notification;
                $frequency = $notification->frequency;
                $frequency['first'] = $input['value'];
                $notification->frequency = $frequency;
                $notification->save();
                break;
            case 'summary':
                $notification = $user->notification;
                $frequency = $notification->frequency;
                $frequency['summary'] = $input['value'];
                $notification->frequency = $frequency;
                $notification->save();
                break;
            case 'display_list':
                $preferences = $user->preferences;
                $preferences['display_list'] = $input['value'];
                $user->update(['preferences' => $preferences]);
                break;
            case 'validity_interest':
            case 'dosage_interest':
            case 'actionability_interest':
            case 'variant_interest':
                $interest = preg_split("/_interest/", $input['name']);
                if ($input['value'] == "1")
                    $user->addInterest($interest[0]);
                else
                    $user->removeInterest($interest[0]);
                $user->save();
                break;
            case 'validity_notify':
            case 'dosage_notify':
            case 'actionability_notify':
            case 'variant_notify':
                $notify = preg_split("/_notify/", $input['name']);
                $notify[0] = 'All' . ucfirst($notify[0]);
                $notification = $user->notification;
                if ($input['value'] == "1")
                {
                    $bucket = $notification->checkGroup('@' . $notify[0]);

                    if ($bucket === false)
                        $notification->addDefault('@' . $notify[0]);

                    $user->addGroup('@' . $notify[0]);
                }
                else
                {
                    $bucket = $notification->checkGroup('@' . $notify[0]);

                    if ($bucket !== false)
                        $notification->removeGroup('@' . $notify[0], $bucket);

                    $user->removeGroup('@' . $notify[0]);
                }
                $notification->save();
                break;
            case 'select[]':
                $panel = Panel::ident($input['ident'])->first();
                if ($panel !== null)
                {
                    $notification = $user->notification;

                    if ($input['value'] == 1){
                        $user->panels()->syncWithoutDetaching([$panel->id]);

                        $bucket = $notification->checkGroup('!' . $input['ident']);

                        if ($bucket === false)
                            $notification->addDefault('!' . $input['ident']);

                    }
                    else {
                        $user->panels()->detach($panel->id);

                        $bucket = $notification->checkGroup('!' . $input['ident']);

                        if ($bucket !== false)
                            $notification->removeGroup('!' . $input['ident'], $bucket);

                    }

                    $notification->save();
                }
                break;
            case 'pause_date':
                $notification = $user->notification;
                $frequency = $notification->frequency;
                $frequency['global_pause_date'] = $input['value'];
                $notification->frequency = $frequency;
                $notification->save();
                break;

        }

        if ($field == 'name')
            $value = $user->name;

        /*
        'name', 'firstname', 'lastname', 'organization', 'display_list',
                                'credentials', 'email', 'profile', 'preferences', 'avatar'
                                */
        return response()->json(['success' => 'true',
                                'status_code' => 200,
                                'field' => $field,
                                'value' => $value,
                                'message' => "Request completed"],
                                200);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggle(Request $request)
    {
        $input = $request->only('value');

        // check if user is already participating
        $user = Auth::guard('api')->user();

        $notification = $user->notification->frequency;

        $notification['global'] = $input['value'] ? "on" : "off";

        $t = $user->notification;
        $t->update(['frequency' => $notification]);

        return response()->json(['success' => 'truue',
                                'status_code' => 200,
                                'message' => "Request completed"],
                                200);

    }


    /**
     * Reports by folder (type).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function remove(Request $request)
    {
        $ident = $request->input('id');

        if (!Auth::guard('api')->check())
            return response()->json(['success' => 'false',
								 'status_code' => 1011,
							 	 'message' => "Permission Denied"],
                                  501);

        $user = Auth::guard('api')->user();

        $report = $user->titles()->ident($ident);

        if ($report === null)
            return response()->json(['success' => 'false',
								 'status_code' => 1012,
							 	 'message' => "Permission Denied"],
                                  501);

        $report->delete();
    }


     /**
     * Reports by folder (type).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function lock(Request $request)
    {
        $ident = $request->input('id');

        if (!Auth::guard('api')->check())
            return response()->json(['success' => 'false',
								 'status_code' => 1011,
							 	 'message' => "Permission Denied"],
                                  501);

        $user = Auth::guard('api')->user();

        $report = $user->titles()->ident($ident);

        if ($report === null)
            return response()->json(['success' => 'false',
								 'status_code' => 1012,
							 	 'message' => "Permission Denied"],
                                  501);

        $report->update(['status' => Title::STATUS_LOCKED]);

        return response()->json(['success' => 'true',
                                'status_code' => 200,
                                'message' => "Report Locked"],
                                200);
    }


    /**
     * Retrieve for edit report
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request, $id = null)
    {
        if (!Auth::guard('api')->check())
            return response()->json(['success' => 'false',
								 'status_code' => 1011,
							 	 'message' => "Permission Denied"],
                                  501);

        $user = Auth::guard('api')->user();

        $title = $user->titles()->ident($id)->first();
        $report = $title->reports()->first();

        // break out list into regex, groups, regions, proper names
        $list = $report->parse_filter();

        $genes = Gene::select('name', 'hgnc_id')->whereIn('name', $list['genes'])->get();

        $region_type = Region::TYPE_REGION_GRCH37;

        $temp_regions = [];

        // split out the type from regions
        foreach ($list['regions'] as $region)
        {
            $split = explode('||', $region);

            if ($split === false)
                continue;

            if (isset($split[1]) && $split[1] == Region::TYPE_REGION_GRCH38)
                $region_type = Region::TYPE_REGION_GRCH38;

            $temp_regions[] = $split[0];
        }

        $regions = implode(';', $temp_regions);

        foreach ($list['regex'] as $item)
        {
            $genes->push(new Gene(['name' => 'All Genes', 'hgnc_id' => '*']));
        }

        foreach ($list['groups'] as $item)
        {
            $group = Group::search($item)->first();

            if ($group === null)
                continue;

            $genes->push(new Gene(['name' => $group->display_name, 'hgnc_id' => $group->search_name]));
        }

        $fields = [ 'title' => $title->title,
                    'description' => $title->description,
                    'startdate' => $report->display_start_date,
                    'stopdate' => $report->display_stop_date,
                    'genes' => $genes,
                    'regions' => $regions,
                    'type' => $region_type == 1 ? 'GRCh37' : 'GRCh38',
        ];

        if ($report === null)
            return response()->json(['success' => 'false',
								 'status_code' => 1012,
							 	 'message' => "Permission Denied"],
                                  501);

        return response()->json(['success' => 'true',
                                'status_code' => 200,
                                'fields' => $fields,
                                'message' => "OK"],
                                200);
    }


     /**
     * Reports by folder (type).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function unlock(Request $request)
    {
        $ident = $request->input('id');

        if (!Auth::guard('api')->check())
            return response()->json(['success' => 'false',
								 'status_code' => 1011,
							 	 'message' => "Permission Denied"],
                                  501);

        $user = Auth::guard('api')->user();

        $report = $user->titles()->ident($ident);

        if ($report === null)
            return response()->json(['success' => 'false',
								 'status_code' => 1012,
							 	 'message' => "Permission Denied"],
                                  501);

        $report->update(['status' => Title::STATUS_ACTIVE]);

        return response()->json(['success' => 'true',
								 'status_code' => 200,
							 	 'message' => "Report UnLocked"],
                                  200);
    }


    /**
     * Reports by folder (type).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reports(Request $request, $type = null)
    {

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $reports = $user->titles;

        }

        switch ($type)
        {
            case Title::TYPE_SYSTEM_NOTIFICATIONS:
                $reports = $user->titles->where('type', Title::TYPE_SYSTEM_NOTIFICATIONS);
                break;
            case Title::TYPE_USER:
                $reports = $user->titles->where('type', Title::TYPE_USER);
                break;
            case Title::TYPE_SHARED:
                $reports = $user->titles->where('type', Title::TYPE_SHARED);
                break;
            default:
                $reports = $user->titles;
        }

        return ReportsResource::collection($reports);
    }


    /**
     * Expand a report list row.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function report_expand(Request $request, $id = null)
    {
		if (empty($id))
			return "Report not found";

		$title = Title::ident($id)->first();

		if ($title === null)
			return "Report not found";

        return view('dashboard.includes.expand-report')->with('title', $title);
	}


}
