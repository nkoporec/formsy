<template>
    <div :class="background">
        <div class="lg:flex md:flex items-center justify-between  w-full cursor-pointer hover:bg-gray-100 accordion py-4" v-on:click="isActive = !isActive" :id="name">
            <div class="md:inline-block lg:inline-block pl-5 w-11/12">
                <label class="text-formsydark justify-between items-center px-2 py-2 rounded-lg cursor-pointer">
                    <input type="checkbox" :id="name" :value="id" name="name" class="form-checkbox focus:outline-none focus:shadow-outline">
                </label>
                <span class="md:inline-block w-11/12 py-5">{{ title }}</span>
            </div>
            <div class="md:flex lg:flex pr-5 hidden">
                <span class="pr-3">{{ date | formatDate }}</span>
                <span class="h-6 w-6 flex items-center justify-center">
                    <i v-show="isActive == false" class="icon-plus-circle text-xl text-formsypurple mt-5"></i>
                    <i v-show="isActive == true" class="icon-minus-circle text-xl text-formsypurple mt-5"></i>
                </span>
            </div>
        </div>

        <div v-show='isActive' class="p-3 pl-20">
            <div v-for="item in formData">
                <span v-if="item.type == 'string'">
                    <span>{{ item.key }}:</span> <span><b>{{ item.value }}</b></span>
                </span>
                <span v-if="item.type == 'file'" class="file w-full block flex">
                    <span>{{ item.key }}:</span> <span><b>{{ item.name }}</b></span>
                    <a :href="'/download/file/' + item.id" class="ml-5 uppercase font-railway font-semibold text-xs bg-formsydark text-white py-1 px-3 rounded">Download</a>
                </span>
            </div>
            <div class="flex mt-5">
                <span class="text-formsypurple md:hidden">{{ date | formatDate }}</span>
                <a :href="submissionUrl" class="ml-auto text-xs uppercase flex py-2 px-2 text-formsypurple hover:text-formsydark">
                    <i class="icon-table text-lg mr-2"></i> Table view
                </a>
            </div>
        </div>

    </div>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
    },
    title: {
      type: String,
    },
    background: {
      type: String,
      default: 'bg-white',
    },
    date: {
      type: String,
    },
    data: {
      type: Object,
    },
    form: {
        type: String,
    },
  },
  data () {
    return {
        formData: [],
        name: `submission-${this.id}`,
        isActive: false,
        submissionUrl: `/view/form/${this.form}/submission/${this.id}`,
    }
  },
  methods: {
    formatData(data) {
        let keys = Object.keys(data);
        let formattedData = [];

        keys.forEach(key => {
            let item = data[key];
            let i = "";
            if (typeof item === 'object' && item !== null) {
                i = {
                    type: 'file',
                    name: item.name,
                    id: item.id,
                    key: key,
                };
            } else {
                i = {
                    type: 'string',
                    value: item,
                    key: key,
                };
            }

            formattedData.push(i);
        })

        return formattedData;
    },
  },
  mounted () {
      this.formData = this.formatData(this.data);
  },
}
</script>
