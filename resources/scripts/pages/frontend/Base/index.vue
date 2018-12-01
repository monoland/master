<template>
    <v-app class="frontend" v-cloak>
        <v-toolbar color="transparent" flat app>
            <v-toolbar-title class="white--text">{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn flat dark :href="signPath" v-if="samedomain">signin</v-btn>
            <v-btn flat dark :to="signPath" v-else></v-btn>
        </v-toolbar>

        <v-content>
            <router-view :key="$route.path"></router-view>
        </v-content>
    </v-app>
</template>

<script>
export default {
    name: 'frontend-base',

    data:() => ({
        title: 'Monoland',
        signPath: null,
        samedomain: true
    }),

    mounted() {
        if (process.env.MIX_DOMAIN_POLICY === 'samedomain') {
            this.signPath = '/account';
            this.samedomain = true;
        } else {
            this.signPath = { name: 'login' };
            this.samedomain = false;
        }
    }

};
</script>