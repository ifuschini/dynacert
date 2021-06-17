<template>
<b-row>
    <b-col sm=3>
              <b-card>
                <b-card-header>
                  <b>List of forms</b>
                </b-card-header>
                    <b-card-text>
                        <b-list-group flush>
                          <b-list-group-item v-for="(form,index) in listForms" v-bind:key="index" href="#" 
                          
                          v-on:click="go(index)"
                          @contextmenu.prevent="$refs.menu.open($event,index)"
                          >{{form.title}}</b-list-group-item>
                        </b-list-group>
                    </b-card-text>
              </b-card>
    </b-col>
     <vue-context ref="menu" v-slot="{ data }">
        <li>
            <a href="#" @click="showSingleViewer(data)">Form Single Page</a>
        </li>
        <li>
            <a href="#" @click="showAllViewer(data)">Form all</a>
        </li>
    </vue-context>

    <b-col v-if="form != null">
        <showFormAll 
        :form="form" />
    </b-col>
</b-row>
</template>
<script>
import axios from 'axios'
import showFormAll from './dynaForm/showFormAll'

import VueContext from 'vue-context'
import { serverBus } from '../main'

export default {
  name:"listForm",
  data() {
    return {
      listForms:[],
      form: null,
    }
  },
  created() {
    this.$gtag.event('dynacert-builder', { method: 'viewer' })

    this.getForms()
  },
    watch: {
        $route() {
          this.getForms()
            this.$gtag.event('dynacert-builder', { method: 'viewer' })
        }
    },  
  methods: {
    showSingleViewer(index) {
            console.log(index)
            console.log(this.listForms[index])
            this.$router.push({ name: 'FormSinglePage', params: {id: this.listForms[index].id}})
    },
    showAllViewer(index) {
            console.log(index)
            this.$router.push({ name: 'FormAll' ,params: {id: this.listForms[index].id}})
    },
    getForms () {
      serverBus.$emit('showLoader',true)
      axios
        .get(this.config.serviceBaseUrl + this.config.url.listForms)
        .then(response => {
            serverBus.$emit('showLoader',false)
            this.listForms = response.data.response
         })
        .catch(e => {
            this.error = e;
      })
    },
    go(index) {
      serverBus.$emit('showLoader',true)
      console.log(index)
      axios
        .get(this.config.serviceBaseUrl + this.config.url.getForm + this.listForms[index].id,{
        }
        )
        .then(response => {
          console.log(response)
          serverBus.$emit('showLoader',false)
          this.form=JSON.parse(response.data.form)
          
         })
        .catch(e => {
            this.error = e;
      })
    },
    goOut(index) {
      console.log('goOut' + index)
      this.$router.push({ path: '/form/' + this.listForms[index]})
    },
    convertBase64(text) {
      return decodeURIComponent(escape(window.atob(text)));
    }
  },
  components: {
    showFormAll,
    VueContext,
  }
    
}
</script>