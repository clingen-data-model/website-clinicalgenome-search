<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use App\Http\Requests\ApiRequest;

use App\User;

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


}
