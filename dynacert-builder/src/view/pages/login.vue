<template>
<div>
  <b-card
    title=""
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
      <b-input-group class="mt-3">
          <template #append>
            <b-button variant="outline-secondary" style="font-size:10px;border:1px solid #ced4da;" v-on:click="showPassword=!showPassword"><b-icon :icon="(showPassword==true)?'eye-slash':'eye'"/></b-button>
          </template>
          <b-form-input placeholder="password" v-model="password" style="" :type="(showPassword==true)?'text':'password'"></b-form-input>
      </b-input-group>
    </b-card-text>

    <b-button variant="primary" class="loginbutton" v-on:click="auth()" size="sm">Log In</b-button>
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
            showPassword: false,
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