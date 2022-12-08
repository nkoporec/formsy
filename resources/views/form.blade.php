@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="container mx-auto px-4 sm:px-8">
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 lg:py-4 overflow-x-auto">
                    <div class="mb-4 lg:flex">
                        <div class="flex-1 pr-4 pb-64">
                            <div class="w-full">
                                @include('elements.form.header')
                                <tabs>
                                    <tab title="Submissions" icon="icon-document-text">
                                        @include('elements.form.submissions')
                                    </tab>
                                    <tab title="Options" icon="icon-adjustments">
                                        @include('elements.form.options')
                                    </tab>
                                    <tab title="Handlers" icon="icon-switch-horizontal">
                                        @include('elements.form.handlers')
                                    </tab>
                                </tabs>
                            </div>
                        </div>
                    </div>
            </div>
    </div>
</div>
@endsection
