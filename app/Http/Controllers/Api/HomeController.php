<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Reports as ReportsResource;
//use App\Http\Requests\ApiRequest;

use Auth;

use App\User;
use App\Title;

class HomeController extends Controller
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
    public function update(Request $request, $id)
    {
        $email = $request->input('email');

        if (empty($email))
            return response()->json(['success' => 'false',
								 'status_code' => 1001,
							 	 'message' => "Invalid Email Address"],
                                  501);

        // check if user is already participating
        $user = User::email($email)->first();

        if ($user === null)
        {
            // add the account and send the activation email
        }
        
        return response()->json(['success' => 'truue',
                                'status_code' => 200,
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
    public function notify(Request $request)
    {
        $input = $request->only('gene', 'old', 'new');

        if (empty($input['gene']))
            return response()->json(['success' => 'false',
								 'status_code' => 1002,
							 	 'message' => "Invalid Gene Symbol"],
                                  501);

        // check if user is already participating
        $user = Auth::guard('api')->user();

        if ($user->genes->where('name', $input['gene'])->first() === null)
            return response()->json(['success' => 'false',
                                'status_code' => 1003,
                                'message' => "Invalid Gene Symbol"],
                                501);

        $notification = $user->notification->frequency;

        if (isset($notification[$input['new']]))
        {
            $new = $notification[$input['new']];
            if (($key = array_search($input['gene'], $new)) === false)
                $new[] = $input['gene'];
            $notification[$input['new']] = $new;

        }
        else
        {
            $notification[$input['new']] = [ $input['gene'] ];
        }

        if (isset($notification[$input['old']]))
        {
            $t = $notification[$input['old']];
            if (($key = array_search($input['gene'], $t)) !== false)
                unset($t[$key]);
            $notification[$input['old']] = array_values($t);
        }

        $t = $user->notification;
        $t->update(['frequency' => $notification]);
        
        return response()->json(['success' => 'truue',
                                'status_code' => 200,
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
    public function reports(Request $request, $type = Title::TYPE_USER)
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
