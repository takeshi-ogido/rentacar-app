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
                        <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-6 flex gap-4">
                            <input
                                type="text"
                                name="search"
                                placeholder="名前または電話番号で検索"
                                value="{{ request('search') }}"
                                class="border rounded px-3 py-2 w-64"
                            >

                            <label>
                                予約開始日:
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ request('start_date') }}"
                                    class="border rounded px-3 py-2"
                                >
                            </label>

                            <label>
                                予約終了日:
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ request('end_date') }}"
                                    class="border rounded px-3 py-2"
                                >
                            </label>

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">検索</button>
                        </form>

                        {{-- 顧客一覧テーブル --}}
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">名前</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">電話番号</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">予約状況</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="border border-gray-300 px-4 py-2">{{ $customer->id }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $customer->name }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $customer->phone }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @if($customer->reservations->isEmpty())
                                                予約なし
                                            @else
                                                <ul class="list-disc ml-5">
                                                    @foreach($customer->reservations as $reservation)
                                                        <li>
                                                            {{ $reservation->start_date->format('Y/m/d') }} 〜 {{ $reservation->end_date->format('Y/m/d') }} <br>
                                                            車両ID: {{ $reservation->car_id }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">顧客が見つかりませんでした。</td>
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
