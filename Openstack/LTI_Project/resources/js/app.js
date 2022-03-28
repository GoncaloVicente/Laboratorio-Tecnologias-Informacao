require('./bootstrap');
//import 'bootstrap';

window.Vue = require('vue');

import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import moment from 'moment';

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY HH:mm:ss')
    }
});


import VueToastify from 'vue-toastify'

Vue.use(VueToastify, {
    position: "top-right"
});

import 'chart.js';


import Vue from 'vue'
import vueFilterPrettyBytes from 'vue-filter-pretty-bytes'

Vue.use(vueFilterPrettyBytes)

import store from "./vuex.js";

import loginComponent from "./components/login.vue";
import logoutComponent from "./components/logout";
import instanciasComponent from "./components/instancias.vue";
import dashboardComponent from "./components/dashboard.vue";
import changeProjectComponent from "./components/changeProject.vue";
import projectsComponent from "./components/projects.vue";
import imagensComponent from "./components/imagens";
import volumesComponent from "./components/volumes";
import containersComponent from "./components/containers";

const login = Vue.component('login', loginComponent);
const logout = Vue.component('logout',logoutComponent);
const instancias = Vue.component('instancias',instanciasComponent);
const dashboard = Vue.component('dashboard', dashboardComponent);
const changeProject = Vue.component('changeProject', changeProjectComponent);
const projects = Vue.component('projects', projectsComponent);
const imagens = Vue.component('images', imagensComponent);
const volumes = Vue.component('volumes',volumesComponent);
const containers = Vue.component('containers',containersComponent);


const routes = [
    { path: '/login', component: login, name: "login" },
    { path: '/logout', component: logout, name:"logout"},
    { path: '/instancias', component: instancias, name:"instancias"},
    { path: '/dashboard', component: dashboard, name: "dashboard"},
    { path: '/changeProject',  props: true, component: changeProject, name:"changeProject"},
    { path: '/projects', component: projects, name:"projects"},
    { path: '/imagens', component: imagens, name:"imagens"},
    { path: '/volumes', component: volumes, name:"volumes"},
    { path: '/containers', component: containers, name:"containers"},
];

const router = new VueRouter({
    routes,
});


new Vue({
    el: "#app",
    data: function () {
        return {
            // user: null,
        }
    },
    router,
    store,
    created() {
        this.$store.commit("loadTokenAndUserFromSession");
    },
    methods: {
        show(){
            $('#sidebar').toggleClass('active');
        }
    },
    components:{
        PulseLoader
    }
});
