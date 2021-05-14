<template>
<div>
        <b-row>
            <b-col >
                <b-card>
                    <template #header>
                                <strong>Load Form saved</strong> 
                    </template>
                    <b-card-text>
                            <select v-on:change="getSurvey(fileSelected)" v-model="fileSelected" class="form-control">
                                <option  :value="null">---- Select a form ----</option>
                                <option v-for="(survey,index) in listSurveys" v-bind:key="index" :value="index">{{survey.title}}({{survey.ID}})</option>
                            </select>

                    </b-card-text>
                </b-card>
                <b-card>
                    <template #header>
                                <strong>Create Form</strong> 
                                            <b-button type="submit" size="sm" style="float:right;margin-right: 5px;" variant="primary" v-if="IDSelected != null" v-on:click="submitlocalSurvey('update')"><b-icon icon="check-circle"/> Update Survey</b-button>
                                            <b-button type="submit" size="sm" style="float:right;margin-right: 5px;" variant="success" v-on:click="submitlocalSurvey('new')"><b-icon icon="check-circle"/> Submit new Survey</b-button>
                    </template>
                            <b-card-text>
                                <b-row>
                                    <b-col>
                                            <b-input
                                            label="Name of form"
                                            placeholder="name of form"
                                            v-model="surveyTitle"
                                            />
                                    </b-col>

                                </b-row>
                                <b-row>
                                    <b-col lg="4">
                                            <div role="group" class="form-group">
                                                <label > Type </label>
                                                <select v-on:change="changeType()" v-model="typeSelected" class="form-control">
                                                <option v-for="item in typeOfField" v-bind:key="item">{{item}}</option>
                                            </select>
                                                
                                                </div>
                                    </b-col>
                                    <b-col lg="4">
                                            <div role="group" class="form-group">
                                                <label > Category </label>
                                                <select  v-model="categorySelected" class="form-control">
                                                <option :value="null">-- select a category --</option>
                                                <option v-for="(category,index) in arrayCategory" v-bind:key="index" :value="category.ID">{{category.name}}</option>
                                            </select>
                                                
                                                </div>
                                    </b-col>
                                </b-row>
                                <b-row>
                                    <b-col>
                                        <b-cardFooter>
                                            <b-alert show variant="danger" v-if="alertSurveyMessage.length!=0">{{alertSurveyMessage}}</b-alert>

                                        </b-cardFooter>
                                    </b-col>
                                </b-row>
                            </b-card-text>
                            </b-card>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col >
                    <b-card>
                    <template #header>
                            <b-row>
                            <b-col>
                                <strong>Insert new "{{typeSelected}}"</strong> 
                            </b-col>
                            <!--b-col v-if="objectFeature.typeOfContent==true">
                            Type Of Content
                                <b-select style="float:right"
                                v-model="typeOfContent" 
                                :options="optionsTypeOfContent" 
                                size="sm" 
                                class="mt-3"
                                />
                            </b-col-->
                            </b-row>
                    </template>
                        <b-card-text>
                            <b-row>
                                <b-col>
                                        <b-input
                                        v-model='questionTitle'
                                        label="Title"
                                        horizontal
                                        placeholder="Insert Title"
                                        v-if="typeSelected != 'Note'"
                                        />
                                        <b-form-textarea 
                                            placeholder="Input text of your note"  
                                            cols="100"  
                                            rows="10" 
                                            v-model="questionTitle"
                                            v-if="typeSelected == 'Note'"
                                        />
                                </b-col>
                                <b-col>
                                    <b-form-checkbox
                                    class="mx-1"
                                    variant="primary"
                                    name="switch1"
                                    variant2="3d" 
                                    v-model="isMandatory"
                                    switch
                                    />
                                    <span v-if="objectFeature.showColOptionValue==false"> 
                                        Mandatory
                                    </span>
                                    <span v-else>
                                    All rows are required
                                    </span>
                                </b-col>
                                <b-col v-if="objectFeature.rememberOnBrowser==true">
                                    <b-form-input
                                        class="mx-1"
                                        v-if="rememberOnBrowser==true"
                                        placeholder="name parameter for storage"
                                        v-model="nameParameterForLocalstorage"
                                    />
                                    <b-form-checkbox
                                    class="mx-1"
                                    variant="primary"
                                    name="switch1"
                                    variant2="3d" 
                                    v-model="rememberOnBrowser"
                                    switch
                                    />
                                    <span> 
                                        Store on browser
                                    </span>
                                </b-col>
                            </b-row>
                            <b-row>
                                <b-col lg="2" v-if="objectFeature.showMinMax==true">
                                    <b-input
                                    label="Min"
                                    v-model="minValue"
                                    placeholder="min value"
                                    @keypress="isNumber($event)"
                                    />
                                </b-col>
                                <b-col lg="2" v-if="objectFeature.showMinMax==true">
                                    <b-input
                                    label="Max"
                                    v-model="maxValue"
                                    placeholder="max value"
                                    @keypress="isNumber($event)"
                                    />
                                </b-col>
                            </b-row>
                        <b-row>
                        <b-col :lg="smOptionValue"  v-if="objectFeature.showRowOptionValue==true">
                            <b-table-simple v-if="optionsRowObject.length>0">
                                <draggable v-model="optionsRowObject" tag="tbody">

                                <b-tr v-for="(option,index) in optionsRowObject" v-bind:key="index">
                                    <b-td>
                                        Row Option {{index +  1}}
                                    </b-td>
                                    <b-td>
                                        {{option}}
                                    </b-td>
                                    <b-td>
                                        <b-button type="submit" size="sm" style="float:right;margin-right: 5px;" variant="danger" v-on:click="deleteRowOption(index)"><b-icon icon="trash-fill"/></b-button>
                                    </b-td>
                                </b-tr>
                                </draggable>
                            </b-table-simple>
                            <hr/>
                        </b-col>
                        <b-col :lg="smOptionValue" v-if="optionsColObject.length>0 && objectFeature.showRowOptionValue==true">
                            <table style="width:100%" class="table table-striped">
                                <draggable v-model="optionsColObject" tag="tbody">

                                <tr v-for="(option,index) in optionsColObject" v-bind:key="index">
                                    <td>
                                        Col Option {{index +  1}}
                                    </td>
                                    <td>
                                        {{option}}
                                    </td>
                                    <td>
                                        <b-button type="submit" size="sm" style="float:right;margin-right: 5px;" variant="danger" v-on:click="deleteColOption(index)"><b-icon icon="trash-fill"/></b-button>
                                    </td>
                                </tr>
                                </draggable>
                            </table>
                            <hr/>
                        </b-col>
                        </b-row>
                        <b-row>
                        <b-col :lg="2" v-if="objectFeature.showRowOptionValue==true">
                        {{labelRowOption}}
                        </b-col>
                        <b-col :lg="smOptionValue-2" v-if="objectFeature.showRowOptionValue==true">
                            <b-form-input
                            v-model='newRowOption'
                            horizontal
                            placeholder="input option"
                            v-on:keyup.enter="addRowOption()"
                            />
                        </b-col>
                        <b-col :lg="2" v-if="objectFeature.showColOptionValue==true">
                        {{labelColOption}}
                        </b-col>
                        <b-col :lg="4" v-if="objectFeature.showColOptionValue==true">
                            <b-input
                            v-model='newColOption'
                            horizontal
                            placeholder="input option"
                            v-on:keyup.enter="addColOption()"
                            />
                        </b-col>
                        </b-row>
                        <b-row>
                            <b-col lg="2" v-if="objectFeature.showDate==true">
                                   <span style="font-color='grey">date, month, year</span> <b-icon :content="$options.calendar" size="lg"/>
                            </b-col>

                        </b-row>
                        <b-row>
                            <b-col lg="2" v-if="objectFeature.showTime==true">
                                   <span style="font-color='grey">hour, minutes</span> <b-icon :content="$options.clock" size="lg"/>
                            </b-col>

                        </b-row>
                        <b-row>
                            <b-col>
                                <b-cardFooter>
                                <b-alert show variant="danger" v-if="alertQuestionMessage.length!=0">{{alertQuestionMessage}}</b-alert>

                                <b-button type="submit" size="sm" style="float:right;margin-right: 5px;" variant="success" v-on:click="submitQuestion()"> Submit question</b-button>
                                <!--b-button type="reset" size="sm" style="float:right;margin-right: 5px;" variant="danger"><b-icon name="cil-ban"/> Reset</b-button-->
                            </b-cardFooter>
                                
                            </b-col>
                        </b-row>

                    </b-card-text>
                </b-card>
                </b-col>
                </b-row>
    </div>
