<template>
    <v-dialog :value="dialog" max-width="300" persistent>
        <v-card class="v-card__dialog">
            <v-card-title class="headline">
                Delete ({{ records.length }}) record from table?
            </v-card-title>

            <v-card-text>
                Deleting this record will delete all records under this table relations as well. You can`t undo this action.
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click.native="cancel" color="green darken-1" flat>Cancel</v-btn>
                <v-btn @click.native="confirm" color="green darken-1" flat>Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'm-delete',

    props: {
        records: {
            type: Array,
            default:() => ([])
        },

        value: Boolean
    },

    data:() => ({
        dialog: false
    }),

    created() {
        this.dialog = this.value;
    },

    methods: {
        cancel: function() {
            this.$emit('cancel');
        },

        confirm: function() {
            this.$emit('confirm');
        }
    },

    watch: {
        value: function(newval) {
            this.dialog = newval;
        }
    }
};
</script>