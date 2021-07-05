<template>
    <div class="border-right info" id="sidebar-wrapper" ref="sidebarWrapper">
      <div class="sidebar-heading" >
      Menu 
      </div>
      <hr/>
      <b-list-group>
        <b-list-group-item 
          v-for="(item, index) in menu" v-bind:key="index" 
          v-on:click="$router.push({ path: '/' +  item.link})" 
          :active="$router.currentRoute.path=='/' + item.link">
          <font-awesome-icon :icon="item.icon" class=" iconClass" />
          <span v-if="showLabel" class="itemSidebar">{{item.name}}</span>
        </b-list-group-item>
      </b-list-group>
      </div>
</template>
<style scoped>
.list-group-item {
    border: 0px;
    border-color:#f8f9fa !important;
}
.list-group-item.active {
  z-index: 2;
  color: #fff;
  background-color:rgb(82, 82, 82);
  border-color:rgb(82, 82, 82);

}
.iconClass {
  margin-right:10px;
}
@media only screen and (max-width: 600px) {
  .info {
    visibility:collapse;
  }
}
/*.sidebar-heading {
  text-transform: uppercase;
  text-align: center;
  padding: 0rem 0rem;

}*/
.itemSidebar {
  text-transform: uppercase;
}


</style>

<script>
import { serverBus } from '../main'

export default {
  watch: {
      $route() {
        this.$forceUpdate()
      }
  },
  created() {
    serverBus.$on('showIcon', () => {
        if (this.showLabel==true) {
          this.showLabel=false
        } else {
          this.showLabel=true
        }
    })    
  },
  data() {
    return {
      showLabel: true,
      styleIcon: '',
      menu: [
        {
          icon: 'building',
          name: 'Builder',
          link: 'dynaManager'
        },
        {
          icon: 'drafting-compass',
          name: 'Mapper',
          link: 'dynaMapper'
        },
        {
          icon: 'eye',
          name: 'Viewer',
          link: 'dynaViewer'
        },
        {
          icon: 'copyright',
          name: 'Category',
          link: 'dynaCategory'
        },
        {
          icon: 'users',
          name: 'Account',
          link: 'dynaAccount'
        },
      ]

    }
  }
}
</script>

