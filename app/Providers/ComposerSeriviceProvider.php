<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\UserComposer;
use App\Http\ViewComposers\AdminUserComposer;

class ComposerSeriviceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      View::composers([
        AdminUserComposer::class => [
          'admin.layout',
        ],
        UserComposer::class => [
          'layout',
          'regist',
          'home',
          'passwords.send',
          'passwords.change',
        ],
      ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
