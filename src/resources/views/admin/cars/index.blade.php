<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('車両管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('admin.cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            新規車両追加
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">画像</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">車両名</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">車種</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">定員</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">料金(/日)</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ミッション</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">BT</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">禁煙<br>/<br>喫煙</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">バックモニター</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ナビ</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ETC</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">状態</th>
                                    <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($cars as $car)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($car->images && $car->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $car->images->first()->path) }}" alt="{{ $car->name }}" class="h-10 w-16 object-cover rounded">
                                        @else
                                            <span class="text-xs text-gray-400">画像なし</span>
                                        @endif
                                    </td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $car->name }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->type }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->capacity }}人</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">¥{{ number_format($car->price) }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->transmission }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->has_bluetooth ? 'あり' : 'なし' }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->smoking_preference }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->has_back_monitor ? 'あり' : 'なし' }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->has_navigation ? 'あり' : 'なし' }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->has_etc ? 'あり' : 'なし' }}</td>
                                    <td class="px-3 text-center py-4 whitespace-nowrap">
                                        @if($car->is_public)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">公開中</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">非公開</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.cars.show', $car) }}" class="text-xs bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-2 rounded">詳細</a>
                                            <form action="{{ route('admin.cars.togglePublish', $car) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs {{ $car->is_public ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white font-semibold py-1 px-2 rounded">
                                                    {{ $car->is_public ? '非公開へ' : '公開へ' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.cars.edit', $car) }}" class="text-xs bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-1 px-2 rounded">編集</a>
                                            <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="inline-block" onsubmit="return confirm('本当に削除してもよろしいですか？この操作は元に戻せません。');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-2 rounded">削除</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        車両データがありません。
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($cars->hasPages())
                        <div class="mt-4 p-4">
                            {{ $cars->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>