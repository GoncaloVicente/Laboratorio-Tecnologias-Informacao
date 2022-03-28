<template>
    <div class="container login-container">

        <definirIP></definirIP>

        <div class="row">
            <div class="col-md-12 login-form-1">
                <div>
                    <img class="center" v-bind:src="'/images/logologin.png'" width=100 height=200/>
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
                    <input type="submit" class="btnSubmit" value="Login" v-on:click.prevent="login()"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    import definirIP from "./definirIP";


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
                axios.post(
                        this.url + "/identity/v3/auth/tokens",
                        {
                            auth: {
                                identity: {
                                    methods: ["password"],
                                    password: {
                                        user: {
                                            name: this.credentials.name,
                                            domain: {
                                                name: "Default"
                                            },
                                            password: this.credentials.password
                                        }
                                    }
                                },
                                scope: {
                                    project: {
                                        domain: {
                                            id: "default"
                                        },
                                        name: "demo"
                                    }
                                }
                            }
                        },
                        {
                            headers: {
                                "Content-Type": "application/json"
                            }
                        }
                    )
                    .then(response => {
                        this.user = response.data.token.user;
                        this.user.token = response.headers["x-subject-token"];
                        this.project = response.data.token.project;
                        this.$store.commit("setPassword", this.credentials.password);
                        this.$store.commit("setToken", this.user.token);
                        this.$store.commit("setProject", this.project);

                        this.$router.push("/dashboard");
                    })
                    .then(response => {
                        this.$store.commit("setUser", this.user);
                    })
                    .catch(error => {
                        this.$vToastify.info("Credênciais Inválidas!");
                        this.showMessage = true;
                    });
            },
        },
    };
</script>
<style media="screen">

</style>
