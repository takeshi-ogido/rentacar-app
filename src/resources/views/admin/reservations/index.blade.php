<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('予約管理') }}
            </h2>
            <div class="flex space-x-2">
                <button id="today-btn" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    今日
                </button>
                <button id="prev-btn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ← 前月
                </button>
                <button id="next-btn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    翌月 →
                </button>
                <a href="{{ route('admin.reservations.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    新規予約
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">予約管理</h2>
                        <div class="text-sm text-gray-600">
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 legend-confirmed rounded mr-3"></div>
                                    <span>確認済み予約</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 legend-pending rounded mr-3"></div>
                                    <span>保留中予約</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 予約期間の説明 -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-blue-800 mb-2">予約期間の表示について</h3>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p>• <span class="font-medium">青色</span>: 確認済み予約（確定済み）</p>
                            <p>• <span class="font-medium">黄色</span>: 保留中予約（確認待ち）</p>
                            <p>• 予約期間は開始日から終了日まで同じ色で表示されます</p>
                            <p>• 空いている日には「予約追加」ボタンが表示されます</p>
                        </div>
                    </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 月表示ヘッダー -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900" id="month-display">
                            {{ Carbon\Carbon::create($year, $month, 1)->format('Y年m月') }}
                        </h3>
                        <div class="text-sm text-gray-500">
                            総予約数: <span class="font-semibold">{{ $reservations->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 車両×カレンダーグリッド -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200" id="reservation-grid">
                        <thead class="bg-gray-50 sticky-header">
                            <tr>
                                <th class="sticky left-0 z-10 bg-gray-50 border-r border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-64 sticky-car-info">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        車両情報
                                    </div>
                                </th>
                                @php
                                    $currentDate = Carbon\Carbon::create($year, $month, 1)->startOfMonth();
                                    $endDate = Carbon\Carbon::create($year, $month, 1)->endOfMonth();
                                @endphp
                                @while($currentDate <= $endDate)
                                    <th class="border-b border-gray-200 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-12">
                                        <div class="text-center">
                                            <div class="font-bold text-gray-700">{{ $currentDate->format('d') }}</div>
                                            <div class="text-xs text-gray-400 font-medium">{{ $currentDate->format('D') }}</div>
                                        </div>
                                    </th>
                                    @php
                                        $currentDate->addDay();
                                    @endphp
                                @endwhile
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cars as $car)
                                <tr class="hover:bg-gray-50">
                                    <!-- 車両情報列（固定） -->
                                    <td class="sticky left-0 z-10 bg-white border-r border-gray-200 px-4 py-3 sticky-car-info min-w-64">
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 shadow-sm">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center mb-2">
                                                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                                        <h4 class="text-sm font-semibold text-gray-900 truncate">
                                                            {{ $car->carModel->name }}
                                                        </h4>
                                                    </div>
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $car->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $car->is_published ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                                            {{ $car->is_published ? '公開' : '非公開' }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 font-mono">#{{ $car->id }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-xs text-gray-600">
                                                            <span class="font-semibold text-blue-600">¥{{ number_format($car->price) }}</span>
                                                            <span class="text-gray-500">/日</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            予約: <span class="font-semibold text-gray-700">{{ $car->reservations->where('status', '!=', 'cancelled')->count() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-center">
                                                <button class="add-reservation-btn bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105"
                                                        data-car-id="{{ $car->id }}"
                                                        data-car-name="{{ $car->carModel->name }}"
                                                        data-car-price="{{ $car->price }}">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    予約追加
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- 日付列 -->
                                    @php
                                        $currentDate = Carbon\Carbon::create($year, $month, 1)->startOfMonth();
                                        $endDate = Carbon\Carbon::create($year, $month, 1)->endOfMonth();
                                    @endphp
                                    @while($currentDate <= $endDate)
                                        @php
                                            $dayReservations = $car->reservations->filter(function($reservation) use ($currentDate) {
                                                return $reservation->status != 'cancelled' &&
                                                       $reservation->start_datetime->toDateString() <= $currentDate->toDateString() &&
                                                       $reservation->end_datetime->toDateString() >= $currentDate->toDateString();
                                            });
                                            
                                            $isToday = $currentDate->isToday();
                                            $isWeekend = $currentDate->isWeekend();
                                            
                                            // 予約期間の開始・終了・中間を判定（修正版）
                                            $isReservationStart = $car->reservations->contains(function($reservation) use ($currentDate) {
                                                return $reservation->status != 'cancelled' && 
                                                       $reservation->start_datetime->format('Y-m-d') === $currentDate->format('Y-m-d');
                                            });
                                            
                                            $isReservationEnd = $car->reservations->contains(function($reservation) use ($currentDate) {
                                                return $reservation->status != 'cancelled' && 
                                                       $reservation->end_datetime->format('Y-m-d') === $currentDate->format('Y-m-d');
                                            });
                                            
                                            $isReservationMiddle = $car->reservations->contains(function($reservation) use ($currentDate) {
                                                return $reservation->status != 'cancelled' && 
                                                       $reservation->start_datetime->format('Y-m-d') < $currentDate->format('Y-m-d') &&
                                                       $reservation->end_datetime->format('Y-m-d') > $currentDate->format('Y-m-d');
                                            });
                                            
                                            // 予約期間のクラスを決定
                                            $reservationClass = '';
                                            if ($isReservationStart) {
                                                $reservationClass = 'reservation-start';
                                            } elseif ($isReservationEnd) {
                                                $reservationClass = 'reservation-end';
                                            } elseif ($isReservationMiddle) {
                                                $reservationClass = 'reservation-middle';
                                            }
                                            
                                            // 保留予約の場合は黄色系
                                            if ($dayReservations->where('status', 'pending')->count() > 0) {
                                                if ($isReservationStart) {
                                                    $reservationClass = 'reservation-pending-start';
                                                } elseif ($isReservationEnd) {
                                                    $reservationClass = 'reservation-pending-end';
                                                } elseif ($isReservationMiddle) {
                                                    $reservationClass = 'reservation-pending-middle';
                                                }
                                            }
                                            
                                            $currentDateFormatted = $currentDate->format('Y-m-d');
                                        @endphp
                                        
                                        <td class="border border-gray-200 px-1 py-2 text-center min-w-12 relative
                                                    {{ $isToday ? 'today-cell' : '' }}
                                                    {{ $isWeekend ? 'weekend-cell' : '' }}
                                                    {{ $reservationClass }}"
                                            data-date="{{ $currentDateFormatted }}"
                                            data-car-id="{{ $car->id }}"
                                            data-car-name="{{ $car->carModel->name }}"
                                            data-car-price="{{ $car->price }}">
                                            
                                            @if($dayReservations->count() > 0)
                                                @foreach($dayReservations as $reservation)
                                                    <div class="reservation-block mb-1 p-2 rounded-lg text-xs cursor-pointer font-medium shadow-sm hover:shadow-md transition-all duration-200
                                                                {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800 border-2 border-green-300 hover:bg-green-200' : '' }}
                                                                {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300 hover:bg-yellow-200' : '' }}
                                                                {{ $reservation->status === 'cancelled' ? 'bg-red-100 text-red-800 border-2 border-red-300 hover:bg-red-200' : '' }}"
                                                         data-reservation-id="{{ $reservation->id }}"
                                                         data-reservation-start="{{ $reservation->start_datetime->format('Y-m-d') }}"
                                                         data-reservation-end="{{ $reservation->end_datetime->format('Y-m-d') }}"
                                                         title="{{ $reservation->name_kanji ?? $reservation->user->name ?? 'ゲスト' }} - {{ $reservation->status === 'confirmed' ? '確定' : ($reservation->status === 'pending' ? '保留' : 'キャンセル') }} ({{ $reservation->start_datetime->format('m/d') }}〜{{ $reservation->end_datetime->format('m/d') }})">
                                                        @if($isReservationStart)
                                                            <div class="font-bold text-sm">{{ Str::limit($reservation->name_kanji ?? $reservation->user->name ?? 'ゲスト', 8) }}</div>
                                                            <div class="text-xs opacity-80 mt-1 font-medium">{{ $reservation->start_datetime->format('m/d') }}〜{{ $reservation->end_datetime->format('m/d') }}</div>
                                                        @else
                                                            <div class="text-center opacity-70 font-bold">●</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-gray-300 text-xs">-</div>
                                            @endif
                                            
                                            <!-- 予約追加ボタン（ホバー時表示、予約がない日のみ） -->
                                            @if($dayReservations->count() == 0)
                                                <button class="add-reservation-day-btn absolute inset-0 w-full h-full opacity-0 hover:opacity-100 transition-opacity bg-blue-100 text-blue-600 text-xs font-medium rounded"
                                                        data-car-id="{{ $car->id }}"
                                                        data-car-name="{{ $car->carModel->name }}"
                                                        data-car-price="{{ $car->price }}"
                                                        data-date="{{ $currentDateFormatted }}">
                                                    +
                                                </button>
                                            @endif
                                        </td>
                                        @php
                                            $currentDate = $currentDate->copy()->addDay();
                                        @endphp
                                    @endwhile
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 予約作成モーダル -->
    <div id="reservation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-6 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-6">新規予約作成</h3>
                <form id="reservation-form" method="POST" action="{{ route('admin.reservations.store') }}">
                    @csrf
                    <input type="hidden" name="car_id" id="modal-car-id">
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- 左列 -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">選択車両</label>
                                <p class="mt-1 text-sm text-gray-900" id="modal-car-name"></p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">開始日</label>
                                <input type="date" name="start_date" id="modal-start-date" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">終了日</label>
                                <input type="date" name="end_date" id="modal-end-date" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">利用期間</label>
                                <p class="mt-1 text-sm text-gray-900" id="modal-period"></p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">料金</label>
                                <p class="mt-1 text-lg font-semibold text-blue-600" id="modal-price"></p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ステータス</label>
                                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending">保留</option>
                                    <option value="confirmed">確定</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- 右列 -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">顧客名（漢字）</label>
                                <input type="text" name="name_kanji" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
                                <input type="email" name="email" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">電話番号</label>
                                <input type="text" name="phone_main" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">備考</label>
                                <textarea name="notes" rows="4" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="cancel-btn" 
                                class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                            キャンセル
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            予約作成
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* 予約期間の視覚化 - 統一された色 */
        .reservation-start,
        .reservation-end,
        .reservation-middle {
            background-color: #e0f2fe !important;
            position: relative;
        }
        
        .reservation-start {
            border-left: 3px solid #0284c7 !important;
        }
        
        .reservation-end {
            border-right: 3px solid #0284c7 !important;
        }
        
        .reservation-pending-start,
        .reservation-pending-end,
        .reservation-pending-middle {
            background-color: #fef3c7 !important;
            position: relative;
        }
        
        .reservation-pending-start {
            border-left: 3px solid #d97706 !important;
        }
        
        .reservation-pending-end {
            border-right: 3px solid #d97706 !important;
        }
        
        /* 予約ブロックのスタイル */
        .reservation-block {
            position: relative;
            z-index: 10;
            transition: all 0.2s ease;
            margin: 0;
            border-radius: 6px;
        }
        
        .reservation-block:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* 予約期間を繋げて表示するためのスタイル */
        .reservation-start .reservation-block {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        
        .reservation-end .reservation-block {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        
        .reservation-middle .reservation-block {
            border-radius: 0;
        }
        
        /* 予約期間の境界線を調整 */
        .reservation-start {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        
        .reservation-end {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        
        .reservation-middle {
            border-radius: 0;
        }
        
        /* 今日の日付のハイライト */
        .today-cell {
            background-color: #dbeafe !important;
            border: 2px solid #3b82f6 !important;
        }
        
        /* 週末の背景 - 予約期間より優先度を下げる */
        .weekend-cell:not(.reservation-start):not(.reservation-end):not(.reservation-middle):not(.reservation-pending-start):not(.reservation-pending-end):not(.reservation-pending-middle) {
            background-color: #f9fafb !important;
        }
        
        /* 予約追加ボタンのスタイル */
        .add-reservation-day-btn {
            z-index: 5;
        }
        
        /* 車両情報列の固定 */
        .sticky-car-info {
            position: sticky;
            left: 0;
            z-index: 20;
            background-color: white;
            border-right: 2px solid #e5e7eb;
        }
        
        /* テーブルヘッダーの固定 */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 30;
            background-color: #f9fafb;
        }
        
        /* ドラッグ選択のスタイル */
        .date-cell {
            cursor: pointer;
            user-select: none;
        }
        
        .date-cell:hover {
            background-color: #EFF6FF !important;
        }
        
        .date-cell.dragging {
            background-color: #DBEAFE !important;
            border: 2px solid #3B82F6 !important;
        }
        
        .date-cell.selected {
            background-color: #BFDBFE !important;
            border: 2px solid #2563EB !important;
        }
        
        .date-cell.in-range {
            background-color: #DBEAFE !important;
            border: 1px solid #60A5FA !important;
        }
        
        /* ドラッグ中のカーソル */
        .dragging-cursor {
            cursor: grabbing !important;
        }
        
        /* 凡例用のスタイル */
        .legend-confirmed {
            background-color: #e0f2fe !important;
        }
        
        .legend-pending {
            background-color: #fef3c7 !important;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentMonth = new Date();
            let selectedCarId = null;
            let selectedCarName = null;
            let selectedCarPrice = null;
            
            // ドラッグ選択の変数
            let isDragging = false;
            let startDate = null;
            let endDate = null;
            let startCell = null;
            
            // 月表示の更新
            function updateMonthDisplay() {
                const monthDisplay = document.getElementById('month-display');
                monthDisplay.textContent = currentMonth.getFullYear() + '年' + 
                    String(currentMonth.getMonth() + 1).padStart(2, '0') + '月';
            }
            
            // カレンダーグリッドの更新
            function updateCalendarGrid() {
                // ページをリロードして新しい月のデータを取得
                const params = new URLSearchParams(window.location.search);
                params.set('year', currentMonth.getFullYear());
                params.set('month', currentMonth.getMonth() + 1);
                window.location.search = params.toString();
            }
            
            // ドラッグ選択の初期化
            function initializeDragSelection() {
                const dateCells = document.querySelectorAll('td[data-date]');
                
                dateCells.forEach(cell => {
                    // 予約があるセルは除外
                    if (cell.querySelector('.reservation-block')) {
                        return;
                    }
                    
                    cell.classList.add('date-cell');
                    
                    // マウスダウンイベント
                    cell.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        
                        // 予約があるセルは除外
                        if (this.querySelector('.reservation-block')) {
                            return;
                        }
                        
                        isDragging = true;
                        startDate = this.dataset.date;
                        startCell = this;
                        
                        // 開始セルをハイライト
                        this.classList.add('dragging');
                        document.body.classList.add('dragging-cursor');
                        
                        // 選択範囲をクリア
                        clearSelection();
                    });
                    
                    // マウスオーバーイベント
                    cell.addEventListener('mouseenter', function(e) {
                        if (!isDragging || !startCell) return;
                        
                        // 予約があるセルは除外
                        if (this.querySelector('.reservation-block')) {
                            return;
                        }
                        
                        // 同じ車両のセルかチェック
                        if (this.dataset.carId !== startCell.dataset.carId) {
                            return;
                        }
                        
                        endDate = this.dataset.date;
                        updateSelection(startDate, endDate, startCell.dataset.carId);
                    });
                });
                
                // マウスアップイベント（ドキュメント全体）
                document.addEventListener('mouseup', function(e) {
                    if (isDragging && startDate && endDate) {
                        // ドラッグ終了時の処理
                        isDragging = false;
                        document.body.classList.remove('dragging-cursor');
                        
                        // 選択された車両情報を取得
                        const carId = startCell.dataset.carId;
                        const carName = startCell.dataset.carName;
                        const carPrice = parseInt(startCell.dataset.carPrice);
                        
                        // モーダルを表示
                        selectedCarId = carId;
                        selectedCarName = carName;
                        selectedCarPrice = carPrice;
                        
                        // 日付を設定
                        document.getElementById('modal-start-date').value = startDate;
                        document.getElementById('modal-end-date').value = endDate;
                        
                        showReservationModal();
                        
                        // 選択をクリア
                        clearSelection();
                    }
                    
                    isDragging = false;
                    startDate = null;
                    endDate = null;
                    startCell = null;
                });
            }
            
            // 選択範囲の更新
            function updateSelection(start, end, carId) {
                clearSelection();
                
                const startDate = new Date(start);
                const endDate = new Date(end);
                
                // 日付の順序を正規化
                if (startDate > endDate) {
                    [startDate, endDate] = [endDate, startDate];
                }
                
                const dateCells = document.querySelectorAll('td[data-date]');
                
                dateCells.forEach(cell => {
                    // 同じ車両のセルかチェック
                    if (cell.dataset.carId !== carId) {
                        return;
                    }
                    
                    const cellDate = new Date(cell.dataset.date);
                    
                    if (cellDate >= startDate && cellDate <= endDate) {
                        // 予約があるセルは除外
                        if (cell.querySelector('.reservation-block')) {
                            return;
                        }
                        
                        if (cellDate.getTime() === startDate.getTime()) {
                            cell.classList.add('selected');
                        } else if (cellDate.getTime() === endDate.getTime()) {
                            cell.classList.add('selected');
                        } else {
                            cell.classList.add('in-range');
                        }
                    }
                });
            }
            
            // 選択のクリア
            function clearSelection() {
                const dateCells = document.querySelectorAll('td[data-date]');
                dateCells.forEach(cell => {
                    cell.classList.remove('dragging', 'selected', 'in-range');
                });
            }
            
            // 予約追加ボタンのイベント
            document.querySelectorAll('.add-reservation-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    selectedCarId = this.dataset.carId;
                    selectedCarName = this.dataset.carName;
                    selectedCarPrice = parseInt(this.dataset.carPrice);
                    
                    // 今日の日付をデフォルトに設定
                    const today = new Date();
                    document.getElementById('modal-start-date').value = today.toISOString().split('T')[0];
                    document.getElementById('modal-end-date').value = today.toISOString().split('T')[0];
                    
                    showReservationModal();
                });
            });
            
            // 日付セルの予約追加ボタンのイベント
            document.querySelectorAll('.add-reservation-day-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    selectedCarId = this.dataset.carId;
                    selectedCarName = this.dataset.carName;
                    selectedCarPrice = parseInt(this.dataset.carPrice);
                    
                    // 選択された日付をデフォルトに設定
                    const selectedDate = this.dataset.date;
                    document.getElementById('modal-start-date').value = selectedDate;
                    document.getElementById('modal-end-date').value = selectedDate;
                    
                    showReservationModal();
                });
            });
            
            // 予約ブロックのクリックイベント
            document.querySelectorAll('.reservation-block').forEach(function(item) {
                item.addEventListener('click', function() {
                    const reservationId = this.dataset.reservationId;
                    window.open('{{ route("admin.reservations.show", ["reservation" => ":id"]) }}'.replace(':id', reservationId), '_blank');
                });
            });
            
            // 予約作成モーダル表示
            function showReservationModal() {
                document.getElementById('modal-car-id').value = selectedCarId;
                document.getElementById('modal-car-name').textContent = selectedCarName;
                
                updatePriceCalculation();
                document.getElementById('reservation-modal').classList.remove('hidden');
            }
            
            // 料金計算
            function updatePriceCalculation() {
                const startDate = new Date(document.getElementById('modal-start-date').value);
                const endDate = new Date(document.getElementById('modal-end-date').value);
                
                if (startDate && endDate && startDate <= endDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    const totalPrice = diffDays * selectedCarPrice;
                    
                    document.getElementById('modal-period').textContent = diffDays + '日間';
                    document.getElementById('modal-price').textContent = '¥' + totalPrice.toLocaleString();
                    
                    // 隠しフィールドに値を設定
                    if (!document.getElementById('start_datetime')) {
                        const startInput = document.createElement('input');
                        startInput.type = 'hidden';
                        startInput.name = 'start_datetime';
                        startInput.id = 'start_datetime';
                        document.getElementById('reservation-form').appendChild(startInput);
                    }
                    
                    if (!document.getElementById('end_datetime')) {
                        const endInput = document.createElement('input');
                        endInput.type = 'hidden';
                        endInput.name = 'end_datetime';
                        endInput.id = 'end_datetime';
                        document.getElementById('reservation-form').appendChild(endInput);
                    }
                    
                    if (!document.getElementById('total_price')) {
                        const totalPriceInput = document.createElement('input');
                        totalPriceInput.type = 'hidden';
                        totalPriceInput.name = 'total_price';
                        totalPriceInput.id = 'total_price';
                        document.getElementById('reservation-form').appendChild(totalPriceInput);
                    }
                    
                    // 必須フィールドを追加
                    const requiredFields = ['name_kana_sei', 'name_kana_mei', 'number_of_adults', 'number_of_children'];
                    requiredFields.forEach(field => {
                        if (!document.getElementById(field)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = field;
                            input.id = field;
                            input.value = field === 'number_of_adults' ? '1' : '0';
                            document.getElementById('reservation-form').appendChild(input);
                        }
                    });
                    
                    // 日時をISO形式に変換
                    document.getElementById('start_datetime').value = startDate.toISOString().slice(0, 16);
                    document.getElementById('end_datetime').value = endDate.toISOString().slice(0, 16);
                    document.getElementById('total_price').value = totalPrice;
                }
            }
            
            // 日付変更時の料金再計算
            document.getElementById('modal-start-date').addEventListener('change', updatePriceCalculation);
            document.getElementById('modal-end-date').addEventListener('change', updatePriceCalculation);
            
            // モーダルを閉じる
            document.getElementById('cancel-btn').addEventListener('click', function() {
                document.getElementById('reservation-modal').classList.add('hidden');
            });
            
            // モーダル外クリックで閉じる
            document.getElementById('reservation-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
            
            // ナビゲーションボタン
            document.getElementById('today-btn').addEventListener('click', function() {
                currentMonth = new Date();
                updateMonthDisplay();
                updateCalendarGrid();
            });
            
            document.getElementById('prev-btn').addEventListener('click', function() {
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                updateMonthDisplay();
                updateCalendarGrid();
            });
            
            document.getElementById('next-btn').addEventListener('click', function() {
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                updateMonthDisplay();
                updateCalendarGrid();
            });
            
            // フォーム送信後の処理
            document.getElementById('reservation-form').addEventListener('submit', function(e) {
                console.log('フォーム送信開始');
                
                const nameKanji = document.querySelector('input[name="name_kanji"]').value;
                const email = document.querySelector('input[name="email"]').value;
                const phoneMain = document.querySelector('input[name="phone_main"]').value;
                
                console.log('入力値:', { nameKanji, email, phoneMain });
                
                if (!nameKanji || !email || !phoneMain) {
                    e.preventDefault();
                    alert('必須項目を入力してください。');
                    return false;
                }
                
                // 隠しフィールドの値を確実に設定
                const startDate = new Date(document.getElementById('modal-start-date').value);
                const endDate = new Date(document.getElementById('modal-end-date').value);
                
                console.log('日付:', { startDate, endDate });
                
                if (startDate && endDate && startDate <= endDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    const totalPrice = diffDays * selectedCarPrice;
                    
                    console.log('計算結果:', { diffDays, totalPrice, selectedCarPrice });
                    
                    // 隠しフィールドに値を設定
                    if (!document.getElementById('start_datetime')) {
                        const startInput = document.createElement('input');
                        startInput.type = 'hidden';
                        startInput.name = 'start_datetime';
                        startInput.id = 'start_datetime';
                        document.getElementById('reservation-form').appendChild(startInput);
                    }
                    
                    if (!document.getElementById('end_datetime')) {
                        const endInput = document.createElement('input');
                        endInput.type = 'hidden';
                        endInput.name = 'end_datetime';
                        endInput.id = 'end_datetime';
                        document.getElementById('reservation-form').appendChild(endInput);
                    }
                    
                    if (!document.getElementById('total_price')) {
                        const totalPriceInput = document.createElement('input');
                        totalPriceInput.type = 'hidden';
                        totalPriceInput.name = 'total_price';
                        totalPriceInput.id = 'total_price';
                        document.getElementById('reservation-form').appendChild(totalPriceInput);
                    }
                    
                    // 必須フィールドを追加
                    const requiredFields = ['name_kana_sei', 'name_kana_mei', 'number_of_adults', 'number_of_children'];
                    requiredFields.forEach(field => {
                        if (!document.getElementById(field)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = field;
                            input.id = field;
                            input.value = field === 'number_of_adults' ? '1' : '0';
                            document.getElementById('reservation-form').appendChild(input);
                        }
                    });
                    
                    // 日時をISO形式に変換
                    document.getElementById('start_datetime').value = startDate.toISOString().slice(0, 16);
                    document.getElementById('end_datetime').value = endDate.toISOString().slice(0, 16);
                    document.getElementById('total_price').value = totalPrice;
                    
                    console.log('フォーム送信準備完了');
                    console.log('送信データ:', {
                        car_id: document.getElementById('modal-car-id').value,
                        start_datetime: document.getElementById('start_datetime').value,
                        end_datetime: document.getElementById('end_datetime').value,
                        total_price: document.getElementById('total_price').value
                    });
                    
                    // フォーム送信を許可（リロードは削除）
                    return true;
                } else {
                    e.preventDefault();
                    alert('日付が正しく設定されていません。');
                    return false;
                }
            });
            
            // ドラッグ選択機能を初期化
            initializeDragSelection();
        });
    </script>
    @endpush
</x-admin-layout> 