<template>
    <li class="nav-item dropdown">
        <a id="navbarDropdown" v-bind:class="{ 'text-danger': notifications.length > 0 }" class="nav-link dropdown-toggle"
            href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Notificações ({{ notifications.length }})<span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

            <notification
            v-for="notification in notifications"
            :key="notification.id"
            :notification="notification"></notification>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item" :href="urlpanel">
                Painel de Notificações
            </a>
        </div>
    </li>
</template>

<script>

export default {
    props : ['urlpanel'],
    mounted(){

    },
    created() {
        this.$store.dispatch('loadNotifications')
    },

    computed:{
        notifications() {
            return this.$store.state.notifications.items
        }
    },

    methods: {
        markAllAsRead() {
            this.$message('confirm', 'Confirmação', 'Tem certeza que deseja marcar todas como lidas?', () =>{
                this.$store.dispatch('markAllAsRead')
            })
        }
    }
}
</script>
