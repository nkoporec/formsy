<template>
  <div>
    <ul :class="bg" class='tabs__header lg:flex shadow-xs justify-between text-white w-full border-b'>
        <li v-for='(tab, index) in tabs'
            :key='tab.title'
            @click='selectTab(index)'
            :class='{"bg-white text-formsydark": (index == selectedIndex)}'
            class="text-sm text-center uppercase w-full py-4 px-24 cursor-pointer lg:flex pl-10"
            >
            <i :class=tab.icon class="mr-2 text-lg"></i> {{ tab.title }}
        </li>
    </ul>
    <slot></slot>
  </div>
</template>

<script>
export default {
    props: {
        bg: {
          type: String,
          default: 'bg-formsypurple'
        },
    },
    data () {
        return {
            selectedIndex: 0,
            tabs: [],
        }
    },
    mounted () {
        this.selectTab(0)
    },
    methods: {
        selectTab (i) {
            this.selectedIndex = i

            // loop over all the tabs
            this.tabs.forEach((tab, index) => {
                tab.isActive = (index === i)
            })
        }
    },
    created () {
        this.tabs = this.$children
    }
}
</script>
