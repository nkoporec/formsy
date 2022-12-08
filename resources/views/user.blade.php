@extends('layouts.form')

@section('form-content')
<div class="flex bg-white rounded shadow overflow-hidden mx-auto lg:max-w-6xl flex-col my-2">
    <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-formsypurple text-left text-xs font-semibold text-white uppercase tracking-wider">
        <i class="icon-user-circle text-white mr-2 text-lg"></i>
        <p>Account Settings</p>
    </div>
    <div class="w-full p-8 font-sans">
        <form method="POST" action="{{ route('user-update') }}" class="">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }}</label>
                <input id="name" type="text" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none  @error('name') bg-red-dark @enderror">
                @error('name')
                  <span class="hidden mt-1 text-sm text-red" role="relative px-3 py-3 mb-4 border rounded">
                     <strong>{{ $message }}</strong>
                  </span>
               @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" name="email" value="{{ $user->email }}" required autocomplete="email" class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none  @error('email') bg-red-dark @enderror">
                @error('email')
                    <span class="hidden mt-1 text-sm text-red" role="relative px-3 py-3 mb-4 border rounded">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="current-password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Current password') }}</label>
                <input id="current-password" type="password" name="current-password" autocomplete="current-password" class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none  @error('current-password') bg-red-dark @enderror">
                @error('current-password')
                    <span class="hidden mt-1 text-sm text-red" role="relative px-3 py-3 mb-4 border rounded">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-4 md:flex">
                <div class="mb-4 md:mr-2 md:mb-0 w-6/12">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('New Password') }}</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none  @error('password') bg-red-dark @enderror">
                    @error('password')
                        <span class="hidden mt-1 text-sm text-red" role="relative px-3 py-3 mb-4 border rounded">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="md:ml-2 w-6/12">
                    <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" name="password_confirmation" autocomplete="new-password" class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none">
                </div>
            </div>
            <div class="mb-6 text-center mt-20 w-32">
                <x-button text="Save" icon="icon-save"></x-button>
            </div>
        </form>
    </div>
</div>
@endsection
