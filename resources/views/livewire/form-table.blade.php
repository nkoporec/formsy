<div>
    <div class="mb-4 lg:flex justify-between items-center">
        <div>
            <div class="flex">
                <div class="w-full inline-flex items-center p-2">
                    <span class="mr-2">Show:</span>
                    <select wire:model="perPage" class="pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none focus:shadow-outline bg-white">
                        <option>10</option>
                        <option>15</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                        <option>All</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="inline-block min-w-full bg-white rounded shadow overflow-hidden mt-10 lg:mt-0">
            @foreach ($forms as $form)
            <a href="view/form/{{$form->id}}" class="font-sans w-full bg-white flex justify-between hover:bg-gray-100">
                <div class="rounded block w-full lg:flex rounded block w-full text-center lg:text-left">
                    <div class="bg-formsypurple py-5 text-white px-2">
                        <i class="icon-collection mt-0 mr-auto ml-auto text-3xl"></i>
                    </div>
                    <div class="lg:ml-10 mt-2">
                        <span class="text-xl font-semibold">{{$form->name}}</span>
                        <span class="text-sm block text-gray-500 font-sans">{{ $form->updated_at->format('j F, Y') }}</span>
                    </div>
                    <div class="lg:ml-auto mt-4 mr-5 ml-5 text-formsydark lg:w-1/12 font-normal font-sans lg:flex lg:justify-between">
                        <span class="inline-block mt-2 mr-8 lg:mr-0"><i class="icon-document-text text-2xl"></i> {{$form->submissions}}</span>
                        <span class="inline-block mt-2">
                            @if($form->status == 1)
                            <i class="icon-lock-open text-2xl"></i>
                            @else
                            <i class="icon-lock-closed text-red-500 text-2xl"></i>
                            @endif
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
    </div>
</div>
