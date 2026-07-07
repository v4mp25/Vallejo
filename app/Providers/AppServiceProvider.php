<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('*', function ($view) {
            $config = \App\Models\ConfiguracionWeb::first() ?? \App\Models\ConfiguracionWeb::create([
                'frase_topbar' => 'Formamos líderes con corazón vallejiano',
                'telefono_contacto' => '+51 927 736 128',
                'correo_contacto' => 'contacto@cesarvallejo.edu.pe'
            ]);
            $view->with('config', $config);
        });
    }
}
