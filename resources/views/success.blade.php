@extends('layouts.result')

@section('content')
<div class="w-full md:w-5/12 text-center p-4">
    <img src="{{URL::asset('/images/submission-success.svg')}}" alt="Success" />
</div>
<div class="w-full md:w-7/12 text-center md:text-left p-4">
    <div class="text-6xl font-medium">Hooray!</div>
    <div class="text-xl md:text-3xl font-medium mb-4">
    Submission successfully submitted.
    </div>
    <div class="text-lg mb-8">
    You may now return to the previous page.
    </div>
    @if ($referer)
        <a href="{{$referer}}" class="border border-white rounded p-4">Go Back</a>
    @endif
</div>
@endsection
