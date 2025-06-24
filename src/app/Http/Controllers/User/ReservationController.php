<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Notifications\ReservationCompleted;
use App\Models\{Reservation, Car, Option};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    private function calculatePrice(Car $car, Carbon $start, Carbon $end, array $selectedOptions): array
    {
        $days = max(ceil($start->diffInMinutes($end) / 1440), 1);
        $carPrice = $car->price * $days;

        $options = Option::findMany(array_keys($selectedOptions))->keyBy('id');
        $optionTotal = 0;
        $selected = [];

        foreach ($selectedOptions as $id => $val) {
            if (!isset($options[$id]) || !$val) continue;
            $opt = $options[$id];
            $qty = $opt->is_quantity ? (int)$val : 1;

            if ($opt->is_quantity) {
                // 数量課金オプション (チャイルドシートなど) は固定料金
                $price = $opt->price * $qty;
            } else {
                // 日額課金オプション (Wi-Fiなど) は日数で計算
                $price = $opt->price * $qty * $days; // $qty は常に1
            }

            $optionTotal += $price;
            $selected[] = ['name' => $opt->name, 'price' => $price, 'unit_price' => $opt->price, 'quantity' => $qty, 'is_quantity' => $opt->is_quantity];
        }

        return [
            'days' => $days,
            'nights' => max($days - 1, 0),
            'isSameDay' => $start->isSameDay($end),
            'carPrice' => $carPrice,
            'optionTotal' => $optionTotal,
            'selectedOptionsDisplay' => $selected,
            'total' => $carPrice + $optionTotal,
        ];
    }

    public function showOptionConfirm(Car $car, Request $request)
    {
        $validated = $request->validate([
            'start_datetime' => ['required', 'date_format:Y-m-d H:i'],
            'end_datetime' => ['required', 'date_format:Y-m-d H:i', 'after:start_datetime'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'numeric'],
        ]);

        $start = Carbon::parse($validated['start_datetime']);
        $end = Carbon::parse($validated['end_datetime']);
        $options = $validated['options'] ?? [];

        $priceData = $this->calculatePrice($car, $start, $end, $options);

        return view('user.reservations.option-confirm', array_merge(
            compact('car', 'start', 'end'),
            $priceData,
            [
                'start_datetime_str' => $validated['start_datetime'],
                'end_datetime_str' => $validated['end_datetime'],
                'selected_options_for_post' => $options,
            ]
        ));
    }

    public function carConfirm(Car $car, Request $request)
    {
        $validated = $request->validate([
            'start_datetime' => ['required', 'date_format:Y-m-d H:i'],
            'end_datetime' => ['required', 'date_format:Y-m-d H:i', 'after:start_datetime'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'integer', 'min:0'],
        ]);

        session(["reservation.{$car->id}" => $validated]);
        return redirect()->route('user.cars.reservations.input', ['car' => $car->id]);
    }

    public function input(Car $car)
    {
        $data = session("reservation.{$car->id}");
        if (!$data) return redirect()->route('user.cars.show', $car)->withErrors('セッションが切れました');

        $start = Carbon::parse($data['start_datetime']);
        $end = Carbon::parse($data['end_datetime']);
        $options = $data['options'] ?? [];

        $priceData = $this->calculatePrice($car, $start, $end, $options);

        return view('user.reservations.input', array_merge(
            compact('car', 'start', 'end'),
            $priceData,
            [
                'start_datetime_str' => $data['start_datetime'],
                'end_datetime_str' => $data['end_datetime'],
                'selected_options_from_session' => $options,
            ]
        ));
    }

    private function reservationValidationRules(): array
    {
        return [
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'name_kanji' => ['required', 'string', 'max:255'],
            'name_kana_sei' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'],
            'name_kana_mei' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'],
            'phone_main' => ['required', 'string', 'max:20'],
            'phone_emergency' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'flight_departure' => ['nullable', 'string', 'max:255'],
            'flight_return' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'start_datetime' => ['required', 'date_format:Y-m-d H:i'],
            'end_datetime' => ['required', 'date_format:Y-m-d H:i', 'after:start_datetime'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function finalConfirm(Car $car, Request $request)
    {
        $validated = $request->validate($this->reservationValidationRules());
        if ($car->id != $validated['car_id']) return back()->withErrors('車両が一致しません')->withInput();        
        $start = Carbon::parse($validated['start_datetime']);
        $end = Carbon::parse($validated['end_datetime']);
        $options = $validated['options'] ?? [];

        $priceData = $this->calculatePrice($car, $start, $end, $options);
        $customer = collect($validated)->except(['start_datetime', 'end_datetime', 'options', 'car_id'])->all();

        return view('user.reservations.final-confirm', array_merge(
            compact('car', 'start', 'end', 'customer'),
            $priceData,
            [
                'start_datetime_str' => $validated['start_datetime'],
                'end_datetime_str' => $validated['end_datetime'],
                'selected_options' => $options,
            ]
        ));
    }

    public function reserved(Car $car, Request $request)
    {
        $validated = $request->validate($this->reservationValidationRules());
        if ($car->id != $validated['car_id']) return back()->withErrors('車両が一致しません')->withInput();

        // 電話番号からハイフンやスペースを除去
        if (!empty($validated['phone_main'])) {
            $validated['phone_main'] = str_replace(['-', 'ー', ' '], '', $validated['phone_main']);
        }
        if (!empty($validated['phone_emergency'])) {
            $validated['phone_emergency'] = str_replace(['-', 'ー', ' '], '', $validated['phone_emergency']);
        }
        $start = Carbon::parse($validated['start_datetime']);
        $end = Carbon::parse($validated['end_datetime']);
        $options = $validated['options'] ?? [];
        $priceData = $this->calculatePrice($car, $start, $end, $options);

        $reservation = new Reservation([...$validated, 'car_id' => $car->id, 'user_id' => auth()->id(), 'options_json' => json_encode($options), 'status' => 'confirmed', 'total_price' => $priceData['total']]);
        $reservation->save();

        Notification::route('mail', $reservation->email)->notify(new ReservationCompleted($reservation));
        Notification::route('mail', config('mail.admin_address'))->notify(new ReservationCompleted($reservation));

        return redirect()->route('user.cars.reservations.complete', ['car' => $car->id, 'reservation' => $reservation->id]);
    }

    public function complete(Car $car, Reservation $reservation)
    {
        return view('user.reservations.complete', compact('reservation'));
    }
}
