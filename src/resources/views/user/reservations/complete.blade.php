<x-user-layout>
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-green-600">予約が完了しました</h1>
                <p class="text-gray-600 mt-2">ご予約ありがとうございます。</p>
            </div>

            {{-- 予約情報カード --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">予約情報</h2>
                <p><span class="font-medium text-gray-700">予約ID:</span> {{ $reservation->id }}</p>
                <p><span class="font-medium text-gray-700">車両名:</span> {{ $reservation->car->name }}</p>
                <p><span class="font-medium text-gray-700">利用開始:</span> {{ $reservation->start_datetime }}</p>
                <p><span class="font-medium text-gray-700">利用終了:</span> {{ $reservation->end_datetime }}</p>
                <p><span class="font-medium text-gray-700">合計金額:</span> <span class="text-lg font-bold text-blue-600">¥{{ number_format($reservation->total_price) }}</span></p>
            </div>

            {{-- 予約者情報カード --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">予約者情報</h2>
                <p><span class="font-medium text-gray-700">お名前:</span> {{ $reservation->name_kana_sei }} {{ $reservation->name_kana_mei }}</p>
                <p><span class="font-medium text-gray-700">メールアドレス:</span> {{ $reservation->email }}</p>
                @if (!empty($reservation->phone))
                    <p><span class="font-medium text-gray-700">電話番号:</span> {{ $reservation->phone }}</p>
                @endif
                <p class="text-gray-600 text-xs">※上記のメールアドレスへ予約完了メールを送信しております。<br>
                    予約情報や当日の流れが記載しておりますので、ご確認よろしくお願いします。</p>
            </div>

            {{-- 戻るボタン --}}
            <div class="text-center pt-4">
                <a href="{{ route('user.cars.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition">
                    車両一覧へ戻る
                </a>
            </div>
        </div>
    </div>
</x-user-layout>
