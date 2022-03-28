<template>
    <div>
        <div class="col-auto mr-auto">
            <div style="margin-top:10px;margin-left:5px;" class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">Select a namespace</label>
                </div>
                <select v-model="option" class="custom-select" id="inputGroupSelect01">
                    <option disabled selected>Select Namespace</option>
                    <option v-for="namespace in namespacesItems" v-on:click="changeNameSpace(namespace.metadata.name)">
                        {{namespace.metadata.name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Used Ports</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Port</th>
                                <th>Protocol</th>
                            </tr>
                            </thead>
                            <tbody v-for="port in ports">
                            <tr>
                                <td class="capitalize">{{port.name}}</td>
                                <td>{{port.containerPort}}</td>
                                <td>{{port.protocol}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal"
                                    style="border-color: gray;"><i class="fa fa-times" aria-hidden="true"></i>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal1" id="myModalImages1">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Deployment</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of deployment"
                                v-model="deployment.name"
                            />
                        </div>
                        <div class="form-group">
                            <label>Namespace</label>
                            <select class="form-control" v-model="deployment.namespace">
                                <option value=" " disabled selected>Choose a namespace</option>
                                <option
                                    v-for="namespace in namespacesItems"
                                    :key="namespace.metadata.name"
                                    v-bind:value="namespace.metadata.name"
                                >{{ namespace.metadata.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Label</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Label of deployment"
                                v-model="deployment.label"
                            />
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Image of deployment"
                                v-model="deployment.image"
                            />
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal"
                                    style="border-color: gray;"><i class="fa fa-times" aria-hidden="true"></i>
                                Cancel
                            </button>
                            <input
                                type="submit"
                                class="btn btn-primary"
                                data-dismiss="modal"
                                :class="{ disabled: isValid }" :disabled="isValid"
                                v-on:click.prevent="createDeployment()"
                                value="Create Deployment"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <h1>Deployments</h1>
        </div>
        <br/>
        <div class="form-group">
            <button
                type="button"
                class="btn btn-outline-dark"
                data-toggle="modal"
                data-target=".bd-example-modal1"
                style="border-color: black;"
            ><i class="fa fa-plus" aria-hidden="true"></i>
                Create Deployment
            </button>
        </div>
        <br>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Labels</th>
                <th>Manager</th>
                <th>Pods Available</th>
                <th>Images</th>
                <th>Memory Limit</th>
                <th>Created</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody v-for="deployment in deployments">
            <tr>
                <td>{{deployment.metadata.name}}</td>
                <td v-if="deployment.metadata.labels['k8s-app']">{{deployment.metadata.labels['k8s-app']}}</td>
                <td v-else>{{deployment.metadata.labels['app']}}</td>
                <td>{{deployment.metadata.managedFields[0].manager}}</td>
                <td>{{deployment.status.availableReplicas}}</td>
                <td>{{deployment.spec.template.spec.containers[0].image}}</td>
                <td>{{deployment.spec.template.spec.containers[0].resources.limits.memory}}</td>
                <td>{{deployment.metadata.managedFields[0].time}}</td>
                <td>
                    <select>
                        <option selected disabled>Select an option</option>
                        <option
                            data-toggle="modal"
                            data-target=".bd-example-modal"
                            v-on:click="detailsDeployment(deployment)"
                        >Used ports
                        </option>
                        <option
                            v-on:click="deleteDeployment(deployment.metadata.name)"
                        >Delete
                        </option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    const Swal = require('sweetalert2');

    export default {
        name: "pods",
        data: function () {
            return {
                url: this.$store.state.url,
                deployments: [],
                namespacesItems: [],
                deployment: {
                    name: null,
                    namespace: this.$store.state.namespace,
                    label: null,
                    image: null,
                    replicas: null
                },
                option: this.$store.state.namespace,
                ports: []
            };
        },
        methods: {
            getDeployments() {
                axios.get(this.url + "/apis/apps/v1/namespaces/" + this.$store.state.namespace + "/deployments").then(response => {
                    this.deployments = response.data.items;
                    for (var i = 0; i < (this.deployments).length; i++) {

                        var date = this.deployments[i].metadata.managedFields[0].time.split("T");
                        this.deployments[i].metadata.managedFields[0].time = date[0];
                    }
                });
            },
            getNameSpaces() {
                axios.get(this.url + "/api/v1/namespaces").then(response => {
                    this.namespacesItems = response.data.items;
                });
            },
            changeNameSpace(namespace) {
                this.$store.commit("setNameSpace", namespace);
                this.$vToastify.success("Namespace " + namespace + " selected");
                this.getDeployments();
            },
            detailsDeployment(deployment){
                this.ports = deployment.spec.template.spec.containers[0].ports;
            },
            deleteDeployment(deployment) {
                axios.delete(this.url + "/apis/apps/v1/namespaces/"+this.$store.state.namespace+"/deployments/" + deployment)
                    .then(response => {
                        let timerInterval
                        Swal.fire({
                            html: 'Terminating in 10 seconds...',
                            timer: 10000,
                            didOpen: () => {
                                Swal.showLoading()
                                timerInterval = setInterval(() => {
                                    const content = Swal.getContent()
                                    if (content) {
                                        const b = content.querySelector('b')
                                        if (b) {
                                            b.textContent = Swal.getTimerLeft()
                                        }
                                    }
                                }, 100)
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            this.getDeployments();
                            this.$vToastify.success("Deployment deleted");
                            this.getDeployments();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Deployment not deleted')
                });
            },
            createDeployment() {
                axios.post(this.url + "/apis/apps/v1/namespaces/" + this.deployment.namespace + "/deployments",
                    {
                        kind: "Deployment",
                        apiVersion: "apps/v1",
                        metadata: {
                            name: this.deployment.name,
                            namespace: this.deployment.namespace,
                            labels: {
                                app: this.deployment.label
                            }
                        },
                        spec: {
                            selector: {
                                matchLabels: {
                                    app: this.deployment.name
                                }
                            },
                            template: {
                                metadata: {
                                    labels: {
                                        app: this.deployment.name
                                    }
                                },
                                spec: {
                                    containers: [
                                        {
                                            name: this.deployment.name,
                                            image: this.deployment.image,
                                            ports: [
                                                {
                                                    containerPort: 80
                                                }
                                            ],
                                            resources: {
                                                limits: {
                                                    memory: "128Mi",
                                                    cpu: "500m"
                                                }
                                            }
                                        }
                                    ]
                                }
                            }
                        }
                    }
                )
                    .then(response => {
                        let timerInterval
                        Swal.fire({
                            html: 'Working ...',
                            timer: 5000,
                            didOpen: () => {
                                Swal.showLoading()
                                timerInterval = setInterval(() => {
                                    const content = Swal.getContent()
                                    if (content) {
                                        const b = content.querySelector('b')
                                        if (b) {
                                            b.textContent = Swal.getTimerLeft()
                                        }
                                    }
                                }, 100)
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            this.getDeployments();
                            this.$vToastify.success("Deployment created");
                            this.getDeployments();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Deployment not created')
                });
            },
        }
        ,
        computed: {
            isValid() {
                if (this.deployment.name && this.deployment.label && this.deployment.image && this.deployment.namespace) {
                    return false;
                } else {
                    return true;
                }
            }
        }
        ,
        mounted() {
            this.getNameSpaces();
            this.getDeployments();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }
</style>
