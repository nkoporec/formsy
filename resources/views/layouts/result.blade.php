<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form processing made easy - {{ env('APP_NAME') }}</title>
    <link rel="icon" href="{{ URL::asset('/images/favicon.ico') }}" type="image/x-icon"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
      .gradient {
        background-image: linear-gradient(135deg, #684ca0 35%, #1c4ca0 100%);
      }
    </style>
</head>
<body class="text-white gradient min-h-screen text-raleway">
    <div class="text-white flex items-center py-64">
        <div class="container mx-auto p-4 flex flex-wrap items-center">
            @yield('content')
        </div>
    </div>
    <div class="bottom-0 flex text-gray-500">
        <p class="ml-auto mr-auto">This form submission was powered by <a class="text-indigo-400" href="https://github.com/nkoporec/formsy">Formsy</a>.</p>
    </div>
</body>
</html>
