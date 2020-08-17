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
Route::get('/test', 'TestController@index')->name('test-index');
Route::get('/test/reports', 'TestController@reports')->name('dosage-reports');
Route::get('/test/stats', 'TestController@stats')->name('dosage-stats');
Route::get('/test/show', 'TestController@show')->name('gene-dosage-show');
Route::get('/test/download', 'TestController@download')->name('dosage-download');

/*
 *	download dosage csv
 */
Route::get('/gene-dosage/download', 'DosageController@download')->name('dosage-download');


/*
 * Gene display routes
 */
Route::group(['prefix' => 'genes'], function () {

		Route::get('/', 'GeneController@index')->name('gene-index');

		Route::get('/page/{page}', 'GeneController@index');

		Route::get('/page/{page}/view/{psize}', 'GeneController@index');

		Route::get('/curations/', 'GeneController@curated')->name('gene-curations');

		Route::get('/{id?}', 'GeneController@show')->name('gene-show');

		Route::get('/{id?}/external-resources', 'GeneController@external')->name('gene-external');


});


/*
 * Drug display routes
 */
Route::group(['prefix' => 'drugs'], function () {

		Route::get('/', 'DrugController@index')->name('drug-index');

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
 * Conditions display routes
 */
Route::group(['prefix' => 'conditions'], function () {

		Route::get('/', 'ConditionController@index')->name('condition-index');

		Route::get('/page/{page}', 'ConditionController@index');

		Route::get('/page/{page}/view/{psize}', 'ConditionController@index');

		Route::get('/{id?}', 'ConditionController@show')->name('condition-show');

});

/*
 * Gene display routes
 */
Route::group(['prefix' => 'affiliate'], function () {

	Route::get('/', 'AffiliateController@index')->name('affiliate-index');

	Route::get('/{id?}', 'AffiliateController@show')->name('affiliate-show');
});


		// Scotts stuff :)

		// Condition demo route
		// STATIC PAGES FOR REFERENCE
		Route::get('/disease/all/', 'DiseaseController@index')->name('disease-index');
		Route::get('/disease/show/', 'DiseaseController@show')->name('disease-show');


		// Dosage demo route
		// STATIC PAGES FOR REFERENCE
		Route::get('/gene-dosage/', 'DosageController@index')->name('dosage-index');

		// SHOW ISN"T NEEDED - MVP IS A REDIRECT
		Route::get('/gene-dosage/{id}', 'DosageController@show')->name('dosage-show');


		// Gene Validity demo route
		// STATIC PAGES FOR REFERENCE
		// Route::get('/gene-disease-validity/all/', 'GeneValidityController@index')->name('gene-disease-validity-index');
		// Route::get('/gene-disease-validity/detail/', 'GeneValidityController@show')->name('gene-disease-validity-show');


		// Actionability demo route
		Route::get('/actionability/', 'ActionabilityController@index')->name('actionability-index');
		Route::get('/actionability/detail/', 'ActionabilityController@show')->name('actionability-show');


		// Variant Path demo route
		// NOT FOR MVP
		Route::get('/variant-pathogenicity/all/', 'VariantPathController@index')->name('variant-path-index');
		Route::get('/variant-pathogenicity/detail/', 'VariantPathController@show')->name('variant-path-show');

		// Variant Path demo route
		// NOT FOR MVP
		Route::get('/publication/all/', 'PublicationController@index')->name('publication-index');
		Route::get('/publication/detail/', 'PublicationController@show')->name('publication-show');

// ************************************************************************************************
// DEMO ROUTES END
// ************************************************************************************************

Auth::routes();

Route::get('/me', 'HomeController@index')->name('dashboard');
