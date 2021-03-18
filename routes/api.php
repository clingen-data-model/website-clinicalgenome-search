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

// Auth routes for passport
Route::post('login', 'Api\AuthController@login');
Route::post('logout', 'Api\AuthController@logout');
Route::post('register', 'Api\AuthController@register');
Route::post('profile', 'Api\SettingsController@update');

// reports
Route::post('reports/remove', 'Api\SettingsController@remove');
Route::post('reports/lock', 'Api\SettingsController@lock');
Route::post('reports/unlock', 'Api\SettingsController@unlock');
Route::get('/reports/{id}', 'Api\SettingsController@edit');



/*
** Provide an api interface between processwire and various databases
*/

// affiliates
Route::resource('affiliates', 'Api\AffiliateController')->only(['index', 'show']);

// genes
Route::resource('genes', 'Api\GeneController')->only(['index']);
Route::get('/genes/look/{term?}', 'Api\GeneController@look')->name('genes.look');
Route::get('/genes/find/{term?}', 'Api\GeneController@find')->name('genes.find');
Route::post('/genes/follow', 'Api\FollowController@create')->name('follows.create');
Route::post('/genes/unfollow', 'Api\FollowController@remove')->name('follows.remove');

// curated genes
Route::resource('curations', 'Api\CurationController')->only(['index']);
Route::get('/genes/expand/{id}', 'Api\GeneController@expand')->name('genes.expand');


// dosage
Route::resource('dosage', 'Api\DosageController')->only(['index']);
Route::get('/dosage/cnv', 'Api\DosageController@cnv')->name('dosage.cnv');
Route::get('/dosage/acmg59', 'Api\DosageController@acmg59')->name('dosage.acmg59');
Route::get('/dosage/region_search/{type}/{region}', 'Api\DosageController@region_search')->name('dosage.region-search');
Route::get('/dosage/expand/{id}', 'Api\DosageController@expand')->name('dosage.expand');

// gene validity
Route::resource('validity', 'Api\ValidityController')->only(['index']);

// drugs
Route::resource('drugs', 'Api\DrugController')->only(['index']);
Route::get('/drugs/look/{term?}', 'Api\DrugController@look')->name('drugs.look');


// diseases
Route::resource('conditions', 'Api\ConditionController')->only(['index', 'show']);
Route::get('/conditions/look/{term?}', 'Api\ConditionController@look')->name('conditions.look');

// region search
Route::get('/region/search/{type}/{region}', 'Api\RegionController@search')->name('region.search');

// dashboard
Route::post('/home/notify', 'Api\HomeController@notify')->name('home.notify');
Route::get('/home/rpex/{type}', 'Api\HomeController@report_expand')->name('home.report');
Route::get('/home/reports/{type}', 'Api\HomeController@reports')->name('home.reports');
Route::post('/home/toggle', 'Api\HomeController@toggle')->name('home.toggle');


