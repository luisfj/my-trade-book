<template>
    <a href="#" @click="showModal(metodo, obj_edit)">
        <i class="material-icons" :class="[mdsize, color]">{{ icon }}</i> <span :class="[textcolor]">{{ text ? text : '' }}</span>
    </a>
</template>

<script>
export default {
    props : ['obj_edit', 'metodo', 'icon', 'color', 'mdsize', 'text', 'textcolor'],
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
/**  Rules for sizing the icon.
.material-icons.md-18 { font-size: 18px; }
.material-icons.md-24 { font-size: 24px; }
.material-icons.md-36 { font-size: 36px; }
.material-icons.md-48 { font-size: 48px; }

 Rules for using icons as black on a light background.
.material-icons.md-dark { color: rgba(0, 0, 0, 0.54); }
.material-icons.md-dark.md-inactive { color: rgba(0, 0, 0, 0.26); }

 Rules for using icons as white on a dark background.
.material-icons.md-light { color: rgba(255, 255, 255, 1); }
.material-icons.md-light.md-inactive { color: rgba(255, 255, 255, 0.3); } */
