<template>
  <div v-show='isActive' class="rounded-l rounded-b rounded-r shadow">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: 'Tab'
    },
    icon: {
      type: String,
      default: ''
    },
  },
  data () {
    return {
      isActive: true
    }
  }
}
</script>
