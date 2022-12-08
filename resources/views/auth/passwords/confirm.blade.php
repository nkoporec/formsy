@extends('layouts.auth')

@section('auth-content')
<a href="#" class="flex items-center justify-center mt-4 text-white rounded-lg shadow-md hover:bg-gray-100">
</a>
<div class="mt-4 flex items-center justify-between">
    <span class="border-b w-1/5 lg:w-1/4"></span>
    <span class="text-xs text-center uppercase">Confirm Password</span>
    <span class="border-b w-1/5 lg:w-1/4"></span>
</div>
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <div class="mt-4">
        <x-forms.password
            name="password"
            id="password"
            label="Password"
            value=""
            required="true"
        />
        @error('password')
            <span class="hidden mt-1 text-sm text-red" role="relative px- py-3 mb-4 border rounded">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="mt-8">
        <button class="cursor-pointer w-full block mx-auto shadow bg-indigo-500 hover:bg-indigo-600 focus:shadow-outline focus:outline-none text-white py-2 rounded font-railway font-semibold">
            <span class="font-bold font-railway text-md">Confirm Password</span>
        </button>
        @if (Route::has('password.request'))
            <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal no-underline font-normal blue bg-transparent" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </div>
</form>
<div class="mt-4 flex items-center justify-between">
    <span class="border-b w-1/5 md:w-1/4"></span>
    <a href="{{ route('login') }}" class="text-xs uppercase hover:text-formsypurple">or log in</a>
    <span class="border-b w-1/5 md:w-1/4"></span>
</div>
@endsection
