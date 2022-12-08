<template>
    <div class="relative text-sm font-raleway font-semibold lg:w-7/12 w-11/12">
        <button id="userButton" :class="{shown: state}" @click.prevent="toggleDropdown" class="flex items-center focus:outline-none text-formsydark hover:text-formsypurple border-gray-200 h-full w-full py-4 px-8">
            <img class="rounded-full mr-4 lg:block hidden" :src="gravatar"></img>
            <span class="md:inline-block">Hi, {{ userName }}</span>
        </button>
        <div id="userMenu" v-show="state" class="bg-white shadow-xl absolute top-0 right-0 min-w-full overflow-auto z-30 border-r border-b border-l">
            <ul class="list-reset">
                <li>
                    <a :href="accountRoute" class="px-4 py-4 block text-formsydark hover:bg-formsypurple hover:text-white no-underline hover:no-underline flex">
                        <i class="icon-user-circle mr-2 text-3xl"></i>
                        <span class="pt-1">My account</span>
                    </a>
                </li>
                <li>
                    <hr class="border-t mx-2 border-gray-400">
                </li>
                <li>
                    <hr class="border-t mx-2 border-gray-400">
                </li>
                <li>
                    <a :href="logoutRoute" class="px-2 py-4 block text-formsydark hover:bg-formsypurple hover:text-white no-underline hover:no-underline flex">
                        <i class="pl-3 icon-logout mr-2 text-3xl"></i>
                        <span class="pt-1">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            gravatar: {
                type: String
            },
            userName: {
                type: String
            },
            accountRoute: {
                type: String
            },
            logoutRoute: {
                type: String
            }
        },
        data() {
            return {
                state: false,
            }
        },
        methods: {
            toggleDropdown (e) {
                this.state = !this.state
            },
            close (e) {
                if (!this.$el.contains(e.target)) {
                    this.state = false
                }
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
