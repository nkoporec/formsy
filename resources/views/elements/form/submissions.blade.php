<div>
    <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 pt-5 overflow-x-auto">

        @if (count($submissions) <= 0)
        <div class='rounded py-4 px-4 text-center text-gray-600 hover:text-grey-800 text-2xl mt-10 flex'><p class='mx-auto'>There are no submissions to show.</p></div>
        @else
            <submissions-table :form='@json($form->name)' :submissions='@json($submissions->getCollection())' :paginate='@json($submissions->links())'>
            </submissions-table>
            <div id="paginate-submissions" class="text-center text-sm text-gray-600 bg-white px-8 py-5 border-t">
                {{$submissions->links()}}
            </div>
        @endif
    </div>
</div>
