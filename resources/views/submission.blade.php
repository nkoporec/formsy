@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="container mx-auto px-4 sm:px-8 pb-64">
        <div class="">
            <div class="flex bg-white shadow-lg rounded-lg my-10 max-w-md md:max-w-2xl">
                <div class="flex px-4 py-6">
                    <div class="">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-semibold text-gray-900 -mt-1">Submission #{{ $submission->id }}</h2>
                        </div>
                        <p class="text-gray-700 mt-1 mb-1">Created {{ $submission->updated_at->format('j F, Y') }}</p>
                        <div class="flex text-gray-700">
                            <div class="w-full inline-flex items-center p-2">
                                <a href="{{ url('/') }}/view/form/{{ $submission->form_id }}"><h2 class="text-s font-semibold leading-tight flex"><i class="gg-format-justify mt-1 mr-2"></i> {{ $submission->form_name }}</h2></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 lg:py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Field
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Value
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submission->data as $key => $value)
                                <tr class="lg:hover:bg-gray-100">
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm bg-gray-100">
                                        <div class="flex items-center">
                                            <div class="ml-3">
                                                <p class="text-left text-xs font-semibold text-gray-600 uppercase whitespace-no-wrap ">
                                                    {{ $key }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    @if (!is_array($value))
                                    <td class="px-5 py-5 border-b border-l border-gray-200 text-sm bg-white">
                                        <p class="text-gray-900 break-all">
                                            {{ $value }}
                                        </p>
                                    </td>
                                    @else
                                    <td class="px-5 py-5 border-b border-l border-gray-200 text-sm bg-white">
                                        <p class="text-gray-900 break-all">
                                            {{ $value['name'] }}
                                            <a href="/download/file/{{$value['id']}}" class="ml-5 uppercase font-railway font-semibold text-xs bg-formsydark text-white py-1 px-3 rounded">Download</a>
                                        </p>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-4 flex justify-between items-center">
                <div class="flex-1 pr-4">
                        <div class="relative md:w-1/3">
                            @if ($previous_submission)
                            <a href="{{ $previous_submission->id }}" class="flex uppercase text-gray-600 hover:text-grey-800 text-xs mt-10">
                                <i class="gg-arrow-left"></i>
                                <span class="pt-1 pl-1">Previous ({{ $previous_submission->id }})</span>
                            </a>
                            @endif
                        </div>
                 </div>
                 <div>
                     @if ($next_submission)
                         <a href="{{ $next_submission->id }}" class="flex uppercase text-gray-600 hover:text-grey-800 text-xs mt-10">
                             <span class="pt-1 pr-1">Next ({{ $next_submission->id }})</span> <i class="gg-arrow-right"></i>
                         </a>
                     @endif
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
