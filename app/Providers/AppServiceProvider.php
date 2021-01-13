<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        // Lấy thông tin tên NNLT
        $pLanguageView = DB::table('languages')->select('name', 'code')->get();
        view()->share('pLanguageView', $pLanguageView);

    }
}
