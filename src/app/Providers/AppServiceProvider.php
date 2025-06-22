<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


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
        Blade::anonymousComponentPath(resource_path('views/components'), 'components'); // グローバルコンポーネント用 (任意)
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin'); // 管理者用コンポーネント
        Blade::anonymousComponentPath(resource_path('views/user/components'), 'user');   // ユーザー用コンポーネント (任意)
    }
}
