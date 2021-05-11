<template>
<b-row>
    
    <b-col v-if="survey != null">
      <center>
        <h1>{{surveyTitle}}</h1>
      </center>
        <showSurveySinglePage 
        :survey="survey" />
    </b-col>
</b-row>
</template>
<script>
import axios from 'axios'
import { serverBus } from '../../main'
import showSurveySinglePage from '../components/showSurveySinglePage'

export default {
  name:"showSurvey",
  data() {
    return {
      listSurveys:[],
      survey: null,
      surveyTitle:null,
    }
  },
  created() {
    this.getSurvey()
  },
  methods: {
  getSurvey() {
      serverBus.$emit('showLoader',true)
      axios
        .get(this.config.serviceBaseUrl + this.config.url.getSurvey,{
          params: {
            id: this.$route.params.id
          }
        }
        )
        .then(response => {
          serverBus.$emit('showLoader',true)
          console.log(response)
          this.survey=JSON.parse(response.data.survey)
          this.list
          this.surveyTitle=response.data.surveyTitle
          console.log(this.survey)
          
         })
        .catch(e => {
            this.error = e;
      })
    },
    goOut(index) {
      console.log('goOut' + index)
    },
    convertBase64(text) {
      return decodeURIComponent(escape(window.atob(text)));
    }
  },
  components: {
    showSurveySinglePage,
  }
    
}
</script>