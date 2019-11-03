<template>
    <div>
        <a class="dropdown-item" href="#">
            <span @click.prevent="markAsRead(notification.id)">Lida</span>
            {{ comment && comment.author ? comment.author.name : 'noa' }} comentou {{ comment ? comment.title : 'no' }}
        </a>
    </div>
</template>

<script>
export default {
    props : ['notification'],
    computed: {
        comment() {
            return this.notification.data.comment;
        }
    },

    methods: {
        markAsRead(idNotification){
            this.$store.dispatch('markAsRead', {id: idNotification})
        }
    }
}
</script>
