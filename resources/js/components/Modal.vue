<template>
  <div v-if="showing" class="modal z-99999 fixed inset-0 w-full h-screen flex items-center justify-center text-formsydark">
    <div class="relative w-full max-w-2xl bg-formsypurple shadow-lg rounded pt-10">
        <span class="top-0 pt-2 absolute left-0 right-0 text-xs uppercase text-white">{{ title }}</span>
        <button aria-label="close" class="hover:text-formsydark absolute text-white top-0 right-0 text-xl mr-2" @click.prevent="close">
            <i class="icon-x"></i>
        </button>
        <div class="bg-white pt-8 rounded-b">
            <slot />
        </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    showing: {
      required: true,
      type: Boolean
    },
    title: {
      type: String
    }
  },
  methods: {
    close() {
        this.$emit('close');
    }
  },
}
</script>
