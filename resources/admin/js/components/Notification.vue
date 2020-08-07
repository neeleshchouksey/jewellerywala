<template>
    <div>
        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @click="this.$root.readNotification">
            <i class="icon-fa icon-fa-bell"><h5 v-if="$root.readNot==0" class="red-dot" ></h5></i></a>
        <div class="dropdown-menu dropdown-menu-right notification-dropdown not-max-height" >
            <h6 class="dropdown-header">Notifications</h6>
            <a v-if="$root.notifications.length" class="dropdown-item" v-for="n in $root.notifications" :href="APP_URL+'/'+USER_TYPE+'/'+n.url">{{n.notification}}</a>
            <a v-if="!$root.notifications.length" href="#">No Data Found</a>
        </div>
    </div>
</template>

<style>
    .notification-dropdown{
        max-height: 400px;
        overflow: scroll;
    }
</style>

<script>
import AdminSellerMixin from "../mixins/AdminSellerMixin";

export default {
    mixins: [AdminSellerMixin],
    created() {
        this.$root.getNotifications();
    }
}
</script>