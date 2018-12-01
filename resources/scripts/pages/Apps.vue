<template>
    <div class="monoland" :class="{ 'monoland--load': !fontLoaded }">
        <router-view v-if="fontLoaded"></router-view>

        <div class="lds-ellipsis" v-else>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<script>
import WebFontLoader from 'webfontloader';

export default {
    name: 'apps-base',

    data:() => ({
        fontLoaded: false,
    }),

    mounted() {
        WebFontLoader.load({
            custom: {
                families: ['Roboto', 'Nunito', 'Material Icons'],
                urls: ['/fonts/webfont.css']
            },
            active: this.setFontLoaded
        })
    },

    methods: {
        setFontLoaded: function() {
            setTimeout(() => {
                this.fontLoaded = true;
            }, 500);
        }
    }
};
</script>