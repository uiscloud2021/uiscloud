<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use App\Models\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Mandando todos los menus a todas las vistas existentes
        view()->composer('*', function ($view) {
            //$empresa_id=1;
            $user_id = auth()->id();
            if($user_id!=null){
                $user_name = auth()->user()->name;
                $files_block = File::where('user_block', $user_name)->count();

                $list_files = File::where('user_block', $user_name)
                ->orderBy('updated_at','DESC')->take(5)->get();

                $view->with(compact('files_block', 'list_files'));
            }
        });

        Schema::defaultStringLength(191);
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
