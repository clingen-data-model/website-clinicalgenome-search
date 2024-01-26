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
use App\Panel;
use App\Genomeconnect;

class GenomeConnectController extends Controller
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

        $gene = Gene::hgnc($input['gene'])->first();

        if ($gene === null)
            return response()->json(['success' => 'false',
                                    'status_code' => 2001,
                                    'message' => "Gene Lookup Error"],
                                    501);

        $gc = $gene->genomeconnect;

        if ($gc === null)
        {
            $gc = new Genomeconnect([
                'type' => 0,
                'status' => Genomeconnect::STATUS_INITIALIZED
            ]);

            $gene->genomeconnect()->save($gc);
        }

        return response()->json(['success' => 'true',
                                 'status_code' => 200,
                                 'gene' => $gene->name,
							 	 'message' => 'Gene Followed'],
							 	 200)->withCookie(cookie('clingenfollow',$user->device_token, 0));

    }


    /**
     * Remove an item.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove(ApiRequest $request)
    {
        $input = $request->only(['ident']);

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

        

        
        $record = Genomeconnect::ident($input['ident'])->first();

        if ($record === null)
                return response()->json(['success' => 'false',
                                    'status_code' => 2003,
                                    'message' => "Irem Lookup Error"],
                                    502);

        $name = $record->gene->name;

        $record->delete();

        return response()->json(['success' => 'true',
                                 'status_code' => 200,
                                 'gene' => $name,
							 	 'message' => 'Item removed'],
							 	 200);

    }


    /**
     * reload the table
     *
     */
    public function reload()
    {
        dd("reload exit)");
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
                case '@AllVariant':
                    $a = ['dosage' => false, 'pharma' => false, 'varpath' => true, 'validity' => false, 'actionability' => false];
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

        return FollowResource::collection($genes);
        //return view('home', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user',
        //            'notification', 'reports', 'system_reports', 'user_reports', 'shared_reports'));
    }

}
