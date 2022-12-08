<template>
    <div>
        <div class="lg:flex justify-between items-center">
            <div class="flex-1 pr-4 pl-4">
                <div class="relative">
                    <input type="search" v-model="search" class="w-full bg-gray-100 pl-10 pr-4 py-2 font-sans rounded shadow-xs focus:outline-none focus:shadow-outline" placeholder="Search...">
                    <div class="absolute top-0 left-0 inline-flex items-center p-2">
                       <span class="text-formsypurple"><i class="icon-search-circle text-xl"></i></span>
                    </div>
                </div>
            </div>
            <div>
                <div class="lg:flex">
                    <div class="w-full md:inline-flex items-center lg:p-2 p-5">
                        <div class="md:px-4 w-full pt-10 md:pt-0 lg:pt-0">
                            <button type="submit" class="uppercase font-railway font-semibold text-xs bg-red-500 hover:bg-red-600 text-white py-3 px-3 rounded" v-on:click.prevent="modalShowing = true"><i class="icon-minus-circle text-sm"></i> Delete</button>
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
            </div>
        </div>
        <div class="w-full border-b pt-4 flex">
            <p class="text-xs uppercase text-gray-300 ml-auto hover:text-formsypurple pr-2">
                <a href="#" v-on:click.prevent="selectAll"><i class="icon-collection"></i>
                    <span v-if="allChecked == false">Select all</span>
                    <span v-if="allChecked == true">Deselect all</span>
                </a>
            </p>
        </div>
        <div class="bg-white mx-auto font-sans">
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
        submissions: {
          type: Array,
        },
        form: {
          type: String,
        },
    },
    data() {
        return {
            submissionsArray: this.submissions,
            modalShowing: false,
            search: null,
            loading: false,
            allChecked: false,
        };
    },
    watch: {
        search(after, before) {
            if (this.search.length >= 1) {
                document.getElementById('paginate-submissions').style.display = "none";
                this.fetch();
                return;
            }

            this.submissionsArray = this.submissions;
            document.getElementById('paginate-submissions').style.display = "block";
        },
    },
    methods: {
        handleDelete(event) {
            this.loading = true;
            const items = document.querySelectorAll('input[name=name]:checked');

            const ids = [];
            for (let i = 0; i < items.length; i++) {
                ids[i] = items[i].value;
            }

            // Execute.
            axios.post('/api/submission/delete', {
                params: { submissions: ids}
            }).then(response => {
                window.location.reload(true);
                })
            .catch(error => {});
        },
        fetch() {
            axios.post('/api/submission/search', {
                params: { search: this.search, form: this.form, count: this.count }
            }).then(response => {
                    this.submissionsArray = response.data;
                })
            .catch(error => {});
        },
        selectAll() {
            const checkboxes = document.getElementsByName('name');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (this.allChecked) {
                    checkboxes[i].checked = false;
                }
                else {
                    checkboxes[i].checked = true;
                }
            }

            this.allChecked = !this.allChecked;
        }
    },
}
</script>
