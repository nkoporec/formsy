@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="container mx-auto px-4 sm:px-8">
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 lg:py-4 overflow-x-auto">
                    <div class="mb-4 flex justify-between items-center pb-64">
                        <div class="flex-1 pr-4 lg:pl-16">
                            <div class="w-full">
                                @yield('form-content')
                            </div>
                        </div>
                    </div>
            </div>
    </div>
</div>
@endsection
