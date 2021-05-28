<template>
    <div class="titleLogo">
         <b-button variant="secondary" v-on:click="sideBar()" style="margin-left:5px"><b-icon icon="list" size="sm"/></b-button>
            <img src="images/logo.png" width="200" height="65" /> 
    </div>
</template>
<style scoped>
    .titleLogo {
        margin-bottom: 10px;
    }
</style>


<script>
import { serverBus } from '../main'

export default {
    
    methods: {
        sideBar() {
            serverBus.$emit('sideBar','toggled')
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