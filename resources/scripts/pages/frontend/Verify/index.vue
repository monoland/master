<template>
    <v-container fill-height>
        <v-layout align-center justify-center row wrap>
            <v-flex md7>
                <div class="display-3 font-weight-thin white--text">Verify Email Address</div>
                <div class="subheading white--text">
                    <p v-if="resendState">A fresh verification link has been sent to your email address.</p>
                    <p v-else>Before proceeding, please check your email for a verification link.</p>
                    <p>If you did not receive the email, click button to request another.</p>
                    <v-btn round :disabled="disabledState" class="mx-0" @click="resend">Resend Verification</v-btn>
                </div>
            </v-flex>

            <v-flex md5 class="text-lg-right">
                <!--  -->
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    name: 'page-verify',

    data:() => ({
        disabledState: false,
        resendState: false
    }),

    methods: {
        resend: async function() {
            this.disabledState = true;

            try {
                let { data } = await this.$http.post('/email/resend');
                this.resendState = data.success;
            } catch (error) {
                this.$error = error;
            } finally {
                this.disabledState = false;
            }
            
        }
    }
};
</script>
