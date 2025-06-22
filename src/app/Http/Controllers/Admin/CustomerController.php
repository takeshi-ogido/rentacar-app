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
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 予約日付フィルタ（日付範囲）
        if ($startDate = $request->input('start_date')) {
            $query->whereHas('reservations', function ($q) use ($startDate) {
                $q->whereDate('start_date', '>=', $startDate);
            });
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereHas('reservations', function ($q) use ($endDate) {
                $q->whereDate('end_date', '<=', $endDate);
            });
        }

        // ページネーションで取得
        $customers = $query->with(['reservations' => function ($q) use ($startDate, $endDate) {
            if ($startDate) {
                $q->whereDate('start_date', '>=', $startDate);
            }
            if ($endDate) {
                $q->whereDate('end_date', '<=', $endDate);
            }
        }])->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }
}