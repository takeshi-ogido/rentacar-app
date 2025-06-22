<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('全車両カレンダー') }}
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
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 車両一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">車両一覧</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($cars as $car)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer car-item" 
                                 data-car-id="{{ $car->id }}" 
                                 data-car-name="{{ $car->carModel->name }}"
                                 data-car-price="{{ $car->price }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $car->carModel->name }}</h4>
                                        <p class="text-sm text-gray-500">車両ID: {{ $car->id }}</p>
                                        <p class="text-sm text-gray-500">¥{{ number_format($car->price) }}/日</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $car->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $car->is_published ? '公開中' : '非公開' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500">
                                        予約数: {{ $car->reservations->count() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- カレンダー -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 予約作成モーダル -->
    <div id="reservation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">新規予約作成</h3>
                <form id="reservation-form" method="POST" action="{{ route('admin.reservations.store') }}">
                    @csrf
                    <input type="hidden" name="car_id" id="modal-car-id">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">選択車両</label>
                            <p class="mt-1 text-sm text-gray-900" id="modal-car-name"></p>
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
                            <label class="block text-sm font-medium text-gray-700">ステータス</label>
                            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending">保留</option>
                                <option value="confirmed">確定</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">備考</label>
                            <textarea name="notes" rows="3" 
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="cancel-btn" 
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                            キャンセル
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            予約作成
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.css' rel='stylesheet' />
    
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/index.global.min.js"></script>
    
    <style>
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.875rem;
            padding: 2px 4px;
        }
        
        .fc-event:hover {
            opacity: 0.8;
        }
        
        .fc-daygrid-event {
            white-space: nowrap;
            border-radius: 3px;
        }
        
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 600;
        }
        
        .fc-button {
            background-color: #3B82F6 !important;
            border-color: #3B82F6 !important;
        }
        
        .fc-button:hover {
            background-color: #2563EB !important;
            border-color: #2563EB !important;
        }
        
        .fc-button-active {
            background-color: #1D4ED8 !important;
            border-color: #1D4ED8 !important;
        }
        
        .car-item.selected {
            background-color: #DBEAFE;
            border-color: #3B82F6;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedCarId = null;
            let selectedCarName = null;
            let selectedCarPrice = null;
            
            // カレンダーの初期化
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,
                height: 'auto',
                events: '{{ route("admin.calendar.reservations") }}',
                select: function(arg) {
                    if (selectedCarId) {
                        showReservationModal(arg.start, arg.end, selectedCarId, selectedCarName, selectedCarPrice);
                    } else {
                        alert('予約を作成する車両を選択してください。');
                    }
                },
                eventClick: function(info) {
                    // 予約詳細を表示
                    window.open('{{ route("admin.reservations.show", "") }}/' + info.event.extendedProps.reservation_id, '_blank');
                },
                eventDidMount: function(info) {
                    // イベントにツールチップを追加
                    const eventEl = info.el;
                    eventEl.title = info.event.title;
                }
            });
            
            calendar.render();
            
            // 車両選択
            document.querySelectorAll('.car-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    // 他の車両の選択を解除
                    document.querySelectorAll('.car-item').forEach(function(car) {
                        car.classList.remove('selected', 'bg-blue-100', 'border-blue-500');
                        car.classList.add('border-gray-200');
                    });
                    
                    // 選択された車両をハイライト
                    this.classList.remove('border-gray-200');
                    this.classList.add('selected', 'bg-blue-100', 'border-blue-500');
                    
                    selectedCarId = this.dataset.carId;
                    selectedCarName = this.dataset.carName;
                    selectedCarPrice = parseInt(this.dataset.carPrice);
                    
                    console.log('選択された車両:', selectedCarName, 'ID:', selectedCarId, '料金:', selectedCarPrice);
                });
            });
            
            // 予約作成モーダル表示
            function showReservationModal(start, end, carId, carName, carPrice) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const totalPrice = diffDays * carPrice;
                
                document.getElementById('modal-car-id').value = carId;
                document.getElementById('modal-car-name').textContent = carName;
                document.getElementById('modal-period').textContent = 
                    startDate.toLocaleDateString('ja-JP') + ' - ' + endDate.toLocaleDateString('ja-JP') + 
                    ' (' + diffDays + '日間)';
                document.getElementById('modal-price').textContent = '¥' + totalPrice.toLocaleString();
                
                // 開始日時と終了日時を隠しフィールドに設定
                if (!document.getElementById('start_datetime')) {
                    const startInput = document.createElement('input');
                    startInput.type = 'hidden';
                    startInput.name = 'start_datetime';
                    startInput.id = 'start_datetime';
                    startInput.value = startDate.toISOString().slice(0, 16);
                    document.getElementById('reservation-form').appendChild(startInput);
                } else {
                    document.getElementById('start_datetime').value = startDate.toISOString().slice(0, 16);
                }
                
                if (!document.getElementById('end_datetime')) {
                    const endInput = document.createElement('input');
                    endInput.type = 'hidden';
                    endInput.name = 'end_datetime';
                    endInput.id = 'end_datetime';
                    endInput.value = endDate.toISOString().slice(0, 16);
                    document.getElementById('reservation-form').appendChild(endInput);
                } else {
                    document.getElementById('end_datetime').value = endDate.toISOString().slice(0, 16);
                }
                
                if (!document.getElementById('total_price')) {
                    const totalPriceInput = document.createElement('input');
                    totalPriceInput.type = 'hidden';
                    totalPriceInput.name = 'total_price';
                    totalPriceInput.id = 'total_price';
                    totalPriceInput.value = totalPrice;
                    document.getElementById('reservation-form').appendChild(totalPriceInput);
                } else {
                    document.getElementById('total_price').value = totalPrice;
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
                
                document.getElementById('reservation-modal').classList.remove('hidden');
            }
            
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
            
            // カレンダーナビゲーション
            document.getElementById('today-btn').addEventListener('click', function() {
                calendar.today();
            });
            
            document.getElementById('prev-btn').addEventListener('click', function() {
                calendar.prev();
            });
            
            document.getElementById('next-btn').addEventListener('click', function() {
                calendar.next();
            });
            
            // フォーム送信後の処理
            document.getElementById('reservation-form').addEventListener('submit', function(e) {
                // フォーム送信前にバリデーション
                const nameKanji = document.querySelector('input[name="name_kanji"]').value;
                const email = document.querySelector('input[name="email"]').value;
                const phoneMain = document.querySelector('input[name="phone_main"]').value;
                
                if (!nameKanji || !email || !phoneMain) {
                    e.preventDefault();
                    alert('必須項目を入力してください。');
                    return false;
                }
            });
        });
    </script>
    @endpush
</x-admin-layout> 