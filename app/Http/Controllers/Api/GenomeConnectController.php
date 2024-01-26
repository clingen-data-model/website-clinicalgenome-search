<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Resources\Genomeconnect as GenomeconnectResource;

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

        $gcs = Genomeconnect::with('gene')->get();
/*
        <tr >
        <td scope="row" data-value="{{ $gc->gene->name }}">{{ $gc->gene->name }}</td>
        <td>
            {{ $gc->variant_count }}
        </td>
        <td>{{ $gc->displayDate($gc->updated_at) }}</td>
        <td>
            <span class="action-remove-gc"><i class="fas fa-trash" style="color:red"></i></span>
        </td>
        <td>{{ $gc->gene->hgnc_id }}</td>
        <td>{{ $gc->ident }}</td>
    </tr>*/

       
        return GenomeconnectResource::collection($gcs);
        //return view('home', compact('display_tabs', 'genes', 'total', 'curations', 'recent', 'user',
        //            'notification', 'reports', 'system_reports', 'user_reports', 'shared_reports'));
    }

}
