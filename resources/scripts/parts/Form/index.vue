<template>
    <v-dialog :value="dialog" transition="dialog-bottom-transition" fullscreen>
        <v-card class="v-card__form" tile>
            <v-toolbar color="blue-grey lighten-5" extended card flat>
                <v-btn icon @click.native="$emit('close')">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-layout row>
                <v-flex xs8 offset-xs2>
                    <v-card class="v-card__extended">
                        <v-toolbar card prominent>
                            <v-toolbar-title class="grey--text">{{ title }}</v-toolbar-title>
                        </v-toolbar>
                        <v-divider></v-divider>
                        <v-card-text class="pa-0">
                            <slot></slot>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'm-form',

    props: {
        title: {
            type: String,
            default: 'Untitled'
        },

        value: Boolean
    },

    data:() => ({
        dialog: false
    }),

    created() {
        this.dialog = this.value;
    },

    watch: {
        value: function(newval) {
            this.dialog = newval;
        }
    }
}
</script>
