<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ------------------------
        // 1. 日時の処理
        // ------------------------
        $startDateTime = null;
        $endDateTime = null;

        if ($request->filled('start_date') && $request->filled('start_time')) {
            $startInput = $request->input('start_date') . ' ' . $request->input('start_time');
            try {
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startInput);
            } catch (\Exception $e) {
                $startDateTime = null; // フォーマットエラー時はnull
            }
        }

        if ($request->filled('end_date') && $request->filled('end_time')) {
            $endInput = $request->input('end_date') . ' ' . $request->input('end_time');
            try {
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $endInput);
            } catch (\Exception $e) {
                $endDateTime = null;
            }
        }

        // ------------------------
        // 2. クエリ構築
        // ------------------------
        $query = Car::query();

        // 車種フィルター
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // 乗車人数フィルター（以上）
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->input('capacity'));
        }

        // 予約重複チェック: 指定された期間に予約が入っていない車のみを対象とする
        if ($startDateTime && $endDateTime && $endDateTime->gt($startDateTime)) {
            $query->whereDoesntHave('reservations', function ($q) use ($startDateTime, $endDateTime) {
                $q->where('status', 'confirmed') // 'confirmed' ステータスの予約のみを考慮
                  ->where(function ($subQuery) use ($startDateTime, $endDateTime) {
                    // 既存の予約が指定期間と少しでも重なる場合は除外
                    // 条件: (予約開始 < 指定終了 AND 予約終了 > 指定開始)
                    $subQuery->where('start_datetime', '<', $endDateTime)
                             ->where('end_datetime', '>', $startDateTime);
                });
            });
        }

        // 並び替え
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'capacity_desc':
                $query->orderBy('capacity', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 関連画像と一緒に取得
        $cars = $query->with('images')->paginate(10)->through(function ($car) use ($startDateTime, $endDateTime) {
            // ページネーションされた各車両に対して料金計算ロジックを適用
            if ($startDateTime && $endDateTime && $endDateTime->gt($startDateTime)) { // 有効な期間が指定されている場合
                // 料金計算用の日数 (24時間単位で切り上げ)
                // 料金計算用の日数: カレンダー上の日数を使用 (最低1日)
                $displayNights = $startDateTime->copy()->startOfDay()->diffInDays($endDateTime->copy()->startOfDay());
                $displayDays = $displayNights + 1;
                $isDayTrip = ($displayNights === 0); // 0泊なら日帰り

                $billingDays = $displayDays; // 料金計算には表示用の日数を使用
                $car->totalPrice = $car->price * $billingDays;

                $nights = $displayNights; // durationLabelで使用するため
                $days = $displayDays; // durationLabelで使用するため
                
                if ($isDayTrip) { // isDayTripを使用
                    $car->durationLabel = '日帰り';
                } else {
                    $car->durationLabel = "{$nights}泊{$days}日";
                }
            } else {
                // 日数・泊数・同日判定・合計料金のデフォルト値を設定
                $car->totalPrice = $car->price; // 期間未指定の場合は1日あたりの料金を合計料金として表示
                $car->durationLabel = '日帰り'; // 期間未指定の場合は「日帰り」と表示            
                }

            return $car;
        });

        // ------------------------
        // 3. 表示
        // ------------------------
        return view('user.cars.index', [
            'cars' => $cars,
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
        ]);
    }    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $car = Car::with(['images', 'carModel', 'reservations'])->findOrFail($id);
        $options = Option::all();

        // クエリから受け取った日時文字列
        $startStr = $request->query('start_datetime');
        $endStr = $request->query('end_datetime');

        // Carbonオブジェクトに変換（nullable）
        $start = $startStr ? Carbon::parse($startStr) : null;
        $end = $endStr ? Carbon::parse($endStr) : null;

        // 日数・泊数・日帰り判定・合計料金の初期化 (デフォルトは1日料金、日帰り)
        $totalPrice = $car->price;
        $days = 1; // 表示用日数
        $nights = 0; // 表示用泊数
        $isDayTrip = true; // 日帰り判定

        if ($start && $end && $end->gt($start)) { // 有効な期間が指定されている場合
            // 料金計算用の日数 (24時間単位で切り上げ)
            // 料金計算用の日数: カレンダー上の日数を使用 (最低1日)
            $displayNights = $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay());
            $displayDays = $displayNights + 1;
            $isDayTrip = ($displayNights === 0); // 0泊なら日帰り

            $billingDays = $displayDays; // 料金計算には表示用の日数を使用
            $totalPrice = $car->price * $billingDays;

            $days = $displayDays;
            $nights = $displayNights;
        } else {
            // 日付指定がない場合は、合計料金は車両の1日料金、期間は日帰りとして表示
            // (これはshow.blade.phpの予約概要サマリーの初期表示のため)
            $totalPrice = $car->price; 
        }

        return view('user.cars.show', [
            'car' => $car,
            'options' => $options,
            'start' => $start,
            'end' => $end,
            'days' => $days,
            'nights' => $nights,
            'isDayTrip' => $isDayTrip, // isSameDayからisDayTripに変更
            'totalPrice' => $totalPrice,
        ]);
    }   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
