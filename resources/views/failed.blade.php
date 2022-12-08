@extends('layouts.result')

@section('content')
<div class="w-full md:w-5/12 text-center p-4">
  <img src="{{URL::asset('/images/submission-failed.svg')}}" alt="Error"/>
</div>
<div class="w-full md:w-7/12 text-center md:text-left p-4">
  <div class="text-6xl font-medium">Ooops!</div>
  <div class="text-xl md:text-3xl font-medium mb-4">
    Something went wrong while trying to process the submission.
  </div>
  <div class="text-lg mb-8">
    Please try again later. If the problem persists please contact the site administrator.
  </div>
    @if ($referer)
        <a href="{{$referer}}" class="border border-white rounded p-4">Go Back</a>
    @endif
</div>
@endsection
