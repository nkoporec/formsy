<form method="POST" action="{{ route('handler-form', ['form_id' => formsy_get_form(), 'handler' => 'sendgrid']) }}">
    @csrf
    <div class="flex bg-white overflow-hidden font-sans text-formsydark flex-col">
        <div class="w-full px-8 pt-6 pb-8 mb-4 bg-white rounded flex">
            <div class="w-2/12 border-r hidden lg:block">
                <img src="{{URL::asset('/images/handlers/sendgrid-logo.png')}}" alt="Sendgrid" class=""/>
            </div>
            <div class="w-10/12 pl-10 pt-5">
                <div class="mb-4 flex">
                    @if (formsy_handler_get_setting('sendgrid', 'enabled'))
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
                        name="password"
                        id="password"
                        label="API Key"
                        value="{{ formsy_handler_get_setting('sendgrid', 'password') }}"
                    />
                </div>
            </div>
        </div>

        <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold uppercase tracking-wider">
            <p>Email Settings</p>
        </div>
        <div class="w-full flex">
            <div class='w-full px-8 pt-6 pb-8 mb-4 bg-white rounded'>
                <div class="mb-4">
                    <x-forms.textfield
                        name="recipients"
                        id="recipients"
                        label="Recipients"
                        value="{{ formsy_handler_get_setting('sendgrid', 'recipients') }}"
                        required="true"
                    />
                </div>
                <div class="mb-4">
                    <x-forms.textfield
                        name="from_email"
                        id="from_email"
                        label="From email"
                        value="{{ formsy_handler_get_setting('sendgrid', 'from_email') }}"
                        required="true"
                    />
                </div>
                <div class="mb-4">
                    <x-forms.textfield
                        name="from_name"
                        id="from_name"
                        label="From name"
                        value="{{formsy_handler_get_setting('sendgrid', 'from_name')}}"
                        required="true"
                    />
                </div>
                <div class="mb-4">
                    <x-forms.textfield
                        name="subject"
                        id="subject"
                        label="Subject"
                        value="{{ formsy_handler_get_setting('sendgrid', 'subject') }} "
                        required="true"
                    />
                </div>
                <div class="mb-10">
                    <x-forms.textarea
                        name="email_body"
                        id="email_body"
                        label="Email"
                        value="{{ formsy_handler_get_setting('sendgrid', 'email_body') }} "
                        required="true"
                        description="You can use [@field_name] in order to display the submision data. For example [@email] will be converted to the submission value of field with name email."
                    />

                </div>
            </div>
        </div>


        <div class="px-5 flex py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold uppercase tracking-wider">
            <p>Conditionals</p>
        </div>
        <div class="w-full pl-4">
            @foreach (formsy_form_get_conditionals('sendgrid') as $key => $condition)
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
