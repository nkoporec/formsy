@extends('layouts.auth')

@section('auth-content')
<a href="#" class="flex items-center justify-center mt-4 text-white rounded-lg shadow-md hover:bg-gray-100">
</a>
<div class="mt-4 flex items-center justify-between">
    <span class="border-b w-1/5 lg:w-1/4"></span>
    <span class="text-xs text-center uppercase">Verify Your Email Address</span>
    <span class="border-b w-1/5 lg:w-1/4"></span>
</div>
@if (session('resent'))
    <div class="relative px-3 py-3 mb-4 border rounded text-green-darker border-green-dark bg-green-lighter" role="alert">
        {{ __('A fresh verification link has been sent to your email address.') }}
    </div>
@endif
{{ __('Before proceeding, please check your email for a verification link.') }}
{{ __('If you did not receive the email') }},
<form method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <div class="mt-8">
        <button class="cursor-pointer w-full block mx-auto shadow bg-indigo-500 hover:bg-indigo-600 focus:shadow-outline focus:outline-none text-white py-2 rounded font-railway font-semibold">
            <span class="font-bold font-railway text-md">click here to request another</span>
        </button>
    </div>
</form>
<div class="mt-4 flex items-center justify-between">
    <span class="border-b w-1/5 md:w-1/4"></span>
    <a href="{{ route('login') }}" class="text-xs uppercase hover:text-formsypurple">or log in</a>
    <span class="border-b w-1/5 md:w-1/4"></span>
</div>
@endsection
