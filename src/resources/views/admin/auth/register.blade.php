<x-guest-layout>
    <form method="POST" action="{{ route('admin.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-admin::input-label for="name" :value="__('Name')" />
            <x-admin::text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-admin::input-error :messages="$errors->get('name')" class="mt-2" />        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-admin::input-label for="email" :value="__('Email')" />
            <x-admin::text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-admin::input-error :messages="$errors->get('email')" class="mt-2" />        
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-admin::input-label for="password" :value="__('Password')" />
            <x-admin::text-input id="password" class="block mt-1 w-full"                            
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-admin::input-error :messages="$errors->get('password')" class="mt-2" />        
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-admin::input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-admin::text-input id="password_confirmation" class="block mt-1 w-full"                            
                type="password"
                name="password_confirmation" 
                required autocomplete="new-password" />

            <x-admin::input-error :messages="$errors->get('password_confirmation')" class="mt-2" />        
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.login') }}">
                {{ __('すでにアカウントをお持ちですか？') }}
            </a>

            <x-admin::primary-button class="ms-4">                
                {{ __('新規登録する') }}
            </x-admin::primary-button>        
        </div>
    </form>
</x-guest-layout>
