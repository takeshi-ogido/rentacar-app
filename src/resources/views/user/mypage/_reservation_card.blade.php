<div class="border rounded-lg p-4 flex flex-col md:flex-row gap-6 shadow-sm">
    {{-- 左側：画像 --}}
    <div class="md:w-1/3">
        @if ($reservation->car->images->first())
            <img src="{{ asset('storage/' . $reservation->car->images->first()->filepath) }}" alt="{{ $reservation->car->name }}" class="w-full h-auto rounded-lg object-cover aspect-video shadow-md">
        @endif
    </div>

    {{-- 右側：詳細情報 --}}
    <div class="md:w-2/3">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-bold text-gray-800">{{ $reservation->car->name }}</h3>
            <span class="text-sm font-medium px-3 py-1 rounded-full whitespace-nowrap
                @if ($reservation->start_datetime > now()) bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif">
                {{ $reservation->start_datetime > now() ? '予約中' : '利用済み' }}
            </span>
        </div>

        {{-- 予約内容 --}}
        <div class="space-y-3 text-sm text-gray-700 mb-6 border-t pt-4">
            <div><strong>予約ID：</strong>{{ $reservation->id }}</div>
            <div><strong>利用期間：</strong>{{ $reservation->start_datetime->format('Y年m月d日 H:i') }} 〜 {{ $reservation->end_datetime->format('Y年m月d日 H:i') }}</div>
            @php
                $nights = $reservation->start_datetime->copy()->startOfDay()->diffInDays($reservation->end_datetime->copy()->startOfDay());
                $days = $nights + 1;
                $isDayTrip = ($nights === 0);
            @endphp
            <div><strong>期間：</strong>{{ $isDayTrip ? '日帰り' : "{$nights}泊{$days}日" }}</div>

            @if (count($reservation->formatted_options))
                <div><strong>オプション：</strong></div>
                <ul class="ml-4 list-disc">
                    @foreach ($reservation->formatted_options as $opt)
                        <li>
                            {{ $opt['name'] }}：¥{{ number_format($opt['unit_price']) }}
                            @if ($opt['is_quantity'])
                                × {{ $opt['quantity'] }}個
                            @else
                                × {{ $days }}日
                            @endif
                            = <strong>¥{{ number_format($opt['price']) }}</strong>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="font-bold text-xl text-gray-900 pt-2">
                合計金額（税込）：¥{{ number_format($reservation->total_price) }}
            </div>
        </div>
    </div>
</div>