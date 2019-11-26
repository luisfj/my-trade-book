<template>
    <div>
        <a class="dropdown-item" href="#">
            <span @click.prevent="markAsRead(notification)">
                {{ post ? post.tipo == 'E' ? 'Vote: ' + post.title : 'Msg: ' + post.title : '--Sem Obj--' }}
            </span>
        </a>
    </div>
</template>

<script>
export default {
    props : ['notification'],
    computed: {
        post() {
            return this.notification.data.post;
        }
    },

    methods: {
        markAsRead(notification){
            bus.$emit('editarNotificacao', notification);
        }
    }
}
</script>
