@extends('layouts.form')

@section('form-content')
<div class="flex bg-white rounded shadow overflow-hidden mx-auto lg:max-w-6xl flex-col my-2 mt-8">
    <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-formsypurple text-left text-xs font-semibold text-white uppercase tracking-wider">
        <i class="icon-search-circle text-white mr-2 text-lg"></i>
        <p>Advance Search</p>
    </div>
    <div class="w-full lg:p-8 p-5 font-sans">
        <advance-search :forms='@json($forms)' :fields='@json($fields)'></advance-search>
    </div>
</div>
@endsection
