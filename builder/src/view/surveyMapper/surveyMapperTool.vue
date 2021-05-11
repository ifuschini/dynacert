<template>
  <div>
    <b-row> 
        <b-col>
        <surveyImageMapper
        ref="pdfMapper"
        :imageToLoad="activeImage"
        :lengthSurvey="survey.length"
        :numberOfPage="page"
        v-if="activeImage != null"
        v-on:indexSelected="indexSelected"
        v-on:getSelections="generate"
        />
        </b-col>
        <b-col lg="5">
        <b-row>
        <b-col lg="1">
        Survey
            </b-col>
            <b-col>
        <select class="form-control" style="margin-bottom:5px;" @change="getSurvey($event)" >
            <option value="null">--- select a survey --- </option>
            <option v-for="(survey,index) in listSurveys" v-bind:key="index" :value="survey.ID">{{survey.title}}</option>
        </select>
            </b-col>
        </b-row>
        <b-button variant="secondary" v-on:click="$refs.pdfMapper.clearSelection();$refs.listComponent.clearSelections()" size="sm">Clear</b-button>
        <b-button variant="danger" v-on:click="$refs.pdfMapper.deleteSelection();$refs.listComponent.clearSelections()" style="margin-left:5px" size="sm">Delete</b-button>
        <b-button variant="success" v-on:click="saveConfig()" style="margin-left:5px" size="sm">Save</b-button>
        <!--b-button variant="success" v-on:click="$refs.pdfMapper.getSelection()" size="sm" style="float:right">Generate <b-icon icon="check-circle"/></b-button-->
        <b-button variant="info" v-on:click="addDynaElement('today')" size="sm" style="float:right;margin-right:5px;">Add Today</b-button>
        <b-button variant="warning" v-on:click="showSignForm=true" size="sm" style="float:right;margin-right:5px;">Sign</b-button>
        <b-card style="margin-top:5px;" v-if="showSignForm==true">
            <template #header>
            Insert disclosure
            </template>
            <b-input type="text" placeholder="Button expand text title"  v-model="titleSign"/>
            <b-form-textarea placeholder="Input your text"  cols="100"  rows="10" v-model="textSign" style="margin-top:5px;"/>        
            <b-button variant="secondary" v-on:click="showSignForm=true;textSign=null;titleSign=null;" size="sm" style="float:close;margin-top:5px;">Cancel</b-button>
            <b-button variant="warning" v-on:click="addDynaElement('sign')" size="sm" style="float:right;margin-top:5px;">Add Sign</b-button>
        </b-card>
        <listComponentMapper
        ref="listComponent"
        :survey="survey"
        :canSelect="activeImage != null"
        v-on:setField="setField"
        style="margin-top:5px;"
        />
        </b-col>
        <b-col lg="2" >
                <div v-if="eventIdSelected!=null">
                    <div v-for="(file,index) in pdfBackground" v-bind:key="index"  >
                        <div class="documento" v-on:click="active(index)" :style="getStyle(index)">
                            <img :src="file" :class="page==index?'fileImage-selected' : 'fileImage'" v-if="file != null" />
                            <div class="preview" v-if="file == null"/>
                        </div>
                            <span v-on:click='deleteItem(index)' v-if="file != null" style="cursor:pointer">Cancella</span>
                    </div>
                    <fileUpload
                            class="btn btn-info btn-sm"
                            :multiple="false"
                            :drop="true"
                            :drop-directory="false"
                            v-model="files"
                            accept="image/jpeg,image/png"
                            @input-filter="inputFilter"
                            :size="1024 * 1024"
                            ref="upload">
                            <!--i class="fa fa-plus"></i-->
                            File Upload
                    </fileUpload>
                </div>
        
        </b-col>
    </b-row>
  </div>
</template>
<style scoped>
.fileImage {
    position: relative;
    top: 0px;
    left:0px;
    max-width: 240px;
    max-height: 190px;
}
.fileImage-selected {
    position: relative;
    top: 0px;
    left:0px;
    max-width: 240px;
    max-height: 190px;
    border-style: double;
    border-color:blue;
}

