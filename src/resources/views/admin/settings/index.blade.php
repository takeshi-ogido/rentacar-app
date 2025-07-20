<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('システム設定') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- 基本情報 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="company_name" value="会社名" />
                                    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" 
                                        value="{{ old('company_name', $settings['company_name']) }}" required />
                                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="company_email" value="メールアドレス" />
                                    <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" 
                                        value="{{ old('company_email', $settings['company_email']) }}" required />
                                    <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="company_phone" value="電話番号" />
                                    <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" 
                                        value="{{ old('company_phone', $settings['company_phone']) }}" required />
                                    <x-input-error :messages="$errors->get('company_phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="business_hours" value="営業時間" />
                                    <x-text-input id="business_hours" name="business_hours" type="text" class="mt-1 block w-full" 
                                        value="{{ old('business_hours', $settings['business_hours']) }}" required />
                                    <x-input-error :messages="$errors->get('business_hours')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-input-label for="company_address" value="住所" />
                                <x-text-input id="company_address" name="company_address" type="text" class="mt-1 block w-full" 
                                    value="{{ old('company_address', $settings['company_address']) }}" required />
                                <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
                            </div>
                        </div>

                        <!-- 予約設定 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">予約設定</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="reservation_advance_days" value="予約可能日数（日前）" />
                                    <x-text-input id="reservation_advance_days" name="reservation_advance_days" type="number" class="mt-1 block w-full" 
                                        value="{{ old('reservation_advance_days', $settings['reservation_advance_days']) }}" min="1" max="365" required />
                                    <x-input-error :messages="$errors->get('reservation_advance_days')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="cancellation_hours" value="キャンセル可能時間（時間前）" />
                                    <x-text-input id="cancellation_hours" name="cancellation_hours" type="number" class="mt-1 block w-full" 
                                        value="{{ old('cancellation_hours', $settings['cancellation_hours']) }}" min="0" max="168" required />
                                    <x-input-error :messages="$errors->get('cancellation_hours')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="max_reservations_per_user" value="ユーザーあたり最大予約数" />
                                    <x-text-input id="max_reservations_per_user" name="max_reservations_per_user" type="number" class="mt-1 block w-full" 
                                        value="{{ old('max_reservations_per_user', $settings['max_reservations_per_user']) }}" min="1" max="10" required />
                                    <x-input-error :messages="$errors->get('max_reservations_per_user')" class="mt-2" />
                                </div>

                                <div class="flex items-center mt-6">
                                    <input id="auto_confirm_reservations" name="auto_confirm_reservations" type="checkbox" 
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" 
                                        {{ old('auto_confirm_reservations', $settings['auto_confirm_reservations']) ? 'checked' : '' }}>
                                    <label for="auto_confirm_reservations" class="ml-2 text-sm text-gray-700">
                                        予約を自動承認する
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 料金設定 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">料金設定</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="deposit_amount" value="デポジット金額（円）" />
                                    <x-text-input id="deposit_amount" name="deposit_amount" type="number" class="mt-1 block w-full" 
                                        value="{{ old('deposit_amount', $settings['deposit_amount']) }}" min="0" required />
                                    <x-input-error :messages="$errors->get('deposit_amount')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="late_return_fee_per_hour" value="延滞料金（円/時間）" />
                                    <x-text-input id="late_return_fee_per_hour" name="late_return_fee_per_hour" type="number" class="mt-1 block w-full" 
                                        value="{{ old('late_return_fee_per_hour', $settings['late_return_fee_per_hour']) }}" min="0" required />
                                    <x-input-error :messages="$errors->get('late_return_fee_per_hour')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="fuel_surcharge" value="燃料サーチャージ（円）" />
                                    <x-text-input id="fuel_surcharge" name="fuel_surcharge" type="number" class="mt-1 block w-full" 
                                        value="{{ old('fuel_surcharge', $settings['fuel_surcharge']) }}" min="0" required />
                                    <x-input-error :messages="$errors->get('fuel_surcharge')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="insurance_fee" value="保険料（円）" />
                                    <x-text-input id="insurance_fee" name="insurance_fee" type="number" class="mt-1 block w-full" 
                                        value="{{ old('insurance_fee', $settings['insurance_fee']) }}" min="0" required />
                                    <x-input-error :messages="$errors->get('insurance_fee')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- システム設定 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">システム設定</h3>
                            <div class="flex items-center">
                                <input id="maintenance_mode" name="maintenance_mode" type="checkbox" 
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" 
                                    {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                                <label for="maintenance_mode" class="ml-2 text-sm text-gray-700">
                                    メンテナンスモードを有効にする
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                メンテナンスモードが有効の場合、一般ユーザーはシステムにアクセスできません。
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
                                {{ __('設定を保存') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
