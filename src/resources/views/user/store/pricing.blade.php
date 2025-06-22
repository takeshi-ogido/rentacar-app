<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('料金表') }}
        </h2>
    </x-slot>
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">料金プラン一覧</h2>
                <p class="text-gray-600">期間ごとの料金を確認できます。</p>
                {{-- ←ここに $slot を入れることも可能 --}}
            </div>

            {{-- テーブル --}}
            <div class="overflow-x-auto bg-white shadow-md rounded-lg py-2">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">期間ごとの料金プラン</th>
                            <th class="px-4 py-3 text-left">料金種別</th>
                            <th class="px-4 py-3 text-center">当日利用</th>
                            <th class="px-4 py-3 text-center">1泊2日</th>
                            <th class="px-4 py-3 text-center">2泊3日</th>
                            <th class="px-4 py-3 text-center">3泊4日</th>
                            <th class="px-4 py-3 text-center">4泊5日</th>
                            <th class="px-4 py-3 text-center">5泊6日</th>
                            <th class="px-4 py-3 text-center">6泊7日</th>
                            <th class="px-4 py-3 text-center">7泊8日</th>
                            <th class="px-4 py-3 text-center">以後1日ごと</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        {{-- 仮データを1行だけ入れておく --}}
                        <tr>
                            <td class="px-4 py-2">現在</td>
                            <td class="px-4 py-2">通常料金</td>
                            <td class="px-4 py-2 text-center">2,420円</td>
                            <td class="px-4 py-2 text-center">4,840円</td>
                            <td class="px-4 py-2 text-center">7,260円</td>
                            <td class="px-4 py-2 text-center">9,680円</td>
                            <td class="px-4 py-2 text-center">12,100円</td>
                            <td class="px-4 py-2 text-center">14,520円</td>
                            <td class="px-4 py-2 text-center">16,940円</td>
                            <td class="px-4 py-2 text-center">19,360円</td>
                            <td class="px-4 py-2 text-center">2,420円</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-user-layout>