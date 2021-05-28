<template>
    <div class="bg-light border-right" id="sidebar-wrapper" ref="sidebarWrapper">
      <div class="sidebar-heading" style="margin-top:30px">Menu </div>
      <b-list-group>
        <b-list-group-item v-for="(item, index) in menu" v-bind:key="index" v-on:click="$router.push({ path: '/' +  item.link})" :active="$router.currentRoute.path=='/' + item.link">{{item.name}}</b-list-group-item>
      </b-list-group>

      </div>
    </div>
</template>
<style scoped>
.list-group-item.active {
  z-index: 2;
  color: #fff;
  background-color:rgb(54, 102, 7);
  border-color:rgb(54, 102, 7);

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
          name: 'Builder',
          link: 'dynaManager'
        },
        {
          name: 'Mapper',
          link: 'dynaMapper'
        },
        {
          name: 'Viewer',
          link: 'dynaViewer'
        },
        {
          name: 'Category',
          link: 'dynaCategory'
        },
      ]

    }
  }
}
</script>

