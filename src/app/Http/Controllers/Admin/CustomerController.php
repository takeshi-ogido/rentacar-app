<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 名前または電話番号検索
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // 予約日付フィルタ（日付範囲）
        if ($startDate = $request->input('start_date')) {
            $query->whereHas('reservations', function ($q) use ($startDate) {
                $q->whereDate('start_datetime', '>=', $startDate);
            });
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereHas('reservations', function ($q) use ($endDate) {
                $q->whereDate('end_datetime', '<=', $endDate);
            });
        }

        // ページネーションで取得
        $customers = $query->with(['reservations' => function ($q) use ($startDate, $endDate) {
            if ($startDate) {
                $q->whereDate('start_datetime', '>=', $startDate);
            }
            if ($endDate) {
                $q->whereDate('end_datetime', '<=', $endDate);
            }
            $q->with(['car.carModel']);
        }])->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        // 顧客の詳細情報と予約履歴を取得
        $customer->load(['reservations' => function ($query) {
            $query->with(['car.carModel'])
                  ->orderBy('start_datetime', 'desc');
        }]);

        // 統計情報を計算
        $totalReservations = $customer->reservations->count();
        $totalSpent = $customer->reservations->sum('total_price');
        $averageReservationValue = $totalReservations > 0 ? $totalSpent / $totalReservations : 0;
        
        // 最近の予約（過去6ヶ月）
        $recentReservations = $customer->reservations()
            ->where('start_datetime', '>=', now()->subMonths(6))
            ->with(['car.carModel'])
            ->orderBy('start_datetime', 'desc')
            ->get();

        return view('admin.customers.show', compact('customer', 'totalReservations', 'totalSpent', 'averageReservationValue', 'recentReservations'));
    }
}