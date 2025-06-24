<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController as AdminRegisteredUserController;
use App\Http\Controllers\Admin\Auth\ProfileController as AdminProfileController; 
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\User\Auth\ProfileController as UserProfileController; 
use App\Http\Controllers\User\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ReservationController;
use Illuminate\Support\Facades\Mail;


// 管理者側ルーティング
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.login'));

    // 未認証の管理者向け
    Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthenticatedSessionController::class, 'store']);

    // 管理者登録画面表示
    Route::get('/register', [AdminRegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [AdminRegisteredUserController::class, 'store']); // 登録処理

    // ログイン後の管理者画面の編集、更新、削除
    Route::middleware('auth:admin')->group(function () {
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

    });

    // 認証済みの管理者向け
    // 管理者側のヘッダー
    Route::middleware('auth:admin')->group(function () { // 'admin' ガードを想定
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // admin.dashboard ビューを作成する必要あり
        })->name('dashboard');

        // 車両管理
        Route::resource('cars', AdminCarController::class)->except(['show']); 
        Route::patch('cars/{car}/toggle-publish', [AdminCarController::class, 'togglePublish'])->name('cars.togglePublish');

        // 顧客管理
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');

        // 売上管理
        Route::get('/reports', function () {
            return view('admin.reports.sales');
        })->name('reports.sales');

        // システム設定
        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('settings.index');


        // 管理者ログアウト
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});






// ユーザー側
Route::get('/', function () {
    return view('welcome');
    })->name('welcome');

// 1. 空車一覧（検索結果）
Route::get('/cars', [CarController::class, 'index'])->name('user.cars.index');

// 2. 車の詳細画面
Route::get('/cars/{car}', [CarController::class, 'show'])->name('user.cars.show');

// 3～7. 予約フロー（car単位にまとめる）
Route::prefix('/cars/{car}/reservations')->name('user.cars.reservations.')->group(function () {

    // ★変更：ステップ1-A：オプション等確認画面表示 (GET)
    Route::get('option-confirm', [ReservationController::class, 'showOptionConfirm'])
        ->name('show-option-confirm');

    // ステップ1-B：オプション等確認後、セッション保存しお客様情報入力へ (POST)
    Route::post('car-confirm', [ReservationController::class, 'carConfirm'])
        ->name('car-confirm');

    // 4. ステップ2：お客様情報入力画面（GET）
    Route::get('input', [ReservationController::class, 'input'])
        ->name('input');

    // 5. ステップ3：最終確認画面（POST）
    Route::post('final-confirm', [ReservationController::class, 'finalConfirm'])
        ->name('final-confirm');

    // 6. ステップ4：予約保存（POST）
    Route::post('store', [ReservationController::class, 'reserved'])
        ->name('reserved');

    // 7. 予約完了画面（GET）
    Route::get('complete/{reservation}', [ReservationController::class, 'complete'])
        ->name('complete');

        // routes/web.php
    Route::get('/test-mail', function () {
        Mail::raw('テストメールの本文です。', function ($message) {
            $message->to('あなた自身の別メールアドレス') // 宛先アドレスに自分の他のアドレスなど
                    ->subject('Laravelからのテストメール');
        });

        return 'メール送信完了';
    });

});




// 料金表
Route::get('/pricing', function () {
    return view('user.store.pricing');
})->name('store.pricing');

// 店舗情報
Route::get('/store-info', function () {
    return view('user.store.info');
})->name('store.info');

// ログイン後のユーザー画面
Route::get('/mypage', function () {
    return view('user.mypage');
})->middleware(['auth', 'verified'])->name('mypage');

// ログイン後のユーザー画面の編集、更新、削除
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');
});

// ログイン不要で予約可能
Route::prefix('user')->name('user.')->group(function () {
    // ログイン不要でアクセス可能
    Route::post('/reservations/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
});

require __DIR__.'/auth.php';
