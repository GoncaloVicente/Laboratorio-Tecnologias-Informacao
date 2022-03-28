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
                                <td>{{port.port}}</td>
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
                        <h3 class="modal-title">Create Service</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Namespace</label>
                            <select class="form-control" v-model="service.namespace" v-on:change="getDeployments">
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
                            <label>Deployment</label>
                            <select class="form-control" v-model="service.deployment">
                                <option value=" " disabled selected>Choose a deployment</option>
                                <option
                                    v-for="deployment in deployments"
                                    :key="deployment.metadata.name"
                                    v-bind:value="deployment.metadata.name"
                                >{{ deployment.metadata.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Port Source</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Number of port source"
                                v-model="service.portSource"
                            />
                        </div>
                        <div class="form-group">
                            <label>Port Destination</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Number of port destination"
                                v-model="service.portDestination"
                            />
                        </div>
                        <div class="form-group">
                            <label>Protocol</label>
                            <select class="form-control" v-model="service.protocol">
                                <option value=" " disabled selected>Choose a protocol</option>
                                <option value="SCTP">SCTP</option>
                                <option value="TCP">TCP</option>
                                <option value="UDP">UDP</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Protocol Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of protocol"
                                v-model="service.protocolName"
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
                                v-on:click.prevent="createService()"
                                value="Create Deployment"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <h1>Services</h1>
        </div>
        <br/>
        <div class="form-group">
            <button
                type="button"
                class="btn btn-outline-dark"
                data-toggle="modal"
                data-target=".bd-example-modal1"
                style="border-color: black;"
                v-on:click="getDeployments"
            ><i class="fa fa-plus" aria-hidden="true"></i>
                Create Service
            </button>
        </div>
        <br>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Manager</th>
                <th>Cluster IP</th>
                <th>Type</th>
                <th>Session Affinity</th>
                <th>Created</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody v-for="service in services">
            <tr>
                <td>{{service.metadata.name}}</td>
                <td>{{service.metadata.managedFields[0].manager}}</td>
                <td>{{service.spec.clusterIP}}</td>
                <td>{{service.spec.type}}</td>
                <td>{{service.spec.sessionAffinity}}</td>
                <td>{{service.metadata.managedFields[0].time}}</td>
                <td>
                    <select>
                        <option selected disabled>Select an option</option>
                        <option
                            data-toggle="modal"
                            data-target=".bd-example-modal"
                            v-on:click="detailsService(service)"
                        >Used ports
                        </option>
                        <option
                            v-on:click="deleteService(service.metadata.name)"
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
        name: "services",
        data: function () {
            return {
                url: this.$store.state.url,
                services: [],
                namespacesItems: [],
                service: {
                    namespace: this.$store.state.namespace,
                    deployment: " ",
                    portSource: null,
                    portDestination: null,
                    protocol: " ",
                    protocolName: null,
                },
                option: this.$store.state.namespace,
                ports: [],
                deployments: []
            };
        },
        methods: {
            getServices() {
                axios.get(this.url + "/api/v1/namespaces/" + this.$store.state.namespace + "/services").then(response => {
                    this.services = response.data.items;
                    for (var i = 0; i < (this.services).length; i++) {

                        var date = this.services[i].metadata.managedFields[0].time.split("T");
                        this.services[i].metadata.managedFields[0].time = date[0];
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
                this.getServices();
            },
            detailsService(service) {
                this.ports = service.spec.ports;
            },
            deleteService(service) {
                axios.delete(this.url + "/api/v1/namespaces/" + this.$store.state.namespace + "/services/" + service
                ).then(response => {
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
                        this.getServices();
                        this.$vToastify.success("Service deleted");
                        this.getServices();
                    })
                }).catch(error => {
                    this.$vToastify.error('Service not deleted')
                });
            },
            getDeployments() {
                this.service.deployment = " ";
                axios.get(this.url + "/apis/apps/v1/namespaces/" + this.service.namespace + "/deployments"
                ).then(response => {
                    this.deployments = response.data.items;
                })
            },
            createService() {
                axios.post(this.url + "/api/v1/namespaces/" + this.service.namespace + "/services", {
                    "kind": "Service",
                    "apiVersion": "v1",
                    "metadata": {
                        "name": this.service.deployment,
                        "namespace": this.service.namespace,
                        "labels": {
                            "app": this.service.deployment,
                        }
                    },
                    "spec": {
                        "ports": [
                            {
                                "name": this.service.protocolName,
                                "protocol": this.service.protocol,
                                "port": parseInt(this.service.portSource),
                                "targetPort": parseInt(this.service.portDestination)
                            }
                        ],
                        "selector": {
                            "app": this.service.deployment
                        },
                        "type": "NodePort",
                        "sessionAffinity": "None",
                        "externalTrafficPolicy": "Cluster"
                    }
                })
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
                            this.getServices();
                            this.$vToastify.success("Service created");
                            this.getServices();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Service not created')
                });
            },
        }
        ,
        computed: {
            isValid() {
                if (this.service.namespace && this.service.deployment != " " && this.service.portSource && this.service.portDestination && this.service.protocol != " " && this.service.protocolName) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        mounted() {
            this.getNameSpaces();
            this.getServices();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }
</style>