</style>

<script>
import fileUpload from 'vue-upload-component'
import imageCompression from 'browser-image-compression';
import surveyImageMapper from './surveyImageMapper'
import listComponentMapper from './listComponentMapper'

import { serverBus } from '../../main'
import axios from 'axios'

export default {
    name:"surveyMapper",
    data() {
        return {
            files:[],
            pdfBackground:[],
            activeImage:null,
            page: null,
            isChange: false,
            configPagesPdf:[],
            survey:[],
            sign: [],
            today: [],
            signDescriptions: {},
            listSurveys:[],
            titleSign:null,
            textSign: null,
            showSignForm: false,
            eventIdSelected: null,
            surveyMap: null,
        }
    },
    created() {
        console.log('surveyMapper')
        console.log(this.survey)
        //if (localStorage.pdfBackground) this.pdfBackground=JSON.parse(localStorage.pdfBackground)
        this.getListSurveys()
    },
    watch: {
        files () {
            console.log('sono qui')
            if (this.files[0].file.size < this.maxSizeImage  * 1024) {
                this.setImage(this.files[0].blob)
            } else {
                this.handleImageUpload(this.files[0].file)
                    //this.messageError='Attenzione l\'immagine risulta troppo grande'
            }
        },

    },
    methods: {
        convertBase64(text) {
        return decodeURIComponent(escape(window.atob(text)));
        },
        getListSurveys () {
                serverBus.$emit('showLoader',true)
                axios
                    .get(this.config.serviceBaseUrl + this.config.url.listSurveys)
                    .then(response => {
                        serverBus.$emit('showLoader',false)
                        this.listSurveys = response.data.response
                    })
                    .catch(e => {
                        this.error = e;
                })
        },
        saveConfig() {
            this.$refs.pdfMapper.getSelection()
            console.log('saveMapper' + this.eventIdSelected)
            this.isChange=false
            this.$emit('saveMapper', this.eventIdSelected,this.pdfBackground)
        },
        getSurvey(event) {
        serverBus.$emit('showLoader',true)
        console.log(event)
        this.eventIdSelected=event.target.value
        if (this.$refs.pdfMapper) this.$refs.pdfMapper.clearSelection()
        if (event.target.value=='null') {
            this.survey=[]
            this.pdfBackground=[]
            this.eventIdSelected=null
        }
        axios
            .get(this.config.serviceBaseUrl + this.config.url.getSurvey ,{
            params: {
                id: event.target.value
            }
            }
            )
            .then(response => {
                console.log(response)
                serverBus.$emit('showLoader',false)
                this.activeImage=null
                this.page=null
                this.survey=JSON.parse(response.data.survey)
                this.getMap(event.target.value)         
            })
            .catch(e => {
                this.error = e;
        })
        },
        getMap(id) {
            serverBus.$emit('showLoader',true)
            console.log(id)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getMap ,{
                params: {
                    surveyId: id
                }
                }
                )
                .then(response => {
                console.log(response)
                serverBus.$emit('showLoader',false)
                this.surveyMap=response.data.map
                this.pdfBackground=response.data.images
                console.log('images')
                console.log(this.pdfBackground)
                this.configPagesPdf=[]
            
                if (response.data.map.pages)  {
                    this.configPagesPdf=response.data.map.pages
                }
                if (response.data.status)
                    this.$emit('changeConfig',this.surveyMap)                
                })
                .catch(e => {
                    this.error = e;
            })
        },

        addDynaElement(type) {
            let id=Date.now()
            let obj={
                    configObj: {
                        type: type
                    },
                    objCoord: {
                        rowOption: null,
                        colOption: null,
                        index: type + '_' + id, 
                    },
            }
            if (type=='sign') {
                this.signDescriptions[id]={
                    title: this.titleSign,
                    text: this.textSign, 
                }
            }
            this.showSignForm=false
            this.titleSign=null
            this.textSign=null
            this.$refs.pdfMapper.addField(obj)

        },
        indexSelected(e) {
            console.log("indexSelected ")
            console.log(e)
            console.log("---------------")
            this.$refs.listComponent.setField(e.index,true,e.rowOption,e.colOption)
        },
        setField(e) {
            console.log(e)
            this.isChange=true
            this.$refs.pdfMapper.addField({
                configObj: this.survey[e.index],
                objCoord: e,
            })
        },
        inputFilter: function (newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                // Filter non-image file
                if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
                return prevent()
                }
            }
        },
         handleImageUpload(blob) {
            console.log('compression image start')
            console.log(blob)
            var imageFile = blob;            
            var options = {
                maxSizeMB: 1,
                maxWidthOrHeight: 1920,
                useWebWorker: true
            }
            imageCompression(imageFile, options)
                .then(function (compressedFile) {
                console.log('compressedFile instanceof Blob', compressedFile instanceof Blob); // true
                console.log(`compressedFile size ${compressedFile.size / 1024 / 1024} MB`); // smaller than maxSizeMB
                console.log(compressedFile)
                this.convertBlobToImage64(compressedFile)
                }.bind(this))
                .catch(function (error) {
                console.log(error.message);
                });
        } ,       
        convertBlobToImage64(blob) {
            var reader = new FileReader(); 
                reader.readAsDataURL(blob); 
                reader.onloadend = function () { 
                var base64String = reader.result; 
                //console.log('Base64 String - ', base64String); 
                this.assignImageToArray(base64String,blob)
                }.bind(this)
        },
        assignImageToArray(img,blob) {
                this.pdfBackground.push(img)
                localStorage.pdfBackground=JSON.stringify(this.pdfBackground)
                this.$forceUpdate
        },
        deleteItem (index) {
            console.log("delete:" + index)
            this.pdfBackground.splice(index,1)
            localStorage.pdfBackground=JSON.stringify(this.pdfBackground)
        },
        active(index) {
            console.log('active page:' + index)
            if (this.page==index) return false
            if (this.isChange==true) {
                let save=confirm("If you change the page you lost your modification, do you want to continue ?")
                if (save==false) return false
            }
            this.isChange=false
            if ((this.$refs.pdfMapper) && this.configPagesPdf.length!=0) this.$refs.pdfMapper.getSelection()
            this.activeImage=this.pdfBackground[index]
            localStorage.activeImage=index
            this.signDescriptions={}
            if (this.configPagesPdf.length!=0){
                for (let i=0;i<this.configPagesPdf[index].signElements.length;i++) {
                    this.signDescriptions[this.configPagesPdf[index].signElements[i].id]= {
                        title: this.configPagesPdf[index].signElements[i].title,
                        text: this.configPagesPdf[index].signElements[i].text, 
                    }
                }
            }
            this.page=index
            //this.eventIdSelected=this.listSurveys[index]
            this.$refs.listComponent.clearSelections()
            
            setTimeout(function () { 
                this.resumeSelection(index)
            }.bind(this), 100)
        
        },
        resumeSelection(page) {
            console.log('resumeSelection')
            this.$refs.pdfMapper.clearSelection()
            console.log(this.survey)
            if (this.surveyMap != null) {
                let currentPage=this.surveyMap.pages[page].coords
                let signElement=this.surveyMap.pages[page].signElements
                let todayElements=this.surveyMap.pages[page].todayElements
                let allId=Object.keys(currentPage)
                for (let i in allId) {
                    let currentObject=currentPage[allId[i]]
                    console.log(currentObject.id)
                    console.log('--->' + this.getPositionQuestion(parseInt(currentObject.id)))
                    console.log(currentObject)
                    let rowOption=null
                    let colOption=null
                    let index=this.getPositionQuestion(parseInt(currentObject.id))
                    if (currentObject.type=="simpletext" || currentObject.type=="email" ||currentObject.type=="tel"||currentObject.type=="date" || currentObject.type=="dropdown") {
                        let objCoord= {
                                    index: index,
                                    rowOption: rowOption,
                                    colOption: colOption,
                                    coords: {
                                        x: currentObject.x,
                                        y: currentObject.y,
                                    },
                        }
                        if (this.$refs.pdfMapper) {
                        this.$refs.pdfMapper.addField({
                            configObj: this.survey[index],
                            objCoord: objCoord,
                        })
                        } 
                    } else if (currentObject.type=="multiplegrid" || currentObject.type=="multiple") {
                        for (let y in currentObject.coord) {
                            console.log(currentObject.coord[y])
                            let arrayCoord=currentObject.coord[y].coord.split("_")
                            rowOption=parseInt(arrayCoord[0])
                            colOption=parseInt(arrayCoord[1])
                            let objCoord= {
                                        index: index,
                                        rowOption: rowOption,
                                        colOption: colOption,
                                        coords: {
                                            x: currentObject.coord[y].x,
                                            y: currentObject.coord[y].y,
                                        },
                            }
                            this.$refs.pdfMapper.addField({
                                configObj: this.survey[index],
                                objCoord: objCoord,
                            })
                        }
                    }
                }
                for (let i=0;i<signElement.length;i++) {
                    console.log("........>" + i)
                    let obj={
                            configObj: {
                                type: 'sign'
                            },
                            objCoord: {
                                rowOption: null,
                                colOption: null,
                                index: 'sign_' + signElement[i].id, 
                                coords: {
                                            x: signElement[i].x,
                                            y: signElement[i].y,
                                },
                            },
                    }
                    this.$refs.pdfMapper.addField(obj)
                }
                for (let i=0;i<todayElements.length;i++) {
                    console.log("........>" + i)
                    let obj={
                            configObj: {
                                type: 'today'
                            },
                            objCoord: {
                                rowOption: null,
                                colOption: null,
                                index: 'today_' + todayElements[i].id, 
                                coords: {
                                            x: todayElements[i].x,
                                            y: todayElements[i].y,
                                },
                            },
                    }
                    this.$refs.pdfMapper.addField(obj)
                }

            } 


        },
        getStyle () {
                return 'cursor:pointer'
        },
        getConfigPdfObject(obj,x=-1,y=-1) {
            let returnObject=null
            if (obj.type=="email"
                ||obj.type=="simpletext"
                ||obj.type=="tel"
                ||obj.type=="date"
                ) {

                returnObject=  {
                    "id": obj.id.toString(),
                    "description": obj.title,
                    "type": obj.type,
                    "append" : false,
                    "x":x,
                    "y":y,
                    "align": "left",
                    "font": "Courier-Bold",
                    "fontSize": 12
                }
            } else if (obj.type=='multiplegrid') {
                returnObject=  {
                    "id": obj.id.toString(),
                    "description": obj.title,
                    "type": obj.type,
                    "append" : false,
                    "coord" : [],
                }
            } else if (obj.type=='choice/check') {
                returnObject=   {
                                "description": "choice/check",
                                "type": obj.type,
                                "coord" : obj.coord,
                                "x":x,
                                "y":y,
                                "align": "left",
                                "font": "Courier-Bold",
                                "checkChar": "X",
                                "fontSize": 12
                            }
            } else if (obj.type=='today') {
                returnObject=   {
                                "description": obj.type,
                                "id" : obj.id,
                                "x":x,
                                "y":y,
                                "align": "left",
                                "font": "Courier-Bold",
                                "fontSize": 12
                            }
            } else if (obj.type=='sign') {
                let title=null
                let text=null
                if (obj.text) 
                    text=obj.text
                else
                    text=this.signDescriptions[obj.id].text
                if (obj.title) 
                    title=obj.title
                else
                    title=this.signDescriptions[obj.id].title

                returnObject=   {
                                "description": obj.type,
                                "id" : obj.id,
                                "title": title,
                                "text": text,
                                "x":x,
                                "y":y,
                            }
            }
            return returnObject

        },
        generate(e) {
            console.log('generate')
            console.log(e)
            let selection=e
            let pageElements={}
            let signElements=[]
            let todayElements=[]
            for (let i=0;i<selection.length;i++) {
                let arrayElement=selection[i].name.split('_')
                let index=arrayElement[0]
                let coord=null
                if (arrayElement.length==2) coord=arrayElement[1]
                if (arrayElement.length==3) coord=arrayElement[1] + "_" + arrayElement[2] 
                if (isNaN(index)==false) {
                    if (this.survey[index].type=='multiplegrid' && (!pageElements[this.survey[index].id])) {
                        pageElements[this.survey[index].id]=this.getConfigPdfObject(this.survey[index],selection[i].x,selection[i].y)
                        pageElements[this.survey[index].id].coord.push (this.getConfigPdfObject({
                            type: 'choice/check',
                            coord: coord,
                        },selection[i].x,selection[i].y))
                    } else if (this.survey[index].type=='multiplegrid' && (pageElements[this.survey[index].id])) {
                        pageElements[this.survey[index].id].coord.push (this.getConfigPdfObject({
                            type: 'choice/check',
                            coord: coord,
                        },selection[i].x,selection[i].y))

                    } else {
                        pageElements[this.survey[index].id]=this.getConfigPdfObject(this.survey[index],selection[i].x,selection[i].y)
                    }
                } else {
                    if (index=='sign') {
                        console.log(this.configPagesPdf)
                        let arraySigns=this.surveyMap.pages[this.page].signElements
                        let pos = arraySigns.map(function(e) { return e.id; }).indexOf(arrayElement[1]);
                        if (pos!= -1) {
                            signElements.push (this.getConfigPdfObject({
                                type: index,
                                id: arraySigns[pos].id,
                                title: arraySigns[pos].title,
                                text: arraySigns[pos].text,
                            },selection[i].x,selection[i].y))
                        } else {
                            console.log('non trovato')
                            console.log(this.signDescriptions[arrayElement[1]])
                            console.log('--------------')
                            signElements.push (this.getConfigPdfObject({
                                type: index,
                                id: arrayElement[1],
                                title: this.signDescriptions[arrayElement[1]].title,
                                text: this.signDescriptions[arrayElement[1]].text,
                            },selection[i].x,selection[i].y))
                        }
                    } else if (index=='today') {
                        todayElements.push (this.getConfigPdfObject({
                            type: index,
                            id: arrayElement[1],
                        },selection[i].x,selection[i].y))
                    }
                }
            }
            this.addPage({
                coords: pageElements,
                todayElements: todayElements,
                signElements: signElements,

            })
        },
        addPage(pageElements) {
            console.log('addPage')
            console.log(this.configPagesPdf)
            console.log('-------------------------')
            for (let i=0;i<this.pdfBackground.length;i++) {
                if ((!this.configPagesPdf[i]) && i !=this.page) {
                    this.configPagesPdf.push({
                        coords: {},
                        signElements:[],
                        todayElements: [],
                    })
                } else if ((this.configPagesPdf[i]) && i ==this.page) {
                    this.configPagesPdf[i]=pageElements
                } else if ((!this.configPagesPdf[i]) && i ==this.page) {
                    this.configPagesPdf.push(pageElements)
                } 
            }
            console.log(this.configPagesPdf)
            this.surveyMap={
                pages: this.configPagesPdf
            }
            this.$emit('changeConfig',this.surveyMap)
        },
        getPositionQuestion(id) {
            let pos = this.survey.map(function(e) { return e.id; }).indexOf(id);
            return pos
        },


    },
    components: {
        fileUpload,
        surveyImageMapper,
        listComponentMapper,
    }
}
</script>

