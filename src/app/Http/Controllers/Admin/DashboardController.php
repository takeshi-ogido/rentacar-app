<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 統計データを取得
        $totalReservations = Reservation::count();
        
        // 今月の売上（仮の計算 - 実際の料金計算ロジックに応じて調整）
        $monthlyRevenue = Reservation::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum(function($reservation) {
                // 仮の料金計算（実際の料金計算ロジックに応じて調整）
                return $reservation->car->price ?? 0;
            });
        
        // 利用可能車両数
        $availableCars = Car::where('is_public', true)->count();
        
        // アクティブユーザー数（過去30日以内にログインしたユーザー、またはlast_login_atがnullのユーザー）
        $activeUsers = User::where(function($query) {
            $query->where('last_login_at', '>=', Carbon::now()->subDays(30))
                  ->orWhereNull('last_login_at');
        })->count();
        
        // 最近の予約（最新10件）
        $recentReservations = Reservation::with(['car.carModel'])
            ->latest()
            ->take(10)
            ->get();

        // 車種別売上データ（今月）
        $carModelSales = DB::table('reservations')
            ->join('cars', 'reservations.car_id', '=', 'cars.id')
            ->join('car_models', 'cars.car_model_id', '=', 'car_models.id')
            ->whereMonth('reservations.created_at', Carbon::now()->month)
            ->whereYear('reservations.created_at', Carbon::now()->year)
            ->select('car_models.car_model', DB::raw('SUM(cars.price) as total_sales'))
            ->groupBy('car_models.id', 'car_models.car_model')
            ->orderBy('total_sales', 'desc')
            ->get()
            ->toArray();

        // 車種別売上にパーセントを追加
        $totalCarSales = collect($carModelSales)->sum('total_sales');
        $carModelSales = collect($carModelSales)->map(function($item) use ($totalCarSales) {
            return [
                'car_model' => $item->car_model,
                'total_sales' => $item->total_sales,
                'percentage' => $totalCarSales > 0 ? round(($item->total_sales / $totalCarSales) * 100, 1) : 0
            ];
        });

        return view('admin.dashboard', compact(
            'totalReservations',
            'monthlyRevenue',
            'availableCars',
            'activeUsers',
            'recentReservations',
            'carModelSales'
        ));
    }
} 