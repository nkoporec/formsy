<form method="POST" action="{{ route('handler-form', ['form_id' => formsy_get_form(), 'handler' => 'mailchimp']) }}">
    @csrf
    <div class="flex bg-white overflow-hidden font-sans text-formsydark flex-col">
        <div class="w-full px-8 pt-6 pb-8 mb-4 bg-white rounded flex">
            <div class="w-2/12 border-r pl-5 pr-10 hidden lg:block">
                <img src="{{URL::asset('/images/handlers/mailchimp.png')}}" alt="Mailchimp" class="pt-16"/>
            </div>
            <div class="w-10/12 pl-10 pt-5">
                <div class="mb-4 flex">
                    @if (formsy_handler_get_setting('mailchimp', 'enabled'))
                        <x-forms.checkbox
                            name="enabled"
                            id="enabled"
                            label="Enable"
                            checked="true"
                            required="false"
                        />
                    @else
                        <x-forms.checkbox
                            name="enabled"
                            id="enabled"
                            label="Enable"
                            checked="false"
                            required="false"
                        />
                    @endif
                </div>
                <div class="mb-4">
                    <x-forms.password
                        name="api_key"
                        id="api_key"
                        label="API Key"
                        value="{{ formsy_handler_get_setting('mailchimp', 'api_key') }}"
                    />
                </div>
                <div class="mb-4">
                    <x-forms.textfield
                        name="audience_id"
                        id="audience_id"
                        label="Audience ID"
                        value="{{ formsy_handler_get_setting('mailchimp', 'audience_id') }}"
                        required="true"
                    />
                </div>
                <div class="mb-4">
                    <x-forms.checkbox
                        name="double_optin"
                        id="double_optin"
                        label="Send confirmation email"
                        checked="true"
                        required="true"
                    />
                </div>
            </div>
        </div>

        <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold uppercase tracking-wider">
            <p>Conditionals</p>
        </div>
        <div class="w-full pl-4">
            @foreach (formsy_form_get_conditionals('mailchimp') as $key => $condition)
            <div class="mb-4 md:flex mt-5 ml-5 md:w-auto w-11/12">
                <div class="mb-4 md:mr-5">
                    @if ($condition['field_name'])
                    <x-forms.textfield
                        name="{{ 'condition' . $key . '_field_name'  }}"
                        id="{{ 'condition' . $key . '_field_name'  }}"
                        label="Field name"
                        value="{{$condition['field_name']}}"
                        required="false"
                    />
                    @else
                    <x-forms.textfield
                        name="{{ 'condition' . $key . '_field_name'  }}"
                        id="{{ 'condition' . $key . '_field_name'  }}"
                        label="Field name"
                        value=""
                        required="false"
                    />
                    @endif
                </div>
                <div class="md:ml-5">
                    @if ($condition['field_value'])
                    <x-forms.textfield
                        name="{{ 'condition' . $key . '_field_value'  }}"
                        id="{{ 'condition' . $key . '_field_value'  }}"
                        label="Field value"
                        value="{{$condition['field_value']}}"
                        required="false"
                    />
                    @else
                    <x-forms.textfield
                        name="{{ 'condition' . $key . '_field_value'  }}"
                        id="{{ 'condition' . $key . '_field_value'  }}"
                        label="Field value"
                        value=""
                        required="false"
                    />
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="lg:w-2/12 ml-4 mt-10 mb-10 mr-4 lg:mr-0">
            <x-button text="Save" icon="icon-save"></x-button>
        </div>
    </div>
</form>
