<div id="sidebar" class="lg:flex hidden h-screen items-center w-24 menu bg-white text-white px-4 font-raleway static fixed hover:text-gray-800 text-opacity-0 border-r">
    <a href="{{ route('home') }}" class="w-full w-11/12 block pt-1 pl-1 align-middle no-underline absolute top-0 left-0 inline-block border-b">
        <img src="{{URL::asset('/images/logo/logo.svg')}}" alt="Logo" class="text-3xl"/>
    </a>
    <ul class="list-reset nav">
        @if (Auth::check())
        <li class="my-2 md:my-0">
            <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'text-formsypurple' : 'text-formsydark'}} block py-3 pl-3 mb-5 align-middle no-underline hover:text-formsypurple w-32">
                <i class="icon-home text-2xl py-3"></i><span class="w-full font-semibold inline-block pl-2 mt-1 md:pb-0 text-md">Home</span>
            </a>
            <a href="{{ route('settings') }}" class="{{ request()->is('settings') ? 'text-formsypurple' : 'text-formsydark'}} block py-3 pl-3 mb-5 align-middle no-underline hover:text-formsypurple w-32">
                <i class="icon-cog text-2xl {{ request()->is('settings') ? 'active' : ''}}"></i><span class="w-full font-semibold inline-block pl-2 mt-1 md:pb-0 text-md">Settings</span>
            </a>
            <a href="{{ route('events') }}" class="{{ request()->is('events') ? 'text-formsypurple' : 'text-formsydark'}} block py-3 pl-3 mb-5 align-middle no-underline hover:text-formsypurple w-32">
                <i class="icon-terminal text-2xl {{ request()->is('events') ? 'active' : ''}}"></i><span class="w-full font-semibold inline-block pl-2 mt-1 md:pb-0 text-md">Events</span>
            </a>
            <a href="{{ route('advance-search') }}" class="{{ request()->is('search') ? 'text-formsypurple' : 'text-formsydark'}} block py-3 pl-3 mb-5 align-middle no-underline hover:text-formsypurple w-32">
                <i class="icon-document-search text-2xl {{ request()->is('search') ? 'active' : ''}}"></i><span class="w-full font-semibold inline-block pl-2 mt-1 md:pb-0 text-md">Search</span>
            </a>
        </li>
        @endif
    </ul>
</div>

<div class="bg-white absolute bottom-0 w-full border-t border-gray-200 flex lg:hidden z-50">
    <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'text-formsypurple' : 'text-formsydark'}} flex flex-grow items-center justify-center p-2 hover:text-indigo-500">
        <div class="text-center">
            <span class="block h-8 text-3xl leading-8">
                <i class="icon-home text-2xl py-3"></i><span class="block text-xs leading-none">Home</span>
            </span>
        </div>
    </a>
    <a href="{{ route('settings') }}" class="{{ request()->is('settings') ? 'text-formsypurple' : 'text-formsydark'}} flex flex-grow items-center justify-center p-2 hover:text-indigo-500">
        <div class="text-center">
            <span class="block h-8 text-3xl leading-8">
                <i class="icon-cog text-2xl py-3"></i><span class="block text-xs leading-none">Settings</span>
            </span>
        </div>
    </a>
    <a href="{{ route('events') }}" class="{{ request()->is('events') ? 'text-formsypurple' : 'text-formsydark'}} flex flex-grow items-center justify-center p-2 hover:text-indigo-500">
        <div class="text-center">
            <span class="block h-8 text-3xl leading-8">
                <i class="icon-terminal text-2xl py-3"></i><span class="block text-xs leading-none">Events</span>
            </span>
        </div>
    </a>
    <a href="{{ route('advance-search') }}" class="{{ request()->is('advance-search') ? 'text-formsypurple' : 'text-formsydark'}} flex flex-grow items-center justify-center p-2 hover:text-indigo-500">
        <div class="text-center">
            <span class="block h-8 text-3xl leading-8">
                <i class="icon-document-search text-2xl py-3"></i><span class="block text-xs leading-none">Advance search</span>
            </span>
        </div>
    </a>
</div>
