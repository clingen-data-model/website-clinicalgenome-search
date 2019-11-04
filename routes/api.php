<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/typeahead/{type?}/{term?}', 'QueryController@typeahead')->name('api-typeahead-gene');


/*
** Provide an api interface between processwire and various databases
*/

// affiliates
//Route::middleware('auth:api')->resource('affiliate', 'Api\AffiliateController');
Route::resource('affiliates', 'Api\AffiliateController');
