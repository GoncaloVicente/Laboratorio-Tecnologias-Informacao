<template>

</template>

<script>
    export default {

        data: function() {
            return {
                url: this.$store.state.url,
            };
        },
        methods:{
            logout(){
                this.$store.commit("clearToken");
                // this.$store.commit("clearProjeto");
                this.$vToastify.info("Sessão terminada!");
                this.$router.push('/');
            }
        },
        mounted(){
            this.logout();
        }
    }
</script>