</template>
<script>
import axios from 'axios'
import draggable from 'vuedraggable'
import { serverBus } from '../../main'
import featuresType from './featureParameters/featuresType'
import { cilTrash, cilCalendar, cilClock } from '@coreui/icons'

export default {
    name: 'surveyMaker',
    trash: cilTrash,
    calendar: cilCalendar,
    clock: cilClock,
    data() {
        return {
            typeOfForm: {
                'Text input': 'simpletext',
                'Mail': 'email',
                'Phone number': 'tel',
                'Text Area': 'multilinetext',
                'Multiple choice': 'multiple',
                'Checkboxes' : 'checkbox',
                'Dropdown' : 'dropdown',
                'Linear scalar': 'linearscalar',
                'Multiple grid': 'multiplegrid',
                'Checkbox grid': 'checkboxgrid',
                'Date': 'date',                
                'Time' : 'time',
                'Note' : 'note',             
            },
            typeOfField : [],
            objectFeature: {
                    showMinMax: false,
                    showRowOptionValue: false,                 
                    showColOptionValue: false,                 
            },
            optionsTypeOfContent: [
                    { value: null, text: 'no one' },
                    { value: 'firstName', text: 'First Name' },
                    { value: 'lastName', text: 'Last Name' },
            ],
            alertQuestionMessage:'',
            alertSurveyMessage:'',
            optionRowNumber: 1,
            optionColNumber: 1,
            optionsRowObject: [],
            optionsColObject: [],
            labelRowOption: '',
            labelColOption: '',
            newRowOption: '',
            newColOption: '',
            questionTitle: '',
            surveyTitle: '',
            isMandatory: false,
            rememberOnBrowser:false,
            nameParameterForLocalstorage:null,
            minValue:null,
            maxValue:null,
            smOptionValue: 12,
            smOptionValueRow: 11,
            listSurveys:[],
            fileSelected: null,
            typeSelected: null,
            typeOfContent: null,
            IDSelected: null,
            arrayCategory: [],
            categorySelected: null,
        }
    },
    created() {
        //this.checkSession();
        this.getSurveys()
        this.getCategories()
        this.init()
    },
    watch: {
    },
    methods: {
        init() {
            //define type of object to show
            console.log('init')
            this.typeOfField= Object.keys(this.typeOfForm)
            this.typeSelected=this.typeOfField[0]
            this.changeType()
            this.questionTitle=''
            this.optionsRowObject=[]
            this.optionsColObject=[]
            this.isMandatory=false
            this.minValue=null
            this.maxValue=null
            this.rememberOnBrowser=false
            this.nameParameterForLocalstorage=null
            this.typeOfContent=null
        },
        getCategories() {
            axios
                .get(this.config.serviceBaseUrl + this.config.url.listCategory ,{
                params: {
                }
                }
                )
                .then(response => {
                    console.log(response)
                    this.arrayCategory=response.data.response
                
                })
                .catch(e => {
                    this.error = e;
            })
        },
        getSurveys () {
        axios
            .get(this.config.serviceBaseUrl + this.config.url.listSurveys)
            .then(response => {
                this.listSurveys = response.data.response
                this.surveyTitle = ''
            })
            .catch(e => {
                this.error = e;
        })
        },

        getSurvey(index) {
        serverBus.$emit('showLoader',true)
        console.log(index)
        axios
            .get(this.config.serviceBaseUrl + this.config.url.getSurvey ,{
            params: {
                id: this.listSurveys[index].ID
            }
            }
            )
            .then(response => {
                serverBus.$emit('showLoader',false)
                this.$emit('loadSavedQuestion',
                        JSON.parse(response.data.survey)
                )
                this.IDSelected=this.listSurveys[index].ID
                this.categorySelected=response.data.category
                this.surveyTitle=response.data.title
            })
            .catch(e => {
                this.error = e;
        })
        },
        changeType: function() {
            console.log(this.typeSelected)
            this.objectFeature=featuresType[this.typeOfForm[this.typeSelected]]
            console.log(this.objectFeature)
            this.optionRowNumber=1
            this.optionColNumber=1
            this.optionsRowObject=[]
            this.optionsColObject=[]
            this.isMandatory=false
            this.questionTitle=''
            this.alertQuestionMessage=''
            this.minValue=null
            this.maxValue=null
            this.smOptionValue=12
            this.smOptionValueRow=11
            if (this.objectFeature.showColOptionValue==true) {
                this.smOptionValue=6
                this.smOptionValueRow=5
            }

            this.labelRowOption="Row " + this.optionRowNumber
            this.labelColOption="Column " + this.optionColNumber
        },
        addRowOption() {
            if (this.newRowOption.length > 0) {
                this.optionsRowObject.push(this.newRowOption)
                this.newRowOption=''
                this.optionRowNumber++
                this.labelRowOption="Row " + this.optionRowNumber
            }
        },
        addColOption() {
            if (this.newColOption.length > 0) {
                this.optionsColObject.push(this.newColOption)
                this.newColOption=''
                this.optionColNumber++
                this.labelColOption="Columm " + this.optionColNumber
            }
        },
        deleteRowOption(index) {
            this.optionsRowObject.splice(index,1)
            this.optionRowNumber=this.optionsRowObject.length + 1
            this.labelRowOption="Column " + this.optionRowNumber
        },
        deleteColOption(index) {
            this.optionsColObject.splice(index,1)
            this.optionColNumber=this.optionsColObject.length + 1
            this.labelColOption="Column " + this.optionColNumber
        },
        checkMandatory() {
            if (this.isMandatory==true) this.isMandatory=false 
            else this.isMandatory=true
            console.log(this.isMandatory)
        },
        submitQuestion() {
            if (this.questionTitle.length == 0) {
                this.alertQuestionMessage='Question title missing'
                return false;
            }
            if (this.optionsRowObject.length == 0 && this.objectFeature.showRowOptionValue==true) {
                this.alertQuestionMessage='Raw option can not be empy'
                return false;
            }
            if (this.optionsColObject.length == 0 && this.objectFeature.showColOptionValue==true) {
                this.alertQuestionMessage='Column option can not be empy'
                return false;
            }
            if (this.objectFeature.showMinMax == true && this.minValue !=null && this.maxValue !=null && parseInt(this.minValue) > parseInt(this.maxValue)) {
                this.alertQuestionMessage='"Min" value cannot be greater thane "Max" value'
                return false;
            }
            let minValueTosend=null
            let maxValueTosend=null
            if (!isNaN(parseInt(this.minValue))) {
                minValueTosend=this.minValue
            } 
            if (!isNaN(parseInt(this.maxValue))) {
                maxValueTosend=this.maxValue
            } 
            let objToSave={
                id: Date.now(),
                type: this.typeOfForm[this.typeSelected],
                labelType: this.typeSelected,
                title: this.questionTitle,
                rowOption: this.optionsRowObject,
                colOption: this.optionsColObject,
                isMandatory: this.isMandatory,
                minValue: minValueTosend,
                maxValue:maxValueTosend,
                rememberOnBrowser: this.rememberOnBrowser,
                nameParameterForLocalstorage: this.nameParameterForLocalstorage,
                typeOfContent: this.typeOfContent,
            }
            console.log(objToSave)
            this.$emit('addQuestion',
                objToSave
            )
            this.init()
        },
        submitlocalSurvey (action) {
            if (this.config.demo==true) {
                alert('It\'s a demo')
            }
            if (this.surveyTitle=='') {
                this.alertSurveyMessage='Attention! the name of the survey can not be empty'
                return false;
            }
            let IDSelected=this.IDSelected
            if (action=='new') IDSelected=null
            this.alertSurveyMessag=''
            this.$emit('saveSurvey',
                this.surveyTitle,
                IDSelected,
                this.categorySelected,
            )
            this.IDSelected=null


        },
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        convertBase64(text) {
          return decodeURIComponent(escape(window.atob(text)));
        },

    },
    components: {
        draggable,
    }
}
</script>