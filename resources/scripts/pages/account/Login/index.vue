<template>
    <v-layout align-center justify-center>
        <v-flex md8>
            <div class="subheading font-weight-bold">{{ title }}</div>
            <div class="display-3 font-weight-thin">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </v-flex>

        <v-flex md4>
            <v-layout justify-center>
                <v-form ref="formLogin" method="POST" action="/account/login" style="width: 100%;">
                    <input type="hidden" name="_token" v-model="token">

                    <v-card class="v-card--auth">
                        <div class="v-card__head">
                            <div class="headline">Account Signin</div>
                            <div class="caption">Enter your credential</div>
                        </div>
                        <v-card-text>
                            <v-text-field v-model="email"
                                color="cyan"
                                label="Email Address"
                                name="email"
                                autocomplete="off"
                            ></v-text-field>
                            
                            <v-text-field v-model="password"
                                :append-icon="visible ? 'visibility_off' : 'visibility'"
                                :type="visible ? 'text' : 'password'"
                                color="cyan"
                                label="Password"
                                name="password"
                                @click:append="visible = !visible"
                            ></v-text-field>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="cyan" dark @click="doSignin" v-if="!token">singin</v-btn>
                            <v-btn color="cyan" dark type="submit" v-else>signin</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-layout>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    name: 'page-login',

    data:() => ({
        title: 'Untitled',
        token: null,
        visible: false,
        email: null,
        password: null
    }),

    mounted() {
        this.title = process.env.MIX_APP_NAME;

        if (process.env.MIX_DOMAIN_POLICY === 'samedomain') {
            this.token = this.$auth.token();
        }
    },

    methods: {
        doSignin: async function () {
            try {
                let token = await this.$http.post('/oauth/token', {
                    grant_type: 'password',
                    client_id: process.env.MIX_CLIENT_ID,
                    client_secret: process.env.MIX_CLIENT_SECRET,
                    username: this.email,
                    password: this.password,
                    scope: '*'
                });

                this.$auth.fetchToken(token.data);

                let user = await this.$http.get('/api/user');
                this.$auth.fetchUser(user.data);
                this.$router.push({ name: process.env.MIX_SIGNIN_TO });

            } catch (error) {
                this.$error = error;
            }
        },

        postSignin: function() {

        }
    }
};
</script>