<template>
  <p>{{projectName}}</p>
</template>

<script type="text/javascript">
export default {
  props:['projectName'],
  data: function() {
    return {
      url: this.$store.state.url,
      project_name: null,
      user:{
        token: null
      },
      project:{

      },
    }
  },
  methods: {
    loginProject() {
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
                  password: this.$store.state.password
                }
              }
            },
            scope: {
              project: {
                domain: {
                  id: "default"
                },
                name: this.projectName
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
          this.$store.commit("setProject", this.project);
          this.$vToastify.success("Changed to project " + this.projectName);
          this.$router.push("/dashboard");

        }).catch(error =>{
          this.$vToastify.error("It wasn't possible to change the project");
        });
    },

  },
  mounted() {
    this.loginProject();
  }
};
</script>
