<?php
/**
 * Created by IntelliJ IDEA.
 * User: subangkit
 * Date: 2019-11-13
 * Time: 14:48
 */

namespace BlackIT\FCMAble;

use Illuminate\Support\ServiceProvider;

class FCMAbleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred
     */
    protected $defer = false;
    /**
     * Config path of midtrans packages
     */
    private $config_path = __DIR__ . '/../config/fcm.php';
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/fcm.php' => config_path('fcm.php'),
        ],'config');

        $this->publishes([
            __DIR__ . '/../public/firebase-messaging-sw.js' => public_path('firebase-messaging-sw.js'),
        ],'public');

        $this->publishes([
            __DIR__.'/../migrations/2019_11_13_143008_create_table_fcm_tokens.php'
            => database_path('migrations/'.date('Y').'_'.str_pad(date('m'), 2, "0", STR_PAD_LEFT).'_'.str_pad(date('d'), 2, "0", STR_PAD_LEFT).'_000000_create_table_fcm_tokens.php'),
        ], 'migrations');
    }
}
