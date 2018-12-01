<template>
    <div class="v-pagetool">
        <v-avatar color="blue-grey">
            <v-icon dark>{{ icon }}</v-icon>
        </v-avatar>
        <div class="v-pagetool__title">
            <div class="title blue-grey--text">{{ title }}</div>
            <div class="caption blue-grey--text">{{ subtitle }}</div>
        </div>

        <v-spacer></v-spacer>

        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'm-header',

    props: {
        icon: {
            type: String,
            default: 'home'
        },

        title: {
            type: String,
            default: 'Untitled'
        },

        subtitle: {
            type: String,
            default: 'Some description will display here.'
        }
    }
}
</script>
