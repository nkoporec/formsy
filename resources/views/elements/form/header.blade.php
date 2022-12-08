<div class="lg:flex bg-white shadow-lg rounded-lg my-10 lg:max-w-2xl">
    <div class="lg:flex px-4 py-6 w-full">
        <div class="w-full">
            <div class="lg:flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-900 -mt-1">Submissions for {{ $form->name }}</h2>
            </div>
            <p class="text-gray-700 font-sans">Created {{ $form->updated_at->format('j F, Y') }}</p>
            <p class="mt-3 text-gray-700 font-sans">
                <ul>
                    <li class='text-gray-700 mt-1 font-sans'>{{ $number_of_submissions }} Submissions</li>
                    @if ($last_submission)
                        <li class='text-gray-700 mt-1 font-sans'>Last submission on {{ $last_submission->updated_at->format('j F, Y') }}</li></li>
                    @endif
                </ul>
            </p>
            <div class='lg:flex mt-10 w-full'>
               @if($form->status == 1)
                 <button type="submit" class="inline-block flot-left button block uppercase shadow bg-orange-500 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer" v-on:click.prevent="modalShowing = true"> <i class="icon-minus-circle text-xs mr-1 text-white"></i> Close form</button>
                 <modal title="" :showing="modalShowing" @close="modalShowing = false">
                    <p class="font-sans font-normal pl-2 text-center">Are you sure you want to close the form? This will cause the form to stop receiving submissions.</p>
                    <div class="flex justify-between border-t mt-8">
                        <a href="{{ url('/') }}/view/form/{{ $form->id }}/status/change" class="text-center cursor-pointer border-r w-full block uppercase mx-auto shadow hover:bg-indigo-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                            <i class="icon-minus-circle text-xl mr-1"></i>
                            <span class="text-xs font-semibold">Close form</span>
                        </a>
                        <button v-on:click.prevent="modalShowing = false" class="cursor-pointer w-full block uppercase mx-auto shadow hover:bg-red-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                            <i class="icon-x-circle text-xl mr-1"></i>
                            <span class="text-xs font-semibold">Cancel</span>
                        </button>
                    </div>
                </modal>
               @else
                <button type="submit" class="inline-block flot-left button block uppercase shadow bg-green-500 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer" v-on:click.prevent="modalShowing = true"> <i class="icon-check-circle text-xs mr-1 text-white"></i> Open form</button>
                <modal title="" :showing="modalShowing" @close="modalShowing = false">
                    <p class="font-sans font-normal pl-2 text-center">Are you sure you want to open the form? This will cause the form to start receiving submissions.</p>
                    <div class="flex justify-between border-t mt-8">
                        <a href="{{ url('/') }}/view/form/{{ $form->id }}/status/change" class="text-center cursor-pointer border-r w-full block uppercase mx-auto shadow hover:bg-indigo-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                            <i class="icon-check-circle text-xl mr-1"></i>
                            <span class="text-xs font-semibold">Open form</span>
                        </a>
                        <button v-on:click.prevent="modalShowing = false" class="cursor-pointer w-full block uppercase mx-auto shadow hover:bg-red-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                            <i class="icon-x-circle text-xl mr-1"></i>
                            <span class="text-xs font-semibold">Cancel</span>
                        </button>
                    </div>
                </modal>
               @endif
               <div class='lg:ml-4 lg:mt-0 mt-5'>
                    <a
                        href="{{ url('/') }}/view/form/{{ $form->id }}/export"
                        class="inline-block flot-left button block uppercase shadow bg-formsydark focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer">
                        <span class='flex'>
                            <i class="text-white text-lg icon-download mr-2"></i> Export All
                        </span>
                    </a>
               </div>
               <div class='lg:ml-auto lg:mt-0 mt-5'>
                    <a
                        href="{{ url('/') }}/view/form/{{ $form->id }}/events"
                        class="inline-block flot-left button block uppercase shadow bg-formsydark focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer">
                        <span class='flex'>
                            <i class="text-white text-lg icon-terminal mr-2"></i> Events
                        </span>
                    </a>
               </div>
            </div>
            </div>
         </div>
    </div>
</div>
