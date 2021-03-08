<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Reports as ReportsResource;
//use App\Http\Requests\ApiRequest;

use Auth;

use App\User;
use App\Title;

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
        $input = $request->only(['name', 'value']);

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
