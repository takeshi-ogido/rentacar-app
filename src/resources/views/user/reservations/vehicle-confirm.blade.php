<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            予約内容の確認
        </h2>
    </x-slot>

    {{--
        以下の変数はコントローラーから渡されることを想定しています:
        $car, $start (Carbon), $end (Carbon), $start_datetime_str, $end_datetime_str,
        $days, $nights, $isSameDay, $carPrice,
        $selectedOptionsDisplayArray (表示用オプション配列), $total,
        $selected_options_for_post (POST送信用オプション配列)
    --}}

    <div class="py-10">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-6">以下の内容で予約を進めます。</h3>

            <div class="space-y-4 text-gray-800">

                <div>
                    <strong>車両名：</strong> {{ $car->name }}
                </div>

                <div>
                    <strong>利用開始：</strong> {{ $start ? $start->format('Y年m月d日 H:i') : '未指定' }}
                </div>

                <div>
                    <strong>利用終了：</strong> {{ $end ? $end->format('Y年m月d日 H:i') : '未指定' }}
                </div>

                <div>
                    <strong>期間：</strong> {{ $isSameDay ? '日帰り' : "{$nights}泊{$days}日" }}
                </div>

                <hr class="my-4">

                <div>
                    <strong>車両料金：</strong> ¥{{ number_format($car->price) }} × {{ $days }}日 = ¥{{ number_format($carPrice) }}
                </div>

                @if (count($selectedOptionsDisplayArray) > 0)
                    <div class="mt-4">
                        <strong>選択オプション：</strong>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                            @foreach ($selectedOptionsDisplayArray as $opt)
                                <li>
                                    {{ $opt['name'] }}：¥{{ number_format($opt['unit_price']) }}
                                    @if ($opt['quantity'] > 1)
                                        × {{ $opt['quantity'] }}個
                                    @endif
                                    × {{ $days }}日 = <strong>¥{{ number_format($opt['price']) }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="mt-4">
                        <strong>選択オプション：</strong> なし
                    </div>
                @endif

                <hr class="my-4">

                <div class="text-lg font-bold">
                    合計金額（税込）：¥{{ number_format($total) }}
                </div>
            </div>

            {{-- お客様情報入力画面へ進むためのフォーム --}}
            {{-- 送信先はセッションに予約情報を保存し、お客様情報入力画面へリダイレクトするルート --}}
            <form action="{{ route('user.cars.reservations.car-confirm', ['car' => $car->id]) }}" method="POST" class="mt-6 space-y-4">
                @csrf
                {{-- hidden fields で予約情報を次のリクエストに渡す --}}
                <input type="hidden" name="start_datetime" value="{{ $start_datetime_str }}">
                <input type="hidden" name="end_datetime" value="{{ $end_datetime_str }}">
                {{-- car_id はルートパラメータで渡るので、フォーム内では不要な場合もあります --}}
                {{-- <input type="hidden" name="car_id" value="{{ $car->id }}"> --}}
                @foreach ($selected_options_for_post as $optionId => $quantity)
                    <input type="hidden" name="options[{{ $optionId }}]" value="{{ $quantity }}">
                @endforeach

                <div class="flex justify-between">
                    <a href="{{ url()->previous() }}" 
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
                        戻る
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                        お客様情報入力へ進む
                    </button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-user-layout>