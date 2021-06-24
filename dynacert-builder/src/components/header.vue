<template>
    <div class="titleLogo info">
         <b-button variant="secondary" v-on:click="sideBar()" style="margin-left:5px"><b-icon icon="list" size="sm"/></b-button>
            <img src="images/logo.png" width="200" height="65" /> 
         <b-button variant="secondary" v-on:click="logout()" class="logout">Logout</b-button>
    </div>
</template>
<style scoped>
    .titleLogo {
        margin-bottom: 10px;
    }
@media only screen and (max-width: 600px) {
  .info {
    visibility:collapse;
  }
}
.logout {
    margin-top:10px;
    margin-left:5px;
    margin-right:5px;
    float:right;
}



</style>


<script>
import { serverBus } from '../main'
import axios from 'axios'

export default {
    
    methods: {
        sideBar() {
            serverBus.$emit('sideBar','toggled')
        },
        logout() {
            axios
                .get(this.config.serviceBaseUrl + this.config.url.logout ,{
                params: {
                }
                }
                )
                .then(response => {
                    this.dynaLogout(this)
                })
                .catch(e => {
                    this.dynaLogout(this)
            })
        }
    }
}
</script>

<style scoped>
.titoloLogo {
    font-family: ConcertOne;
    font-size: 3rem;
    color:brown;
}

</style>