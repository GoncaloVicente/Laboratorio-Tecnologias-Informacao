<template>
    <div class="container login-container">

        <definirIP></definirIP>

        <div class="row">
            <div class="col-md-12 login-form-1">
                <div>
                    <img class="center" v-bind:src="'/images/logoLoginKub.png'" width=100 height=200/>
                </div>
                <br>
                <br>
                <br>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nome de utilizador"   v-model="credentials.name" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" v-model="credentials.password" />
                </div>
                <div class="form-group">
                    <input type="submit" class="btnSubmit" style="background-color: blue" value="Login" v-on:click.prevent="login()"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    import definirIP from "./definirIPKubernetes";


    export default {
        components: {
            "definirIP": definirIP,
        },
        data: function() {
            return {
                user: {
                    token: null
                },
                project:{

                },
                credentials: {
                    name: null,
                    password: null
                },
                header: new XMLHttpRequest(),
                url: this.$store.state.url,
                showMessage: false,
                message: ""
            };
        },
        methods: {
            login() {
                this.$store.commit("setToken", "no");
                this.$router.push("/dasboardKubernetes");
            },
        },
    };
</script>
<style media="screen">

</style>
