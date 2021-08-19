<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->load_helpers();
    }


    /**
     * Load global helpers
     *
     * @return void
     */
    protected function load_helpers()
    {
        foreach (glob(__DIR__.'/../Helpers/*.php') as $filename) {

            if (basename($filename) == "helper.php")
                continue;

            require_once $filename;
        }
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
