<template>
    <div>
        <br>
        <div>
            <h1>Nodes</h1>
        </div>
        <br/>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Opertating System</th>
                <th>osImage</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody v-for="node in nodes">
            <tr>
                <td>{{node.metadata.name}}</td>
                <td>{{node.status.conditions[4].status}}</td>
                <td class="capitalize">{{node.status.nodeInfo.operatingSystem}}</td>
                <td>{{node.status.nodeInfo.osImage}}</td>
                <td>{{node.metadata.managedFields[0].time}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "nodes",
        data: function () {
            return {
                url: this.$store.state.url,
                nodes: []
            };
        },
        methods: {
            getNodes() {
                axios.get(this.url + "/api/v1/nodes")
                    .then(response => {
                        this.nodes = response.data.items;

                        for(var i = 0; i<(this.nodes).length; i++){

                            var date = this.nodes[i].metadata.managedFields[0].time.split("T");
                            this.nodes[i].metadata.managedFields[0].time = date[0];
                        }
                    });
            },
        },
        mounted() {
            this.getNodes();
        }
    }
</script>

<style scoped>
    td.capitalize {
        text-transform: capitalize;
    }
</style>
