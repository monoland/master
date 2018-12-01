<template>
    <v-app class="account" v-cloak>
        <v-content>
            <v-container fill-height>
                <router-view :key="$route.path"></router-view>
            </v-container>
        </v-content>

        <v-footer color="transparent" app>
            <v-container class="container__flat" fill-height>
                <v-layout align-center justify-center>
                    monoland &copy; 2018
                </v-layout>
            </v-container>
        </v-footer>

        <v-snackbar v-model="$root.message.show"
            :color="$root.message.type"
            :timeout="$root.message.time"
        >
            {{ $root.message.text }}
            <v-btn dark flat @click="$root.message.show = false">close</v-btn>
        </v-snackbar>
    </v-app>
</template>

<script>
export default {
    name: 'frontend-base'
};
</script>
