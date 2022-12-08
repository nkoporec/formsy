@extends('layouts.auth')

@section('auth-content')
<a href="#" class="flex items-center justify-center mt-4 text-white rounded-lg shadow-md hover:bg-gray-100">
</a>
<div class="mt-4 flex items-center justify-between">
    <span class="border-b w-1/5 lg:w-1/4"></span>
    <span class="text-xs text-center uppercase">Login with email</span>
    <span class="border-b w-1/5 lg:w-1/4"></span>
</div>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mt-4">
        <x-forms.email
            name="email"
            id="email"
            label="E-Mail Address"
            value="{{ old('email') }}"
            required="true"
        />
    </div>
    <div class="mt-4">
        @if (Route::has('password.request'))
            <div class="flex justify-between">
                <label for="password" class="block text-sm mb-2">{{ __('Password') }}</label>
                <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-formsypurple">{{ __('Forgot Your Password?') }}</a>
            </div>
        @endif
        <x-forms.password
            name="password"
            id="password"
            label=""
            value=""
            required="true"
        />
        @error('password')
            <span class="hidden mt-1 text-sm text-red" role="relative px-3 py-3 mb-4 border rounded">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="mt-8">
        <button class="cursor-pointer w-full block mx-auto shadow bg-indigo-500 hover:bg-indigo-600 focus:shadow-outline focus:outline-none text-white py-2 rounded font-railway font-semibold">
            <i class="icon-login text-md mr-1"></i>
            <span class="font-bold font-railway text-md">Log in</span>
        </button>
    </div>
</form>
<div class="mt-4 flex items-center justify-between">
    <span class="border-b w-1/5 md:w-1/4"></span>
    <a href="{{ route('register') }}" class="text-xs uppercase hover:text-formsypurple">or sign up</a>
    <span class="border-b w-1/5 md:w-1/4"></span>
</div>
@endsection
