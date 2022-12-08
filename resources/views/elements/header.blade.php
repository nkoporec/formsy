<nav id="header" class="bg-white text-white w-auto flex-1 border-b-1 border-gray-300 order-1 lg:order-2 h-24">
    <div class="flex h-full justify-between items-center">
        @if (Auth::check())
            @livewire('header-search')
        @endif
        @if (Auth::check())
        <div class="flex relative inline-block text-formsypurple h-full">
            <notification user_id="{{Auth::id()}}"></notification>
        </div>
        <div class="flex relative inline-block md:w-2/12 w-3/12">
            <user-menu
                account-route="{{ route('user') }}"
                logout-route="{{ route('logout') }}"
                user-name="{{ Auth::user()->name }}"
                gravatar="{{ Gravatar::src(Auth::user()->email, 50) }}">
            </user-menu>
        </div>
        @endif
    </div>
</nav>
