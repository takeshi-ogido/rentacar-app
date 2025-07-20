<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class MypageController extends Controller
{
    /**
     * ログインユーザーの予約履歴一覧を表示する
     */
    public function index()
    {
        $user = Auth::user();
        $allReservations = Reservation::where('user_id', $user->id)
            ->with('car.images')
            ->orderBy('start_datetime', 'desc') // 利用開始日が新しい順
            ->get();

        // 利用開始日時を基準に、予約を「予約中」と「利用済み」に振り分ける
        [$upcomingReservations, $pastReservations] = $allReservations->partition(function ($reservation) {
            return $reservation->start_datetime > now();
        });

        return view('user.mypage.index', compact('upcomingReservations', 'pastReservations'));
    }
}