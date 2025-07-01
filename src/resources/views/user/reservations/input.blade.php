<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            お客様情報の入力
        </h2>
    </x-slot>
    
    <div class="py-10">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

            {{-- ▼ 予約内容の表示 --}}
            <h3 class="text-lg font-semibold mb-4 text-gray-800">予約内容</h3>
            <div class="space-y-2 mb-8 text-sm text-gray-700">
                <div><strong>車両名：</strong>{{ $car->name }}</div>
                <div><strong>利用開始：</strong>{{ $start->format('Y年m月d日 H:i') }}</div>
                <div><strong>利用終了：</strong>{{ $end->format('Y年m月d日 H:i') }}</div>
                <div><strong>利用期間：</strong>{{ $isDayTrip ? '日帰り' : "{$nights}泊{$days}日" }}</div>
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

            {{-- ▼ お客様情報の入力 --}}
            <h3 class="text-lg font-semibold mb-4 text-gray-800">お客様情報</h3>
            <form action="{{ route('user.cars.reservations.final-confirm', ['car' => $car->id]) }}" method="POST">                @csrf

                {{-- hidden fields --}}
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <input type="hidden" name="start_datetime" value="{{ $start_datetime_str }}">
                <input type="hidden" name="end_datetime" value="{{ $end_datetime_str }}">
                @foreach ($selected_options_from_session as $key => $value)                    
                <input type="hidden" name="options[{{ $key }}]" value="{{ $value }}">
                @endforeach

                {{-- 漢字氏名 --}}
                <div class="mb-4">
                    <label for="name_kanji" class="block text-gray-700">お名前（漢字・フルネーム）<span class="text-red-500">*</span></label>
                    <input type="text" id="name_kanji" name="name_kanji" class="form-input w-full @error('name_kanji') border-red-500 @enderror" required placeholder="例：山田 太郎" value="{{ old('name_kanji') }}">                    
                    @error('name_kanji')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- カタカナ：姓 --}}
                <div class="mb-4">
                    <label for="name_kana_sei" class="block text-gray-700">お名前（カタカナ・姓）<span class="text-red-500">*</span></label>
                    <input type="text" id="name_kana_sei" name="name_kana_sei" class="form-input w-full @error('name_kana_sei') border-red-500 @enderror" required placeholder="例：ヤマダ" value="{{ old('name_kana_sei') }}">
                    @error('name_kana_sei')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror                
                </div>

                {{-- カタカナ：名 --}}
                <div class="mb-4">
                    <label for="name_kana_mei" class="block text-gray-700">お名前（カタカナ・名）<span class="text-red-500">*</span></label>
                    <input type="text" id="name_kana_mei" name="name_kana_mei" class="form-input w-full @error('name_kana_mei') border-red-500 @enderror" required placeholder="例：タロウ" value="{{ old('name_kana_mei') }}">
                    @error('name_kana_mei')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror                
                </div>

                {{-- 電話番号 --}}
                <div class="mb-4">
                    <label for="phone_main" class="block text-gray-700">電話番号（予約者）<span class="text-red-500">*</span></label>
                    <input type="tel" id="phone_main" name="phone_main" class="form-input w-full @error('phone_main') border-red-500 @enderror" required placeholder="例：09012345678" value="{{ old('phone_main') }}">
                    @error('phone_main')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror                
                </div>

                {{-- 緊急連絡先 --}}
                <div class="mb-4">
                    <label for="phone_emergency" class="block text-gray-700">緊急連絡先の電話番号 </label>
                    <input type="tel" id="phone_emergency" name="phone_emergency" class="form-input w-full @error('phone_emergency') border-red-500 @enderror" placeholder="例：08098765432" value="{{ old('phone_emergency') }}">
                    @error('phone_emergency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror                
                </div>

                {{-- メールアドレス --}}
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">メールアドレス<span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="form-input w-full @error('email') border-red-500 @enderror" required placeholder="例：example@example.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror                
                </div>

                {{-- フライト情報 --}}
                <div class="mb-4">
                    <label for="flight_departure" class="block text-gray-700">往路のフライト便名（任意）</label>
                    <input type="text" id="flight_departure" name="flight_departure" class="form-input w-full @error('flight_departure') border-red-500 @enderror" placeholder="例：ANA123" value="{{ old('flight_departure') }}">
                    @error('flight_departure')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror                
                </div>

                {{-- 復路フライト情報 --}}
                <div class="mb-4">
                    <label for="flight_return" class="block text-gray-700">復路のフライト便名（任意）</label>
                    <input type="text" id="flight_return" name="flight_return" class="form-input w-full @error('flight_return') border-red-500 @enderror" placeholder="例：JAL789" value="{{ old('flight_return') }}">
                    @error('flight_return')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="note" class="block text-gray-700">備考欄</label>
                    <textarea id="note" name="note" class="form-input w-full @error('note') border-red-500 @enderror" placeholder="例：前日入りしてるので、来店します。など">{{ old('note') }}</textarea>
                    @error('note')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror               
                </div>

                {{-- ボタン --}}
                <div class="flex justify-between mt-6">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-gray-800">
                        戻る
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        次へ（最終確認）
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-user-layout>