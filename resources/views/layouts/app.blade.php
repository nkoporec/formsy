<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form processing made easy | {{ env('APP_NAME') }}</title>
    <link rel="icon" href="{{ URL::asset('/images/favicon.ico') }}" type="image/x-icon"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles
    @notifyCss
</head>
<body class="bg-gray-100">
    <div id="app" class="flex h-screen bg-gray-100 font-raleway text-formsydark">
        <!-- Side bar-->
        @if (Auth::check())
            @include('elements.sidebar')
        @endif
        <!-- Content -->
        <div class="flex flex-row flex-wrap flex-1 flex-grow content-start w-full overflow-y-auto">
            @if (Auth::check())
                <div class="h-40 lg:h-20 w-full flex flex-wrap">
                    @include('elements.header')
                </div>
            @endif
            <div id="main-content" class="w-full">
                <div class="flex flex-wrap">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @livewireScripts
    <x:notify-messages />
    @notifyJs
    <script src="https://cdn.jsdelivr.net/gh/livewire/vue@v0.3.x/dist/livewire-vue.js"></script>
</body>
</html>
