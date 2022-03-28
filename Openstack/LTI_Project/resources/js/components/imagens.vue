<template>
    <div>
        <br/>
        <div>
            <h1>Images</h1>
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
                Create Image
            </button>
        </div>

        <div class="modal fade bd-example-modal" id="myModalImages">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Create Image</h3><br>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <h3>Image Details</h3>
                        <p>Specify an image to upload to the Image service</p>
                        <div class="form-group">
                            <label>Name</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Name of image"
                                v-model="imagem.nome"
                            />
                        </div>
                        <div class="form-group">
                            <h3>Image Source</h3>
                            <label>URI</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="Uri of image"
                                v-model="imagem.uri"
                            />
                        </div>
                        <div class="form-group">
                            <label>Format</label>
                            <select class="form-control" id="selectFormat" v-model="imagem.formato">
                                <option value=" " disabled selected>Choose a format</option>
                                <option value="iso">ISO - Optical Disk Image</option>
                                <option value="ami">Ami - Amazon Kernel Image</option>
                                <option value="ari">ARI - Amazon Ramdisk Image</option>
                                <option value="aki">AKI - Amazon Kernel Image</option>
                                <option value="vhd">VHD - Virtual Hard Disk</option>
                                <option value="vmdk">VMDK - Virtual Machine Disk</option>
                                <option value="raw">RAW</option>
                                <option value="qcow2">QCOW2 - QEMU Emulator</option>
                                <option value="vdi">VDI - Virtual Disk Image</option>
                                <option value="ploop">PLOOP - Virtuozzo/Parallels Loopback Disk</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <h3>Image requirements</h3>
                            <label>Minimum Disk (GB)</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Minimum Disk (GB)"
                                v-model="imagem.min_disco"
                            />
                        </div>
                        <div class="form-group">
                            <label>Minimum RAM memory (MB)</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="number"
                                placeholder="Minimum RAM memory (MB)"
                                v-model="imagem.min_ram"
                            />
                        </div>
                        <div class="form-group">
                            <h3>Image Sharing</h3>
                            <label class="form-control" style="color: #212529;border: transparent;padding: 0px;">Visibility</label>
                            <button id="visibilidade_private" v-on:click="visibilidadeImagem('private')">Private
                            </button>
                            <button id="visibilidade_shared" v-on:click="visibilidadeImagem('shared')">Shared</button>
                            <button id="visibilidade_community" v-on:click="visibilidadeImagem('community')">Community
                            </button>
                        </div>
                        <div class="form-group">
                            <label class="form-control" style="color: #212529;border: transparent;padding: 0px;">Protected</label>
                            <button id="protecao_true" v-on:click="protecaoImagem('true')">Yes</button>
                            <button id="protecao_false" v-on:click="protecaoImagem('false')">No</button>
                        </div>
                        <br/>
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
                                v-on:click.prevent="criarImagem()"
                                value="Create Image"
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
                <th>Status</th>
                <th>Visibility</th>
                <th>Protected</th>
                <th>Disk Format</th>
                <th>Size</th>
            </tr>
            </thead>
            <tbody v-for="imagem in imagens">
            <tr>
                <td>{{imagem.name}}</td>
                <td class="capitalize">{{imagem.status}}</td>
                <td class="capitalize">{{imagem.visibility}}</td>
                <td class="capitalize">{{imagem.protected}}</td>
                <td class="uppercase">{{imagem.disk_format}}</td>
                <td>{{$options.filters.prettyBytes(imagem.size)}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    const Swal = require('sweetalert2');

    export default {
        name: "imagens",
        data: function () {
            return {
                url: this.$store.state.url,
                imagens: [],
                imagem: {
                    nome: null,
                    formato: " ",
                    min_disco: null,
                    min_ram: null,
                    visibilidade: null,
                    protecao: null,
                    uri: null
                },
            };
        },
        methods: {
            getImages: function () {
                axios.get(this.url + "/image/v2/images", {
                    headers: {"x-auth-token": this.$store.state.token}
                })
                    .then(response => {
                        this.imagens = response.data.images;
                    });
            },
            visibilidadeImagem: function (valor) {

                document.getElementById("visibilidade_shared").style.borderColor = null;
                document.getElementById("visibilidade_private").style.borderColor = null;
                document.getElementById("visibilidade_community").style.borderColor = null;
                document.getElementById("visibilidade_shared").style.backgroundColor = null;
                document.getElementById("visibilidade_private").style.backgroundColor = null;
                document.getElementById("visibilidade_community").style.backgroundColor = null;


                this.imagem.visibilidade = valor;
                document.getElementById("visibilidade_" + valor).style.borderColor = "gray";
                document.getElementById("visibilidade_" + valor).style.backgroundColor = "gray";

            },
            protecaoImagem: function (valor) {
                document.getElementById("protecao_true").style.borderColor = null;
                document.getElementById("protecao_false").style.borderColor = null;
                document.getElementById("protecao_false").style.backgroundColor = null;
                document.getElementById("protecao_true").style.backgroundColor = null;

                this.imagem.protecao = valor;
                document.getElementById("protecao_" + valor).style.borderColor = "gray";
                document.getElementById("protecao_" + valor).style.backgroundColor = "gray";
            },
            criarImagem() {
                if (this.imagem.protecao === "true") {
                    this.imagem.protecao = true;
                }
                if (this.imagem.protecao === "false") {
                    this.imagem.protecao = false;
                }

                axios
                    .post(
                        this.url + "/image/v2/images",
                        {
                            name: this.imagem.nome,
                            disk_format: this.imagem.formato,
                            min_disk: parseInt(this.imagem.min_disco),
                            min_ram: parseInt(this.imagem.min_ram),
                            protected: this.imagem.protecao,
                            visibility: this.imagem.visibilidade
                        },
                        {
                            headers: { "x-auth-token": this.$store.state.token }
                        }
                    )
                    .then(response => {
                        this.imageId = response.data.id;
                        axios
                            .post(
                                this.url +  "/image/v2/images/" + this.imageId + "/import",
                                {
                                    uri: this.image.uri
                                },
                                {
                                    headers: { "x-auth-token": this.$store.state.token }
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
                                    this.$vToastify.success('Image Uploaded!');

                                    this.imagem.nome = null;
                                    this.imagem.formato = null;
                                    this.imagem.min_disco = null;
                                    this.imagem.min_ram = null;
                                    this.imagem.protecao = null;
                                    this.imagem.visibilidade = null;

                                    this.getImages();
                                })
                            })
                            .catch(error => {

                            });
                    })
                    .catch(error => {

                    });
            },
        },
        computed: {
            isValid() {
                if (this.imagem.nome && this.imagem.uri && this.imagem.formato && this.imagem.min_disco && this.imagem.min_ram && this.imagem.visibilidade && this.imagem.protecao) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        mounted() {
            this.getImages();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }

    td.uppercase {
        text-transform: uppercase;
    }
</style>
