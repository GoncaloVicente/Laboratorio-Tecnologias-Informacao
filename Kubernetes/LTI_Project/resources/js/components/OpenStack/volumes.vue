<template>
    <div>
        <br/>
        <div>
            <h1>Volume</h1>
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
                Create Volume
            </button>
        </div>

        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Volume</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of volume"
                                v-model="volume.nome"
                            />
                        </div>
                        <div class="form-group">
                            <label>Size (GiB)</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Size of volume"
                                v-model="volume.tamanho"
                            />
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <select class="form-control" v-model="volume.image_id">
                                <option value=" " disabled selected>Choose an image</option>
                                <option
                                    v-for="image in imagens"
                                    :key="image.id"
                                    v-bind:value="image.id"
                                >{{ image.name }}
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
                                v-on:click.prevent="criarVolume()"
                                value="Create Volume"
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
                <th>Size</th>
                <th>Description</th>
                <th>Status</th>
                <th>Type</th>
                <th>Availability Zone</th>
                <th>Bootable</th>
                <th>Encrypted</th>
            </tr>
            </thead>
            <tbody v-for="volume in volumes" :key="volume.id">
            <tr>
                <td v-if="volume.name == ''">{{ volume.id }}</td>
                <td v-else>{{volume.name}}</td>
                <td>{{ volume.size}} GiB</td>
                <td style="padding-left: 3%" v-if="volume.description == null || volume.description == ''">-</td>
                <td v-else>{{volume.description}}</td>
                <td class="capitalize">{{volume.status}}</td>
                <td>{{volume.volume_type}}</td>
                <td>{{volume.availability_zone}}</td>
                <td v-if="volume.bootable === 'true'">Yes</td>
                <td v-else>No</td>
                <td v-if="volume.encrypted == false">No</td>
                <td v-else>Yes</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    const Swal = require('sweetalert2');

    export default {
        name: "volumes",
        data: function () {
            return {
                url: this.$store.state.url,
                volumes: [],
                volume: {
                    nome: "",
                    tamanho: "",
                    image_id: " "
                },
                imagens: []
            };
        },
        methods: {
            getVolumes() {
                axios.get(this.url + "/volume/v3/" + this.$store.state.project + "/volumes/detail",
                    {
                        headers: {"x-auth-token": this.$store.state.token}
                    }
                )
                    .then(response => {
                        this.volumes = response.data.volumes;
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
            criarVolume() {
                axios.post(this.url + "/volume/v3/" + this.$store.state.project + "/volumes",
                    {
                        volume: {
                            size: this.volume.tamanho,
                            availability_zone: null,
                            source_volid: null,
                            description: null,
                            multiattach: false,
                            snapshot_id: null,
                            backup_id: null,
                            name: this.volume.nome,
                            imageRef: this.volume.image_id,
                            volume_type: null,
                            metadata: {},
                            consistencygroup_id: null
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
                            this.$vToastify.success("Volume Created");
                            this.getVolumes();
                        })
                    }).catch(error => {
                    this.$vToastify.error("Volume not created! Make sure the image is active!");
                });
                this.nome = null;
                this.tamanho = null;
                this.image_id = " ";
            }
        },
        computed: {
            isValid() {
                if (this.volume.nome && this.volume.tamanho && this.volume.image_id != " ") {
                    return false;
                } else {
                    return true;
                }
            }
        },
        mounted() {
            this.getVolumes();
            this.getImages();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }
</style>
