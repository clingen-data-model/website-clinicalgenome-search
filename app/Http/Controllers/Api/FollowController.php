<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Auth;

use App\User;
use App\Gene;
use App\Notification;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ApiRequest $request)
    {
        $input = $request->only(['gene', 'email']);
    
        if (Auth::guard('api')->user())
        {
            $user = Auth::guard('api')->user();
        }
        else
        {
            // is this an existing user?
            $user = User::where('email', $input["email"])->first();

            if ($user === null)
            {
                // create a new one
                $parms = ['name' => 'ClinGen User',
                        'email' => $input["email"]
                ];

                try {
                    $user = new User($parms);
                    $user->api_token = Str::random(60);
                    $user->device_token = Str::random(40);
                    $user->password = Hash::make(Str::random(12));
                    $user->save();
                }
                catch (\Exception $e){
                    return response()->json(['success' => 'false',
                                        'status_code' => 2002,
                                        'message' => $e->getMessage()],
                                        502);
                }
            }
        }

        // find the gene

        // handle group expressions
        if ($input['gene'] == "*")
        {
            $notify = $user->notification;

            if (empty($notify->frequency['Groups']))
            {
                $frequency = $notify->frequency;
                $frequency['Groups'] = ['AllGenes'];
                $notify->frequency = $frequency;
            }
            else
            {
                $frequency = $notify->frequency;
                if (!in_array('AllGenes', $frequency['Groups']))
                    $frequency['Groups'][] = 'AllGenes';
                $notify->frequency = $frequency;
            }
                
            $notify->addDefault($input['gene']);
            $notify->save();
            return response()->json(['success' => 'true',
								 'status_code' => 200,
							 	 'message' => 'Gene Followed'],
							 	 200)->withCookie(cookie('clingenfollow',$user->device_token, 0));
        }

        $gene = Gene::hgnc($input['gene'])->first();

        if ($gene === null)
            return response()->json(['success' => 'false',
                                    'status_code' => 2001,
                                    'message' => "Gene Lookup Error"],
                                    501);

        $user->genes()->sync([$gene->id], false);

        // do some self repairing in the event notifications are lost
        $notify = $user->notification;

        if ($notify === null)
        {
            $notify = new Notification();
            $notify->addDefault($user->genes);
            $user->notification()->save($notify);
        }
        else
        {
            $notify->addDefault($gene);
            $notify->save();
        }

        return response()->json(['success' => 'true',
								 'status_code' => 200,
							 	 'message' => 'Gene Followed'],
							 	 200)->withCookie(cookie('clingenfollow',$user->device_token, 0));

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove(ApiRequest $request)
    {
        $input = $request->only(['gene']);

        /*if(auth('api')->check()){
dd("loggedit");  } else {
dd("not logged in");  }*/

        if (Auth::guard('api')->user())
        {
            $user = Auth::guard('api')->user();
        }
        else
        {
            $cookie = $request->cookie('clingenfollow');

            if ($cookie === null)
                return response()->json(['success' => 'false',
                                    'status_code' => 2001,
                                    'message' => "Cookie Lookup Error"],
                                    501);

            // is this an existing user?
            $user = User::cookie($cookie)->first();
        }

        if ($user === null)
                return response()->json(['success' => 'false',
                                    'status_code' => 2002,
                                    'message' => "User Lookup Error"],
                                    502);
       
        if ($input['gene'] == "*")
        {
            $notify = $user->notification;

            $frequency = $notify->frequency;
            $frequency['Groups'] = [];
            $notify->frequency = $frequency;
                
            $notify->save();
            return response()->json(['success' => 'true',
								 'status_code' => 200,
							 	 'message' => 'Gene UnFollowed'],
							 	 200);
        }

        $gene = Gene::hgnc($input['gene'])->first();

        if ($gene === null)
            return response()->json(['success' => 'false',
                                    'status_code' => 2001,
                                    'message' => "Gene Lookup Error"],
                                    501);
        
        $user->genes()->detach($gene->id);

        // remove from the notification list
        $notify = $user->notification;
        $frequency = $notify->frequency;

        foreach (["Daily", "Weekly", "Monthly", "Pause", "Default"] as $list)
        {
            if (($key = array_search($gene->name, $frequency[$list], true)) !== false) {
                unset($frequency[$list][$key]);
            }
        }
        
        $notify->frequency = $frequency;        
        $notify->save();

        return response()->json(['success' => 'true',
								 'status_code' => 200,
							 	 'message' => 'Gene UnFollowed'],
							 	 200);

    }
}
