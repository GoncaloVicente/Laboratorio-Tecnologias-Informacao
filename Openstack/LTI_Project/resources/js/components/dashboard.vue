<template>
    <div class="col-md-12">
        <br>
        <h1>Overview</h1>
        <br>
        <h3>Limits Summary</h3>
        <h5>Computing</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Instances</span>
                        <span class="info-box-number">Used {{estatisticasCompute.totalInstancesUsed}} of {{estatisticasCompute.maxTotalInstances}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">vCPUs</span>
                        <span class="info-box-number">Used {{estatisticasCompute.totalCoresUsed}} of {{estatisticasCompute.maxTotalCores}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">RAM</span>
                        <span class="info-box-number">Used {{estatisticasCompute.totalRAMUsed}} B of {{estatisticasCompute.maxTotalRAMSize}} B</span>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <h5>Volume</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Volume</span>
                        <span class="info-box-number">Used {{estatisticasVolume.totalVolumesUsed}} of {{estatisticasVolume.maxTotalVolumes}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Snapshots of Volume</span>
                        <span class="info-box-number">Used {{estatisticasVolume.totalSnapshotsUsed}} of {{estatisticasVolume.maxTotalSnapshots}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Storage of Volume</span>
                        <span class="info-box-number">Used {{estatisticasVolume.totalGigabytesUsed}} GB of {{estatisticasVolume.maxTotalVolumeGigabytes}} GB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">

    export default {
        data: function () {
            return {
                url: this.$store.state.url,
                projects: [],
                instances: [],
                images: [],
                flavors: [],
                project_id: "",
                project_name: "",
                estatisticasCompute: [],
                estatisticasVolume: []
            };
        },
        methods: {
            getImages: function () {
                axios.get(this.url + "/image/v2/images", {
                    headers: {'x-auth-token': this.$store.state.token}
                })

                    .then(response => {
                        this.images = response.data.images;
                    })
            },

            getProjects() {
                axios
                    .get(this.url + "/identity/v3/auth/projects", {
                        headers: {"x-auth-token": this.$store.state.token}
                    })
                    .then(response => {
                        this.projects = response.data.projects;
                        this.$store.commit("setProjectNames", this.projects);
                    });
            },
            getProjectID() {
                axios
                    .get(this.url + "/identity/v3/auth/tokens", {
                        headers: {
                            "x-auth-token": this.$store.state.token,
                            "x-subject-token": this.$store.state.token
                        }
                    })
                    .then(response => {
                        this.project_id = response.data.token.project.id;
                        this.project_name = response.data.token.project.name;
                        this.$store.commit("setProject", this.project_id);
                        this.$store.commit("setProjectName", this.project_name);
                    });
            },
            getEstatisticasCompute() {
                axios
                    .get(this.url + "/compute/v2.1/limits", {
                        headers: {
                            "x-auth-token": this.$store.state.token
                        }
                    })
                    .then(response => {
                        this.estatisticasCompute = response.data.limits.absolute;
                    });
            },
            getEstatisticasVolume(){
                axios
                    .get(this.url + "/volume/v3/" + this.$store.state.project + "/limits", {
                        headers: {
                            "x-auth-token": this.$store.state.token
                        }
                    })
                    .then(response => {
                        this.estatisticasVolume = response.data.limits.absolute;
                    });
            }
        },
        mounted() {
            this.getProjects();
            this.getProjectID();
            this.getImages();
            this.getEstatisticasCompute();
            this.getEstatisticasVolume();
        },
    };
</script>

<style media="screen">
    .dropbtn {
        background-color: #4caf50;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    Change color of dropdown links on hover *

    /
    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>
