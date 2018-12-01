<template>
    <v-tooltip bottom>
        <v-btn slot="activator" @click="onClick" :disabled="disabled" icon dark>
            <v-icon>{{ icon }}</v-icon>
        </v-btn>
        <span>{{ message }}</span>
    </v-tooltip>
</template>


<script>
export default {
    name: 'm-button',

    props: {
        icon: {
            type: String,
            default: 'folder'
        },

        disabled: {
            type: Boolean,
            default: false
        },

        tooltip: {
            type: String,
            default: null
        }
    },

    data:() => ({
        message: null
    }),

    mounted() {
        if (!this.tooltip) {
            switch (this.icon) {
                case 'add':
                    this.message = process.env.MIX_TIP_ADD;
                    break;
                
                case 'folder':
                    this.message = process.env.MIX_TIP_LINK;
                    break;
                
                case 'edit':
                    this.message = process.env.MIX_TIP_EDIT;
                    break;
                
                case 'delete':
                    this.message = process.env.MIX_TIP_DELETE;
                    break;
                
                case 'refresh':
                    this.message = process.env.MIX_TIP_RELOAD;
                    break;

                case 'search':
                    this.message = process.env.MIX_TIP_FIND;
                    break;
            }
        } else {
            this.message = this.tooltip;
        }
    },

    methods: {
        onClick: function() {
            this.$emit('click');
        }
    }
};
</script>
