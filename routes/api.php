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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//Route::get('/typeahead/{type?}/{term?}', 'QueryController@typeahead')->name('api-typeahead-gene');


/*
** Provide an api interface between processwire and various databases
*/

// affiliates
Route::resource('affiliates', 'Api\AffiliateController')->only(['index', 'show']);

// genes
Route::resource('genes', 'Api\GeneController')->only(['index']);
Route::get('/genes/look/{term?}', 'Api\GeneController@look')->name('genes.look');

// curated genes
Route::resource('curations', 'Api\CurationController')->only(['index']);

// dosage
Route::resource('dosage', 'Api\DosageController')->only(['index']);
Route::get('/dosage/cnv', 'Api\DosageController@cnv')->name('dosage.cnv');
Route::get('/dosage/acmg59', 'Api\DosageController@acmg59')->name('dosage.acmg59');
Route::get('/dosage/region_search/{type}/{region}', 'Api\DosageController@region_search')->name('dosage.region-search');

// gene validity
Route::resource('validity', 'Api\ValidityController')->only(['index']);

// drugs
Route::resource('drugs', 'Api\DrugController')->only(['index']);
Route::get('/drugs/look/{term?}', 'Api\DrugController@look')->name('drugs.look');


// diseases
Route::resource('conditions', 'Api\ConditionController')->only(['index', 'show']);
Route::get('/conditions/look/{term?}', 'Api\ConditionController@look')->name('conditions.look');

