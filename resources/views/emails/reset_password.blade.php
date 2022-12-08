@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'Change your password',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')
        <br>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <br>
        <p>This password reset link will expire in 60 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        <br>
        <p>Regards, <br> {{ env('APP_NAME') }}</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
            'title' => 'Reset Password',
            'link' => $data["url"],
    ])

@stop
