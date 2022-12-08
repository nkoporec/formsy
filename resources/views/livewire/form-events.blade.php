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
    <div class="text-left text-sm text-gray-600">
        <a href='{{url("/")}}/view/form/{{ $form->id }}' class="flex uppercase text-gray-600 hover:text-grey-800 text-xs mt-10 hover:text-formsypurple">
            <i class="text-lg icon-arrow-circle-left mr-2"></i>
            <span>Back to form overview</span>
        </a>
    </div>
</div>
