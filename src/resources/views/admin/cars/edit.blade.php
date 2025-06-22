<!-- resources/views/admin/cars/edit.blade.php -->
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('車両編集') }}
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

                    <form method="POST" action="{{ route('admin.cars.update', $car) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                            <!-- 左カラム -->
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">車両名 <span class="text-red-500">*</span></label>
                                    <input id="name" name="name" type="text" value="{{ old('name', $car->name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300" placeholder="アクア、ノートなど"/>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">車種 <span class="text-red-500">*</span></label>
                                    <select id="type" name="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="">選択してください</option>
                                        @foreach (['軽自動車', 'セダン', 'SUV', 'ミニバン', 'コンパクト', 'ステーションワゴン', 'その他'] as $value)
                                            <option value="{{ $value }}" {{ old('type', $car->type) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">料金 (/日) <span class="text-red-500">*</span></label>
                                    <input id="price" name="price" type="number" min="0" value="{{ old('price', $car->price) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300" />
                                </div>

                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">定員 <span class="text-red-500">*</span></label>
                                    <input id="capacity" name="capacity" type="number" min="1" value="{{ old('capacity', $car->capacity) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300" placeholder="例: 5" />
                                </div>

                                <div>
                                    <label for="transmission" class="block text-sm font-medium text-gray-700">ミッション <span class="text-red-500">*</span></label>
                                    <select id="transmission" name="transmission" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="">選択してください</option>
                                        <option value="AT" {{ old('transmission', $car->transmission) == 'AT' ? 'selected' : '' }}>AT (オートマチック)</option>
                                        <option value="MT" {{ old('transmission', $car->transmission) == 'MT' ? 'selected' : '' }}>MT (マニュアル)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="smoking_preference" class="block text-sm font-medium text-gray-700">禁煙/喫煙 <span class="text-red-500">*</span></label>
                                    <select id="smoking_preference" name="smoking_preference" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">
                                        <option value="">選択してください</option>
                                        <option value="禁煙" {{ old('smoking_preference', $car->smoking_preference) == '禁煙' ? 'selected' : '' }}>禁煙</option>
                                        <option value="喫煙可" {{ old('smoking_preference', $car->smoking_preference) == '喫煙可' ? 'selected' : '' }}>喫煙可</option>
                                        <option value="電子タバコのみ可" {{ old('smoking_preference', $car->smoking_preference) == '電子タバコのみ可' ? 'selected' : '' }}>電子タバコのみ可</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="license_plate" class="block text-sm font-medium text-gray-700">ナンバープレート</label>
                                    <input id="license_plate" name="license_plate" type="text" value="{{ old('license_plate', $car->license_plate) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300"
                                        placeholder="例: 横浜500 あ 12-34" />
                                </div>

                                <div>
                                    <label for="vin_number" class="block text-sm font-medium text-gray-700">車台番号</label>
                                    <input id="vin_number" name="vin_number" type="text" value="{{ old('vin_number', $car->vin_number) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300"
                                        placeholder="例: ABCD1234567890XYZ" />
                                </div>

                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">色</label>
                                    <input id="color" name="color" type="text" value="{{ old('color', $car->color) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300"
                                        placeholder="例: ホワイト, ブラック など" />
                                </div>
                            </div>

                            <!-- 右カラム -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">オプション</label>
                                    <div class="space-y-2 mt-1">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_bluetooth" name="has_bluetooth" value="1" {{ old('has_bluetooth', $car->has_bluetooth) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_bluetooth" class="ml-2 text-sm text-gray-900">Bluetooth搭載</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_back_monitor" name="has_back_monitor" value="1" {{ old('has_back_monitor', $car->has_back_monitor) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_back_monitor" class="ml-2 text-sm text-gray-900">バックモニター搭載</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_navigation" name="has_navigation" value="1" {{ old('has_navigation', $car->has_navigation) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_navigation" class="ml-2 text-sm text-gray-900">カーナビ搭載</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_etc" name="has_etc" value="1" {{ old('has_etc', $car->has_etc) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                            <label for="has_etc" class="ml-2 text-sm text-gray-900">ETC車載器搭載</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">車両説明</label>
                                    <textarea id="description" name="description" rows="6"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-200 focus:border-indigo-300">{{ old('description', $car->description) }}</textarea>
                                </div>

                                <!-- 既存画像表示 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">現在の車両画像</label>
                                    <div class="flex flex-wrap gap-4">
                                        @forelse ($car->images as $image)
                                            <div class="relative w-24 h-24 border rounded overflow-hidden bg-gray-100">
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="車両画像" class="object-cover w-full h-full" />
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500">画像が登録されていません。</p>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- 新規画像アップロード -->
                                <div>
                                    <label for="images" class="block text-sm font-medium text-gray-700">新しい車両画像 (複数選択可)</label>
                                    <input id="images" name="images[]" type="file" multiple accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-xs text-gray-500">
                                        最大5枚までアップロード可能です。<br>
                                        複数選択する場合は、WindowsではCtrlキー、MacではCommandキーを押しながら選択してください。
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">公開設定 <span class="text-red-500">*</span></label>
                                    <div class="flex space-x-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="is_public" value="1" 
                                                {{ old('public', $car->is_public) == 1 ? 'checked' : '' }} required
                                                class="form-radio text-indigo-600" />
                                            <span class="ml-2">公開する</span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="radio" name="is_public" value="0" 
                                                {{ old('public', $car->is_public) == 0 ? 'checked' : '' }} required
                                                class="form-radio text-indigo-600" />
                                            <span class="ml-2">非公開</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        更新する
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="mt-8 text-center">
                        <a href="{{ route('admin.cars.index') }}" class="text-indigo-600 hover:text-indigo-900">一覧に戻る</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>