<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $credentialsPath = config('firebase.credentials.file');
            $databaseUrl = config('firebase.database.url');

            if (is_null($credentialsPath) || !file_exists($credentialsPath)) {
                throw new \Exception('Firebase credentials file not found: ' . $credentialsPath);
            }

            return (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri($databaseUrl);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
