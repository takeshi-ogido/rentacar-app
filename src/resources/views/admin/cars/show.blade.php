<!-- 車両の詳細画面の表示 -->
<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('車両詳細') }} - {{ $car->carModel->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reservations.create', ['car_id' => $car->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    予約追加
                </a>
                <a href="{{ route('admin.cars.edit', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    編集
                </a>
                <a href="{{ route('admin.cars.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    戻る
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 車両基本情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">車両情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">車両ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $car->id }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">車種</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $car->carModel->name }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">年式</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $car->year }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">料金（1日あたり）</label>
                                <p class="mt-1 text-sm text-gray-900">¥{{ number_format($car->price) }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">ステータス</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $car->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $car->is_published ? '公開中' : '非公開' }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">登録日</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $car->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">更新日</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $car->updated_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 予約状況 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">予約状況</h3>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.cars.show', ['car' => $car, 'year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                               class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                ← 前月
                            </a>
                            <span class="text-lg font-medium">{{ $startDate->format('Y年m月') }}</span>
                            <a href="{{ route('admin.cars.show', ['car' => $car, 'year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                               class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                翌月 →
                            </a>
                            @if($startDate->format('Y-m') !== now()->format('Y-m'))
                                <a href="{{ route('admin.cars.show', $car) }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                    今月
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- 予約カレンダー -->
                    <div class="mb-6">
                        @php
                            // 重複予約をチェック（期間の重複を検出）
                            $duplicateReservations = [];
                            
                            // 予約期間の重複をチェック
                            $allReservations = $reservations->toArray();
                            $overlappingDates = [];
                            
                            for ($i = 0; $i < count($allReservations); $i++) {
                                for ($j = $i + 1; $j < count($allReservations); $j++) {
                                    $res1 = $allReservations[$i];
                                    $res2 = $allReservations[$j];
                                    
                                    // 期間の重複をチェック（終了日と開始日が同じ場合は除外）
                                    $start1 = \Carbon\Carbon::parse($res1['start_datetime']);
                                    $end1 = \Carbon\Carbon::parse($res1['end_datetime']);
                                    $start2 = \Carbon\Carbon::parse($res2['start_datetime']);
                                    $end2 = \Carbon\Carbon::parse($res2['end_datetime']);
                                    
                                    // 重複条件：期間が重なっている（終了日と開始日が同じ場合は除外）
                                    if ($start1 < $end2 && $start2 < $end1 && $end1 != $start2 && $end2 != $start1) {
                                        // 重複する日付を特定
                                        $overlapStart = max($start1, $start2);
                                        $overlapEnd = min($end1, $end2);
                                        
                                        $currentDate = $overlapStart->copy();
                                        while ($currentDate <= $overlapEnd) {
                                            $dateKey = $currentDate->format('Y-m-d');
                                            if (!isset($overlappingDates[$dateKey])) {
                                                $overlappingDates[$dateKey] = [];
                                            }
                                            if (!in_array($res1, $overlappingDates[$dateKey])) {
                                                $overlappingDates[$dateKey][] = $res1;
                                            }
                                            if (!in_array($res2, $overlappingDates[$dateKey])) {
                                                $overlappingDates[$dateKey][] = $res2;
                                            }
                                            $currentDate->addDay();
                                        }
                                    }
                                }
                            }
                            
                            // 重複する日付を$duplicateReservationsに変換
                            foreach ($overlappingDates as $dateKey => $dateReservations) {
                                if (count($dateReservations) > 1) {
                                    $duplicateReservations[$dateKey] = $dateReservations;
                                }
                            }
                        @endphp
                        
                        @if(count($duplicateReservations) > 0)
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <h4 class="text-red-800 font-medium">重複予約の警告</h4>
                                </div>
                                <p class="text-red-700 text-sm mt-1">
                                    以下の日付で期間が重複する予約が検出されました。貸し出し期間中の重複は問題があります。
                                </p>
                                <div class="mt-2 space-y-1">
                                    @foreach($duplicateReservations as $dateKey => $dateReservations)
                                        <div class="text-sm text-red-600">
                                            <strong>{{ \Carbon\Carbon::parse($dateKey)->format('m/d') }}:</strong>
                                            @foreach($dateReservations as $reservation)
                                                <span class="inline-block bg-red-200 px-2 py-1 rounded mr-1 mb-1">
                                                    予約{{ $reservation['id'] }} ({{ $reservation['user'] ? $reservation['user']['name'] : 'ゲスト' }})
                                                </span>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <!-- カレンダーテーブル -->
                            <table class="w-full">
                                <!-- 曜日ヘッダー -->
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="p-3 text-center text-sm font-medium text-red-600 w-1/7">日</th>
                                        <th class="p-3 text-center text-sm font-medium w-1/7">月</th>
                                        <th class="p-3 text-center text-sm font-medium w-1/7">火</th>
                                        <th class="p-3 text-center text-sm font-medium w-1/7">水</th>
                                        <th class="p-3 text-center text-sm font-medium w-1/7">木</th>
                                        <th class="p-3 text-center text-sm font-medium w-1/7">金</th>
                                        <th class="p-3 text-center text-sm font-medium text-blue-600 w-1/7">土</th>
                                    </tr>
                                </thead>
                                
                                <!-- カレンダー本体 -->
                                <tbody>
                                    @php
                                        $firstDayOfMonth = $startDate->copy()->startOfMonth();
                                        $lastDayOfMonth = $endDate->copy()->endOfMonth();
                                        
                                        // 月の最初の週の日曜日を取得
                                        $firstDayOfCalendar = $firstDayOfMonth->copy()->startOfWeek();
                                        // 月の最後の週の土曜日を取得
                                        $lastDayOfCalendar = $lastDayOfMonth->copy()->endOfWeek();
                                        
                                        $currentDate = $firstDayOfCalendar->copy();
                                    @endphp
                                    
                                    @while($currentDate <= $lastDayOfCalendar)
                                        <!-- 週の行 -->
                                        <tr>
                                            @for($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                                                @php
                                                    $dateKey = $currentDate->format('Y-m-d');
                                                    $isToday = $currentDate->isToday();
                                                    $isCurrentMonth = $currentDate->month === $month;
                                                    $isWeekend = $currentDate->isWeekend();
                                                    $hasReservation = isset($reservationCalendar[$dateKey]);
                                                    $reservationCount = $hasReservation ? count($reservationCalendar[$dateKey]) : 0;
                                                    
                                                    // 背景色の決定
                                                    if (!$isCurrentMonth) {
                                                        $bgColor = 'bg-gray-50';
                                                    } elseif ($isToday) {
                                                        $bgColor = 'bg-blue-100';
                                                    } elseif ($hasReservation) {
                                                        $bgColor = 'bg-red-50';
                                                    } else {
                                                        $bgColor = 'bg-white';
                                                    }
                                                    
                                                    // テキスト色の決定
                                                    if (!$isCurrentMonth) {
                                                        $textColor = 'text-gray-400';
                                                    } elseif ($isWeekend) {
                                                        $textColor = $currentDate->dayOfWeek === 0 ? 'text-red-600' : 'text-blue-600';
                                                    } else {
                                                        $textColor = 'text-gray-900';
                                                    }
                                                @endphp
                                                
                                                <!-- 日付セル -->
                                                <td class="border-r border-b border-gray-200 min-h-[120px] relative {{ $bgColor }} align-top">
                                                    <div class="p-2 h-full flex flex-col">
                                                        <!-- 日付番号 -->
                                                        <div class="text-sm font-medium {{ $textColor }} {{ $isToday ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center' : '' }} mb-1">
                                                            {{ $currentDate->format('j') }}
                                                        </div>
                                                        
                                                        <!-- 予約表示 -->
                                                        @if($hasReservation)
                                                            <div class="flex-1 space-y-1">
                                                                @foreach(array_slice($reservationCalendar[$dateKey], 0, 3) as $reservation)
                                                                    <div class="bg-red-500 text-white text-xs p-1 rounded truncate" 
                                                                         title="予約ID: {{ $reservation->id }} - {{ $reservation->user ? $reservation->user->name : 'ゲスト' }} ({{ $reservation->start_datetime->format('H:i') }}〜{{ $reservation->end_datetime->format('H:i') }})">
                                                                        {{ $reservation->user ? $reservation->user->name : 'ゲスト' }}
                                                                    </div>
                                                                @endforeach
                                                                @if($reservationCount > 3)
                                                                    <div class="text-xs text-red-600 font-medium">
                                                                        +{{ $reservationCount - 3 }}件
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="flex-1"></div>
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                                @php
                                                    $currentDate->addDay();
                                                @endphp
                                            @endfor
                                        </tr>
                                    @endwhile
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- 凡例 -->
                        <div class="mt-4 flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-white border border-gray-300 mr-2"></div>
                                <span>空き</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-50 border border-red-200 mr-2"></div>
                                <span>予約あり</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-100 border border-blue-300 mr-2"></div>
                                <span>今日</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-50 border border-gray-200 mr-2"></div>
                                <span>他月</span>
                            </div>
                        </div>
                    </div>

                    <!-- 予約一覧 -->
                    <div>
                        <h4 class="text-md font-medium mb-3">{{ $startDate->format('Y年m月') }}の予約一覧</h4>
                        @if($reservations->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">予約ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">顧客名</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">開始日</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">終了日</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">期間</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($reservations as $reservation)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $reservation->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $reservation->user ? $reservation->user->name : 'ゲスト' }}
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
                            <p class="text-gray-500 text-center py-4">この期間の予約はありません。</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>