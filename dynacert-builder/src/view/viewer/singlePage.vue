<template>
<b-row>
    
    <b-col v-if="form != null">
      <center>
        <h1>{{formTitle}}</h1>
      </center>
        <showFormSinglePage 
        :form="form" />
    </b-col>
</b-row>
</template>
<script>
import axios from 'axios'
import { serverBus } from '../../main'
import showFormSinglePage from '../dynaForm/showFormSinglePage'

export default {
  name:"showForm",
  data() {
    return {
      listForms:[],
      form: null,
      formTitle:null,
    }
  },
  created() {
    this.getForm()
  },
  methods: {
  getForm() {
      serverBus.$emit('showLoader',true)
      axios
        .get(this.config.serviceBaseUrl + this.config.url.getForm,{
          params: {
            id: this.$route.params.id
          }
        }
        )
        .then(response => {
          serverBus.$emit('showLoader',true)
          console.log(response)
          this.form=JSON.parse(response.data.form)
          this.list
          this.formTitle=response.data.formTitle
          console.log(this.form)
          
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
    showFormSinglePage,
  }
    
}
</script>