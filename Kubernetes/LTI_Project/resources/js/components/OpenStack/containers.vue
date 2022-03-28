<template>
    <div>
        <br/>
        <div>
            <h1>Containers</h1>
        </div>
        <br>
        <div class="form-group">

            <button
                type="button"
                class="btn btn-outline-dark"
                data-toggle="modal"
                data-target=".bd-example-modal"
                style="border-color: black;"
            ><i class="fa fa-plus" aria-hidden="true"></i>
                Create Container
            </button>
        </div>

        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Container</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of container"
                                v-model="container.nome"
                            />
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <select class="form-control" v-model="container.image_id">
                                <option value=" " disabled selected>Choose an image</option>
                                <option
                                    v-for="image in imagens"
                                    :key="image.id"
                                    v-bind:value="image.id"
                                >{{ image.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>CPU</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Number of cpu's"
                                v-model="container.numCpu"
                            />
                        </div>
                        <div class="form-group">
                            <label>Memory (MB)</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Memory of container"
                                v-model="container.memory"
                            />
                        </div>
                        <div class="form-group">
                            <label>Availability Zone</label>
                            <select class="form-control" v-model="container.availabilityZone">
                                <option value=" " disabled selected>Choose an availability zone</option>
                                <option
                                    v-for="az in availability_zones"
                                    :key="az.zoneName"
                                    v-bind:value="az.zoneName"
                                >{{ az.zoneName }}
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
                                v-on:click.prevent="criarContainer()"
                                value="Create Container"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Addresses</th>
                <th>Number of CPU</th>
                <th>RAM Memory</th>
            </tr>
            </thead>
            <tbody v-for="container in containers" :key="container.id">
            <tr>
                <td v-if="container.name == ''">{{ container.id }}</td>
                <td v-else>{{container.name}}</td>
                <td>{{ container.image}}</td>
                <td v-if="container.addresses">{{container.addresses}}</td>
                <td v-else>Not defined</td>
                <td>{{container.cpu}}</td>
                <td>{{container.memory}} MB</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    const Swal = require('sweetalert2');

    export default {
        name: "containers",
        data: function () {
            return{
                url: this.$store.state.url,
                containers: [],
                imagens: [],
                availability_zones: [],
                container: {
                    nome: null,
                    image_id: " ",
                    numCpu: null,
                    memory: null,
                    availabilityZone: " "
                }
            }
        },
        methods:{
            getContainers() {
                axios.get(this.url + "/container/v1/containers", {
                        headers: { "x-auth-token": this.$store.state.token }
                    })
                    .then(response => {
                        this.containers = response.data.containers;
                    });
            },
            getImages: function () {
                axios.get(this.url + "/image/v2/images", {
                    headers: {"x-auth-token": this.$store.state.token}
                })
                    .then(response => {
                        this.imagens = response.data.images;
                    });
            },
            getAvailabilityZones() {
                axios.get(this.url + "/compute/v2.1/os-availability-zone", {
                    headers: {"x-auth-token": this.$store.state.token}
                }).then(response => {
                    this.availability_zones = response.data.availabilityZoneInfo;
                })
            },
            criarContainer: function () {
                axios.post(
                        this.url + "/container/v1/containers",
                        {

                            name: this.container.name,
                            image: this.container.image_id,
                            cpu: this.container.numCpu,
                            memory: this.container.memory,
                            availability_zone: this.container.availabilityZone
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
                            this.$vToastify.show("Container Created");
                            this.getContainers();
                        })
                    }).catch(error =>{
                    this.$vToastify.error("Container not created!")
                });
            }
        },
        computed: {
            isValid() {
                if (this.container.nome && this.volume.image_id != " " && this.container.numCpu && this.container.memory) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        mounted() {
            this.getContainers();
            this.getImages();
            this.getAvailabilityZones();
        }
    }
</script>

<style scoped>

</style>
