<template>
    <div>
        <div>
        <center>
           <span v-on:click="goHome()"> <img src="images/logo.png" style="margin-bottom:20px"/></span>
            </center>
          <router-view/>
          <br/><br/>
                <template>
       <div class="d-flex  align-items-center px-3 py-2 footer-cookie" v-if="accepted==false">
        <strong class="mr-auto">We use cookies or similar technologies as specified in the <u>
        <span v-on:click="cookiePolicy()">cookie policy</span></u></strong>
        <b-button style="float:right!important" size="sm" variant="success" v-on:click="acceptedAction()">Accept</b-button>
       </div>

       <div class="d-flex bg-dark text-light align-items-center px-3 py-2 footer" style="">
        <strong class="mr-auto">Idel Fuschini - DynaCert</strong>
        <span style="float:right">v{{config.version}}</span>
       </div>
      </template>
        </div>
        <loader v-if="showLoader==true"/>

    </div>
</template>
<style lang="scss">
  @import '../assets/custom-vars.scss';
  @import '~bootstrap/scss/bootstrap.scss';
  @import '~bootstrap-vue/src/index.scss';

</style>
<script>
import { serverBus } from '../main'
import loader from '../loader/loader'
export default {
  components: {
    loader,
  },
  data() {
      return {
        showLoader: false,
        accepted:false,
      }
  },
  created() {
    console.log('accepted')
    serverBus.$on('showLoader', (msg) => {
        this.showLoader=msg
    })
    localStorage.removeItem('surveyConfig')
    localStorage.removeItem('pdfBack')
    localStorage.removeItem('configPdf')
    if(localStorage.accepted) this.accepted=true

  },
  methods: {
    goHome() {
      this.$router.push({ name: 'list'})
    }, 
    acceptedAction() {
      localStorage.accepted='true'
      this.accepted=true
    },
    cookiePolicy() {
      this.$router.push({ name: 'Cookie'})
    }, 

  }
   
}
</script>