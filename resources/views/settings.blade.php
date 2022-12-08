@extends('layouts.form')

@section('form-content')
<div class="flex bg-white rounded shadow overflow-hidden mx-auto lg:max-w-6xl flex-col my-2 mt-8">
    <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-formsypurple text-left text-xs font-semibold text-white uppercase tracking-wider">
        <i class="icon-cog text-white mr-2 text-lg"></i>
        <p>General Settings</p>
    </div>
    <div class="w-full p-8 font-sans">
        <div class="mb-4">
            <label for="form_endpoint" class="block text-formsydark text-sm font-bold mb-2">{{ __('Form Endpoint') }}</label>
            <input id="form_endpoint" type="text" name="form_url" value="{{ $form_endpoint }}" disabled autofocus class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none">
            <p class="text-formsydark text-italic text-center"><small>An URL endpoint to be used within the form action.</small></p>
        </div>
        <div class="mb-4 mt-10">
            <div class="flex mb-2">
                <label for="appid" class="block text-formsydark text-sm font-bold mb-2">{{ __('App ID') }}</label>
                <div class="text-center ml-auto font-bold">
                    <form class="bg-red-500 hover:bg-red-600 text-white py-1 px-4 rounded" method="POST" action="{{ route('re-generate-app-id') }}">
                        @csrf
                        <button type="submit" class="uppercase font-railway" v-on:click.prevent="modalShowing = true"><i class="icon-refresh text-white"></i></button>
                        <modal title="Re-generate app id" :showing="modalShowing" @close="modalShowing = false">
                            <p class="font-sans font-normal">Are you sure you want to re-generate the app token ? This will break any existing form integrations.</p>
                            <div class="flex justify-between border-t mt-8">
                                <button class="cursor-pointer border-r w-full block uppercase mx-auto shadow hover:bg-indigo-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                                    <i class="icon-refresh text-xl mr-1"></i>
                                    <span class="text-xs font-semibold">Re-generate</span>
                                </button>
                                <button v-on:click.prevent="modalShowing = false" class="cursor-pointer w-full block uppercase mx-auto shadow hover:bg-red-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                                    <i class="icon-x-circle text-xl mr-1"></i>
                                    <span class="text-xs font-semibold">Cancel</span>
                                </button>
                            </div>
                        </modal>
                    </form>
                </div>
            </div>
            <input id="app_id" type="text" name="appId" value="{{ $appId }}" disabled autofocus class="bg-gray-100 py-4 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none">
        </div>
    </div>
</div>
@endsection
