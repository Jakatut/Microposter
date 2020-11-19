import Vue from "vue";
import VueRouter from "vue-router"

Vue.use(VueRouter);

new VueRouter({
    routes: [
        {
            path: '/auth/:provider/callback',
            component: {
                template: '<div class="auth-component"></div>'
            }
        },
    ]
})