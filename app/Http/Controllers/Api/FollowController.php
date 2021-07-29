<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Resources\Follow as FollowResource;

use Auth;

use App\User;
use App\Gene;
use App\Notification;
use App\Group;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ApiRequest $request)
    {
        $input = $request->only(['gene', 'email', 'type', 'build', 'display']);

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

        $notification = $user->notification;

        // handle group expressions
        if (isset($input['type']) && $input['type'] == 'region')
        {
            $name = '%' . $input['gene'];

            // ignore commas and white space for search purposes
            $name = str_replace(',', '', $name);
            $name = preg_replace('/\s/', '', $name);

            // TODO:  Validate its a good region string!
            $goodname = substr($name, 1);

            if (isset($input['build']) && $input['build'] == 'GRCh38')
                $name = $name . '||2';

            $group = $user->owngroups()->search($name)->first();

            if ($group === null)
            {

                $group = new Group([
                                    'name' => $name,
                                    'user_id' => $user->id,
                                    'search_name' => $name,
                                    'type' => ($input['build'] == 'GRCh38' ?
                                                Group::TYPE_REGION_38 :
                                                Group::TYPE_REGION_37)
                                    ]);
            }

            // quick fix for  unique constraint on the groups table
            $group->name = $group->ident;

            // if the display name is empty, create from the search_name
            $group->display_name = empty($input['display']) ? $goodname : $input['display'];

            // update the parameters
            $group->description = $goodname;

            $group->save();

            $hasit = $user->groups()->where('groups.id', $group->id)->exists();

            if (($group !== null) && ($hasit === false))
                $user->groups()->attach($group->id);

            $bucket = $notification->checkGroup($group->search_name);

            if ($bucket === false)
                $notification->addDefault($group->search_name);

            $notification->save();

        }
        else if ($input['gene'] == "*")
        {
            $name = $input['gene'];
            $group = Group::search($name)->first();

            $hasit = $user->groups()->where('groups.id', $group->id)->exists();

            if (($group !== null) && ($hasit === false))
                $user->groups()->attach($group->id);

            $bucket = $notification->checkGroup($group->search_name);

            if ($bucket === false)
                $notification->addDefault($group->search_name);

            $notification->save();
            //$notify = $user->notification;

            /*if (empty($notify->frequency['Groups']))
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
            }*/

            /*$notify->addDefault($input['gene']);
            $notify->save();
            return response()->json(['success' => 'true',
								 'status_code' => 200,
							 	 'message' => 'Gene Followed'],
							 	 200)->withCookie(cookie('clingenfollow',$user->device_token, 0));*/
        }
        else if ($input['gene'][0] == '@')
        {
            $name = $input['gene'];
            $group = Group::search($name)->first();

            $hasit = $user->groups()->where('groups.id', $group->id)->exists();

            if (($group !== null) && ($hasit === false))
            {
                $user->groups()->attach($group->id);
            }

            $bucket = $notification->checkGroup($group->name);
            if ($bucket === false)
                $notification->addDefault($group->name);

            $notification->save();
        }
        else
        {

            $gene = Gene::hgnc($input['gene'])->first();

            if ($gene === null)
                return response()->json(['success' => 'false',
                                        'status_code' => 2001,
                                        'message' => "Gene Lookup Error"],
                                        501);

            $user->genes()->sync([$gene->id], false);

            $name = $gene->name;



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
                $notify->addDefault($name);
                $notify->save();
            }
        }

        return response()->json(['success' => 'true',
                                 'status_code' => 200,
                                 'gene' => $name,
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

        $notification = $user->notification;

        if ($input['gene'] == '*')
        {
            $name = $input['gene'];
            $group = Group::search($name)->first();
            if ($group !== null)
                $user->groups()->detach($group->id);

            $bucket = $notification->checkGroup($group->search_name);

            if ($bucket !== false)
                $notification->removeGroup($group->search_name, $bucket);

            $user->notification->save();
            return response()->json(['success' => 'true',
                                 'status_code' => 200,
                                 'gene' => $name,
							 	 'message' => 'Gene UnFollowed'],
							 	 200);
        }
        else if ($input['gene'][0] == '%')
        {
            $name = $input['gene'];
            $group = $user->owngroups()->search($name)->first();
            if ($group !== null)
                $user->groups()->detach($group->id);

            $bucket = $notification->checkGroup($group->name);

            if ($bucket !== false)
                $notification->removeGroup($group->name, $bucket);

            //soft delete the group
            $group->delete();
        }
        else if ($input['gene'][0] == '@')
        {
            $name = $input['gene'];
            $group = Group::search($name)->first();
            if ($group !== null)
                $user->groups()->detach($group->id);

            $bucket = $notification->checkGroup($group->name);

            if ($bucket !== false)
                $notification->removeGroup($group->name, $bucket);
        }
        else
        {

            $gene = Gene::hgnc($input['gene'])->first();

            if ($gene === null)
                return response()->json(['success' => 'false',
                                        'status_code' => 2001,
                                        'gene' => $name,
                                        'message' => "Gene Lookup Error"],
                                        501);

            $user->genes()->detach($gene->id);

            $name = $gene->name;
        }

        // remove from the notification list
        $notify = $user->notification;
        $frequency = $notify->frequency;

        foreach (["Daily", "Weekly", "Monthly", "Pause", "Default"] as $list)
        {
            if (!isset($frequency[$list]))
                continue;
            if (in_array($name, $frequency[$list]))
            {
                $frequency[$list] = array_diff($frequency[$list], array($name));
                $frequency[$list] = array_values($frequency[$list]);
            }
        }

        $notify->frequency = $frequency;
        $notify->save();

        return response()->json(['success' => 'true',
                                 'status_code' => 200,
                                 'gene' => $name,
							 	 'message' => 'Gene UnFollowed'],
							 	 200);

    }


    /**
     * reload the table
     *
     */
    public function reload()
    {
        $genes = collect();

        if (Auth::guard('api')->check())
        {
            $user = Auth::guard('api')->user();

            $genes = $user->genes;

            $notification = $user->notification;

        }
        else
        {
            return response()->json(['success' => 'false',
                                        'status_code' => 3001,
                                        'message' => "Permission Denied"],
                                        501);
        }

        foreach ($user->groups as $group)
        {
            $type = 1;

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
                default:
                    if (substr($group->search_name,0, 1) == '%')
                        $type = 2;
                    $a = ['dosage' => false, 'pharma' => false, 'varpath' => false, 'validity' => false, 'actionability' => false];
                    break;

            }

            $gene = new Gene(['name' => $group->display_name,
                                'hgnc_id' => $group->search_name,
                                'activity' => $a,
                                'type' => $type,
                                'date_last_curated' => ''
                            ]);

            $genes->prepend($gene);
        }


        return FollowResource::collection($genes);
        //return view('home', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user',
        //            'notification', 'reports', 'system_reports', 'user_reports', 'shared_reports'));
    }


    /**
     * Expand a region entry row.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dare_expand(Request $request, $group = null)
    {
		if (empty($group))
			return "Region not found";

		$region = Group::where('search_name', '%' . $group)->first();

		if ($region === null)
			return "Region not found";

        $type = ($region->type == Group::TYPE_REGION_38 ? 'GRCh38' : 'GRCh37');

        $genes = Gene::searchList(['type' => $type,
                    "region" => $region->description,
                    'option' => 1 ]);

        return view('dashboard.includes.expand-region')
            ->with('group', $region)
            ->with('genes', $genes->collection);
	}
/*
    @foreach ($genes as $gene)
                    <tr data-hgnc="{{ $gene->hgnc_id }}">
                        <td scope="row" class="table-symbol" data-value="{{ $gene->name }}">{{ $gene->name }}</td>
                        <td>
                            <img src="/images/clinicalValidity-{{ $gene->hasActivity('validity') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/dosageSensitivity-{{ $gene->hasActivity('dosage') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/clinicalActionability-{{ $gene->hasActivity('actionability') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/variantPathogenicity-{{ $gene->hasActivity('varpath') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/Pharmacogenomics-{{ $gene->hasActivity('pharma') ? 'on' : 'off' }}.png" width="22" height="22">
                        </td>
                        <td>{{ $gene->displayDate($gene->date_last_curated) }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="text-left btn btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="selection">{{ $gene->hgnc_id == '*' || $gene->hgnc_id[0] == '@' ? $notification->setting($gene->hgnc_id) : $notification->setting($gene->name) }}</span><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a data-value="Daily">Daily</a></li>
                                    <li><a data-value="Weekly">Weekly</a></li>
                                    <li><a data-value="Monthly">Monthly</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a data-value="Default">Default</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a data-value="Pause">Pause</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <span class="action-follow-gene"><i class="fas fa-star" style="color:green"></i></span>
                        </td>
                    </tr>
                @endforeach
                */
}
