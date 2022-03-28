<template>
    <div class="container login-container">
        <div class="row">
            <div class="col login-form-1">
                <div style="margin-left: auto; margin-right: auto; width: 8em; font-size: 30px;" class="row">
                    <p>Choose an app</p>
                </div>
                <br>
                <br>
                <div class="row">
                    <a class="col" v-on:click.prevent="goKubernetes()"><img class="center" v-bind:src="'/images/logoLoginKub.png'"></a>
                    <a class="col" v-on:click.prevent="goOpenstack()"><img class="center" v-bind:src="'/images/logologin.png'"></a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "goFor",
        methods: {
            goOpenstack() {
                this.$store.commit("setProjeto", "openstack");
                this.$router.push("/login");
            },
            goKubernetes() {
                this.$store.commit("setProjeto", "kubernetes");
                this.$router.push("/kuberneteslogin");

            },

        },
        mounted() {
            this.$store.commit("clearUser");
            this.$store.commit("clearNameSpace");
        }
    }
</script>

<style scoped>

</style>
