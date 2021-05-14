<template>
<b-row>
    <b-col sm=3>
              <b-card>
                <b-card-header>
                  <b>List of surveys</b>
                </b-card-header>
                    <b-card-text>
                        <b-list-group flush>
                          <b-list-group-item v-for="(survey,index) in listSurveys" v-bind:key="index" href="#" 
                          
                          v-on:click="go(index)"
                          @contextmenu.prevent="$refs.menu.open($event,index)"
                          >{{survey.title}}</b-list-group-item>
                        </b-list-group>
                    </b-card-text>
              </b-card>
    </b-col>
     <vue-context ref="menu" v-slot="{ data }">
        <li>
            <a href="#" @click="showSingleViewer(data)">Survey Single Page</a>
        </li>
        <li>
            <a href="#" @click="showAllViewer(data)">Survey all</a>
        </li>
    </vue-context>

    <b-col v-if="survey != null">
        <showSurveyAll 
        :survey="survey" />
    </b-col>
</b-row>
</template>
<script>
import axios from 'axios'
import showSurveyAll from './components/showSurveyAll'

import VueContext from 'vue-context'
import { serverBus } from '../main'

export default {
  name:"listSurvey",
  data() {
    return {
      listSurveys:[],
      survey: null,
    }
  },
  created() {
    this.$gtag.event('dynacert-builder', { method: 'viewer' })

    this.getSurveys()
  },
    watch: {
        $route() {
          this.getSurveys()
            this.$gtag.event('dynacert-builder', { method: 'viewer' })
        }
    },  
  methods: {
    showSingleViewer(index) {
            console.log(index)
            console.log(this.listSurveys[index])
            this.$router.push({ name: 'SurveySinglePage', params: {id: this.listSurveys[index].ID}})
    },
    showAllViewer(index) {
            console.log(index)
            this.$router.push({ name: 'SurveyAll' ,params: {id: this.listSurveys[index].ID}})
    },
    getSurveys () {
      serverBus.$emit('showLoader',true)
      axios
        .get(this.config.serviceBaseUrl + this.config.url.listSurveys)
        .then(response => {
            serverBus.$emit('showLoader',false)
            this.listSurveys = response.data.response
         })
        .catch(e => {
            this.error = e;
      })
    },
    go(index) {
      serverBus.$emit('showLoader',true)
      console.log(index)
      axios
        .get(this.config.serviceBaseUrl + this.config.url.getSurvey ,{
          params: {
            id: this.listSurveys[index].ID
          }
        }
        )
        .then(response => {
          console.log(response)
          serverBus.$emit('showLoader',false)
          this.survey=JSON.parse(response.data.survey)
          
         })
        .catch(e => {
            this.error = e;
      })
    },
    goOut(index) {
      console.log('goOut' + index)
      this.$router.push({ path: '/survey/' + this.listSurveys[index]})
    },
    convertBase64(text) {
      return decodeURIComponent(escape(window.atob(text)));
    }
  },
  components: {
    showSurveyAll,
    VueContext,
  }
    
}
</script>