<template>
    <div>
        <div :class="'d-flex' + toggle" id="wrapper">
        <sideBar/>
        <div id="page-content-wrapper">
          <navigationBar/>
          <router-view/>
        </div>
        </div>
    </div>
</template>
<style lang="scss">
  // Import custom SASS variable overrides, or alternatively
  // define your variable overrides here instead
  @import '../../assets/custom-vars.scss';

  // Import Bootstrap and BootstrapVue source SCSS files
  @import '~bootstrap/scss/bootstrap.scss';
  @import '~bootstrap-vue/src/index.scss';

  // General style overrides and custom classes
  body {
    margin: 0;
  }

  .my-widget {
    color: var(--danger);
  }
</style>
<script>
import navigationBar from "./NavigationBar"
import sideBar from "./SideBar"
import { serverBus } from '../../main'

export default { 
    components: {
        navigationBar,
        sideBar,
    },
    data () {
      return {
        toggle: '',
      }
    },
    created() {
        serverBus.$on('sideBar', (msg) => {
                if (this.toggle == '') this.toggle=' ' + msg
                else this.toggle=''
        })

    }
}
</script>