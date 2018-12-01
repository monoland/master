<template>
    <v-dialog :value="loading" width="300" persistent>
        <v-card>
            <v-card-text>
                loading data..
                <v-progress-linear
                    indeterminate
                    color="cyan"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'm-loader',

    props: {
        value: Boolean
    },

    data: () => ({
        loading: false
    }),

    created() {
        this.loading = this.value;
    },

    watch: {
        value: function(newval) {
            this.loading = newval;
        }
    }
};
</script>