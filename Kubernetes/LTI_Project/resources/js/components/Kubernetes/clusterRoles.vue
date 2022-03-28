<template>
    <div>
        <br>
        <div>
            <h1>Cluster Roles</h1>
        </div>
        <br/>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Manager</th>
                <th>Operation</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody v-for="clusterRole in clusterRoles">
            <tr>
                <td>{{clusterRole.metadata.name}}</td>
                <td>{{clusterRole.metadata.managedFields[0].manager}}</td>
                <td>{{clusterRole.metadata.managedFields[0].operation}}</td>
                <td>{{clusterRole.metadata.creationTimestamp}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "clusterRoles",
        data: function () {
            return {
                url: this.$store.state.url,
                clusterRoles: []
            };
        },
        methods: {
            getClusterRoles() {
                axios.get(this.url + "/apis/rbac.authorization.k8s.io/v1/clusterroles")
                    .then(response => {
                        this.clusterRoles = response.data.items;

                        for(var i = 0; i<(this.clusterRoles).length; i++){

                            var date = this.clusterRoles[i].metadata.creationTimestamp.split("T");
                            this.clusterRoles[i].metadata.creationTimestamp = date[0];
                        }
                    });
            },
        },
        mounted() {
            this.getClusterRoles();
        }
    }
</script>

<style scoped>

</style>
