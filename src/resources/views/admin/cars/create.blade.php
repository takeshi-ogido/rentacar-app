<!-- 車両登録 -->
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新規車両登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.cars.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                            <!-- 左カラム -->
                            <div class="space-y-4">
                                <!-- 車両名 -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">車両名 <span class="text-red-500">*</span></label>
                                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300" placeholder="アクア、ノートなど"/>
                                </div>

                                <!-- 車種 -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">車種 <span class="text-red-500">*</span></label>
                                    <select id="type" name="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="">選択してください</option>
                                        @foreach (['軽自動車', 'セダン', 'SUV', 'ミニバン', 'コンパクト', 'ステーションワゴン', 'その他'] as $value)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 料金 -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">料金 (/日) <span class="text-red-500">*</span></label>
                                    <input id="price" name="price" type="number" min="0" value="{{ old('price') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300" />
                                </div>

                                <!-- 定員 -->
                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">定員 <span class="text-red-500">*</span></label>
                                    <input id="capacity" name="capacity" type="number" min="1" value="{{ old('capacity') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300" placeholder="例: 5" />
                                </div>

                                <!-- ミッション -->
                                <div>
                                    <label for="transmission" class="block text-sm font-medium text-gray-700">ミッション <span class="text-red-500">*</span></label>
                                    <select id="transmission" name="transmission" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="">選択してください</option>
                                        <option value="AT" {{ old('transmission', 'AT') == 'AT' ? 'selected' : '' }}>AT (オートマチック)</option>
                                        <option value="MT" {{ old('transmission') == 'MT' ? 'selected' : '' }}>MT (マニュアル)</option>
                                    </select>
                                </div>

                                <!-- 禁煙/喫煙 -->
                                <div>
                                    <label for="smoking_preference" class="block text-sm font-medium text-gray-700">禁煙/喫煙 <span class="text-red-500">*</span></label>
                                    <select id="smoking_preference" name="smoking_preference" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="">選択してください</option>
                                        <option value="禁煙" {{ old('smoking_preference', '禁煙') == '禁煙' ? 'selected' : '' }}>禁煙</option>
                                        <option value="喫煙可" {{ old('smoking_preference') == '喫煙可' ? 'selected' : '' }}>喫煙可</option>
                                        <option value="電子タバコのみ可" {{ old('smoking_preference') == '電子タバコのみ可' ? 'selected' : '' }}>電子タバコのみ可</option>
                                    </select>
                                </div>

                                <!-- ナンバープレート（任意） -->
                                <div>
                                    <label for="license_plate" class="block text-sm font-medium text-gray-700">ナンバープレート</label>
                                    <input id="license_plate" name="license_plate" type="text" value="{{ old('license_plate') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300"
                                        placeholder="例: 横浜500 あ 12-34" />
                                </div>

                                <!-- 車台番号（任意） -->
                                <div>
                                    <label for="vin_number" class="block text-sm font-medium text-gray-700">車台番号</label>
                                    <input id="vin_number" name="vin_number" type="text" value="{{ old('vin_number') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300"
                                        placeholder="例: ABCD1234567890XYZ" />
                                </div>

                                <!-- 色 -->
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">色</label>
                                    <input id="color" name="color" type="text" value="{{ old('color') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300"
                                        placeholder="例: ホワイト, ブラック など" />
                                </div>
                            </div>

                            <!-- 右カラム -->
                            <div class="space-y-4">
                                <!-- オプション -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">オプション</label>
                                    <div class="space-y-2 mt-1">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_bluetooth" name="has_bluetooth" value="1" {{ old('has_bluetooth') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_bluetooth" class="ml-2 text-sm text-gray-900">Bluetooth搭載</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_back_monitor" name="has_back_monitor" value="1" {{ old('has_back_monitor') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_back_monitor" class="ml-2 text-sm text-gray-900">バックモニター搭載</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_navigation" name="has_navigation" value="1" {{ old('has_navigation') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_navigation" class="ml-2 text-sm text-gray-900">カーナビ搭載</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_etc" name="has_etc" value="1" {{ old('has_etc') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_etc" class="ml-2 text-sm text-gray-900">ETC車載器搭載</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- 車両説明 -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">車両説明</label>
                                    <textarea id="description" name="description" rows="6"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">{{ old('description') }}</textarea>
                                </div>

                                <!-- 車両画像 -->
                                <div>
                                    <label for="images" class="block text-sm font-medium text-gray-700">車両画像 (複数選択可)</label>
                                    <input id="images" name="images[]" type="file" multiple accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-xs text-gray-500">
                                        最大5枚までアップロードできます。<br>
                                        画像は一度にまとめて選択してください。<br>
                                        <strong>※注意：</strong><br>
                                        ・Macの場合は、ファイル選択時にCommandキーを押しながら複数選択してください。<br>
                                        ・Windowsの場合は、ファイル選択時にCtrlキーを押しながら複数選択してください。<br>
                                        一枚ずつ追加はできませんのでご注意ください。<br>
                                        1枚あたりのファイルサイズは2MBまでです。
                                    </p>
                                    <!-- ここにプレビュー画像を表示 -->
                                    <div id="preview" class="mt-4 flex flex-wrap gap-4"></div>
                                </div>
                                <!-- 公開状態 -->
                                <div>
                                    <label for="is_public" class="block text-sm font-medium text-gray-700">公開状態</label>
                                    <select id="is_public" name="is_public"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="1" {{ old('is_public', 1) == 1 ? 'selected' : '' }}>公開</option>
                                        <option value="0" {{ old('is_public') == 0 ? 'selected' : '' }}>非公開</option>
                                    </select>
                                </div>

                                <!-- ボタン -->
                                <div class="flex justify-end pt-4 gap-6">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        登録する
                                    </button>
                                    <a href="{{ route('admin.cars.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mx-4 py-2">キャンセル</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="text-green-600 font-semibold mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-4 mt-8"></div>
                    <h3 class="text-lg font-semibold mt-12 mb-4">登録済み車両一覧</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($cars as $car)
                            <div class="border p-4 rounded shadow-sm bg-white flex items-start gap-4">
                                {{-- 左側：画像 --}}
                                @if($car->images->isNotEmpty())
                                    <div class="flex flex-col gap-2 w-40 flex-shrink-0">
                                        @foreach($car->images->take(3) as $image)
                                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $car->name }}" class="rounded border object-cover w-full h-24" />
                                        @endforeach
                                    </div>
                                @else
                                    <div class="w-40 h-24 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">画像なし</div>
                                @endif

                                {{-- 右側：情報 --}}
                                <div class="flex flex-col">
                                    <h4 class="text-md font-bold">{{ $car->name }}</h4>
                                    <p class="text-sm text-gray-600">車種: {{ $car->type }}</p>
                                    <p class="text-sm text-gray-600">料金: ¥{{ number_format($car->price) }}/日</p>
                                    <p class="text-sm text-gray-600">ミッション: {{ $car->transmission }}</p>
                                    <p class="text-sm text-gray-600">禁煙/喫煙: {{ $car->smoking_preference }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>                
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.getElementById('images').addEventListener('change', function(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = ''; // 以前のプレビューをクリア

            const files = event.target.files;
            if (files.length > 5) {
                alert('最大5枚までアップロード可能です。');
                event.target.value = ''; // 選択をリセット
                return;
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return; // 画像以外は無視

                if (file.size > 2 * 1024 * 1024) { // 2MBチェック
                    alert(`${file.name} は2MBを超えています。`);
                    event.target.value = ''; // 選択をリセット
                    preview.innerHTML = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('h-24', 'w-auto', 'rounded', 'border', 'border-gray-300');
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    @endpush
</x-admin-layout>