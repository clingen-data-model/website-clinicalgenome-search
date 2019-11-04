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


Route::get('/', 'HomeController@index')->name('home');

/*
 * Test route - remove for production
 */
Route::get('/test/test', 'TestController@test')->name('test-test');


/*
 * Gene display routes
 */
Route::group(['prefix' => 'genes'], function () {
	
		Route::get('/', 'GeneController@index')->name('gene-index');
		
		Route::get('/page/{page}', 'GeneController@index');
		
		Route::get('/page/{page}/view/{psize}', 'GeneController@index');
		
		Route::get('/{id?}', 'GeneController@show')->name('gene-show');
		
		Route::get('/curations/', 'GeneController@curated')->name('gene-curations');
		
});

/*
 * Gene Validity display routes
 */
Route::group(['prefix' => 'validity'], function () {
	
		Route::get('/', 'ValidityController@index')->name('validity-index');
		
		Route::get('/page/{page}', 'ValidityController@index');
		
		Route::get('/page/{page}/view/{psize}', 'ValidityController@index');
		
		Route::get('/{id?}', 'ValidityController@show')->name('validity-show');
				
});


		// Scotts stuff :)

		// Condition demo route
		Route::get('/condition/all/', 'ConditionController@index')->name('condition-index');
		Route::get('/condition/show/', 'ConditionController@show')->name('condition-show');
		Route::get('/condition/curations/', 'ConditionController@curated')->name('condition-curations');

		// Dosage demo route
		Route::get('/dosage/all/', 'DosageController@index')->name('dosage-index');
		Route::get('/dosage/detail/', 'DosageController@show')->name('dosage-show');
		Route::get('/dosage/stats/', 'DosageController@stats')->name('dosage-stats');
		Route::get('/dosage/reports/', 'DosageController@reports')->name('dosage-reports');
		Route::get('/dosage/download/', 'DosageController@download')->name('dosage-download');


		// Gene Validity demo route
		Route::get('/gene-disease-validity/all/', 'GeneValidityController@index')->name('gene-disease-validity-index');
		Route::get('/gene-disease-validity/detail/', 'GeneValidityController@show')->name('gene-disease-validity-show');


		// Actionability demo route
		Route::get('/actionability/all/', 'ActionabilityController@index')->name('actionability-index');
		Route::get('/actionability/detail/', 'ActionabilityController@show')->name('actionability-show');


		// Variant Path demo route
		Route::get('/variant-path/all/', 'VariantPathController@index')->name('variant-path-index');
		Route::get('/variant-path/detail/', 'VariantPathController@show')->name('variant-path-show');

		// Variant Path demo route
		Route::get('/publication/all/', 'PublicationController@index')->name('publication-index');
		Route::get('/publication/detail/', 'PublicationController@show')->name('publication-show');

// ************************************************************************************************
// DEMO ROUTES END 
// ************************************************************************************************

Auth::routes();

Route::get('/me', 'HomeController@index')->name('dashboard');
