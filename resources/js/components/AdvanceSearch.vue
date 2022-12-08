<template>
    <div class="">
        <div class="py-4 px-4">
            <div class="mb-8">
                <label for="submission_id" class="block text-sm mb-2">Submission ID</label>
                <input
                    id="submission_id"
                    type="number"
                    name="submission_id"
                    v-model="submission_id"
                    class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                />
            </div>
            <div class="my-8">
                <label for="form" class="block text-sm mb-2">Form</label>
                <select name="form" id="form" v-model="form" class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none">
                    <option value="" selected> - Select a form -</option>
                    <option v-for="form in forms" :value="form.id">{{ form.name }}</option>
                </select>
            </div>
            <div class="lg:flex w-full my-8">
                <div class="block lg:w-3/12 pr-10">
                    <label for="date_start" class="block text-sm mb-2">Start Date</label>
                    <vuejs-datepicker v-model="date_start" :clear-button="true" name="date_start" id="date_start" input-class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"></vuejs-datepicker>
                </div>
                <div class="block lg:w-3/12 mt-5 lg:mt-0">
                    <label for="date_end" class="block text-sm mb-2">End Date</label>
                    <vuejs-datepicker v-model="date_end" :clear-button="true" name="date_end" id="date_end" input-class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"></vuejs-datepicker>
                </div>
            </div>
            <div class="my-10">
                <details>
                  <summary class="h-10 uppercase text-xs font-bold cursor-pointer bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none">Custom fields</summary>
                  <div class="border py-2 px-5 bg-gray-100">
                    <div class="lg:flex w-full mt-5" v-for="id in fields_number">
                        <div class="block lg:w-6/12 pr-5">
                            <label for="custom_field_name" class="block text-sm mb-2">Field name</label>
                            <select :id="'custom_field_name_' + id" :name="'custom_field_name_' + id" class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none">
                                <option value="" selected> - Select field -</option>
                                <option v-for="field in fields" :value="field">{{ field }}</option>
                            </select>
                        </div>
                        <div class="block lg:w-6/12 pr-5 mt-10 lg:mt-0">
                            <label for="submission_id" class="block text-sm mb-2">Field Value</label>
                            <input
                                :id="'custom_field_value_' + id"
                                :name="'custom_field_value_' + id"
                                type="text"
                                :v-model="'custom_field_value_' + id"
                                class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                            />
                        </div>
                    </div>
                    <div class="lg:w-3/12 mt-10 pb-5">
                        <button v-on:click="addCustomField" class="cursor-pointer w-full block uppercase mx-auto shadow bg-formsydark focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
                            <i class="icon-plus-circle text-xl mr-1"></i>
                            <span class="text-xs font-semibold">Add additional field</span>
                        </button>
                    </div>
                  </div>
                </details>
            </div>
            <div class="w-full mt-10 flex">
                <div class="lg:w-2/12 w-4/12">
                <button v-on:click="search" class="cursor-pointer w-full block uppercase mx-auto shadow bg-indigo-500 hover:bg-indigo-600 focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
                    <i class="icon-search-circle text-xl mr-1 mt-1"></i>
                    <span class="text-xs font-semibold">Search</span>
                </button>
                </div>

                <div v-if="hasResults == true" class="ml-auto">
                    <button type="submit" class="ml-auto uppercase font-railway font-semibold text-xs bg-red-500 hover:bg-red-600 text-white py-3 px-3 rounded" v-on:click.prevent="modalShowing = true"><i class="icon-minus-circle text-sm"></i> Delete</button>
                    <modal v-if="loading == false" title="" :showing="modalShowing" @close="modalShowing = false">
                        <p class="font-sans font-normal text-center">
                            <span v-if="loading == false" >
                                Are you sure you want to delete selected submissions?
                            </span>
                        </p>
                        <div class="flex justify-between border-t mt-8" v-if="loading == false">
                            <button class="cursor-pointer border-r w-full block uppercase mx-auto shadow hover:bg-indigo-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold" v-on:click.prevent="handleDelete">
                                <i class="icon-minus-circle text-xl mr-1"></i>
                                <span class="text-xs font-semibold">Delete</span>
                            </button>
                            <button v-on:click.prevent="modalShowing = false" class="cursor-pointer w-full block uppercase mx-auto shadow hover:bg-red-600 focus:shadow-outline focus:outline-none hover:text-white text-xs py-2 font-railway font-semibold">
                                <i class="icon-x-circle text-xl mr-1"></i>
                                <span class="text-xs font-semibold">Cancel</span>
                            </button>
                        </div>
                    </modal>
                </div>
            </div>
        </div>
        <div v-if="hasResults == true" class="border-t pt-10 pb-5">
            <submission-tab v-for="submission in submissionsArray" :key="submission.id"
                :id="submission.id"
                :title="submission.first_data"
                :data="submission.data"
                :form="submission.form_name"
                :date="submission.updated_at"
            >
            </submission-tab>
        </div>
    <spinner v-if="loading == true" />
    </div>
</template>

<script>
    export default {
        props: {
            forms: {
              type: Array,
            },
            fields: {
              type: Object,
            },
        },
        data() {
            return {
                submission_id: '',
                form: '',
                date_start: '',
                date_end: '',
                fields_number: 1,
                hasResults: false,
                submissionsArray: [],
                modalShowing: false,
                loading: false,
            }
        },
        methods: {
            addCustomField() {
                this.fields_number++;
            },
            search() {
                this.loading = true;

                // Get all custom fields values.
                let customFieldsData = [];
                let customFields = document.querySelectorAll('*[id^="custom_field_name_"]');

                customFields.forEach(function(field) {
                    let fieldId = field.id.replace('custom_field_name_', '');
                    let fieldValue = document.getElementById(`custom_field_value_${fieldId}`).value;
                    let fieldName = field.value;

                    var item = {name: fieldName, value:fieldValue};
                    customFieldsData.push(item);
                })

                axios.post('/api/advance-search', {
                    params: {
                        submission_id: this.submission_id,
                        form: this.form,
                        date_start: this.date_start,
                        date_end: this.date_end,
                        custom_fields: customFieldsData,
                    }
                }).then(response => {
                        this.submissionsArray = response.data;
                        this.loading = false;

                        if (this.submissionsArray.length <= 0) {
                            this.hasResults = false;
                        }
                        else {
                            this.hasResults = true;
                        }
                    })
                .catch(error => {});
                },
                handleDelete(event) {
                    this.loading = true;
                    const items = document.querySelectorAll('input[name=name]:checked');

                    const ids = [];
                    for (let i = 0; i < items.length; i++) {
                        ids[i] = items[i].value;
                    }

                    // Execute.
                    axios.post('/api/submission/delete', {
                        params: { submissions: ids }
                    }).then(response => {
                        window.location.reload(true);
                        })
                    .catch(error => {});
                },
        },
    }
</script>
