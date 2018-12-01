<template>
    <div class="v-pagesearch">
        <input ref="input" :value="search" 
            @input="bouncing" 
            @focus="onFocus = true"
            @blur="onFocus = false"
            class="v-pagesearch__text" 
            type="text" 
            placeholder="Search Program"
            :class="{ 'v-pagesearch__text--focus': onFocus }"
        >
        <v-spacer></v-spacer>
        <v-btn color="blue-grey" icon flat @click.native="$emit('close')">
            <v-icon>close</v-icon>
        </v-btn>
    </div>
</template>


<script>
import { debounce } from "debounce";

export default {
    name: 'm-search',

    props: {
        value: {
            type: String,
            default: null
        }
    },

    data:() => ({
        search: null,
        onFocus: false
    }),

    created() {
        this.search = this.value;
    },

    methods: {
        bouncing: debounce(function(e) {
            this.search = e.target.value;
            this.$emit('input', this.search);
        }, 500),

        focus: function() {
            this.$refs.input.focus();
        }
    },

    watch: {
        value: function(newval) {
            this.search = newval;
        }
    }
};
</script>
