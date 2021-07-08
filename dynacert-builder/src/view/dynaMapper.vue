<template>
        <div>
        <b-tabs ref="tabs" >
            <b-tab  title="Dyna Mapper" @click.capture="changeTab(0)" active>
                <div class="helper" v-if="selectForm==false && showHelper==true">
                    <div class="box">
                    <span class="chooseText">Please choose a form</span>
                        <cool-select
                            ref="select"
                            v-model="selectedForm"
                            :items="listForms"
                            placeholder="Select name"
                            @keydown.enter.native.prevent
                            item-value="id"
                            item-text="title"
                        />
                    </div>
                </div>
                <dynaMapperTool v-if="selectForm==true && showHelper==false"
                    ref="dynaMapperTool"
                    v-on:changeConfig="changeConfig"
                    v-on:saveMapper="saveMapper"
                    v-on:getListForm="getListForm"
                    :listForms="listForms"
                    style="height: 80vh;"

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

        </div>
</template>
<style scoped>
.helper{
  position: relative;
  top: 0px;
  left:0px;
	background-color: rgb(68, 66, 66);
	padding: 0;
	margin: 0;
	height: 100vh;
	width:100vw;
	display:flex;
	align-items: center;
	justify-content: center;
    opacity: 1.0;
    z-index:99;
  
}
.box {
  position: absolute;
  margin: 120px auto 0;
  top: 10vh;
  left: 35vw;
}
.chooseText {
    font-size: 2rem;
    color:white;
}

</style>

<script>
import JsonEditor from '../components/JsonEditor'
import dynaMapperTool from './dynaMapper/mapperTool'
import { serverBus } from '../main'
import axios from 'axios'
import { CoolSelect } from "vue-cool-select";

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
            listForms: [],
            selectForm: false,
            showHelper: false,
            selectedForm: null,
         }
    },
    methods: {
        changeTab(tab) {
            if (tab ==1) this.showEditor=true
            else this.showEditor=false
        },
        getListForm(e) {
            console.log('getListForm')
            this.listForms=e;
        },
        changeConfig(e) {
            console.log('changeConfig')
            console.log(e)
            this.configPagesPdf=e
        },
        onChangeJson(e) {
            console.log('onChangeJson')
        },
        getListForms () {
                serverBus.$emit('showLoader',true)
                axios
                    .get(this.config.serviceBaseUrl + this.config.url.listForms)
                    .then(response => {
                        serverBus.$emit('showLoader',false)
                        this.listForms = response.data.response
                        this.$emit('getListForm',this.listForms)
                        this.showHelper=true
                        setTimeout(function () { 
                            this.$refs.select.setFocused();
                        }.bind(this), 100)
                        
                    })
                    .catch(e => {
                        this.dynaLogout(this)
                        this.error = e;
                })
        },
        saveMapper(id,arrayImages) {
            console.log('saveMapper' )
            if (this.configPagesPdf.pages.length==0) {
                this.alertDynaMessage='Attention! the field can not be empty'
                return false;
            }
            if (this.config.demo==true) {
                alert('is a demo')
                return false
            }

            serverBus.$emit('showLoader',true)
            axios
                .post(this.config.serviceBaseUrl + this.config.url.saveFormMap,{
                    formId: id,
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
                    this.dynaLogout(this)
                    this.error=e;
                })
        },


    },
    watch: {
        $route() {
            this.$gtag.event('dynacert-builder', { method: 'maps' })
        },
        selectedForm () {
            this.selectForm=true
            this.showHelper=false
            let objToSend={
                target: {
                    value: this.selectedForm
                }
            }
            setTimeout(function () { 
                this.$refs.dynaMapperTool.getForm(objToSend) 
            }.bind(this), 100)
        }
    },  
    created() {
        console.log('created')
        this.$gtag.event('dynacert-builder', { method: 'maps' })
        this.getListForms()
    },
    components: {
        dynaMapperTool,
        JsonEditor,
        CoolSelect,
    }
}
</script>
