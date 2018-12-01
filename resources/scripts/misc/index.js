import SecureLS from 'secure-ls';
import Axios from 'axios';
import Echo from 'laravel-echo';

class Auth
{
    constructor() {
        this.store = new SecureLS({ 
            encryptionSecret: process.env.MIX_APP_KEY 
        });

        if (this.store.get('user_name')) {
            this.user = Object.assign({}, {
                avatar: this.store.get('user_avatar'),
                name: this.store.get('user_name'),
                email: this.store.get('user_email')
            });
        } else {
            this.user = null;
        }
    }

    clientId() {
        return process.env.MIX_CLIENT_ID;
    }

    clientSecret() {
        return process.env.MIX_CLIENT_SECRET;
    }

    baseUrl() {
        return process.env.MIX_BASE_URL;
    }

    expired() {
        let minute = parseInt((Date.now() - parseInt(this.store.get('token_create'))) / 1000);
        let expire = parseInt(this.store.get('expires_in'));

        return minute >= expire;
    }

    check() {
        return !!this.store.get('access_token') && !this.expired();
    }

    token() {
        if (process.env.MIX_DOMAIN_POLICY === 'samedomain') {
            return (document.head.querySelector('meta[name="csrf-token"]')).content;
        } else {
            return this.store.get('token_type') + ' ' + this.store.get('access_token');
        }
    }

    fetchToken(param) {
        if (param.access_token) { this.store.set('access_token', param.access_token); }
        if (param.expires_in) { this.store.set('expires_in', param.expires_in); }
        if (param.refresh_token) { this.store.set('refresh_token', param.refresh_token); }
        if (param.token_type) { this.store.set('token_type', param.token_type); }
        
        this.store.set('token_create', Date.now());
    }

    fetchUser(param) {
        if (param.avatar) { this.store.set('user_avatar', param.avatar); }
        if (param.name) { this.store.set('user_name', param.name); }
        if (param.email) { this.store.set('user_email', param.email); }
        if (param.roles) { this.store.set('user_roles', param.roles); }
        if (param.pages) { this.store.set('user_pages', JSON.stringify(param.pages)); }

        this.user = Object.assign({}, {
            avatar: param.avatar,
            name: param.name,
            email: param.email,
            roles: param.roles,
            pages: JSON.stringify(param.pages) 
        });
    }

    signout() {
        return this.store.removeAll();
    }
}

export const Misc = {
    install(Vue) {
        if (this.installed) { return; }
        
        this.installed = true;

        // define auth
        let $auth = new Auth();
        
        Object.defineProperty(Vue.prototype, '$auth', {
            get() {
                return $auth;
            }
        });

        // define http
        Object.defineProperty(Vue.prototype, '$http', {
            get() {
                let headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };

                if (process.env.MIX_DOMAIN_POLICY === 'samedomain') {
                    headers =  Object.assign({
                        'X-CSRF-TOKEN': $auth.token()
                    }, headers);
                } else {
                    headers =  Object.assign({
                        'Authorization': $auth.token()
                    }, headers);
                }

                return Axios.create({
                    baseURL: process.env.MIX_BASE_URL,
                    headers
                });
            }
        });

        // define request
        Object.defineProperty(Vue.prototype, '$ajax', {
            get() {
                let headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                return Axios.create({
                    baseURL: process.env.MIX_BASE_URL,
                    headers
                });
            }
        });

        // define echo
        let $echo = null;
        
        if (process.env.MIX_SOCKET_IO === 'true') {
            window.io = require('socket.io-client');

            $echo = new Echo({
                broadcaster: 'socket.io',
                host: process.env.MIX_SOCKET_HOST
            });
        }

        Object.defineProperty(Vue.prototype, '$echo', {
            get() {
                return $echo;
            }
        });

        // define message
        let $message = {};

        Object.defineProperty(Vue.prototype, '$message', {
            get() {
                return $message;
            },

            set(newval) {
                $message = {
                    type: 'success',
                    text: newval,
                    show: true
                };

                this.$root.message = $message;
            }
        });

        // define error
        let $error = {};

        Object.defineProperty(Vue.prototype, '$error', {
            get() {
                return $error;
            },

            set(newval) {
                if (newval.hasOwnProperty('message')) {
                    if (newval.message === 'Request failed with status code 401') {
                        $auth.signout();
                        this.$router.push({ name: process.env.MIX_SIGNOUT_TO });
                    } else {
                        $error = {
                            type: 'error',
                            text: newval.message,
                            show: true
                        };

                        this.$root.message = $error;
                    }
                }

                if (newval.hasOwnProperty('response')) {
                    let { message, errors } = newval.response.data;

                    if (errors) {
                        $error = {
                            type: 'error',
                            text: message,
                            show: true
                        };

                        this.$root.message = $error;
                    }
                }

                if (process.env.NODE_ENV === 'production') {
                    window.console.clear();
                }
            }
        });
    }
};