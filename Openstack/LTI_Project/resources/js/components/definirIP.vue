<template>
    <div>
        <h1
            class="btn btn-outline-dark"
            data-toggle="modal"
            data-target=".bd-example-modal-xs"
            id="ipServer"
            style="background-color: transparent;border-color: transparent"
        ></h1>
        <div class="modal fade bd-example-modal-xs" id="myModalImages" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Endereço Servidor OpenStack</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>IP Servidor OpenStack:</label>
                            <input
                                class="form-control mb-2 mr-sm-2 mb-sm-0"
                                type="text"
                                placeholder="IP"
                                v-model="ip"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input
                            type="submit"
                            class="btn btn-success"
                            data-dismiss="modal"
                            v-on:click.prevent="defineIPServer()"
                            value="Guardar"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                ip: this.$store.state.ip,
                url: null,
            };
        },
        methods: {
            defineIPServer() {
                this.url = "http://" + this.ip;
                this.$store.commit("setIP", this.ip);
                this.$store.commit("setUrl", this.url);
                this.$vToastify.success("Defined IP " + this.ip);

            },
        },
        mounted() {
            document.getElementById("ipServer").click();
        },
    };
</script>


<style scoped>

</style>
