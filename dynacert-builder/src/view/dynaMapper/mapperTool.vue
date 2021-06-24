<template>
  <div>
    <b-row> 
        <b-col>
            {{numPages}}
            <pdf 
                style="width:1px;height:1px"
                ref="myPdfComponent"
                :src="src"
                :page="key"
            ></pdf>        

        <formImageMapper
        ref="pdfMapper"
        :imageToLoad="activeImage"
        :lengthForm="form.length"
        :numberOfPage="page"
        v-if="activeImage != null"
        v-on:indexSelected="indexSelected"
        v-on:getSelections="generate"
        />
        </b-col>
        <b-col lg="5">
        <b-row>
        <b-col lg="1">
        Form
            </b-col>
            <b-col>
        <select class="form-control" style="margin-bottom:5px;" @change="getForm($event)" >
            <option value="null">--- select a form --- </option>
            <option v-for="(form,index) in listForms" v-bind:key="index" :value="form.id">{{form.title}}</option>
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
        :form="form"
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
                            accept="image/jpeg,image/png,application/pdf"
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
import formImageMapper from './imageMapper'
import listComponentMapper from './listComponentMapper'
import pdf from 'vue-pdf'



import { serverBus } from '../../main'
import axios from 'axios'

export default {
    name:"formMapper",
    data() {
        return {
            files:[],
            pdfBackground:[],
            activeImage:null,
            page: null,
            isChange: false,
            configPagesPdf:[],
            form:[],
            sign: [],
            today: [],
            signDescriptions: {},
            listForms:[],
            titleSign:null,
            textSign: null,
            showSignForm: false,
            eventIdSelected: null,
            formMap: null,
            src:null,
            numPages:null,
            key: 1,
        }
    },
    created() {
        console.log('formMapper')
        console.log(this.form)
        this.getListForms()
    },
    watch: {
        files () {
            if (this.files[0].file.type !='application/pdf') {

                if (this.files[0].file.size < this.maxSizeImage  * 1024) {
                    this.setImage(this.files[0].blob)
                } else {
                    this.handleImageUpload(this.files[0].file)
                        //this.messageError='Attenzione l\'immagine risulta troppo grande'
                }  
            } else {
                    this.convertBlobToImage64(this.files[0].file,true)
            }
        },

    },
    methods: {
        loadPdf(pdfUrl) {
            this.src=pdf.createLoadingTask(pdfUrl);
            this.src.promise.then(pdf => {   
                console.log(pdf)
                this.numPages = pdf.numPages;
            });
            setTimeout(function () { 
                            this.parsePdf();
            }.bind(this), 100)            
        },     
        parsePdf() {
            //this.pdfBackground=[]
            this.$refs.myPdfComponent.pdf.forEachPage(function(page) {
                this.convertPdfInImage(page)
 
            }.bind(this)) 

        },
        async convertPdfInImage(page) {
                var scale=1.0
                var type= 'image/png'
                var quality= 1.00
                var viewport = page.getViewport({ scale: scale })
                var canvas = document.createElement('canvas'), ctx = canvas.getContext('2d')
                var renderContext = { canvasContext: ctx, viewport: viewport }

                canvas.height = viewport.height
                canvas.width = viewport.width

                // @ts-ignore
                await page.render(renderContext).promise
                this.pdfBackground.push(canvas.toDataURL(type, quality))
        },
        convert() {
                var url = 'pdf/example2.pdf';
                var loadingTask = pdfjsLib.getDocument(url);
                loadingTask.promise.then(function(pdf) {
                //
                // Fetch the first page
                //
                    console.log(pdf)
                    pdf.getPage(1).then(function(page) {
                    //var decoder = new TextDecoder('utf8');
                    //var b64encoded = btoa(decoder.decode(page));
                    //console.log(b64encoded)
                    var scale = 1.0;
                    var viewport = page.getViewport({ scale: scale, });

                    //
                    // Prepare canvas using PDF page dimensions
                    //
                    var canvas = document.getElementById('the-canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    //
                    // Render PDF page into canvas context
                    //
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport,
                        };
                        page.render(renderContext);
                    });   
                });
        },     
        showData(obj) {
            console.log(obj)
        },
        convertBase64(text) {
        return decodeURIComponent(escape(window.atob(text)));
        },
        getListForms () {
                serverBus.$emit('showLoader',true)
                axios
                    .get(this.config.serviceBaseUrl + this.config.url.listForms)
                    .then(response => {
                        serverBus.$emit('showLoader',false)
                        this.listForms = response.data.response
                    })
                    .catch(e => {
                        this.dynaLogout(this)
                        this.error = e;
                })
        },
        saveConfig() {
            if (this.$refs.pdfMapper) this.$refs.pdfMapper.getSelection()
            console.log('saveMapper' + this.eventIdSelected)
            this.isChange=false
            this.$emit('saveMapper', this.eventIdSelected,this.pdfBackground)
        },
        getForm(event) {
        serverBus.$emit('showLoader',true)
        console.log(event)
        this.eventIdSelected=event.target.value
        if (this.$refs.pdfMapper) this.$refs.pdfMapper.clearSelection()
        if (event.target.value=='null') {
            this.form=[]
            this.pdfBackground=[]
            this.eventIdSelected=null
        }
        axios
            .get(this.config.serviceBaseUrl + this.config.url.getForm + event.target.value,{
            }
            )
            .then(response => {
                console.log(response)
                serverBus.$emit('showLoader',false)
                this.activeImage=null
                this.page=null
                this.form=JSON.parse(response.data.form)
                this.getMap(event.target.value)         
            })
            .catch(e => {
                this.dynaLogout(this)
                this.error = e;
        })
        },
        getMap(id) {
            serverBus.$emit('showLoader',true)
            console.log(id)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getMap + id ,{
                }
                )
                .then(response => {
                console.log(response)
                serverBus.$emit('showLoader',false)
                this.formMap=response.data.map
                this.pdfBackground=response.data.images
                console.log('images')
                console.log(this.pdfBackground)
                this.configPagesPdf=[]
            
                if (response.data.map.pages)  {
                    this.configPagesPdf=response.data.map.pages
                }
                if (response.data.status)
                    this.$emit('changeConfig',this.formMap)                
                })
                .catch(e => {
                    this.dynaLogout(this)
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
                configObj: this.form[e.index],
                objCoord: e,
            })
        },
        inputFilter: function (newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                // Filter non-image file
                if (!/\.(jpeg|jpe|jpg|gif|png|webp|pdf)$/i.test(newFile.name)) {
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
                this.convertBlobToImage64(compressedFile,false)
                }.bind(this))
                .catch(function (error) {
                console.log(error.message);
                });
        } ,       
        convertBlobToImage64(blob,isPdf=false) {
                var reader = new FileReader(); 
                    reader.readAsDataURL(blob); 
                    reader.onloadend = function () { 
                    var base64String = reader.result; 
                    //console.log('Base64 String - ', base64String);
                    if (isPdf==false) {
                        this.assignImageToArray(base64String)
                    } else {
                        this.loadPdf(base64String)

                    }
 
                    }.bind(this)
        },
        assignImageToArray(img) {
                this.pdfBackground.push(img)
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
            //this.eventIdSelected=this.listForms[index]
            this.$refs.listComponent.clearSelections()
            
            setTimeout(function () { 
                this.resumeSelection(index)
            }.bind(this), 100)
        
        },
        resumeSelection(page) {
            console.log('resumeSelection')
            this.$refs.pdfMapper.clearSelection()
            console.log(this.form)
            if (this.formMap != null) {
                let currentPage=this.formMap.pages[page].coords
                let signElement=this.formMap.pages[page].signElements
                let todayElements=this.formMap.pages[page].todayElements
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
                            configObj: this.form[index],
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
                                configObj: this.form[index],
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
                    if (this.form[index].type=='multiplegrid' && (!pageElements[this.form[index].id])) {
                        pageElements[this.form[index].id]=this.getConfigPdfObject(this.form[index],selection[i].x,selection[i].y)
                        pageElements[this.form[index].id].coord.push (this.getConfigPdfObject({
                            type: 'choice/check',
                            coord: coord,
                        },selection[i].x,selection[i].y))
                    } else if (this.form[index].type=='multiplegrid' && (pageElements[this.form[index].id])) {
                        pageElements[this.form[index].id].coord.push (this.getConfigPdfObject({
                            type: 'choice/check',
                            coord: coord,
                        },selection[i].x,selection[i].y))

                    } else {
                        pageElements[this.form[index].id]=this.getConfigPdfObject(this.form[index],selection[i].x,selection[i].y)
                    }
                } else {
                    if (index=='sign') {
                        console.log(this.configPagesPdf)
                        let arraySigns=this.formMap.pages[this.page].signElements
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
            this.formMap={
                pages: this.configPagesPdf
            }
            this.$emit('changeConfig',this.formMap)
        },
        getPositionQuestion(id) {
            let pos = this.form.map(function(e) { return e.id; }).indexOf(id);
            return pos
        },


    },
    components: {
        fileUpload,
        formImageMapper,
        listComponentMapper,
        pdf,
    }
}
</script>

