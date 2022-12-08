<template>
    <div>
        <a href="#" @click.prevent="toggleDropdown" class="inline-block border-l pl-5 border-r pr-5 h-full">
            <span v-if="unreadNotifications >= 1" class="rounded-full ml-1 mt-5 uppercase absolute bg-formsydark text-white w-5 h-5 flex items-center justify-center">
                <span class="py-5 px-5 font-semibold text-xs">{{ unreadNotifications }}</span>
            </span>
            <div class="mt-10">
                <i class="icon-bell text-xl"></i>
            </div>
        </a>
        <div id="notificationMenu" v-show="state" class="bg-white mt-24 shadow-xl absolute top-0 right-0 w-64 overflow-auto z-30 border-r border-b border-l">
            <ul class="list-reset" v-for="item in notifications">
                <a href="#" class="px-4 py-4 block text-formsydark hover:bg-formsypurple hover:text-white no-underline hover:no-underline flex">
                    <span class="pt-1 text-sm">{{ item.message }} <br> <small class='text-gray-500'>{{ item.time }}</small></span>
                    <span v-if="item.status == 1" class="rounded-full mt-1 uppercase bg-formsydark text-white text-xs h-8 w-10 flex items-center justify-center">
                        <span class="py-2 px-2 "><small>New</small></span>
                    </span>
                </a>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user_id: {
                type: String,
            }
        },
        data() {
            return {
                state: false,
                notifications: false,
                unreadNotifications: 0,
            }
        },
        methods: {
            toggleDropdown (e) {
                this.state = !this.state

                this.clearNotifications();
            },
            close (e) {
                if (!this.$el.contains(e.target)) {
                    this.state = false
                }
            },
           getNotifications() {
               this.$http.get(`/user/${this.user_id}/notifications`)
                   .then((response)=> {
                       this.notifications = response.data;
                   })
            },
           getUnreadNotifications() {
               this.$http.get(`/user/${this.user_id}/notifications/unread`)
                   .then((response)=> {
                       this.unreadNotifications = response.data.count;
                   })
            },
           clearNotifications() {
               this.$http.get(`/user/${this.user_id}/notifications/clear`)
                   .then((response)=> {
                        console.log('Notifications cleared');
                   })
            },
        },
        mounted () {
            document.addEventListener('click', this.close);

            this.getUnreadNotifications();
            this.getNotifications();
        },
        beforeDestroy () {
            document.removeEventListener('click',this.close)
        }
    }
</script>
