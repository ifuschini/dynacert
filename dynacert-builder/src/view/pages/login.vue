<template>
<div>
  <b-card
    title="Dyna Login"
    img-src="images/logo.png"
    img-alt="Image"
    img-top
    tag="article"
    style="max-width: 20rem;"
    class="mb-1 center"
  >
    <b-card-text>
      <b-alert show variant="danger" v-if="error!=null">{{error}}</b-alert>
      <b-input placeholder="username" v-model="username"/>
      <b-input placeholder="password" v-model="password" style="margin-top:10px" type="password"/>
    </b-card-text>

    <b-button variant="primary" class="loginbutton" v-on:click="auth()">Login</b-button>
  </b-card>
</div>
</template>
<style scoped>
.center {
  margin: auto;
  width: 50%;
  padding: 10px;
  top:30vh;
}
.loginbutton {
  align-content: right;
}
</style>
<script>
import axios from 'axios'
export default {
    data() {
        return {
            username: null,
            password: null,
            status: 'not_accepted',
            error: null,
        }
    },
    created() {
    },
    methods: {
        auth() {
            this.error=null
            axios
            .post(this.config.serviceBaseUrl +  this.config.url.login,{
                            username: this.username,
                            password: this.password,
            })
                    .then(response => {
                      console.log(response.data)
                      localStorage.logedin='true'
                      this.$router.push({ name: 'manager'}).catch(err => {})

                    })
                    .catch (e=> {
                                this.error='Credential error';
                                this.$isAuthenticated.value=false
                                console.log(e)
                    })

        },
        ask() {
          alert('Non riesci ad entrare ? Chiedi in segreteria')
        }
    }
}
</script>