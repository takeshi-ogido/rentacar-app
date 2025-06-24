<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            最終確認
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

        {{--
        以下の変数はコントローラー (ReservationController@finalConfirm) から渡されます:
        $car, $start (Carbon), $end (Carbon), $start_datetime_str, $end_datetime_str,
        $days, $nights, $isSameDay, $carPrice,
        $selectedOptionsDisplay (表示用オプション配列), $selected_options (POST送信用オプション配列), $total,
        $customer (顧客情報配列)
    --}}

            {{-- ▼ 予約内容 --}}
            <h3 class="text-lg font-semibold mb-4 text-gray-800">予約内容の確認</h3>
            <div class="space-y-2 text-sm text-gray-700 mb-6">
                <div><strong>車両名：</strong>{{ $car->name }}</div>
                <div><strong>利用開始：</strong>{{ $start->format('Y年m月d日 H:i') }}</div>
                <div><strong>利用終了：</strong>{{ $end->format('Y年m月d日 H:i') }}</div>
                <div><strong>期間：</strong>{{ $isSameDay ? '日帰り' : "{$nights}泊{$days}日" }}</div>
                <div><strong>車両料金：</strong>¥{{ number_format($car->price) }} × {{ $days }}日 = ¥{{ number_format($carPrice) }}</div>

                @if (count($selectedOptionsDisplay))
                    <div><strong>オプション：</strong></div>
                    <ul class="ml-4 list-disc">
                        @foreach ($selectedOptionsDisplay as $opt)
                            <li>
                                {{ $opt['name'] }}：¥{{ number_format($opt['unit_price']) }}
                                @if ($opt['is_quantity'])
                                    × {{ $opt['quantity'] }}個
                                    = <strong>¥{{ number_format($opt['price']) }}</strong>
                                @else
                                    × {{ $days }}日 = <strong>¥{{ number_format($opt['price']) }}</strong>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div><strong>オプション：</strong>なし</div>
                @endif

                <div class="font-bold text-lg text-gray-900 mt-2">
                    合計金額（税込）：¥{{ number_format($total) }}
                </div>
            </div>

            {{-- ▼ お客様情報 --}}
            <h3 class="text-lg font-semibold mb-4 text-gray-800">お客様情報の確認</h3>
            <div class="space-y-2 text-sm text-gray-700 mb-6">
                <div><strong>お名前（漢字）：</strong>{{ $customer['name_kanji'] }}</div>
                <div><strong>お名前（カナ）：</strong>{{ $customer['name_kana_sei'] ?? '' }} {{ $customer['name_kana_mei'] ?? '' }}</div>
                <div><strong>電話番号（予約者）：</strong>{{ $customer['phone_main'] }}</div>
                <div><strong>緊急連絡先：</strong>{{ $customer['phone_emergency'] ?? '未入力' }}</div>
                <div><strong>メールアドレス：</strong>{{ $customer['email'] }}</div>
                <div><strong>往路フライト便名：</strong>{{ $customer['flight_departure'] ?? '未入力' }}</div>
                <div><strong>復路フライト便名：</strong>{{ $customer['flight_return'] ?? '未入力' }}</div>
                <div><strong>備考：</strong>{{ $customer['note'] ?? '未入力' }}</div>
            </div>

            {{-- ▼ 送信フォーム (予約保存処理へ) --}}
            <form action="{{ route('user.cars.reservations.reserved', ['car' => $car->id]) }}" method="POST">
                @csrf

                {{-- hidden fields --}}
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <input type="hidden" name="start_datetime" value="{{ $start_datetime_str }}">
                <input type="hidden" name="end_datetime" value="{{ $end_datetime_str }}">

                @foreach ($selected_options as $key => $value) {{-- $selected_options はコントローラから渡される --}}                    
                <input type="hidden" name="options[{{ $key }}]" value="{{ $value }}">
                @endforeach

                @foreach ($customer as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="flex justify-between mt-6">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-gray-800">
                        戻る
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        この内容で予約する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-user-layout>