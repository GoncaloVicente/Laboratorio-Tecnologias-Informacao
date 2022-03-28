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
                this.$vToastify.info("Sessão terminada!");
                this.$router.push('/login');
            }
        },
        mounted(){
            this.logout();
        }
    }
</script>
