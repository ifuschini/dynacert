<template>
    <div class="bg-light border-right info" id="sidebar-wrapper" ref="sidebarWrapper">
      <div class="sidebar-heading" style="margin-top:30px">Menu </div>
      <b-list-group>
        <b-list-group-item 
          v-for="(item, index) in menu" v-bind:key="index" 
          v-on:click="$router.push({ path: '/' +  item.link})" 
          :active="$router.currentRoute.path=='/' + item.link">
          <font-awesome-icon :icon="item.icon" style="margin-right:5px;"/>
          {{item.name}}
        </b-list-group-item>
      </b-list-group>
      </div>
</template>
<style scoped>
.list-group-item.active {
  z-index: 2;
  color: #fff;
  background-color:rgb(54, 102, 7);
  border-color:rgb(54, 102, 7);

}
@media only screen and (max-width: 600px) {
  .info {
    visibility:collapse;
  }
}


</style>

<script>
export default {
  watch: {
      $route() {
        this.$forceUpdate()
      }
  },
  data() {
    return {
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

