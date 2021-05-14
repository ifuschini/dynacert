<template>
        <b-tabs ref="tabs" >
            <b-tab  title="Survey Mapper" @click.capture="changeTab(0)" active>
                <surveyMapperTool
                    v-on:changeConfig="changeConfig"
                    v-on:saveMapper="saveMapper"
                
                />
            </b-tab>
            <b-tab title="Json Editor" @click.capture="changeTab(1)">
                <json-editor ref="editor" 
                                        style="height:800px" 
                                        :onChange="onChangeJson" 
                                        :options="options" 
                                        :json="configPagesPdf" 
                                        v-if="showEditor==true"
                                        />    
            </b-tab>
        </b-tabs>

</template>
<script>
import JsonEditor from '../components/JsonEditor'
import surveyMapperTool from './surveyMapper/surveyMapperTool'
import { serverBus } from '../main'
import axios from 'axios'
export default {
    data() {
        return {
            options: { 
                mode: 'code',
                modes:  ['tree','code']
            },
            configPagesPdf:{
                pages:[]
            },
            showEditor: false,
            

        }
    },
    methods: {
        changeTab(tab) {
            if (tab ==1) this.showEditor=true
            else this.showEditor=false
        },
        changeConfig(e) {
            console.log('changeConfig')
            console.log(e)
            this.configPagesPdf=e
        },
        onChangeJson(e) {
            console.log('onChangeJson')
        },
        saveMapper(id,arrayImages) {
            console.log('saveMapper' )
            if (this.configPagesPdf.pages.length==0) {
                this.alertSurveyMessage='Attention! the survey can not be empty'
                return false;
            }
            if (this.config.demo==true) {
                alert('is a demo')
                return false
            }
            serverBus.$emit('showLoader',true)
            axios
                .post(this.config.serviceBaseUrl + this.config.url.saveSurveyMap,{
                    surveyId: id,
                    map:JSON.stringify(this.configPagesPdf),
                    images: JSON.stringify(arrayImages),
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
                    //this.showTreeView=true
                    //alert('Salvataggio avvenuto con successo')
                })
                .catch (e=> {
                            this.error=e;
                })

        },


    },
    components: {
        surveyMapperTool,
        JsonEditor,
    }
}
</script>
