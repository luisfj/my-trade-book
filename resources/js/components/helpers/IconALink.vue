<template>
    <a href="#" @click="showModal(metodo, obj_edit)">
        <i class="material-icons" :class="[mdsize, color]">{{ icon }}</i>
    </a>
</template>

<script>
export default {
    props : ['obj_edit', 'metodo', 'icon', 'color', 'mdsize'],
    computed: {
        comment() {
           // return this.notification.data.comment;
        }
    },

    methods: {
        showModal(metodo, obj_edit){
            bus.$emit(metodo, obj_edit);
        }
    }
}
</script>
