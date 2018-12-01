<template>
    <v-container class="pa-0" fluid fill-height>
        <v-layout column>
            <v-flex md12>
                <div class="v-pagetool">
                    <v-avatar color="blue-grey">
                        <v-icon dark>account_circle</v-icon>
                    </v-avatar>
                    <div class="v-pagetool__title">
                        <div class="title blue-grey--text">Untitled</div>
                        <div class="caption blue-grey--text">some describtion display here</div>
                    </div>
                </div>

                <v-breadcrumbs :items="breadcrumbs" dark></v-breadcrumbs>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    name: 'backend-dashboard',

    data:() => ({
        breadcrumbs: [
            { text: 'Home', href: '/backend/' },
            { text: 'Dashboard', disabled: true }
        ]
    })
};
</script>
