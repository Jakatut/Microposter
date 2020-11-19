import Vue from 'vue'
import VueAxios from 'vue-axios'
import VueSocialauth from 'vue-social-auth'
import axios from 'axios';

Vue.use(VueAxios, axios)
Vue.use(
    VueSocialauth,
    {
        providers: {
            github: {
                clientId: '9d6a405a6c83993ded71',
                redirectUri: '/auth/github/callback' // Your client app URL
            }
        }
    }
)

<button @click="AuthProvider('github')">auth Github</button>
<button @click="AuthProvider('facebook')">auth Facebook</button>
<button @click="AuthProvider('google')">auth Google</button>
<button @click="AuthProvider('twitter')">auth Twitter</button>

<script>
export default {
    data() {
        return {};
    },
    methods: {
        AuthProvider(provider) {
            var self = this;

            this.$auth
                .authenticate(provider)
                .then(response => {
                    self.SocialLogin(provider, response);
                })
                .catch(err => {
                    console.log({ err: err });
                });
        },

        SocialLogin(provider, response) {
            this.$http
                .post("/sociallogin/" + provider, response)
                .then(response => {
                    console.log(response.data);
                })
                .catch(err => {
                    console.log({ err: err });
                });
        }
    }
};
</script>
