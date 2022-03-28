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
        <div class="modal fade bd-example-modal1" id="myModalImages1">
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
        <div class="modal fade bd-example-modal2" id="myModalImages2">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Containers</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <h5>Resources</h5>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Image</th>
                                <th>CPU Request</th>
                                <th>Memory Request</th>
                            </tr>
                            </thead>
                            <tbody v-for="container in containers">
                            <tr>
                                <td>{{container.name}}</td>
                                <td>{{container.image}}</td>
                                <td>{{container.resources.requests.cpu}}</td>
                                <td>{{container.resources.requests.memory}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <h5>Volumes</h5>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Path</th>
                                <th>Read Only</th>
                            </tr>
                            </thead>
                            <tbody v-for="volume in volumes">
                            <tr>
                                <td>{{volume.name}}</td>
                                <td>{{volume.mountPath}}</td>
                                <td class="capitalize">{{volume.readOnly}}</td>
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
        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Pod</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <p style="color:red">Use only lowercase letters</p>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of pod"
                                v-model="pod.name"
                            />
                        </div>
                        <div class="form-group">
                            <label>Namespace</label>
                            <select class="form-control" v-model="pod.namespace">
                                <option value=" " disabled selected>Choose a namespace</option>
                                <option
                                    v-for="namespace in namespacesItems"
                                    :key="namespace.metadata.name"
                                    v-bind:value="namespace.metadata.name"
                                >{{ namespace.metadata.name }}
                                </option>
                            </select>
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
                                v-on:click.prevent="createPod()"
                                value="Create Pod"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <h1>Pods</h1>
        </div>
        <br/>
        <div class="form-group">
            <button
                type="button"
                class="btn btn-outline-dark"
                data-toggle="modal"
                data-target=".bd-example-modal"
                style="border-color: black;"
            ><i class="fa fa-plus" aria-hidden="true"></i>
                Create Pod
            </button>
        </div>
        <br>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Node</th>
                <th>Host IP</th>
                <th>Pod IP</th>
                <th>Status</th>
                <th>QoS Class</th>
                <th>Created</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody v-for="pod in pods">
            <tr>
                <td>{{pod.metadata.name}}</td>
                <td v-if="pod.spec.nodeName">{{pod.spec.nodeName}}</td>
                <td v-else>-</td>
                <td>{{pod.status.hostIP}}</td>
                <td>{{pod.status.podIP}}</td>
                <td>{{pod.status.phase}}</td>
                <td>{{pod.status.qosClass}}</td>
                <td>{{pod.metadata.managedFields[0].time}}</td>
                <td>
                    <select>
                        <option selected disabled>Select an option</option>
                        <option
                            data-toggle="modal"
                            data-target=".bd-example-modal1"
                            v-on:click="usedPorts(pod)"
                        >Used Ports
                        </option>
                        <option
                            data-toggle="modal"
                            data-target=".bd-example-modal2"
                            v-on:click="containersPod(pod)"
                        >Containers
                        </option>
                        <option
                            v-on:click="deletePod(pod.metadata.name)"
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
                pods: [],
                namespacesItems: [],
                pod: {
                    name: null,
                    namespace: this.$store.state.namespace
                },
                option: this.$store.state.namespace,
                ports: [],
                containers: [],
                volumes: []
            };
        },
        methods: {
            getPods() {
                axios.get(this.url + "/api/v1/namespaces/" + this.$store.state.namespace + "/pods").then(response => {
                    this.pods = response.data.items;
                    for (var i = 0; i < (this.pods).length; i++) {

                        var date = this.pods[i].metadata.managedFields[0].time.split("T");
                        this.pods[i].metadata.managedFields[0].time = date[0];
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
                this.getPods();
            },
            usedPorts(pod){
                this.ports = pod.spec.containers[0].ports;
            },
            containersPod(pod){
                this.containers = pod.spec.containers;
                this.volumes = pod.spec.containers[0].volumeMounts;
            },
            deletePod(pod) {
                axios.delete(this.url + "/api/v1/namespaces/" + this.$store.state.namespace + "/pods/" + pod)
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
                            this.getPods();
                            this.$vToastify.success("Pod deleted");
                            this.getPods();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Pod not deleted')
                });
            },
            createPod() {
                axios.post(this.url + "/api/v1/namespaces/" + this.pod.namespace + "/pods", {
                    kind: "Pod",
                    apiVersion: "v1",
                    metadata: {
                        name: this.pod.name,
                        namespace: this.pod.namespace,
                        labels: {
                            name: "nginx4"
                        }
                    },
                    spec: {
                        containers: [
                            {
                                name: "nginx",
                                image: "nginx",
                                ports: [{containerPort: 80}],
                                resources: {
                                    limits: {
                                        memory: "128Mi",
                                        cpu: "500m"
                                    }
                                }
                            }
                        ]
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
                            this.getPods();
                            this.$vToastify.success("Pod created");
                            this.getPods();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Pod not created')
                });
            },
        },
        computed: {
            isValid() {
                if (this.pod.name && this.pod.namespace != " ") {
                    return false;
                } else {
                    return true;
                }
            }
        }
        ,
        mounted() {
            this.getNameSpaces();
            this.getPods();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }
</style>
