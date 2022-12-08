<template v-show="show">
    <li>
        <a :href="url" class="p-4 border-t border-gray-200 block text-formsydark cursor-pointer hover:bg-formsypurple hover:text-white rounded flex">
            <i class="icon-document-text inline-block mr-2 text-xl"></i>
            {{ submissionData.first_data }}
            <span class="text-xs ml-auto">
                <span class="mr-5 flex">
                    <i class="icon-collection mt-0 mr-2 ml-auto text-lg"></i> {{ submissionData.form.name }}
                </span>
                <span class="flex mt-2">
                    <i class="icon-clock mr-2 ml-auto text-lg"></i> {{ update_date }}
                </span>
            </span>
        </a>
    </li>
</template>

<script>
    export default {
        props: {
            url: {
                type: String
            },
            submission: {
                type: String
            },
            update_date: {
                type: String,
            }
        },
        data() {
            let submissionData = JSON.parse(this.submission);

            return {
                show: true,
                submissionData: submissionData,
            }
        },
        methods: {
            close (e) {
                this.show = false
            }
        },
        mounted () {
            document.addEventListener('click', this.close)
        },
        beforeDestroy () {
            document.removeEventListener('click',this.close)
        }
    }
</script>
