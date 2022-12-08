@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'Welcome to {{ env('APP_NAME') }} !',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')
        <br>
        <h2> How it works </h2>
        <br>
        <p>{{ env('APP_NAME') }} is the easiest way to handle form processing without any back-end services.</p>
        <p>Working with {{ env('APP_NAME') }} is super easy, see below steps on how to get started:</p>
        <ul>
            <li>Create a form you want to connect to {{ env('APP_NAME') }}</li>
            <li>Set the form action attribute to {{ env('APP_NAME') }} endpoint, which is defined in your dashboard.</li>
            <li>Start receiving submissions</li>
        </ul>

    @include('beautymail::templates.sunny.contentEnd')
@stop
