<template>
    <v-app v-cloak>
        <v-navigation-drawer width="240" fixed clipped app>
            <v-list class="v-list--navdraw">
                <template v-for="(menu, index) in $root.backend.menus">
                    <v-list-tile :to="menu.to" v-if="menu.type === 'item'">
                        <v-list-tile-action><v-icon>{{ menu.icon }}</v-icon></v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ menu.text }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider :class="menu.class" v-else-if="menu.type === 'divider'"></v-divider>

                    <v-subheader class="text-uppercase" v-else-if="menu.type === 'subheader'">{{ menu.text }}</v-subheader>
                    
                    <v-list-group :prepend-icon="menu.icon" v-else>
                        <v-list-tile slot="activator">
                            <v-list-tile-title>{{ menu.text }}</v-list-tile-title>    
                        </v-list-tile>

                        <template v-for="(item, index) in menu.items">
                            <v-list-tile :to="item.to">
                                <v-list-tile-action>
                                    <v-icon>{{ item.icon }}</v-icon>
                                </v-list-tile-action>
                                
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>
                    </v-list-group> 
                </template>
            </v-list>
        </v-navigation-drawer>
        
        <v-toolbar color="white" height="56" fixex clipped-left app>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>notifications</v-icon>
            </v-btn>
            <v-menu
                :close-on-content-click="false"
                :nudge-width="250"
                offset-x
            >
                <v-btn slot="activator" icon>
                    <v-icon>account_circle</v-icon>
                </v-btn>

                <v-card>
                    <v-list>
                        <v-list-tile avatar>
                            <v-list-tile-avatar>
                                <v-icon>account_circle</v-icon>
                            </v-list-tile-avatar>

                            <v-list-tile-content>
                                <v-list-tile-title>Account Name</v-list-tile-title>
                                <v-list-tile-sub-title>Account Detail</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-tile>
                            <v-list-tile-title>Some menu #1</v-list-tile-title>
                        </v-list-tile>

                        <v-list-tile>
                            <v-list-tile-title>Some menu #2</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-menu>
            
        </v-toolbar>
        
        <v-content>
            <router-view :key="$route.path"></router-view>
        </v-content>
    </v-app>
</template>

<script>
export default {
    name: 'backend-base',

    data:() => ({
        title: 'Untitled',
        user: {}
    }),

    mounted() {
        this.title = process.env.MIX_APP_NAME;
    },

    methods: {}
};
</script>