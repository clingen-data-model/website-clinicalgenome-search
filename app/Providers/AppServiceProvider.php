<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
        Builder::macro('whereLike', function ($attributes, string $searchTerms) {
            $this->where(function($query) use ($attributes, $searchTerms) {
                foreach(Arr::wrap($attributes) as $attribute) {
                    $query->orWhere(function($query) use ($attribute, $searchTerms) {
                        foreach(explode(' ', $searchTerms) as $searchTerm) {
                            $query->where($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    });
                }
            });
            return $this;
        });

        Builder::macro('toRawSql', function() {
            dd(vsprintf(str_replace(
                ['?'], ['\'%s \''], $this->toSql()), $this->getBindings()
            ));
        });
    }
}
