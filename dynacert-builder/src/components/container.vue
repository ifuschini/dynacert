<template>
    <div :class="'d-flex' + toggle" id="wrapper">
              <sidebar/>
            <div id="page-content-wrapper">
              <headerComponent/>
              <router-view class="info"/>
            </div>
            <info/>
           <Loader v-if="showLoader==true"/>
    </div>
</template>
<style lang="scss">
  @import '../assets/custom-vars.scss';
  @import '~bootstrap/scss/bootstrap.scss';
  @import '~bootstrap-vue/src/index.scss';
</style>
<style>
@media only screen and (max-width: 600px) {
  .info {
    visibility:collapse;
  }
}

.btn {
    padding: 7px 12px;
    font-size: 15px;
    border-radius: 7px;
    text-transform: uppercase;
}
.btn-sm {
    padding: 5px 10px;
    font-size: 10px;
    border-radius: 5px;
    text-transform: uppercase;
}
.form-control {
  font-size: 10px;
}



</style>


<script>
// @ts-nocheck
import { serverBus } from '../main'
import Loader from '../loader/animationLoader'
import info from '../info/info'
import headerComponent from './header'
import sidebar from './sidebar'
export default {
  name: "Container",
  components: {
    Loader,
    headerComponent,
    sidebar,
    info,
  },
  data() {
      return {
        showLoader: false,
        toggle:'',
      }
  },
  created() {
    serverBus.$on('showLoader', (msg) => {
       console.log('showLoader:' + msg)
        this.showLoader=msg
    })
    serverBus.$on('sideBar', (msg) => {
            if (this.toggle == '') this.toggle=' ' + msg
            else this.toggle=''
    })
},
   
}
</script>