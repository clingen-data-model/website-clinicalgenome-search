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



// Auth routes for passport
Route::post('login', 'Api\AuthController@login');
Route::post('logout', 'Api\AuthController@logout');
Route::post('forgot', 'Api\AuthController@forgot');
Route::post('register', 'Api\AuthController@register');
Route::post('profile', 'Api\SettingsController@update');
Route::post('change-password', 'Api\AuthController@change_password');
Route::get('auth/signup/activate/{token}', 'Api\AuthController@signupActivate');

// Auth related routes for email confirmation
Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify'); // Make sure to keep this as your route name
Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');


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

// acmg
Route::get('genes/acmg', 'Api\GeneController@acmg_index');
Route::get('genes/acmg/expand/{id}', 'Api\GeneController@acmg_expand');

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

// clinical actionability
Route::resource('actionability', 'Api\ActionabilityController')->only(['index']);

// drugs
Route::resource('drugs', 'Api\DrugController')->only(['index']);
Route::get('/drugs/look/{term?}', 'Api\DrugController@look')->name('drugs.look');


// diseases
Route::resource('conditions', 'Api\ConditionController')->only(['index', 'show']);
Route::get('/conditions/look/{term?}', 'Api\ConditionController@look')->name('conditions.look');
Route::get('/conditions/find/{term?}', 'Api\ConditionController@find')->name('conditions.find');
Route::post('/conditions/follow', 'Api\FollowController@create_disease')->name('followd.create');
Route::post('/conditions/unfollow', 'Api\FollowController@remove_disease')->name('followd.remove');




// Geneconnect
//Route::get('/genes/look/{term?}', 'Api\GeneController@look')->name('genes.look');
//Route::get('/genes/find/{term?}', 'Api\GeneController@find')->name('genes.find');
Route::post('/gc/follow', 'Api\GenomeConnectController@create')->name('gc.create');
Route::post('/gc/remove', 'Api\GenomeConnectController@remove')->name('gc.remove');
Route::get('/home/gc/reload', 'Api\GenomeConnectController@reload')->name('gc.reload');

// region search
Route::get('/region/search/{type}/{region}', 'Api\RegionController@search')->name('region.search');

// dashboard
Route::post('/home/notify', 'Api\HomeController@notify')->name('home.notify');
Route::get('/home/rpex/{type}', 'Api\HomeController@report_expand')->name('home.report');
Route::get('/home/reports/{type}', 'Api\HomeController@reports')->name('home.reports');
Route::post('/home/toggle', 'Api\HomeController@toggle')->name('home.toggle');
Route::post('/home/toggle-pause', 'Api\HomeController@pause')->name('home.pause');
Route::get('/home/follow/reload', 'Api\FollowController@reload')->name('home.reload');
Route::get('/home/dare/expand/{group}', 'Api\FollowController@dare_expand')->name('home.rexpand');
Route::get('/home/dape/expand/{group}', 'Api\FollowController@dape_expand')->name('home.pexpand');
Route::get('/home/follow/reload_disease', 'Api\FollowController@reload_disease')->name('home.reload_disease');


// filters
Route::resource('filters', 'Api\FilterController');


