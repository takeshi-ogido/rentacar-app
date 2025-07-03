<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        // 現在の設定値を取得
        $settings = [
            'company_name' => config('app.name', 'レンタカーシステム'),
            'company_email' => config('mail.from.address', 'info@example.com'),
            'company_phone' => config('app.phone', '03-1234-5678'),
            'company_address' => config('app.address', '東京都渋谷区...'),
            'business_hours' => config('app.business_hours', '9:00-18:00'),
            'reservation_advance_days' => config('app.reservation_advance_days', 30),
            'cancellation_hours' => config('app.cancellation_hours', 24),
            'deposit_amount' => config('app.deposit_amount', 50000),
            'late_return_fee_per_hour' => config('app.late_return_fee_per_hour', 1000),
            'fuel_surcharge' => config('app.fuel_surcharge', 0),
            'insurance_fee' => config('app.insurance_fee', 2000),
            'maintenance_mode' => config('app.maintenance_mode', false),
            'max_reservations_per_user' => config('app.max_reservations_per_user', 3),
            'auto_confirm_reservations' => config('app.auto_confirm_reservations', false),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string|max:500',
            'business_hours' => 'required|string|max:100',
            'reservation_advance_days' => 'required|integer|min:1|max:365',
            'cancellation_hours' => 'required|integer|min:0|max:168',
            'deposit_amount' => 'required|integer|min:0',
            'late_return_fee_per_hour' => 'required|integer|min:0',
            'fuel_surcharge' => 'required|integer|min:0',
            'insurance_fee' => 'required|integer|min:0',
            'maintenance_mode' => 'boolean',
            'max_reservations_per_user' => 'required|integer|min:1|max:10',
            'auto_confirm_reservations' => 'boolean',
        ]);

        // 設定値を更新（実際の実装ではデータベースや設定ファイルに保存）
        $settings = $request->only([
            'company_name', 'company_email', 'company_phone', 'company_address',
            'business_hours', 'reservation_advance_days', 'cancellation_hours',
            'deposit_amount', 'late_return_fee_per_hour', 'fuel_surcharge',
            'insurance_fee', 'maintenance_mode', 'max_reservations_per_user',
            'auto_confirm_reservations'
        ]);

        // チェックボックスの値を適切に処理
        $settings['maintenance_mode'] = $request->has('maintenance_mode');
        $settings['auto_confirm_reservations'] = $request->has('auto_confirm_reservations');

        // 設定をキャッシュに保存（実際の実装ではデータベースに保存）
        Cache::put('system_settings', $settings, now()->addDays(30));

        return redirect()->route('admin.settings.index')
            ->with('success', 'システム設定を更新しました。');
    }
} 