<template>
<div>
    <center>
    <h4>{{surveyTitle}}</h4>
    </center>
    <div  v-for="(question,index) in survey" v-bind:key="index" style="align-content:left;">
            <showSingleQuestion 
                ref="question"
                :question="question"
                :index="index"
            />
            <b-alert variant="danger" :show="showErrorMessage[index]">{{alertSurveyMessage[index]}}</b-alert>
    </div>
            <b-button variant="success" v-on:click="saveData()" style="float:right;width:100%;margin-top:10px;margin-bottom:60px;">{{language[config.currentLanguage].signBtn}}</b-button>            
    
</div>
    
</template>
<style scoped>

</style>
<script> 
import showSingleQuestion from '../../../builder/src/view/components/showSingleQuestion'
import { serverBus } from '../main'
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
            showErrorMessage:[],
            alertSurveyMessage:[],
        }
    },
    created() {
        this.getSurvey()
        this.$gtag.event('dynacert-online', { method: 'start' })


    },
    watch: {
        $route() {
            this.getSurvey()
            this.$gtag.event('dynacert-online', { method: 'start' })
        },
    },
    methods: {
        getSurvey() {
            serverBus.$emit('showLoader',true)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getSurvey,{
                    params: {
                        id: this.$route.params.data
                    }
                })
                .then(response => {
                this.survey=response.data.survey
                if (response.data.expiredLicenseDate==true) {
                    alert('Errore: L1C3NZ@')
                } else {
                    serverBus.$emit('showLoader',false)
                }
                localStorage.costumer=response.data.costumer
                localStorage.emailCostumer=response.data.emailCostumer
                //localStorage.pdfBack=JSON.stringify(response.data.pdfBack)
                localStorage.pdfBack=JSON.stringify(response.data.images)
                localStorage.configPdf=JSON.stringify(response.data.configPdf)
                localStorage.surveyConfig=JSON.stringify(response.data.survey)
                console.log('localStorage')
                this.surveyTitle=response.data.surveyTitle
                for (let i=0;i<this.survey.length;i++) {
                    this.alertSurveyMessage.push('')
                }
                })
                .catch(e => {
                    this.error = e;
            })
        },
        saveData() {
            this.responses=[]
            let height=-1;
            this.showErrorMessage=[]
            this.alertSurveyMessage=[]
            let lastName='NC';
            let firstName='NC';
            let objectToRemember={}
            for (let i=0;i<this.survey.length;i++) {
                let objectToStore=this.$refs.question[i].getInfoFromObject() 
                if (objectToStore.status=='ok') {
                        this.responses.push(objectToStore.object)
                        if (this.survey[i].typeOfContent == 'lastName') {
                                lastName=objectToStore.object
                        }
                        if (this.survey[i].typeOfContent == 'firstName') {
                                firstName=objectToStore.object
                        }
                        if (this.survey[i].rememberOnBrowser == true) {
                            if (this.survey[i].nameParameterForLocalstorage==null)
                                objectToRemember[this.survey[i].id]=objectToStore.object
                            else 
                                objectToRemember[this.survey[i].nameParameterForLocalstorage]=objectToStore.object
                        }

                        this.alertSurveyMessage.push('')
                        this.showErrorMessage.push(false)
                }   else {
                        this.responses.push(null)
                        console.log(this.$refs.question[i])
                        if (height==-1) height=this.$refs.question[i].$el.offsetTop
                        this.alertSurveyMessage.push(objectToStore.errorMessage)
                        this.showErrorMessage.push(true)

                }
            }
            console.log(this.alertSurveyMessage)
            console.log(JSON.stringify(this.responses))
            console.log(JSON.stringify(objectToRemember))
            if (height!=-1) window.scrollTo(0, height) 
            else {
                localStorage.userData=JSON.stringify(objectToRemember)
                this.$router.push({ name: 'Sign' ,params: {data: {              
                    pdfData: this.responses,
                    lastName: lastName,
                    firstName: firstName,
                }
                }})
            }

            console.log('finish control')
        }

    },

    components: {
        showSingleQuestion
    }
    

}
</script>