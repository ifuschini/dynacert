<template>
    <div>
  <b-navbar type="dark" variant="dark">
    <b-navbar-nav :class="defaultNavbarClass">
        <b-button size="sm" class="menuButton" v-on:click="clickBurgerMenu()"> <font-awesome-icon icon="bars" /></b-button>
        <b-nav-item href="#">Home</b-nav-item>
    </b-navbar-nav>
    <b-navbar-nav class="ml-auto">
        <b-nav-item v-on:click="esci()" right >Esci</b-nav-item>
      <!-- Navbar dropdowns -->
    </b-navbar-nav>
  </b-navbar>
</div>
</template>
<style scoped>
@media only screen and (max-width: 600px) {
  .menuButton {
    display: none;
  }
}

</style>

<script>
import { serverBus } from '../../main'
import axios from 'axios'


export default {
    created() {
        this.currentPath=this.$route.fullPath.split('/')[2]
        if (this.$route.params.ln) i18n.locale = this.$route.params.ln
        serverBus.$on('isVisibleBar', (isBarVisible) => {
            if (isBarVisible==true) {
                this.defaultNavbarClass='navigationBar-open'
            } else {
                this.defaultNavbarClass='navigationBar-close'
            }
        })

    },
    data(){ 
        return {
            currentPath: null,
            defaultNavbarClass:'navigationBar-close',
            languages: [
              "it",
              "en",
            ]
        }
    },
    watch: {
      $route() {
      }
    },    
    methods: {
        clickBurgerMenu() {
            serverBus.$emit('sideBar','toggled')
        },
        esci() {
            axios
                .get(this.config.serviceBaseUrl +  this.config.url.exit,{
                })
                .then(response => {
                  console.log(response.data)
                  this.$router.push({ path: '/pages/login'})
                })
                .catch(e => {
                    this.error = e;
            })

        }
    }
}
</script>