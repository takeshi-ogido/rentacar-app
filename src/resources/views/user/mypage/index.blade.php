<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            ご予約の確認
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($upcomingReservations->isNotEmpty() || $pastReservations->isNotEmpty())
                <div x-data="{ activeTab: 'upcoming' }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    {{-- タブボタン --}}
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <button @click="activeTab = 'upcoming'"
                                    :class="{
                                        'border-blue-500 text-blue-600': activeTab === 'upcoming',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'upcoming'
                                    }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                                予約中
                                @if($upcomingReservations->count() > 0)
                                    <span class="ml-2 bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $upcomingReservations->count() }}</span>
                                @endif
                            </button>
                            <button @click="activeTab = 'past'"
                                    :class="{
                                        'border-blue-500 text-blue-600': activeTab === 'past',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'past'
                                    }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                                利用済み
                                @if($pastReservations->count() > 0)
                                    <span class="ml-2 bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pastReservations->count() }}</span>
                                @endif
                            </button>
                        </nav>
                    </div>

                    {{-- タブコンテンツ --}}
                    <div class="p-6">
                        {{-- 予約中コンテンツ --}}
                        <div x-show="activeTab === 'upcoming'" x-transition>
                            <div class="space-y-6">
                                @forelse ($upcomingReservations as $reservation)
                                    @include('user.mypage._reservation_card', ['reservation' => $reservation])
                                @empty
                                    <p class="text-gray-500">現在、予約中の車両はありません。</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- 利用済みコンテンツ --}}
                        <div x-show="activeTab === 'past'" x-transition style="display: none;">
                            <div class="space-y-6">
                                @forelse ($pastReservations as $reservation)
                                    @include('user.mypage._reservation_card', ['reservation' => $reservation])
                                @empty
                                    <p class="text-gray-500">利用済みの車両はありません。</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        ご予約履歴はありません。
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-user-layout>