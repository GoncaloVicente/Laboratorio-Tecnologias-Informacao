import deployments from "./components/Kubernetes/deployments";

require('./bootstrap');
//import 'bootstrap';

window.Vue = require('vue');

import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import moment from 'moment';

Vue.filter('formatDate', function (value) {
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

import loginComponent from "./components/OpenStack/login.vue";
import logoutComponent from "./components/OpenStack/logout";
import instanciasComponent from "./components/OpenStack/instancias.vue";
import dashboardComponent from "./components/OpenStack/dashboard.vue";
import changeProjectComponent from "./components/OpenStack/changeProject.vue";
import projectsComponent from "./components/OpenStack/projects.vue";
import imagensComponent from "./components/OpenStack/imagens";
import volumesComponent from "./components/OpenStack/volumes";
import containersComponent from "./components/OpenStack/containers";

import goForComponent from "./components/goFor";
import dasboardKubernetesComponent from "./components/Kubernetes/dashboard";
import definirIPKubernetesComponent from "./components/Kubernetes/definirIPKubernetes";
import kubernetesloginComponent from "./components/Kubernetes/loginKubernetes";
import namespacesComponent from "./components/Kubernetes/namespaces";
import nodesComponent from "./components/Kubernetes/nodes";
import podsComponent from "./components/Kubernetes/pods";
import deploymentsComponent from "./components/Kubernetes/deployments";
import servicesComponent from "./components/Kubernetes/services";
import endpointsComponent from "./components/Kubernetes/endpoints";
import clusterRolesComponent from "./components/Kubernetes/clusterRoles";

const login = Vue.component('login', loginComponent);
const logout = Vue.component('logout', logoutComponent);
const instancias = Vue.component('instancias', instanciasComponent);
const dashboard = Vue.component('dashboard', dashboardComponent);
const changeProject = Vue.component('changeProject', changeProjectComponent);
const projects = Vue.component('projects', projectsComponent);
const imagens = Vue.component('images', imagensComponent);
const volumes = Vue.component('volumes', volumesComponent);
const containers = Vue.component('containers', containersComponent);

const goFor = Vue.component('goFor', goForComponent);
const dasboardKubernetes = Vue.component('dasboardKubernetes', dasboardKubernetesComponent);
const definirIPKubernetes = Vue.component('definirIPKubernetes', definirIPKubernetesComponent);
const kuberneteslogin = Vue.component('kuberneteslogin', kubernetesloginComponent);
const namespaces = Vue.component('namespaces', namespacesComponent);
const nodes = Vue.component('nodes', nodesComponent);
const pods = Vue.component('pods', podsComponent);
const deployment = Vue.component('deployments', deploymentsComponent);
const services = Vue.component('services', servicesComponent);
const endpoints = Vue.component('endPoints', endpointsComponent);
const clusterRoles = Vue.component('clusterRoles', clusterRolesComponent);


const routes = [
    {path: '/login', component: login, name: "login"},
    {path: '/logout', component: logout, name: "logout"},
    {path: '/instancias', component: instancias, name: "instancias"},
    {path: '/dashboard', component: dashboard, name: "dashboard"},
    {path: '/changeProject', props: true, component: changeProject, name: "changeProject"},
    {path: '/projects', component: projects, name: "projects"},
    {path: '/imagens', component: imagens, name: "imagens"},
    {path: '/volumes', component: volumes, name: "volumes"},
    {path: '/containers', component: containers, name: "containers"},

    {path: '/', component: goFor, name: "escolha"},
    {path: '/dasboardKubernetes', component: dasboardKubernetes, name: "dasboardKubernetes"},
    {path: '/definirIPKubernetes', component: definirIPKubernetes, name: "definirIPKubernetes"},
    {path: '/kuberneteslogin', component: kuberneteslogin, name: "kuberneteslogin"},
    {path: '/namespaces', component: namespaces, name: "namespaces"},
    {path: '/nodes', component: nodes, name: "nodes"},
    {path: '/pods', component: pods, name: "pods"},
    {path: '/deployments', component: deployment, name: "deployments"},
    {path: '/services', component: services, name: "services"},
    {path: '/endpoints', component: endpoints, name: "endpoints"},
    {path: '/clusterRoles', component: clusterRoles, name: "clusterRoles"},

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
        show() {
            $('#sidebar').toggleClass('active');
        }
    },
    components: {
        PulseLoader
    }
});
