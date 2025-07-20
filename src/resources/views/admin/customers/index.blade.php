<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('顧客管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                        {{-- 検索フォーム --}}
                        <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-6">
                            <div class="flex flex-wrap gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">検索</label>
                                    <input
                                        type="text"
                                        name="search"
                                        placeholder="名前または電話番号で検索"
                                        value="{{ request('search') }}"
                                        class="border rounded px-3 py-2 w-64"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">予約開始日</label>
                                    <input
                                        type="date"
                                        name="start_date"
                                        value="{{ request('start_date') }}"
                                        class="border rounded px-3 py-2"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">予約終了日</label>
                                    <input
                                        type="date"
                                        name="end_date"
                                        value="{{ request('end_date') }}"
                                        class="border rounded px-3 py-2"
                                    >
                                </div>
                                <div>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        検索
                                    </button>
                                    @if(request('search') || request('start_date') || request('end_date'))
                                        <a href="{{ route('admin.customers.index') }}" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                            クリア
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        {{-- 顧客一覧テーブル --}}
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">名前</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">電話番号</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">予約状況</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="border border-gray-300 px-4 py-2">{{ $customer->id }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $customer->name }}
                                            </a>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @if($customer->phone)
                                                {{ $customer->phone }}
                                            @else
                                                <span class="text-gray-500">未登録</span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @if($customer->reservations->isEmpty())
                                                <span class="text-gray-500">予約なし</span>
                                            @else
                                                <div class="space-y-1">
                                                    @foreach($customer->reservations->take(3) as $reservation)
                                                        <div class="text-sm">
                                                            @if($reservation->start_datetime && $reservation->end_datetime)
                                                                <div class="font-medium text-blue-600">
                                                                    {{ $reservation->start_datetime->format('m/d') }} 〜 {{ $reservation->end_datetime->format('m/d') }}
                                                                </div>
                                                                <div class="text-xs text-gray-600">
                                                                    {{ $reservation->car->carModel->name ?? '車両不明' }}
                                                                </div>
                                                            @else
                                                                <div class="text-gray-500 text-xs">日付未設定</div>
                                                                <div class="text-xs text-gray-600">
                                                                    {{ $reservation->car->carModel->name ?? '車両不明' }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    @if($customer->reservations->count() > 3)
                                                        <div class="text-xs text-gray-500">
                                                            他{{ $customer->reservations->count() - 3 }}件
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                詳細を見る
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">顧客が見つかりませんでした。</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    {{-- ページネーション --}}
                    <div class="mt-4">
                        {{ $customers->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
