@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="container mx-auto px-4 sm:px-8">
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 lg:py-4 overflow-x-auto pb-64">
                    <div class="mb-4 flex">
                        <div class="flex-1 pr-4">
                            <div class="flex mb-5">
                                <h2 class="text-2xl font-semibold leading-tight">Events</h2>
                                <div class="ml-auto">
                                    <button type="submit" class="uppercase font-railway font-semibold text-xs bg-red-500 hover:bg-red-600 text-white py-3 px-3 rounded" v-on:click.prevent="modalShowing = true"><i class="icon-minus-circle text-white"></i> Clear all</button>
                                    <modal title="" :showing="modalShowing" @close="modalShowing = false">
                                        <p class="font-sans font-normal px-5">Are you sure you want to permanently delete all events entries ? This will also delete form events.</p>
                                        <div class="flex justify-between border-t mt-8">
                                            <a href="{{ route('events-delete') }}" class="text-center cursor-pointer border-r w-full block uppercase mx-auto shadow hover:bg-indigo-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                                                <i class="icon-minus-circle text-xl mr-1"></i>
                                                <span class="text-xs font-semibold">Delete</span>
                                            </a>
                                            <button v-on:click.prevent="modalShowing = false" class="cursor-pointer w-full block uppercase mx-auto shadow hover:bg-red-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                                                <i class="icon-x-circle text-xl mr-1"></i>
                                                <span class="text-xs font-semibold">Cancel</span>
                                            </button>
                                        </div>
                                    </modal>
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <div class="bg-white mx-auto rounded-lg shadow font-sans">
                                        <table class="w-full bg-white shadow rounded-lg mb-4">
                                            <tbody>
                                                <tr class="border-b text-sm">
                                                    <th class="text-left p-3 px-5 border-r">Type</th>
                                                    <th class="text-left p-3 px-5 border-r">Message</th>
                                                    <th class="text-left p-3 px-5">Time</th>
                                                </tr>
                                                @forelse ($events as $event)
                                                <tr class="border-b hover:bg-gray-100">
                                                    <td class="p-3 px-5 border-r">
                                                        @if ($event->type == "error")
                                                        <span class="relative inline-block px-3 py-1 text-red-900 leading-tight">
                                                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                                            <span class="relative uppercase text-sm">{{ucfirst($event->type)}}</span>
                                                        </span>
                                                        @elseif ($event->type == "info")
                                                        <span class="relative inline-block px-3 py-1 text-blue-900 leading-tight">
                                                            <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                                                            <span class="relative uppercase text-sm">{{ucfirst($event->type)}}</span>
                                                        </span>
                                                        @else
                                                        <span class="relative inline-block px-3 py-1 text-orange-900 leading-tight">
                                                            <span aria-hidden class="absolute inset-0 bg-orange-200 opacity-50 rounded-full"></span>
                                                            <span class="relative uppercase text-sm">{{ucfirst($event->type)}}</span>
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td class="p-3 px-5 border-r">{!! $event->message !!}</td>
                                                    <td class="p-3 px-5 w-2/12">{{$event->updated_at}}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="p-3 px-5"></td>
                                                    <td class="p-3 px-5 text-center">No event logs found.</td>
                                                    <td class="p-3 px-5"></td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center text-sm text-gray-600">
                                        {{$events->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
    </div>
</div>
@endsection
