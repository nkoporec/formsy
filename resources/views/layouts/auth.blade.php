@extends('layouts.app')

@section('content')
<div class="w-full flex text-center justify-center mt-5 absolute">
    <div class="p-2">
        @foreach ($errors->all() as $error)
            <div class="mb-5 items-center bg-white leading-none rounded-md p-2 shadow text-sm text-red-600 font-sans shadow-lg">
                <span class="inline-flex bg-red-600 text-white rounded-md h-6 px-3 justify-center items-center">Error</span>
                <span class="inline-flex px-2">
                    {{ $error }}
                </span>
            </div>
        @endforeach
    </div>
</div>
<div class="container mx-auto lg:py-48 py-24">
    <div class="flex bg-white  shadow-lg overflow-hidden mx-auto lg:w-6/12 w-11/12">
        <div class="w-full p-8 font-sans text-formsydark">
            <div class="mx-auto w-6/12 ">
                <img src="{{URL::asset('/images/logo/logo.svg')}}" alt="Logo"/>
            </div>
            @yield('auth-content')
        </div>
    </div>
</div>
@endsection
