
@extends('layouts.form')

@section('form-content')
<div class="flex bg-white rounded shadow overflow-hidden mx-auto lg:max-w-6xl flex-col my-2 mt-8">
    <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-formsypurple text-left text-xs font-semibold text-white uppercase tracking-wider">
        <i class="icon-cog text-white mr-2 text-lg"></i>
        <p>Delete form</p>
    </div>
    <div class="w-full p-8 font-sans">
        <h2 class="font-bold text-xl">Are you sure you want to delete form <span class="text-formsypurple">{{$form->name}}</span> and all it's submissions?</h2>
        <form method="POST" action="{{ route('delete-form', ['id' => formsy_get_form()]) }}" class="mt-10">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Please type <span class="text-formsypurple">{{$form->name}}</span> to confirm</label>
                <input id="form-name" type="text" name="form-name" value="" required class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none  @error('name') bg-red-dark @enderror">
            </div>
            <div class="mb-6 text-center mt-10 w-32">
                <x-button text="Delete" type="danger" icon="icon-minus-circle"></x-button>
            </div>
        </form>
    </div>
</div>
@endsection
