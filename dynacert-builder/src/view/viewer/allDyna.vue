<template>
<div>
    <div  v-for="(question,index) in form" v-bind:key="index" style="align-content: left;">
            <showSingleQuestion 
                ref="question"
                :question="question"
                :index="index"
            />
            <b-alert show variant="danger" v-if="alertFormMessage[index].length!=0">{{alertFormMessage[index]}}</b-alert>
    </div>
        {{responses}}

    
</div>
    
</template>
<style scoped>

</style>
<script>
import showSingleQuestion from '../dynaForm/showSingleQuestion'
import { serverBus } from '../../main'
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
            alertFormMessage:[],
        }
    },
    created() {
        this.getForm()
    },
    methods: {
        getForm() {
            serverBus.$emit('showLoader',true)
            console.log("---->"  + this.$route.params.id)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getForm + "/",{
                params: {
                    id: this.$route.params.id
                }
                }
                )
                .then(response => {
                serverBus.$emit('showLoader',false)
                this.form=JSON.parse(response.data.form)
                this.formTitle=response.data.formTitle
                for (let i=0;i<this.form.length;i++) {
                    this.alertFormMessage[i]='' 
                }
                })
                .catch(e => {
                    this.error = e;
            })
        },
        saveData() {
            this.responses=[]
            for (let i=0;i<this.form.length;i++) {
                let objectToStore=this.$refs.question[i].getInfoFromObject() 
                if (objectToStore.status=='ok') {
                        this.responses.push(objectToStore.object)
                        this.alertFormMessage[i]=''
                }   else {
                        this.responses.push(null)
                        this.alertFormMessage[i]=objectToStore.errorMessage
                }


            }

        }

    },

    components: {
        showSingleQuestion
    }
    

}
</script>