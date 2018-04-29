<?php

namespace Shridhar\Sms;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->publishes([
            __DIR__ . "/config.php" => config_path("sms.php")
        ]);
    }

}
