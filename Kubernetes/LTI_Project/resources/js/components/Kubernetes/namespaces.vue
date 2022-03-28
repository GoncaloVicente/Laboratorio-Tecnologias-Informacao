<template>
    <div>
        <br/>
        <div>
            <h1>Namespaces</h1>
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
                Create Namespace
            </button>
        </div>
        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Namespace</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of namespace"
                                v-model="namespace.name"
                            />
                        </div>
                        <div class="form-group">
                            <label>Label</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Label of namespace"
                                v-model="namespace.label"
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
                                v-on:click.prevent="createNamespace()"
                                value="Create Namespace"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Labels</th>
                <th>Status</th>
                <th>Created</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody v-for="namespace in namespacesItems">
            <tr>
                <td>{{ namespace.metadata.name }}</td>
                <td>{{ namespace.metadata.labels['kubernetes.io/metadata.name']}}</td>
                <td>{{ namespace.status.phase }}</td>
                <td>{{namespace.metadata.managedFields[0].time}}</td>
                <td>
                    <select>
                        <option selected disabled>Select an option</option>
                        <option
                            v-on:click="deleteNamespace(namespace.metadata.name)"
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
        name: "namespaces",
        data: function () {
            return {
                url: this.$store.state.url,
                namespacesItems: [],
                namespace: {
                    name: null,
                    label: null
                },
            };
        },
        methods: {
            getNameSpaces() {
                axios.get(this.url + "/api/v1/namespaces").then(response => {
                    this.namespacesItems = response.data.items;
                    for (var i = 0; i < (this.namespacesItems).length; i++) {

                        var date = this.namespacesItems[i].metadata.managedFields[0].time.split("T");
                        this.namespacesItems[i].metadata.managedFields[0].time = date[0];
                    }
                });
            },
            deleteNamespace(namespace) {
                axios.delete(this.url + "/api/v1/namespaces/" + namespace)
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
                            this.getNameSpaces();
                            this.$vToastify.success("Namespace deleted");
                            this.getNameSpaces();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Namespace not deleted')
                });
            },
            createNamespace() {
                axios.post(this.url + "/api/v1/namespaces", {
                        "apiVersion": "v1",
                        "kind": "Namespace",
                        "metadata": {
                            "name": this.namespace.name,
                            "labels": {
                                "name": this.namespace.label
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
                            this.getNameSpaces();
                            this.$vToastify.success("Namespace created");
                            this.getNameSpaces();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Namespace not created')
                });
            },
        }
        ,
        computed: {
            isValid() {
                if (this.namespace.name && this.namespace.label) {
                    return false;
                } else {
                    return true;
                }
            }
        }
        ,
        mounted() {
            this.getNameSpaces();
        }
    }
</script>

<style scoped>

</style>
