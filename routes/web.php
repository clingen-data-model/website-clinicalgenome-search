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

//Route::get('/', 'HomeController@index')->name('home');
Route::get('/', function () {
	return redirect()->route('gene-curations');
})->name('home');
/*
 * Test route - remove for production
 */
// Route::get('/test/test', 'TestController@test')->name('test-test');
 Route::get('/test', 'TestController@index')->name('test-index');
// Route::get('/test/reports', 'TestController@reports')->name('dosage-reports');
// Route::get('/test/stats', 'TestController@stats')->name('dosage-stats');
// Route::get('/test/show', 'TestController@show')->name('gene-dosage-show');

/*
 *	download dosage csv
 */
Route::get('/gene-validity/download', 'ValidityController@download')->name('validity-download');


/*
 * Gene display routes
 */
Route::group(['prefix' => 'genes'], function () {

		Route::get('/', 'GeneController@index')->name('gene-index');

		Route::post('/', 'GeneController@search')->name('gene-search');

		Route::get('/page/{page}', 'GeneController@index');

		Route::get('/page/{page}/view/{psize}', 'GeneController@index');

		Route::get('/curations/', 'GeneController@curated')->name('gene-curations');

		Route::get('/{id?}', 'GeneController@show')->name('gene-show');

		Route::get('/{id?}/by-activity', 'GeneController@show_by_activity')->name('gene-by-activity');

		Route::get('/{id?}/external-resources', 'GeneController@external')->name('gene-external');


});


/*
 * Drug display routes
 */
Route::group(['prefix' => 'drugs'], function () {

		Route::get('/', 'DrugController@index')->name('drug-index');

		Route::post('/', 'DrugController@search')->name('drug-search');

		Route::get('/page/{page}', 'DrugController@index');

		Route::get('/page/{page}/view/{psize}', 'DrugController@index');

		Route::get('/{id?}', 'DrugController@show')->name('drug-show');

});


/*
 * Gene Validity display routes
 */
Route::group(['prefix' => 'gene-validity'], function () {

		Route::get('/', 'ValidityController@index')->name('validity-index');

		Route::get('/page/{page}', 'ValidityController@index');

		Route::get('/page/{page}/view/{psize}', 'ValidityController@index');

		Route::get('/{id?}', 'ValidityController@show')->name('validity-show');

});


/*
 * Region display routes
 */
Route::group(['prefix' => 'regions'], function () {

	Route::get('/', 'RegionController@index')->name('region-index');

	Route::post('/', 'RegionController@index')->name('region-search');

});

/*
 * Conditions display routes
 */
Route::group(['prefix' => 'conditions'], function () {

		Route::get('/', 'ConditionController@index')->name('condition-index');

		Route::post('/', 'ConditionController@search')->name('condition-search');

		Route::get('/page/{page}', 'ConditionController@index');

		Route::get('/page/{page}/view/{psize}', 'ConditionController@index');

		Route::get('/{id?}', 'ConditionController@show')->name('condition-show');

		// TODO
		Route::get('/{id?}/external-resources', 'ConditionController@external')->name('condition-external');

});


/*
 * Affiliates display routes
 */
Route::group(['prefix' => 'affiliate'], function () {

	Route::get('/', 'AffiliateController@index')->name('affiliate-index');

	Route::get('/{id?}', 'AffiliateController@show')->name('affiliate-show');
});


/*
 * Dosage display routes
 */
Route::group(['prefix' => 'gene-dosage'], function () {

	Route::get('/', 'DosageController@index')->name('dosage-index');

	Route::get('/region_search', function () {
		return redirect()->route('dosage-index');
	});

	Route::post('/region_search', 'DosageController@region_search')->name('dosage-region-search');

	Route::get('/region_search/{type}/{region}', 'DosageController@region_search')->name('dosage-region-research');

	Route::get('/download', 'DosageController@download')->name('dosage-download');

	Route::get('/ftp', 'DosageController@ftps')->name('dosage-ftp');

	Route::get('/cnv', 'DosageController@cnv')->name('dosage-cnv');

	Route::get('/acmg59', 'DosageController@acmg59')->name('dosage-acmg59');

	Route::get('/{id?}', 'DosageController@show')->name('dosage-show');
});


/*
 * Actionability display routes
 */
Route::group(['prefix' => 'actionability'], function () {

	Route::get('/', 'ActionabilityController@index')->name('actionability-index');

	//Route::get('/{id?}', 'ActionabilityController@show')->name('actionability-show');
});

// ************************************************************************************************
// DEMO ROUTES
// ************************************************************************************************

		// Variant Path demo route
		// NOT FOR MVP
		Route::get('/variant-pathogenicity/all/', 'VariantPathController@index')->name('variant-path-index');
		Route::get('/variant-pathogenicity/detail/', 'VariantPathController@show')->name('variant-path-show');

		// Variant Path demo route
		// NOT FOR MVP
		Route::get('/publication/all/', 'PublicationController@index')->name('publication-index');
		Route::get('/publication/detail/', 'PublicationController@show')->name('publication-show');

		// New dosagw index page
		Route::get('/new-dosage', 'DosageController@newindex')->name('new-dosage-index');
		Route::get('/new-dosage/reports', 'DosageController@newreports')->name('dosage-reports');
		Route::get('/new-dosage/stats', 'DosageController@newstats')->name('dosage-stats');

// ************************************************************************************************
// DEMO ROUTES END
// ************************************************************************************************

Auth::routes();