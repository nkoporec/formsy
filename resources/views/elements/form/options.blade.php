<form method="POST" action="{{ route('save-form-options', ['id' => formsy_get_form()]) }}">
    @csrf
    <div class="flex bg-white overflow-hidden font-sans text-formsydark flex-col">
        <div class="w-full flex">
            <div class='w-full px-8 pt-6 pb-8 mb-4 bg-white rounded'>
                <div class="mb-4">
                    <x-forms.url
                        name="redirect_url"
                        id="redirect_url"
                        label="Redirect Url"
                        value="{{ formsy_form_get_option('redirect_url') }}"
                        required="false"
                        description="An URL to redirect after submission. Defaults to submission URL."
                    />
                </div>
                <hr class="mt-8 mb-8">
                <div class="mb-4">
                    @if (formsy_form_get_option('stopforumspam'))
                    <x-forms.checkbox
                        name="stopforumspam"
                        id="stopforumspam"
                        label="Enable Stop Forum Spam Protection"
                        checked="true"
                        required="false"
                        description="Stop Forum Spam is a third-party service to block suspected spammers"
                    />
                    @else
                    <x-forms.checkbox
                        name="stopforumspam"
                        id="stopforumspam"
                        label="Enable Stop Forum Spam Protection"
                        checked="false"
                        required="false"
                        description="Stop Forum Spam is a third-party service to block suspected spammers"
                    />
                    @endif
                </div>
            <hr class="mt-8 mb-8">
            <div class="mb-4">
                @if (formsy_form_get_option('honeypot_enabled'))
                <x-forms.checkbox
                    name="honeypot_enabled"
                    id="honeypot_enabled"
                    label="Enable Honeypot field"
                    checked="true"
                    required="false"
                    description="A honeypot is a hidden field that is visible to bots but not humans, it's used to prevent spam submissions."
                />
                @else
                <x-forms.checkbox
                    name="honeypot_enabled"
                    id="honeypot_enabled"
                    label="Enable Honeypot field"
                    checked="false"
                    required="false"
                    description="A honeypot is a hidden field that is visible to bots but not humans, it's used to prevent spam submissions."
                />
                @endif
                <div class="pl-5 pt-5 lg:w-4/12">
                    <x-forms.textfield
                        name="honeypot_field"
                        id="honeypot_field"
                        label="Honeypot field name"
                        value="{{ formsy_form_get_option('honeypot_field') }}"
                        required="false"
                        description="Name attribute of your honeypot field."
                    />
                </div>
            </div>
            </div>
        </div>
        <div class="lg:flex">
            <div class="lg:w-2/12 lg:ml-4 ml-2 mr-2 lg:mr-0 mb-10">
                <x-button text="Save" icon="icon-save"></x-button>
            </div>
            <div class="ml-auto lg:mr-5 pl-2 lg:pl-0">
                <a
                    href="{{ url('/') }}/view/form/{{ $form->id }}/delete"
                    class="inline-block flot-left button block uppercase shadow bg-red-500 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer mb-5">
                    <span class='flex'>
                        <i class="text-white text-lg icon-minus-circle mr-2"></i> <span class="text-xs font-semibold">Delete Form</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</form>
