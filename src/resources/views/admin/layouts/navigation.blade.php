<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        <x-application-logo class="block w-auto text-gray-800 fill-current h-9" />
                        <span class="text-xl font-bold text-gray-800">レンタカー管理システム</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.cars.index')" :active="request()->routeIs('admin.cars.index')">
                        {{ __('車両管理') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reservations.index')" :active="request()->routeIs('admin.reservations.*')">
                        {{ __('予約管理') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.index')">
                        {{ __('顧客管理') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reports.sales')" :active="request()->routeIs('admin.reports.sales')">
                        {{ __('売上管理') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('ダッシュボード') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.index')">
                        {{ __('システム設定') }}
                    </x-nav-link>
                </div>
            </div>
            <!-- Settings Dropdown -->
            @auth('admin')
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::guard('admin')->user()->name }}</div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('admin.profile.edit')">
                            {{ __('プロフィール編集') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('admin.logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('ログアウト') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth
            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.cars.index')" :active="request()->routeIs('admin.cars.index')">
                {{ __('車両管理') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.reservations.index')" :active="request()->routeIs('admin.reservations.*')">
                {{ __('予約管理') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.index')">
                {{ __('顧客管理') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.reports.sales')" :active="request()->routeIs('admin.reports.sales')">
                {{ __('売上管理') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('ダッシュボード') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.index')">
                {{ __('システム設定') }}
            </x-responsive-nav-link>
        </div>
        <!-- Responsive Settings Options -->
        @auth('admin')
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800">{{ Auth::guard('admin')->user()->name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::guard('admin')->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.profile.edit')">
                    {{ __('プロフィール編集') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('admin.logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('ログアウト') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
