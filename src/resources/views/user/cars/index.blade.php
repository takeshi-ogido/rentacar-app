<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('車両一覧・検索') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-y-8 md:gap-x-12">

                <!-- 左カラム：検索フォーム -->
                <div class="w-full md:w-1/3">
                    <form method="GET" action="{{ route('user.cars.index') }}" class="bg-white p-6 rounded shadow sticky top-24">
                        <h2 class="text-lg font-semibold mb-4">レンタカーをさがそう！</h2>

                        {{-- 他の絞り込み条件を維持するための隠しフィールド --}}
                        <input type="hidden" name="type" value="{{ request('type') }}">
                        <input type="hidden" name="capacity" value="{{ request('capacity') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        {{-- 利用開始日時 --}}
                        <div class="mb-4 flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[150px]">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">利用開始日</label>
                                <input type="text" id="start_date" name="start_date" class="border rounded p-2 w-full" value="{{ request('start_date') }}">
                            </div>
                            <div class="flex-1 min-w-[100px]">
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">利用開始時間</label>
                                <input type="time" step="60" id="start_time" name="start_time" class="border rounded p-2 w-full" value="{{ request('start_time') }}">
                            </div>
                        </div>

                        <div class="text-center mb-4">↓</div>

                        {{-- 利用終了日時 --}}
                        <div class="mb-4 flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[150px]">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">利用終了日</label>
                                <input type="text" id="end_date" name="end_date" class="border rounded p-2 w-full" value="{{ request('end_date') }}">
                            </div>
                            <div class="flex-1 min-w-[100px]">
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">利用終了時間</label>
                                <input type="time" step="60" id="end_time" name="end_time" class="border rounded p-2 w-full" value="{{ request('end_time') }}">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            検索
                        </button>
                    </form>
                </div>

                <!-- 右カラム：検索結果 + フィルター -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white p-6 rounded shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <h2 class="text-lg font-semibold">検索結果</h2>

                            <form method="GET" action="{{ route('user.cars.index') }}" class="flex flex-wrap gap-2 sm:justify-end">
                                @foreach (['start_date', 'start_time', 'end_date', 'end_time'] as $param)
                                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                                @endforeach

                                <select name="type" onchange="this.form.submit()" class="w-40 border-gray-300 rounded-md shadow-sm px-2 py-1">
                                    <option value="">車種</option>
                                    @foreach (['軽自動車', 'コンパクトカー','セダン', 'SUV','ミニバン','ハイエース'] as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>

                                <select name="capacity" onchange="this.form.submit()" class="w-40 border-gray-300 rounded-md shadow-sm px-2 py-1">
                                    <option value="">人数</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ request('capacity') == $i ? 'selected' : '' }}>{{ $i }}人</option>
                                    @endfor
                                </select>

                                <select name="sort" onchange="this.form.submit()" class="w-40 border-gray-300 rounded-md shadow-sm px-2 py-1">
                                    <option value="">並び替え</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>料金が安い順</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>料金が高い順</option>
                                </select>
                            </form>
                        </div>

                        {{-- 結果表示 --}}
                        @if ($cars->isNotEmpty())
                            @foreach ($cars as $car)
                                <div class="border rounded-lg p-4 shadow-sm flex gap-4 w-full mb-4">
                                    {{-- 画像ギャラリー --}}
                                    <div x-data="{ selected: '{{ $car->images->first()?->filepath ?? '' }}' }">
                                        @php $images = $car->images; @endphp

                                        @if ($images->isNotEmpty())
                                            <div class="w-full aspect-video mb-2 overflow-hidden rounded shadow">
                                                <img
                                                    :src="'{{ asset('storage') }}/' + selected"
                                                    class="w-full h-full object-cover transition duration-300 ease-in-out transform hover:scale-105"
                                                >
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($images as $image)
                                                    <div
                                                        class="w-20 h-16 overflow-hidden rounded border cursor-pointer hover:opacity-80"
                                                        :class="{ 'ring-2 ring-blue-500': selected === '{{ $image->filepath }}' }"
                                                        @click="selected = '{{ $image->filepath }}'"
                                                    >
                                                        <img src="{{ asset('storage/' . $image->filepath) }}" alt="サブ画像" class="w-full h-full object-cover">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="w-full aspect-video bg-gray-200 flex items-center justify-center text-sm text-gray-500 rounded mb-2">
                                                No Image
                                            </div>
                                            <div class="flex gap-2">
                                                @for ($i = 0; $i < 4; $i++)
                                                    <div class="w-16 h-12 bg-gray-100 border flex items-center justify-center text-xs text-gray-400 rounded">
                                                        No Image
                                                    </div>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 車両情報 --}}
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div class="text-lg font-semibold">{{ $car->name }}</div>
                                        <div class="text-sm text-gray-600 mb-1">{{ $car->type }} / {{ $car->capacity }}人乗り</div>

                                        {{-- 装備バッジ --}}
                                        <div class="text-sm mt-2 flex flex-wrap gap-2">
                                            @php
                                                $badge = "flex items-center gap-1 px-2 py-1 bg-gray-200 rounded text-xs shadow-sm hover:scale-105 transition-transform duration-200";
                                            @endphp

                                            <span class="{{ $badge }}">
                                                {{ $car->smoking_preference_label }}
                                            </span>
                                            <span class="{{ $badge }}">🕹 {{ $car->transmission }}車</span>
                                            @foreach ($car->equipment_list as $equipment)
                                                <span class="{{ $badge }}">{{ $equipment['icon'] }} {{ $equipment['label'] }}</span>
                                            @endforeach
                                        </div>

                                        {{-- 料金表示 --}}
                                        <div class="mt-3 font-bold text-lg text-blue-600">
                                            @if ($car->totalPrice)
                                                合計料金: ¥{{ number_format($car->totalPrice) }}
                                                <span class="text-sm text-gray-600 ml-2">（{{ $car->durationLabel }}）</span>
                                                <div class="text-sm text-gray-500">1日あたり: ¥{{ number_format($car->price) }}</div>
                                            @else
                                                <div class="text-sm text-gray-500">1日あたり料金: ¥{{ number_format($car->price) }}</div>
                                            @endif
                                        </div>

                                        {{-- 詳細ボタン --}}
                                        <div class="mt-4 flex justify-end">
                                            <a href="{{ route('user.cars.show', [
                                                'car' => $car->id,
                                                'start_datetime' => request('start_date') && request('start_time') ? request('start_date') . ' ' . request('start_time') : null,
                                                'end_datetime' => request('end_date') && request('end_time') ? request('end_date') . ' ' . request('end_time') : null,
                                            ]) }}"
                                               class="inline-block bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded"
                                               onclick="if (!'{{ request('start_date') }}' || !'{{ request('start_time') }}' || !'{{ request('end_date') }}' || !'{{ request('end_time') }}') { alert('利用日時をすべて指定して検索してください。'); return false; }">
                                                詳細を見る
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- ページネーション --}}
                            <div class="mt-6 text-sm text-gray-700">
                                {{ $cars->links() }}
                            </div>
                        @else
                            <div class="text-gray-500 text-sm mt-4">
                                該当する車両が見つかりませんでした。
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Litepicker --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const startDateEl = document.getElementById('start_date');
                const startTimeEl = document.getElementById('start_time');
                const endDateEl = document.getElementById('end_date');
                const endTimeEl = document.getElementById('end_time');

                const startPicker = new Litepicker({
                    element: startDateEl,
                    format: 'YYYY-MM-DD',
                    minDate: new Date(),
                    setup: (picker) => {
                        picker.on('selected', () => updateEndConstraints());
                    }
                });

                const endPicker = new Litepicker({
                    element: endDateEl,
                    format: 'YYYY-MM-DD',
                    minDate: new Date(),
                    setup: (picker) => {
                        picker.on('selected', () => validateEndTime());
                    }
                });

                [startDateEl, startTimeEl].forEach(el => {
                    el.addEventListener('change', updateEndConstraints);
                });

                [endDateEl, endTimeEl].forEach(el => {
                    el.addEventListener('change', validateEndTime);
                });

                function updateEndConstraints() {
                    const startDate = startDateEl.value;
                    if (startDate) {
                        endPicker.setOptions({ minDate: startDate });
                    }
                    validateEndTime();
                }

                function validateEndTime() {
                    const startDate = startDateEl.value;
                    const startTime = startTimeEl.value;
                    const endDate = endDateEl.value;
                    const endTime = endTimeEl.value;

                    if (!startDate || !startTime || !endDate || !endTime) return;

                    const start = new Date(`${startDate}T${startTime}`);
                    const end = new Date(`${endDate}T${endTime}`);

                    if (end < start) {
                        alert('終了日時は開始日時より後の時間を選択してください。');
                        endTimeEl.value = '';
                        endTimeEl.classList.add('border-red-500');
                        endTimeEl.title = '開始日時より後の時刻を選んでください';
                    } else {
                        endTimeEl.classList.remove('border-red-500');
                        endTimeEl.title = '';
                    }
                }

                updateEndConstraints();
            });
        </script>
    @endpush
</x-user-layout>