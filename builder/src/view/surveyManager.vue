<template>   
<div> 
        <b-tabs ref="tabs" >
        <b-tab  title="Form Builder" @click.capture="changeTab(0)" active>
            <b-row>
            <b-col sm="6">
                <surveyMaker
                    ref="surveyMaker"
                    v-on:loadSavedQuestion="loadSavedQuestion"
                    v-on:addQuestion="addQuestion"
                    v-on:saveSurvey="saveSurvey"
                />
            </b-col>
            <b-col sm="6" >
                <div class="scroll-area">
                <!-- show survey -->
                        <surveyListComponents 
                            :survey="survey"
                            v-on:updatePosition="onChangeJsonOrder"
                            />
                </div>
            </b-col>
        </b-row>
        </b-tab>
        <b-tab  @click.capture="changeTab(1)" title="Preview">
            <surveyPreview
                ref="preview"
                :survey="survey"
            />

        </b-tab>
        <b-tab title="Json Editor" @click.capture="changeTab(2)">
            <json-editor ref="editor" 
                                    style="height:800px" 
                                    :onChange="onChangeJson" 
                                    :options="options" 
                                    :json="survey" 
                                    v-if="showEditor==true"
                                    />    
        </b-tab>
    </b-tabs>
</div>
</template>
<style scoped>
.scroll-area{
  width:100%;
  height:100vh;
  float:left;
  overflow-y:scroll;
}
</style>

<script>
import JsonEditor from '../components/JsonEditor'
import surveyPreview from './components/surveyPreview'
import surveyListComponents from './components/surveyListComponent'
import surveyMaker from './components/surveyMaker'
import { serverBus } from '../main'
import axios from 'axios'


export default {
    name: 'createForm',
    data () {
        return {
            options: { 
                mode: 'code',
                modes:  ['tree','code']
            },
            survey: [],
            newJsonFromEditor: [],
            showEditor:true,
            showMapper:false,
        }


    },
    created() {

    },
    watch: {
        $route() {
            serverBus.$emit('showLoader',false)
        }
    },  
    methods: {
        
        changeTab: function(e) {
            console.log('changeTab')
            if (e != 2) {
                if (this.showMapper==true) this.survey=this.newJsonFromEditor
            } else {
              this.showEditor=false
              setTimeout(function () { this.showEditor=true}.bind(this), 500)
            }
            if (e==3) {
                this.showMapper=true
            }
            console.log(this.survey)
        }, 
        loadSavedQuestion(survey) {
            console.log('loadSavedQuestion') 
            console.log(survey)
            this.survey=survey
            this.newJsonFromEditor=this.survey
        },
        addQuestion(newJson) {
            console.log('onChangeSurvey')
            console.log(newJson)
            this.survey.push(newJson)
            // handle json changes
        },
        onChangeJsonOrder(e) {
            console.log('onChangeJsonOrder received')
            console.log(e)
            this.survey=e
            this.newJsonFromEditor=this.survey
        },
        onChangeJson(e) {
            console.log('onChangeJson received')
            console.log(e)
            this.newJsonFromEditor=e
            // handle json changes
        },
        saveSurvey(surveyTitle,IDSurvey,categorySelected) {
            console.log('saveSurvey')
            
            if (this.survey.length==0) {
                this.alertSurveyMessage='Attention! the survey can not be empty'
                return false;
            }
            if (this.config.demo==true) {
                console.log('is a demo')
                return false
            }
            serverBus.$emit('showLoader',true)
            axios
                .post(this.config.serviceBaseUrl + this.config.url.saveSurvey,{
                    surveyTitle: surveyTitle,
                    survey:JSON.stringify(this.survey),
                    IDSurvey: IDSurvey,
                    categorySelected: categorySelected,
                },{
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',            
                    }
                }
                )
                .then(response => {
                    serverBus.$emit('showLoader',false)
                    console.log(response)
                    this.$refs.surveyMaker.getSurveys()
                    this.$refs.surveyMaker.init()
                    //this.showTreeView=true
                    //alert('Salvataggio avvenuto con successo')
                })
                .catch (e=> {
                            this.error=e;
            })

        }
    },
    components: {
        JsonEditor,
        surveyPreview,
        surveyListComponents,
        surveyMaker,
    }

}
</script>