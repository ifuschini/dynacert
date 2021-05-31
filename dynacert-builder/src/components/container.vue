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