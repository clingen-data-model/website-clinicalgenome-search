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

Route::get('/demo', 'DemoController@index')->name('home');


// ************************************************************************************************
// DEMO ROUTES START 
// ************************************************************************************************\


		// Gene demo route
		Route::get('/demo/gene/list/', 'DemoController@geneIndex')->name('gene-index');
		Route::get('/demo/gene/show/', 'DemoController@geneShow')->name('gene-show');

		// Dosage demo route
		Route::get('/demo/dosage/list/', 'DemoController@dosageIndex')->name('dosage-index');
		Route::get('/demo/dosage/detail/', 'DemoController@dosageShow')->name('dosage-show');
		Route::get('/demo/dosage/stats/', 'DemoController@dosageStats')->name('dosage-stats');
		Route::get('/demo/dosage/reports/', 'DemoController@dosageReports')->name('dosage-reports');
		Route::get('/demo/dosage/download/', 'DemoController@dosageDownload')->name('dosage-download');


		// Gene Validity demo route
		Route::get('/demo/gene-disease-validity/list/', 'DemoController@geneValidityIndex')->name('gene-disease-validity-index');
		Route::get('/demo/gene-disease-validity/detail/', 'DemoController@geneValidityShow')->name('gene-disease-validity-show');


		// Actionability demo route
		Route::get('/demo/actionability/list/', 'DemoController@actionabilityIndex')->name('actionability-index');
		Route::get('/demo/actionability/detail/', 'DemoController@actionabilityShow')->name('actionability-show');


		// Variant Path demo route
		Route::get('/demo/variant-path/list/', 'DemoController@varaintPathIndex')->name('variant-path-index');
		Route::get('/demo/variant-path/detail/', 'DemoController@varaintPathShow')->name('variant-path-show');

// ************************************************************************************************
// DEMO ROUTES END 
// ************************************************************************************************

Auth::routes();

Route::get('/home', 'HomeController@index')->name('dashboard');
