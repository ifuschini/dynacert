<template>
  <loader v-if="$root.loading"></loader>
  <router-view v-else></router-view>
</template>

<script>
import loader from './loader/animationLoader.vue'
export default {
  name: 'app',
  components: {
    loader,
  }
}
</script>

<style>
</style>