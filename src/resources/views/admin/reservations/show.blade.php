<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('予約詳細') }} - ID: {{ $reservation->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reservations.edit', $reservation) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    編集
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    戻る
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 予約基本情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">予約情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">予約ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->id }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">ステータス</label>
                                @php
                                    $statusColor = match($reservation->status) {
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $statusText = match($reservation->status) {
                                        'confirmed' => '確定',
                                        'pending' => '保留',
                                        'cancelled' => 'キャンセル',
                                        'completed' => '完了',
                                        default => '不明'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">利用開始日時</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($reservation->start_datetime)
                                        {{ $reservation->start_datetime->format('Y年m月d日 H:i') }}
                                    @else
                                        未設定
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">利用終了日時</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($reservation->end_datetime)
                                        {{ $reservation->end_datetime->format('Y年m月d日 H:i') }}
                                    @else
                                        未設定
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">利用期間</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($reservation->start_datetime && $reservation->end_datetime)
                                        @php
                                            $startDate = $reservation->start_datetime->startOfDay();
                                            $endDate = $reservation->end_datetime->startOfDay();
                                            $days = $startDate->diffInDays($endDate) + 1;
                                        @endphp
                                        {{ $days }}日
                                    @else
                                        未設定
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">総料金</label>
                                <p class="mt-1 text-sm text-gray-900">¥{{ number_format($reservation->total_price ?? 0) }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">作成日</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->created_at->format('Y年m月d日 H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">更新日</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->updated_at->format('Y年m月d日 H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 顧客情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">顧客情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">顧客名</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($reservation->user)
                                        <a href="{{ route('admin.customers.show', $reservation->user) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $reservation->user->name }}
                                        </a>
                                    @else
                                        {{ $reservation->name_kanji ?? 'ゲスト' }}
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->email ?? '未設定' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">電話番号</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->phone_main ?? '未設定' }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">緊急連絡先</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->phone_emergency ?? '未設定' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">出発便</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->flight_departure ?? '未設定' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">帰着便</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->flight_return ?? '未設定' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 車両情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">車両情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">車両ID</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.cars.show', $reservation->car) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $reservation->car->id }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">車種</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->car->carModel->name ?? '不明' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">車両名</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $reservation->car->name ?? '不明' }}</p>
                            </div>
                        </div>
                        <div>
                            @if($reservation->car->images && $reservation->car->images->isNotEmpty())
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">車両画像</label>
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $reservation->car->images->first()->image_path) }}" 
                                             alt="車両画像" 
                                             class="h-32 w-auto object-cover rounded">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- オプション情報 -->
            @if($reservation->options && $reservation->options->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">選択オプション</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">オプション名</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">数量</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">単価</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">小計</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reservation->options as $option)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $option->name ?? '不明' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $option->pivot->quantity ?? 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ¥{{ number_format($option->pivot->price ?? $option->price ?? 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ¥{{ number_format($option->pivot->total_price ?? $option->price ?? 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">選択オプション</h3>
                        <p class="text-gray-500">選択されたオプションはありません。</p>
                    </div>
                </div>
            @endif

            <!-- 備考 -->
            @if($reservation->note)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">備考</h3>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $reservation->note }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout> 