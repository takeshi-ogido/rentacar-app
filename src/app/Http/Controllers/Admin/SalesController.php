<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        // 期間フィルター
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // 統計データ
        $totalRevenue = Reservation::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->sum(function($reservation) {
                return $reservation->car->price ?? 0;
            });

        $monthlyRevenue = Reservation::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum(function($reservation) {
                return $reservation->car->price ?? 0;
            });

        $monthlyReservations = Reservation::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $averagePrice = $monthlyReservations > 0 ? $monthlyRevenue / $monthlyReservations : 0;

        // 月別売上データ（過去12ヶ月）
        $monthlySalesData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlySales = Reservation::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->get()
                ->sum(function($reservation) {
                    return $reservation->car->price ?? 0;
                });
            
            $monthlySalesData[] = [
                'month' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
                'sales' => $monthlySales
            ];
        }

        // 車種別売上データ
        $carModelSales = DB::table('reservations')
            ->join('cars', 'reservations.car_id', '=', 'cars.id')
            ->join('car_models', 'cars.car_model_id', '=', 'car_models.id')
            ->whereBetween('reservations.created_at', [$startDate, $endDate])
            ->select('car_models.car_model', DB::raw('SUM(cars.price) as total_sales'))
            ->groupBy('car_models.id', 'car_models.car_model')
            ->orderBy('total_sales', 'desc')
            ->get();

        // 車種別売上にパーセントを追加
        $totalCarSales = $carModelSales->sum('total_sales');
        $carModelSales = $carModelSales->map(function($item) use ($totalCarSales) {
            $item->percentage = $totalCarSales > 0 ? round(($item->total_sales / $totalCarSales) * 100, 1) : 0;
            return $item;
        });

        // 日別売上データ（過去30日）
        $dailySalesData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailySales = Reservation::whereDate('created_at', $date)
                ->get()
                ->sum(function($reservation) {
                    return $reservation->car->price ?? 0;
                });
            
            $dailySalesData[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('M d'),
                'sales' => $dailySales
            ];
        }

        // 売上詳細データ
        $salesDetails = Reservation::with(['car.carModel'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20);

        return view('admin.reports.sales', compact(
            'totalRevenue',
            'monthlyRevenue',
            'monthlyReservations',
            'averagePrice',
            'monthlySalesData',
            'carModelSales',
            'dailySalesData',
            'salesDetails',
            'startDate',
            'endDate'
        ));
    }
} 