<template>
<div>
    <center>
    <h4>{{formTitle}}</h4>
    </center>
    <div  v-for="(question,index) in form" v-bind:key="index" style="align-content:left;">
            <showSingleQuestion 
                ref="question"
                :question="question"
                :index="index"
            />
            <b-alert variant="danger" :show="showErrorMessage[index]">{{alertFormMessage[index]}}</b-alert>
    </div>
            <b-button variant="success" v-on:click="saveData()" style="float:right;width:100%;margin-top:10px;margin-bottom:60px;">{{language[config.currentLanguage].signBtn}}</b-button>            
    
</div>
    
</template>
<style scoped>

</style>
<script> 
import showSingleQuestion from '../../../dynacert-builder/src/view/dynaForm/showSingleQuestion'
import { serverBus } from '../main'
import axios from 'axios'



export default {
    name: 'formPreview',
    props: [
    ],
    data() {
        return {
            listForms:[],
            form: null,
            formTitle:null,
            responses: [],
            showErrorMessage:[],
            alertFormMessage:[],
        }
    },
    created() {
        this.getForm()
        this.$gtag.event('dynacert-online', { method: 'start' })


    },
    watch: {
        $route() {
            this.getForm()
            this.$gtag.event('dynacert-online', { method: 'start' })
        },
    },
    methods: {
        getForm() {
            serverBus.$emit('showLoader',true)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getForm + this.$route.params.data,{
                })
                .then(response => {
                this.form=response.data.form
                serverBus.$emit('showLoader',false)
                localStorage.formConfig=JSON.stringify(response.data.form)
                console.log('localStorage')
                this.formTitle=response.data.title
                for (let i=0;i<this.form.length;i++) {
                    this.alertFormMessage.push('')
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
            this.alertFormMessage=[]
            let lastName='NC';
            let firstName='NC';
            let objectToRemember={}
            for (let i=0;i<this.form.length;i++) {
                let objectToStore=this.$refs.question[i].getInfoFromObject() 
                if (objectToStore.status=='ok') {
                        this.responses.push(objectToStore.object)
                        if (this.form[i].typeOfContent == 'lastName') {
                                lastName=objectToStore.object
                        }
                        if (this.form[i].typeOfContent == 'firstName') {
                                firstName=objectToStore.object
                        }
                        if (this.form[i].rememberOnBrowser == true) {
                            if (this.form[i].nameParameterForLocalstorage==null)
                                objectToRemember[this.form[i].id]=objectToStore.object
                            else 
                                objectToRemember[this.form[i].nameParameterForLocalstorage]=objectToStore.object
                        }

                        this.alertFormMessage.push('')
                        this.showErrorMessage.push(false)
                }   else {
                        this.responses.push(null)
                        console.log(this.$refs.question[i])
                        if (height==-1) height=this.$refs.question[i].$el.offsetTop
                        this.alertFormMessage.push(objectToStore.errorMessage)
                        this.showErrorMessage.push(true)

                }
            }
            console.log(this.alertFormMessage)
            console.log(JSON.stringify(this.responses))
            console.log(JSON.stringify(objectToRemember))
            if (height!=-1) window.scrollTo(0, height) 
            else {
                localStorage.userData=JSON.stringify(objectToRemember)
                this.$router.push({ name: 'Sign' ,params: {data: {              
                    pdfData: this.responses,
                    id: this.$route.params.data,
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