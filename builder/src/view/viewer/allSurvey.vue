<template>
<div>
    <div  v-for="(question,index) in survey" v-bind:key="index" style="align-content: left;">
            <showSingleQuestion 
                ref="question"
                :question="question"
                :index="index"
            />
            <b-alert show variant="danger" v-if="alertSurveyMessage[index].length!=0">{{alertSurveyMessage[index]}}</b-alert>
    </div>
        {{responses}}
            <b-button variant="success" v-on:click="saveData()" style="float:right;margin-top:10px">Send data</b-button>            
    
</div>
    
</template>
<style scoped>

</style>
<script>
import showSingleQuestion from '../components/showSingleQuestion'
import { serverBus } from '../../main'
import axios from 'axios'



export default {
    name: 'surveyPreview',
    props: [
    ],
    data() {
        return {
            listSurveys:[],
            survey: null,
            surveyTitle:null,
            responses: [],
            alertSurveyMessage:[],
        }
    },
    created() {
        this.getSurvey()
    },
    methods: {
        getSurvey() {
            serverBus.$emit('showLoader',true)
            console.log("---->"  + this.$route.params.id)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getSurvey + "/",{
                params: {
                    id: this.$route.params.id
                }
                }
                )
                .then(response => {
                serverBus.$emit('showLoader',false)
                this.survey=JSON.parse(response.data.survey)
                this.surveyTitle=response.data.surveyTitle
                for (let i=0;i<this.survey.length;i++) {
                    this.alertSurveyMessage[i]='' 
                }
                })
                .catch(e => {
                    this.error = e;
            })
        },
        saveData() {
            this.responses=[]
            for (let i=0;i<this.survey.length;i++) {
                let objectToStore=this.$refs.question[i].getInfoFromObject() 
                if (objectToStore.status=='ok') {
                        this.responses.push(objectToStore.object)
                        this.alertSurveyMessage[i]=''
                }   else {
                        this.responses.push(null)
                        this.alertSurveyMessage[i]=objectToStore.errorMessage
                }


            }

        }

    },

    components: {
        showSingleQuestion
    }
    

}
</script>