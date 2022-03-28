<template>
<div>

  <br>
    <div>
      <h1>Projects</h1>
    </div>
    <br>
</div>
</template>

<script type="text/javascript">



export default {
  data: function() {

    return{
      url: this.$store.state.url,
      projects: [],
      active_prj : this.$store.state.project,
      user:{
        token: null
      },


      };
    },
    methods: {
      getProjects() {
      axios
        .get(this.url + "/identity/v3/auth/projects", {
          headers: { "x-auth-token": this.$store.state.token }
        })
        .then(response => {
          this.projects = response.data.projects;
        });
    },
     changeProject(project) {
      axios
        .post(this.url + "/identity/v3/auth/tokens", {
          auth: {
            identity: {
              methods: ["password"],
              password: {
                user: {
                  name: this.$store.state.user.name,
                  domain: {
                    name: "Default"
                  },
                  password: "devstack"
                }
              }
            },
            scope: {
              project: {
                domain: {
                  id: "default"
                },
                name: project.name
              }
            }
          }
        })
        .then(response => {
          this.$store.commit("clearToken");
          this.$store.commit("clearProject");
          this.user = response.data.token.user;
          this.user.token = response.headers["x-subject-token"];
          this.$store.commit("setToken", this.user.token);
          this.$store.commit("setProject", project.id);
          this.$vToastify.success("Changed to project " + project.name);
          this.$router.push("/dashboard");
        });
    },


  },
  mounted() {
    this.getProjects();
  }
}
</script>
