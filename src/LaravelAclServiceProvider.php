<?php

namespace Palano\LaravelAcl;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LaravelAclServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations/' => base_path('/database/migrations')
        ], 'migrations');

        $this->registerBladeRole();
        $this->registerBladePermission();
        $this->registerBladeAbility();
    }

    protected function registerBladeRole()
    {
        Blade::directive('ifRole', function ($expression) {
            return "<?php if(Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });

        Blade::directive('elseifRole', function ($expression) {
            return "<?php elseif(Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });

        Blade::directive('endifRole', function ($expression) {
            return "<?php endif; ?>";
        });
    }

    protected function registerBladePermission()
    {
        Blade::directive('ifPermission', function ($expression) {
            return "<?php if(Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('elseifPermission', function ($expression) {
            return "<?php elseif(Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endifPermission', function ($expression) {
            return "<?php endif; ?>";
        });
    }

    protected function registerBladeAbility()
    {
        Blade::directive('ifAbility', function ($expression) {
            return "<?php if(Auth::check() && Auth::user()->hasAbility({$expression})): ?>";
        });

        Blade::directive('elseifAbility', function ($expression) {
            return "<?php elseif(Auth::check() && Auth::user()->hasAbility({$expression})): ?>";
        });

        Blade::directive('endifAbility', function ($expression) {
            return "<?php endif; ?>";
        });
    }
}
