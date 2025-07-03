<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController as AdminRegisteredUserController;
use App\Http\Controllers\Admin\Auth\ProfileController as AdminProfileController; 
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\User\Auth\ProfileController as UserProfileController; 
use App\Http\Controllers\User\CarController;
use App\Http\Controllers\User\MypageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ReservationController;
use Illuminate\Support\Facades\Mail;

// 管理者側ルーティング
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.login'));

    // 未認証の管理者（ゲスト）のみアクセス可能なルート
    Route::middleware('guest:admin')->group(function() {
        Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthenticatedSessionController::class, 'store']);
        Route::get('/register', [AdminRegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [AdminRegisteredUserController::class, 'store']);
    });

    // 認証済みの管理者のみアクセス可能なルート
    Route::middleware('auth:admin')->group(function () {
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

    });

    // 認証済みの管理者向け
    // 管理者側のヘッダー
    Route::middleware('auth:admin')->group(function () { // 'admin' ガードを想定
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 車両管理
        Route::resource('cars', AdminCarController::class)->except(['show']); 
        Route::get('cars/{car}', [AdminCarController::class, 'show'])->name('cars.show');
        Route::patch('cars/{car}/toggle-publish', [AdminCarController::class, 'togglePublish'])->name('cars.togglePublish');

        // 予約管理
        Route::resource('reservations', AdminReservationController::class);
        Route::get('reservations/create', [AdminReservationController::class, 'create'])->name('reservations.create');
        Route::post('reservations', [AdminReservationController::class, 'store'])->name('reservations.store');

        // カレンダー管理
        Route::get('calendar', [App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('calendar.index');
        Route::get('calendar/reservations', [App\Http\Controllers\Admin\CalendarController::class, 'getReservations'])->name('calendar.reservations');

        // 顧客管理
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        // 売上管理
        Route::get('/reports', [SalesController::class, 'index'])->name('reports.sales');

        // システム設定
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        // 管理者ログアウト
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

// ユーザー側
Route::get('/', fn() => view('welcome'))->name('welcome');
Route::get('/pricing', fn() => view('user.store.pricing'))->name('store.pricing');
Route::get('/store-info', fn() => view('user.store.info'))->name('store.info');

// 車両一覧・詳細
Route::get('/cars', [CarController::class, 'index'])->name('user.cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('user.cars.show');

// 予約フロー
Route::prefix('/cars/{car}/reservations')->name('user.cars.reservations.')->group(function () {
    Route::get('option-confirm', [ReservationController::class, 'showOptionConfirm'])->name('show-option-confirm');
    Route::post('car-confirm', [ReservationController::class, 'carConfirm'])->name('car-confirm');
    Route::get('input', [ReservationController::class, 'input'])->name('input');
    Route::post('final-confirm', [ReservationController::class, 'finalConfirm'])->name('final-confirm');
    Route::post('store', [ReservationController::class, 'reserved'])->name('reserved');
    Route::get('complete/{reservation}', [ReservationController::class, 'complete'])->name('complete');
});

// 認証済みユーザー向け
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');
});

// 認証関連
require __DIR__.'/auth.php';

// テスト用ルート（開発環境でのみ有効）
if (app()->isLocal()) {
    Route::get('/test-mail', function () {
        Mail::raw('テストメールの本文です。', function ($message) {
            $message->to(config('mail.admin_address'))->subject('Laravelからのテストメール');
        });
        return 'テストメールを送信しました。';
    });
}
