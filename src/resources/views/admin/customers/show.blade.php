<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('顧客詳細') }} - {{ $customer->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.customers.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    戻る
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 顧客基本情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">顧客情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">顧客ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->id }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">名前</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->name }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">電話番号</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($customer->phone)
                                        {{ $customer->phone }}
                                    @else
                                        <span class="text-gray-500">未登録</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">登録日</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">最終ログイン</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->last_login_at ? $customer->last_login_at->format('Y/m/d H:i') : '未ログイン' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 統計情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">利用統計</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $totalReservations }}</div>
                            <div class="text-sm text-blue-600">総予約回数</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">¥{{ number_format($totalSpent) }}</div>
                            <div class="text-sm text-green-600">総利用金額</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">¥{{ number_format($averageReservationValue) }}</div>
                            <div class="text-sm text-purple-600">平均利用金額</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 最近の予約履歴 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">最近の予約履歴（過去6ヶ月）</h3>
                    
                    @if($recentReservations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">予約ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">車両</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">開始日</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">終了日</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">期間</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">金額</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentReservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $reservation->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $reservation->car->carModel->name ?? ($reservation->car->name ?? '車両不明') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($reservation->start_datetime && $reservation->end_datetime)
                                                    {{ $reservation->start_datetime->format('Y/m/d H:i') }}
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($reservation->start_datetime && $reservation->end_datetime)
                                                    {{ $reservation->end_datetime->format('Y/m/d H:i') }}
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($reservation->start_datetime && $reservation->end_datetime)
                                                    @php
                                                        // 開始日と終了日の日数を計算（1泊2日は2日として表示）
                                                        $startDate = $reservation->start_datetime->startOfDay();
                                                        $endDate = $reservation->end_datetime->startOfDay();
                                                        $days = $startDate->diffInDays($endDate) + 1;
                                                        $period = $days . '日';
                                                    @endphp
                                                    {{ $period }}
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ¥{{ number_format($reservation->total_price ?? 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColor = match($reservation->status) {
                                                        'confirmed' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    $statusText = match($reservation->status) {
                                                        'confirmed' => '確定',
                                                        'pending' => '保留',
                                                        'cancelled' => 'キャンセル',
                                                        default => '不明'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.reservations.show', $reservation) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    詳細
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">過去6ヶ月の予約履歴はありません。</p>
                    @endif
                </div>
            </div>

            <!-- 全予約履歴 -->
            @if($customer->reservations->count() > $recentReservations->count())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">全予約履歴</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">予約ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">車両</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">開始日</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">終了日</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">期間</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">金額</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $reservation->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $reservation->car->carModel->name ?? ($reservation->car->name ?? '車両不明') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($reservation->start_datetime && $reservation->end_datetime)
                                                    {{ $reservation->start_datetime->format('Y/m/d H:i') }}
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($reservation->start_datetime && $reservation->end_datetime)
                                                    {{ $reservation->end_datetime->format('Y/m/d H:i') }}
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($reservation->start_datetime && $reservation->end_datetime)
                                                    @php
                                                        // 開始日と終了日の日数を計算（1泊2日は2日として表示）
                                                        $startDate = $reservation->start_datetime->startOfDay();
                                                        $endDate = $reservation->end_datetime->startOfDay();
                                                        $days = $startDate->diffInDays($endDate) + 1;
                                                        $period = $days . '日';
                                                    @endphp
                                                    {{ $period }}
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ¥{{ number_format($reservation->total_price ?? 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColor = match($reservation->status) {
                                                        'confirmed' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    $statusText = match($reservation->status) {
                                                        'confirmed' => '確定',
                                                        'pending' => '保留',
                                                        'cancelled' => 'キャンセル',
                                                        default => '不明'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.reservations.show', $reservation) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    詳細
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout> 