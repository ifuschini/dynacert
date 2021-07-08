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
      <b-alert :show="showError" 
        variant="danger"  
        size="sm" 
        style="text-transform: uppercase;text-align: center;"
        >{{error}}</b-alert>
      <b-input placeholder="username" v-model="username" v-on:keyup.enter="auth()"/>
      <b-input-group class="mt-3">
          <template #append>
            <b-button variant="outline-secondary" style="font-size:10px;border:1px solid #ced4da;" v-on:click="showPassword=!showPassword"><b-icon :icon="(showPassword==true)?'eye-slash':'eye'"/></b-button>
          </template>
          <b-form-input placeholder="password" v-model="password" style="" :type="(showPassword==true)?'text':'password'" v-on:keyup.enter="auth()" ></b-form-input>
      </b-input-group>
    </b-card-text>

    <b-button variant="primary" class="loginbutton" v-on:click="auth()" size="sm" style="float:right">Log In</b-button>
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
            username: '',
            password: '',
            status: 'not_accepted',
            error: null,
            showError:false,
            showPassword: false,
        }
    },
    created() {
    },
    methods: {
        auth() {
            if (this.username=='') {
                this.error='username is empty'
                this.showError=true
                return false
            }
            if (this.password=='') {
                this.error='password is empty'
                this.showError=true
                return false
            }
            this.error=null
            this.showError=false
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
                                this.showError=true
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