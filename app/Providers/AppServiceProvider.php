<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Response;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_DEBUG')) {
            DB::listen(function($query) {
                File::append(
                    storage_path('/logs/query.log'),
                    $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
                );
                /*Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );*/
            });
        }
        //rvr 27/5/2019
        Response::macro('attachmentCSV', function ($content,$name) {

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'.$name,
            ];

            return Response::make($content, 200, $headers);

        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
