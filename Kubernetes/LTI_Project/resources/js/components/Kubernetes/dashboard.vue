<template>
    <div class="col-md-12">
        <br>
        <h1>Overview</h1>
        <br>
        <h3>Cluster</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Namespaces</span>
                        <span class="info-box-number">{{namespacesItems}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Nodes</span>
                        <span class="info-box-number">{{nodes}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Cluster Roles</span>
                        <span class="info-box-number">{{clusterRoles}}</span>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <h3>Workload</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pods</span>
                        <span class="info-box-number">{{pods}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Deployments</span>
                        <span class="info-box-number">{{deployments}}</span>
                    </div>
                </div>
            </div>
        </div>
        <h3>Services</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Services</span>
                        <span class="info-box-number">{{services}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Endpoints</span>
                        <span class="info-box-number">{{endpoints}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "dashboard",
        data: function () {
            return {
                url: this.$store.state.url,
                namespacesItems: null,
                nodes: null,
                clusterRoles: null,
                pods: null,
                deployments: null,
                services: null,
                endpoints: null
            };
        },
        methods: {
            getNameSpaces() {
                axios.get(this.url + "/api/v1/namespaces").then(response => {
                    this.namespacesItems = response.data.items.length;
                });
            },
            getNodes() {
                axios.get(this.url + "/api/v1/nodes")
                    .then(response => {
                        this.nodes = response.data.items.length;
                    });
            },
            getClusterRoles() {
                axios.get(this.url + "/apis/rbac.authorization.k8s.io/v1/clusterroles")
                    .then(response => {
                        this.clusterRoles = response.data.items.length;
                    });
            },
            getPods() {
                axios.get(this.url + "/api/v1/pods").then(response => {
                    this.pods = response.data.items.length;
                });
            },
            getDeployments() {
                axios.get(this.url + "/apis/apps/v1/deployments").then(response => {
                    this.deployments = response.data.items.length;
                });
            },
            getServices() {
                axios.get(this.url + "/api/v1/services").then(response => {
                    this.services = response.data.items.length;
                });
            },
            getEndpoints() {
                axios.get(this.url + "/api/v1/endpoints").then(response => {
                    this.endpoints = response.data.items.length;
                });
            },
        },
        mounted() {
            this.getNameSpaces();
            this.getNodes();
            this.getClusterRoles();
            this.getPods();
            this.getDeployments();
            this.getServices();
            this.getEndpoints();
        }
    }
</script>

<style scoped>

</style>
