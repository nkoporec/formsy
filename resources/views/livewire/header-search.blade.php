<div class="relative ml-auto mr-auto lg:w-2/5 w-3/5">
    <div>
        <div class="my-2 relative rounded">
            <div class="absolute h-10 left-0 top-0 flex items-center shadow-xs pl-3 text-lg bg-formsypurple rounded-l">
                <i class="icon-search-circle rounded-lg pr-3"></i>
            </div>
            <div class="ml-10">
                <input
                    id="search-toggle"
                    wire:model="searchKeyword"
                    type="search"
                    placeholder="Search for submission"
                    class="bg-gray-100 rounded font-sans focus:outline-none text-formsydark block lg:w-full w-11/12 focus:outline-none py-2 px-4 block appearance-none"
                />
            </div>
            @if ($submissions)
                <ul class="absolute w-full list-reset bg-white shadow-lg z-50">
                @foreach($submissions as $submission)
                    <header-search
                        url="{{ url('/') }}/view/form/{{ $submission->form_id}}/submission/{{ $submission->id }}"
                        submission="{{json_encode($submission)}}"
                        update_date="{{ $submission->updated_at->format('j F, Y') }}"
                    >
                    </header-search>
                @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>



