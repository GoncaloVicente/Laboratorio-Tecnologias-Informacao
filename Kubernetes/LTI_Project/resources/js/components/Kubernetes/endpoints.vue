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
                        <h3 class="modal-title">Addresses</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th>Node Name</th>
                            </tr>
                            </thead>
                            <tbody v-for="address in addresses">
                            <tr>
                                <td v-if="address.ip">{{address.ip}}</td>
                                <td v-else>-</td>
                                <td v-if="address.nodeName != undefined">{{address.nodeName}}</td>
                                <td v-else>-</td>
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
        <br>
        <div>
            <h1>Endpoints</h1>
        </div>
        <br>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Manager</th>
                <th>Cluster Service</th>
                <th>Created</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody v-for="endpoint in endpoints">
            <tr>
                <td>{{endpoint.metadata.name}}</td>
                <td>{{endpoint.metadata.managedFields[0].manager}}</td>
                <td v-if="endpoint.metadata.labels['kubernetes.io/cluster-service']" class="capitalize">{{endpoint.metadata.labels['kubernetes.io/cluster-service']}}</td>
                <td v-else>-</td>
                <td>{{endpoint.metadata.managedFields[0].time}}</td>
                <td>
                    <select>
                        <option selected disabled>Select an option</option>
                        <option
                            data-toggle="modal"
                            data-target=".bd-example-modal"
                            v-on:click="portsEndPoints(endpoint)"
                        >Used ports
                        </option>
                        <option
                            data-toggle="modal"
                            data-target=".bd-example-modal1"
                            v-on:click="subsetsAddressesEndPoints(endpoint)"
                        >Subsets Addresses
                        </option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "endpoints",
        data: function () {
            return {
                url: this.$store.state.url,
                endpoints: [],
                namespacesItems: [],
                option: this.$store.state.namespace,
                ports: [],
                addresses: []
            };
        },
        methods: {
            getEndpoints() {
                axios.get(this.url + "/api/v1/namespaces/" + this.$store.state.namespace + "/endpoints").then(response => {
                    this.endpoints = response.data.items;
                    for (var i = 0; i < (this.endpoints).length; i++) {

                        var date = this.endpoints[i].metadata.managedFields[0].time.split("T");
                        this.endpoints[i].metadata.managedFields[0].time = date[0];
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
                this.getEndpoints();
            },
            portsEndPoints(endpoint){
                this.ports = [];
                this.ports = endpoint.subsets[0].ports;
            },
            subsetsAddressesEndPoints(endpoint){
                this.addresses = [];
                this.addresses = endpoint.subsets[0].addresses;
            },
        },
        mounted() {
            this.getNameSpaces();
            this.getEndpoints();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }
</style>
