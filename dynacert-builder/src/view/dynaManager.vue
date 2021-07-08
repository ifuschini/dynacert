<template>   
<div> 
        <b-tabs ref="tabs" >
        <b-tab  title="Form Builder" @click.capture="changeTab(0)" active>
            <b-row>
            <b-col sm="6">
                <formMaker
                    ref="formMaker"
                    v-on:loadSavedQuestion="loadSavedQuestion"
                    v-on:addQuestion="addQuestion"
                    v-on:saveForm="saveForm"
                    v-on:clearForm="clearForm"
                    style="height: 100vh;"
                />
            </b-col>
            <b-col sm="6" >
                <div class="scroll-area">
                <!-- show form -->
                        <formListComponents 
                            :form="form"
                            v-on:updatePosition="onChangeJsonOrder"
                            />
                </div>
            </b-col>
        </b-row>
        </b-tab>
        <b-tab  @click.capture="changeTab(1)" title="Preview" :disabled="form.length==0">
            <formPreview
                ref="preview"
                :form="form"
            />

        </b-tab>
        <b-tab title="Json Editor" @click.capture="changeTab(2)" :disabled="form.length==0">
            <json-editor ref="editor" 
                                    style="height:800px" 
                                    :onChange="onChangeJson" 
                                    :options="options" 
                                    :json="form" 
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
import formPreview from './dynaForm/formPreview'
import formListComponents from './dynaForm/formListComponent'
import formMaker from './dynaForm/formMaker'
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
            form: [],
            newJsonFromEditor: [],
            showEditor:true,
            showMapper:false,
        }


    },
    created() {
        this.$gtag.event('dynacert-builder', { method: 'manager' })
    },
    watch: {
        $route() {
            serverBus.$emit('showLoader',false)
            this.$gtag.event('dynacert-builder', { method: 'manager' })
        }
    },  
    methods: {
        
        changeTab: function(e) {
            console.log('changeTab')
            if (e != 2) {
                if (this.showMapper==true) this.form=this.newJsonFromEditor
            } else {
              this.showEditor=false
              setTimeout(function () { this.showEditor=true}.bind(this), 500)
            }
            if (e==3) {
                this.showMapper=true
            }
            console.log(this.form)
        }, 
        loadSavedQuestion(form) {
            console.log('loadSavedQuestion') 
            console.log(form)
            this.form=form
            this.newJsonFromEditor=this.form
        },
        addQuestion(newJson) {
            console.log('onChangeForm')
            console.log(newJson)
            this.form.push(newJson)
            // handle json changes
        },
        clearForm() {
            this.form=[]
        },
        onChangeJsonOrder(e) {
            console.log('onChangeJsonOrder received')
            console.log(e)
            this.form=e
            this.newJsonFromEditor=this.form
        },
        onChangeJson(e) {
            console.log('onChangeJson received')
            console.log(e)
            this.newJsonFromEditor=e
            // handle json changes
        },
        saveForm(formTitle,IDForm,categorySelected) {
            console.log('saveForm')

            if (this.form.length==0) {
                console.log(this.form)
                this.alertFormMessage='Attention! the form can not be empty'
                return false;
            }
            if (this.config.demo==true) {
                console.log('is a demo')
                return false
            }
            serverBus.$emit('showLoader',true)
            axios
                .post(this.config.serviceBaseUrl + this.config.url.saveForm,{
                    formTitle: formTitle,
                    form:JSON.stringify(this.form),
                    IDForm: IDForm,
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
                    this.$refs.formMaker.updateListForms(response.data.response)
                    this.$refs.formMaker.init()
                })
                .catch (e=> {
                    this.dynaLogout(this)
                    this.error=e;
            })

        }
    },
    components: {
        JsonEditor,
        formPreview,
        formListComponents,
        formMaker,
    }

}
</script>