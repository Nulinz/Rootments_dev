<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // require_once app_path('helper/helper.php');

         // Get the current Git branch
        //  $branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

        //  // Default DB name
        //  $dbName = 'rootments';

        //  // Set the DB name based on the current branch
        //  if (($branch === 'main')||($branch === 'error')) {
        //      $dbName = 'rootments';
        //  }
        //  else {
        //      // For any other branch, append the branch name to the DB name
        //      $dbName = 'rootments_' . $branch;
        //  }

        //  // Dynamically update the DB_DATABASE config
        //  Config::set('database.connections.mysql.database', $dbName);
        
         View::composer('*', function ($view) {
            $view->with('isImpersonating', session()->has('impersonator_id'));
        });

    }
}
