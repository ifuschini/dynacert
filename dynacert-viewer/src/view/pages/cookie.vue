<template>
  <div>
  <span v-on:click="goHome()"><u>home</u></span><br/>
  <br/>
  <div class="textInformativa">
      text for cookie information  
  </div>
  </div>
</template>
<style scoped>
.textInformativa {
  font-size: 0.5rem;
  margin-left:5px;
  margin-right:5px;
  margin-bottom:25px;
}

</style>

<script>
export default {
  methods: {
    goHome() {
      this.$router.push({ name: 'Survey'})
    }, 
  }
}
</script>

