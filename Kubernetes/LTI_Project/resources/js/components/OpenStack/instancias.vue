<template>
    <div>
        <br/>
        <div>
            <h1>Instances</h1>
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
                Create Instances
            </button>
        </div>
        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Instance</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <h3>Details</h3>
                            <label>Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of instance"
                                v-model="instance.name"
                            />
                        </div>
                        <div class="form-group">
                            <label>Availability Zone</label>
                            <select class="form-control" v-model="instance.availabilityZone">
                                <option value=" " disabled selected>Choose an availability zone</option>
                                <option
                                    v-for="az in availability_zones"
                                    :key="az.zoneName"
                                    v-bind:value="az.zoneName"
                                >{{ az.zoneName }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <h3>Source</h3>
                            <label>Image</label>
                            <select class="form-control" v-model="instance.image">
                                <option value=" " disabled selected>Choose an image</option>
                                <option
                                    v-for="image in images"
                                    :key="image.id"
                                    v-bind:value="image.id"
                                >{{ image.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <h3>Flavor</h3>
                            <label>Flavors</label>
                            <select class="form-control" v-model="instance.flavor">
                                <option value=" " disabled selected>Choose a flavor</option>
                                <option
                                    v-for="flavor in flavors"
                                    :key="flavor.id"
                                    v-bind:value="flavor.id"
                                >{{ flavor.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <h3>Network</h3>
                            <label>Networks</label>
                            <select class="form-control" v-model="instance.network">
                                <option value=" " disabled selected>Choose a network</option>
                                <option
                                    v-for="network in networks"
                                    :key="network.id"
                                    v-bind:value="network.id"
                                >{{ network.name }}
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
                                v-on:click.prevent="criarInstancia()"
                                value="Create Instance"
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
                <th>Instance Name</th>
                <th>Status</th>
                <th>Flavor</th>
                <th>Key Pair</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody v-for="instance in instances" :key="instance.id">
            <tr>
                <td v-if="!instance.name && !edit">-</td>
                <td v-else-if="edit == instance.id"><input v-model="instance.name">&nbsp;<i class="fa fa-floppy-o"
                                                                                            aria-hidden="true"
                                                                                            v-on:click="instanceEdit(instance)"></i>
                </td>
                <td v-else>{{ instance.name}}</td>
                <td v-if="instance.status === 'ERROR'">Error</td>
                <td v-if="instance.status === 'SHUTOFF'">Shutoff</td>
                <td v-if="instance.status === 'PAUSED'">Paused</td>
                <td v-if="instance.status === 'SUSPENDED'">Suspended</td>
                <td v-if="instance.status === 'ACTIVE'">Active</td>
                <td>
                    <div v-for="flavor in flavors" :key="flavor.id">
                        <a v-if="flavor.id === instance.flavor.id">{{flavor.name}}</a>
                    </div>
                </td>
                <td>
                    <div v-for="keypairs in keypairs" :key="keypairs.id">
                        <a v-if="keypairs.keypair.name != 'KeyPair'">{{ keypairs.keypair.name }}</a>
                        <a v-else> - </a>
                    </div>
                </td>
                <td>
                    <select>
                        <option selected disabled>Select an option</option>
                        <option
                            v-show="instance.status == 'ACTIVE'"
                            v-on:click="viewconsole(instance)"
                        >Console
                        </option>
                        <option
                            v-on:click="editar(instance)"
                        >Edit
                        </option>
                        <option
                            v-on:click="deleteInstance(instance)"
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
        name: "instancias",
        data: function () {
            return {
                url: this.$store.state.url,
                instances: [],
                images: [],
                flavors: [],
                keypairs: [],
                networks: [],
                edit: false,
                availability_zones: [],
                instance: {
                    id: "",
                    name: "",
                    availabilityZone: " ",
                    image: " ",
                    flavor: " ",
                    network: " "
                },
            };
        },
        methods: {
            getKeyPairs: function () {
                axios.get(this.url + "/compute/v2.1/os-keypairs", {
                    headers: {
                        'x-auth-token': this.$store.state.token,
                        'x-openstack-nova-api-version': '2.2'
                    }
                }).then(response => {
                    this.keypairs = response.data.keypairs;
                })
            },
            getInstances() {
                axios
                    .get(this.url + "/compute/v2.1/servers/detail", {
                        headers: {"x-auth-token": this.$store.state.token}
                    })

                    .then(response => {
                        this.instances = response.data.servers;
                    });
            },
            getAvailabilityZones() {
                axios.get(this.url + "/compute/v2.1/os-availability-zone", {
                    headers: {"x-auth-token": this.$store.state.token}
                }).then(response => {
                    this.availability_zones = response.data.availabilityZoneInfo;
                })
            },
            getFlavors: function () {
                axios
                    .get(this.url + "/compute/v2.1/flavors/detail", {
                        headers: {"x-auth-token": this.$store.state.token}
                    })

                    .then(response => {
                        this.flavors = response.data.flavors;
                    });
            },
            getImages: function () {
                axios
                    .get(this.url + "/image/v2/images", {
                        headers: {"x-auth-token": this.$store.state.token}
                    })

                    .then(response => {
                        this.images = response.data.images;
                    });
            },
            getNetworks: function () {
                axios.get(this.url + ":9696/v2.0/networks", {
                    headers: {"x-auth-token": this.$store.state.token}
                })
                    .then(response => {
                        this.networks = response.data.networks;
                    });
            },
            viewconsole(instance) {
                axios.post(
                    this.url + "/compute/v2.1/servers/" + instance.id + "/action",
                    {
                        "os-getVNCConsole": {
                            type: "novnc"
                        }
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                            "x-auth-token": this.$store.state.token
                        }
                    }
                )
                    .then(response => {
                        this.console_url = response.data.console.url;
                        window.open(this.console_url);
                    });
            },
            editar: function (instance) {
                this.edit = instance.id;
            },
            instanceEdit: function (instance) {
                this.edit = false;

                this.instance.id = instance.id;
                this.instance.name = instance.name;

                axios.put(
                    this.url + "/compute/v2.1/servers/" + this.instance.id,
                    {
                        server: {
                            name: this.instance.name
                        }
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                            "x-auth-token": this.$store.state.token
                        }
                    }
                )
                    .then(response => {
                        let timerInterval
                        Swal.fire({
                            html: 'Working ...',
                            timer: 3000,
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
                            this.$vToastify.info("Instance edit successfully!");
                            this.getInstances();
                        })
                    }).catch(error => {
                    this.$vToastify.error("Instance not edited! An error Ocurred");
                });
            },
            deleteInstance: function (instance) {
                axios.delete(this.url + "/compute/v2.1/servers/" + instance.id, {
                    headers: {"x-auth-token": this.$store.state.token}
                })
                    .then(response => {

                        let timerInterval
                        Swal.fire({
                            html: 'Working ...',
                            timer: 3000,
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
                            this.$vToastify.success("Instance Deleted With Success");
                            this.getInstances();
                        })
                    }).catch(error => {
                    this.$vToastify.error("Instance not deleted! An error Ocurred");
                });
            },
            criarInstancia: function () {
                axios.post(
                        this.url + "/compute/v2.1/servers",
                        {
                            server: {
                                flavorRef: this.instance.flavor,
                                name: this.instance.name,
                                availability_zone: this.instance.availabilityZone,

                                networks: [
                                    {
                                        uuid: this.instance.network
                                    }
                                ],
                                imageRef: this.instance.image,
                            }
                        },
                        {
                            headers: {
                                "Content-Type": "application/json",
                                "x-auth-token": this.$store.state.token
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
                            this.getInstances();
                            this.$vToastify.info("Instance Created");
                            this.getInstances();
                        })
                    }).catch(error => {
                    this.$vToastify.error('Instance not created')
                });
            }
        }
        ,
        computed: {
            isValid() {
                if (this.instance.name && this.instance.availability_zone != " " && this.instance.image != " " && this.instance.flavor != " " && this.instance.network != " ") {
                    return false;
                } else {
                    return true;
                }
            }
        }
        ,
        mounted() {
            this.getInstances();
            this.getFlavors();
            this.getImages();
            this.getKeyPairs();
            this.getAvailabilityZones();
            this.getNetworks();
        }
    }
</script>

<style scoped>

</style>
