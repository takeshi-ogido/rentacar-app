<x-user-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('店舗情報') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="bg-white p-8">
                <!-- 店舗情報テーブル -->
                <table class="table-auto w-full mb-8 border-gray-100 shadow-lg border">
                    <tbody>
                        <tr class="border-b-2">
                            <th class="text-left px-4 py-4 w-32">店舗名</th>
                            <td class="px-4 py-4">オージーアイレンタカー</td>
                        </tr>
                        <tr class="border-b-2">
                            <th class="text-left px-4 py-4">TEL</th>
                            <td class="px-4 py-4">
                                <a href="tel:0988514443" class="text-blue-600 hover:underline">09812345678</a>
                            </td>
                        </tr>
                        <tr class="border-b-2">
                            <th class="text-left px-4 py-4">メールアドレス</th>
                            <td class="px-4 py-4">
                                <a href="mailto:contact@okinawa-rentacar.jp" class="text-blue-600 hover:underline">aiueo@okinawa-rentacar.jp</a>
                            </td>
                        </tr>
                        <tr class="border-b-2">
                            <th class="text-left px-4 py-4">住所</th>
                            <td class="px-4 py-4">〒901-0224 沖縄県国頭郡今帰仁村字渡帰仁</td>
                        </tr>
                        <tr class="border-b-2">
                            <th class="text-left px-4 py-4 w-32">営業時間</th>
                            <td class="px-4 py-4">8:00〜20:00</td>
                        </tr>
                        <tr>
                            <th class="text-left px-4 py-4">アクセス</th>
                            <td class="px-4 py-4">
                                那覇空港またはゆいレール赤嶺駅より無料送迎あります。<br>
                                那覇空港送迎希望の方は、航空便名を備考にご記入ください。
                            </td>
                        </tr>

                    </tbody>
                </table>

                <!-- Google Maps埋め込み -->
                <div class="mb-3">
                    <iframe
                        src="https://www.google.com/maps?q=〒901-0224+沖縄県豊見城市与根50-56&output=embed"
                        width="100%"
                        height="400"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                    ></iframe>
                </div>

                <div class="text-center mb-8">
                    <a href="https://www.google.com/maps?q=〒901-0224+沖縄県豊見城市与根50-56"
                    target="_blank"
                    class="inline-block font-semibold py-2 px-6 rounded">
                        Google Mapsで見る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-user-layout>