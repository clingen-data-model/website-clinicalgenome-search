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
		// STATIC PAGES FOR REFERENCE
		Route::get('/disease/all/', 'DiseaseController@index')->name('disease-index');
		Route::get('/disease/show/', 'DiseaseController@show')->name('disease-show');

		// Drugs demo route
		// STATIC PAGES FOR REFERENCE
		Route::get('/drug/', 'DrugController@index')->name('drug-index');
		Route::get('/drug/show/', 'DrugController@show')->name('drug-show');

		// Dosage demo route
		// STATIC PAGES FOR REFERENCE
		Route::get('/dosage/', 'DosageController@index')->name('dosage-index');

		// SHOW ISN"T NEEDED - MVP IS A REDIRECT
		Route::get('/dosage/detail/', 'DosageController@show')->name('dosage-show');


		// Gene Validity demo route
		// STATIC PAGES FOR REFERENCE
		 Route::get('/gene-disease-validity/all/', 'GeneValidityController@index')->name('gene-disease-validity-index');
		 Route::get('/gene-disease-validity/detail/', 'GeneValidityController@show')->name('gene-disease-validity-show');


		// Actionability demo route
		Route::get('/actionability/', 'ActionabilityController@index')->name('actionability-index');
		Route::get('/actionability/detail/', 'ActionabilityController@show')->name('actionability-show');


		// Variant Path demo route
		// NOT FOR MVP
		Route::get('/variant-path/all/', 'VariantPathController@index')->name('variant-path-index');
		Route::get('/variant-path/detail/', 'VariantPathController@show')->name('variant-path-show');

		// Variant Path demo route
		// NOT FOR MVP
		Route::get('/publication/all/', 'PublicationController@index')->name('publication-index');
		Route::get('/publication/detail/', 'PublicationController@show')->name('publication-show');

// ************************************************************************************************
// DEMO ROUTES END
// ************************************************************************************************

Auth::routes();

Route::get('/me', 'HomeController@index')->name('dashboard');
