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

Route::get('/{id}', 'HomeController@ccid')->where(['id' => 'CCID:[0-9]+'])->name('test-ccid');

Route::get('/', function () {

	return redirect()->route('gene-curations');

});

Route::get('/CCID:{id}', 'TestController@ccid')->where(['id' => 'CCID:[0-9]+'])->name('test-ccid');

Route::get('/kb/curations', function () {

	return redirect()->route('gene-curations');
});



Auth::routes(['verify' => true]);

Route::get('/kb', function () {

	return redirect()->route('gene-curations');

})->name('home');


Route::group(['prefix' => '/dashboard'], function () {
	Route::get('/{message?}', 'HomeController@index')->name('dashboard-index');
	Route::get('/profile', 'HomeController@profile')->name('dashboard-profile');
	Route::post('/profile', 'HomeController@update_profile')->name('dashboard-update-profile');
	Route::get('/preferences', 'HomeController@preferences')->name('dashboard-preferences');
	Route::post('/preferences', 'HomeController@update')->name('dashboard-update');
	Route::get('/reports', 'HomeController@reports')->name('dashboard-reports');
	Route::post('/reports', 'HomeController@create_reports')->name('dashboard-new-reports');
	Route::get('/reports/{id}', 'HomeController@show_report')->name('dashboard-show-report');
    Route::post('/region', 'HomeController@create_region')->name('dashboard-region-search');
});

Route::get('/reports/view/{id}', 'HomeController@view')->name('dashboard-show-report');
Route::get('/members', 'MembersController@index')->name('members.index');
Route::post('/members/sync', 'MembersController@sync')->name('members.sync');

/*
 * Gene display routes
 */
Route::group(['prefix' => 'kb/genes'], function () {

		Route::get('/', 'GeneController@index')->name('gene-index');

		Route::get('/acmgsf', 'GeneController@acmg_index')->name('acmg-index');

		Route::post('/', 'GeneController@search')->name('gene-search');

        Route::post('/search-by-name', 'GeneController@searchByName')->name('gene-name-search');

		Route::get('/page/{page}', 'GeneController@index');

		Route::get('/page/{page}/view/{psize}', 'GeneController@index');

		Route::get('/curations/', 'GeneController@curated')->name('gene-curations');

		Route::get('/{id?}', 'GeneController@show_by_activity')->name('gene-show');

		//Route::get('/{id?}/by-activity', 'GeneController@show_by_activity')->name('gene-by-activity');

		Route::get('/{id?}/by-disease', 'GeneController@show_by_disease')->name('gene-by-disease');

		Route::get('/{id?}/groups', 'GeneController@show_groups')->name('gene-groups');

		Route::get('/{id?}/external-resources', 'GeneController@external')->name('gene-external');

		Route::get('/{id?}/genomeconnect', 'GeneController@show_genomeconnect')->name('gene-genomeconnect');

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

		Route::get('/download/ls', 'ValidityController@download_ls')->name('validity-ls-download');

		Route::get('/page/{page}', 'ValidityController@index');

		Route::get('/page/{page}/view/{psize}', 'ValidityController@index');

		Route::get('/{id?}', 'ValidityController@show')->name('validity-show');

        Route::post('feedback', 'ValidityController@feedback');

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

        Route::get('/{id?}/groups', 'ConditionController@show_groups')->name('condition-groups');

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

	Route::get('/downloadall', 'DosageController@downloadall')->name('dosagefull-download');


	Route::get('/ftp', function () {
		return redirect(route('download-index'), 301);
	});

	//Route::get('/ftp', 'DosageController@ftps')->name('dosage-ftp');

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

    Route::get('/report-index', 'ActionabilityController@report_index')->name('actionability-report-index-index');

	//Route::get('/{id?}', 'ActionabilityController@show')->name('actionability-show');
});

/*
 * Actionability display routes
 */
Route::group(['prefix' => 'kb/downloads'], function () {

	Route::get('/', 'HomeController@downloads')->name('download-index');

	//Route::get('/{id?}', 'ActionabilityController@show')->name('actionability-show');
});


/*
 * Stats & Others display routes
 */
Route::group(['prefix' => 'kb/reports'], function () {

	Route::get('/stats/{sort?}', 'ReportController@statistics')->name('stats-index');
	Route::get('/curation-activity-summary-report', 'ReportController@genesReportDownload')->name('curation-activity-summary-cvs');
	Route::get('/acmg-activity-summary-report', 'ReportController@acmgReportDownload')->name('acmg-activity-summary-cvs');


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

Route::post('/kb/genomeconnect/upload', 'HomeController@gc_upload')->name('gencon-upload');

Auth::routes();
