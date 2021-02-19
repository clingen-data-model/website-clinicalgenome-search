<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/message', 'error.message-standard');

Route::get('/', function () {

	return redirect()->route('gene-curations');

});

Route::get('/kb', function () {

	return redirect()->route('gene-curations');

})->name('home');


Route::group(['prefix' => '/dashboard'], function () {
	Route::get('/', 'HomeController@index')->name('dashboard-index');
	Route::get('/profile', 'HomeController@profile')->name('dashboard-profile');
	Route::get('/preferences', 'HomeController@preferences')->name('dashboard-preferences');
	Route::post('/preferences', 'HomeController@update')->name('dashboard-update');
});

/*
 * Gene display routes
 */
Route::group(['prefix' => 'kb/genes'], function () {

		Route::get('/', 'GeneController@index')->name('gene-index');

		Route::post('/', 'GeneController@search')->name('gene-search');

		Route::get('/page/{page}', 'GeneController@index');

		Route::get('/page/{page}/view/{psize}', 'GeneController@index');

		Route::get('/curations/', 'GeneController@curated')->name('gene-curations');

		Route::get('/{id?}', 'GeneController@show_by_activity')->name('gene-show');

		//Route::get('/{id?}/by-activity', 'GeneController@show_by_activity')->name('gene-by-activity');

		Route::get('/{id?}/by-disease', 'GeneController@show_by_disease')->name('gene-by-disease');

		Route::get('/{id?}/external-resources', 'GeneController@external')->name('gene-external');

		//Route::get('/{id?}/external_resources_genes', 'GeneController@external');

		Route::get('/{id?}/external_resources_genes', function ($id) {
		 	return redirect(route('gene-external', $id), 301);
		 });

});


/*
 * Drug display routes
 */
Route::group(['prefix' => 'kb/drugs'], function () {

		Route::get('/', 'DrugController@index')->name('drug-index');

		Route::post('/', 'DrugController@search')->name('drug-search');

		Route::get('/page/{page}', 'DrugController@index');

		Route::get('/page/{page}/view/{psize}', 'DrugController@index');

		Route::get('/{id?}', 'DrugController@show')->name('drug-show');

});


/*
 * Gene Validity display routes
 */
Route::group(['prefix' => 'kb/gene-validity'], function () {

		Route::get('/', 'ValidityController@index')->name('validity-index');

		Route::get('/download', 'ValidityController@download')->name('validity-download');

		Route::get('/page/{page}', 'ValidityController@index');

		Route::get('/page/{page}/view/{psize}', 'ValidityController@index');

		Route::get('/{id?}', 'ValidityController@show')->name('validity-show');

});


/*
 * Region display routes
 */
Route::group(['prefix' => 'kb/regions'], function () {

	Route::get('/', 'RegionController@index')->name('region-index');

	Route::post('/', 'RegionController@search')->name('region-search');

});


/*
 * Conditions display routes
 */
Route::group(['prefix' => 'kb/conditions'], function () {

		Route::get('/', 'ConditionController@index')->name('condition-index');

		Route::post('/', 'ConditionController@search')->name('condition-search');

		Route::get('/page/{page}', 'ConditionController@index');

		Route::get('/page/{page}/view/{psize}', 'ConditionController@index');

		Route::get('/{id?}', 'ConditionController@show')->name('condition-show');

		Route::get('/{id?}/by-gene', 'ConditionController@show_by_gene')->name('disease-by-gene');

		// TODO
		Route::get('/{id?}/external-resources', 'ConditionController@external')->name('condition-external');

});


/*
 * Affiliates display routes
 */
Route::group(['prefix' => 'kb/affiliate'], function () {

	Route::get('/', 'AffiliateController@index')->name('affiliate-index');

	Route::get('/{id?}', 'AffiliateController@show')->name('affiliate-show');
});


/*
 * Dosage display routes
 */
Route::group(['prefix' => 'kb/gene-dosage'], function () {

	Route::get('/', 'DosageController@index')->name('dosage-index');

	Route::get('/region_search', 'DosageController@region_search_refresh')->name('dosage-region-search-refresh');

	Route::post('/region_search', 'DosageController@region_search')->name('dosage-region-search');

	Route::get('/region_search/{type}/{region}', 'DosageController@region_search')->name('dosage-region-research');

	Route::get('/download', 'DosageController@download')->name('dosage-download');

	Route::get('/ftp', 'DosageController@ftps')->name('dosage-ftp');

	Route::get('/cnv', 'DosageController@cnv')->name('dosage-cnv');

	Route::get('/acmg59', 'DosageController@acmg59')->name('dosage-acmg59');

	Route::get('/region/{id?}', 'DosageController@region_show')->name('dosage-region-show');

	Route::get('/{id?}', 'DosageController@show')->name('dosage-show');
});


/*
 * Actionability display routes
 */
Route::group(['prefix' => 'kb/actionability'], function () {

	Route::get('/', 'ActionabilityController@index')->name('actionability-index');

	//Route::get('/{id?}', 'ActionabilityController@show')->name('actionability-show');
});


/*
 * Stats & Others display routes
 */
Route::group(['prefix' => 'kb/reports'], function () {

	Route::get('/stats', 'ReportController@statistics')->name('stats-index');

	//Route::get('/{id?}', 'ActionabilityController@show')->name('actionability-show');
});


// Variant pathogenicity routes
Route::group(['prefix' =>'kb/variant-pathogenicity'], function () {
	// Variant Path demo route
	// NOT FOR MVP
	Route::get('/all/', 'VariantPathController@index')->name('variant-path-index');
	Route::get('/detail/', 'VariantPathController@show')->name('variant-path-show');

});

// Redirect and/or legacy routes
Route::get('/kb/home', 'HomeController@home');

Route::get('/test', 'TestController@index');

Auth::routes();
