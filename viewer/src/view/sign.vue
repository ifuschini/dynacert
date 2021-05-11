<template>
    <div>
        <center>
            <div v-if="showPreview==true">
                <div v-if="signToShow!=null">
                    <h1>Sign {{signToShow + 1}} of {{totalSignFieldToShow}}</h1>
                    <h5>{{signFieldToShow[signIdToShow].title}}</h5>
                    <h6>{{signFieldToShow[signIdToShow].text}}</h6>
                    <b-collapse id="collapse-4" v-model="signVisible" class="mt-2">
                        <vueSignature v-if="mobileSignUrl==null" ref="signature" :sigOption="optionSignature"  class="signature"></vueSignature> 
                        <div class="signature" v-if="mobileSignUrl!=null">
                            <img :src="mobileSignUrl" class="imageSignMobile" />
                        </div>
                        <br></br>
                        <b-button  @click="clear" style="margin-right:20px;min-width:120px;margin-top:10px;margin-bottom:5px;">Cancella</b-button>                                            
                        <b-button v-on:click="goNextSign()"   variant="success" style="min-width:120px;margin-top:10px;margin-bottom:5px;" :disabled='disableSignBtn'>{{btnLabel}}</b-button><br/>
                        <b-alert v-if="showSignAlert" variant="danger" class="alertMessage" show>{{alertMessage}}</b-alert>
                    </b-collapse>
                    </div>
                <b-collapse id="collapse-4" v-model="completeVisible" class="mt-2">
                <h3>Grazie!</h3><br/>
                <h3>Il documento è stato inviato correttamente</h3>
                 <b-button v-on:click="downloadPdf()" style="margin-bottom:10px" size="sm">Download pdf<b-icon icon="file-earmark-arrow-down"></b-icon></b-button><br/>

                </b-collapse>
                <!--iframe ref="iframe" class="iframeClassHidden"></iframe-->
            </div>
        </center>
                <privacy/>   
   </div>
</template>
<style scoped>
.infor {
    font-size:0.6rem;
}
.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}
.loader2 {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 2s linear infinite;
}
.alertMessage {
    max-width:800px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.iframeClass {
      width: 100vw;; 
      height:100vw; 
      max-width:600px; 
      max-height:850px; 
}
.iframeClassHidden {
        width:0; 
        height:0; 
        border:0; 
        border:none;
    }
@media only screen and (max-width: 600px) {
    .iframeClass {
        width:0; 
        height:0; 
        border:0; 
        border:none;
    }
}
.visibleIframeClass {
      width: 100vw;
      height:100vw; 
      max-width:600px; 
      max-height:850px; 
      border-color: rgb(3, 130, 248);
      border: 10px solid rgb(3, 130, 248);
      border-radius: 10px;
      margin-top: 10px;
}
.imageSignMobile {
    width:480px;
    height: 100px;
}
.signature {
    border-color:black;
    border-style:solid;
    max-width:480px;
    max-height:100px;
    margin-left:2px;
    margin-right: 2px;
    background-color: #ffffff;
}


</style>
<script>
import PDFDocument from 'pdfkit/js/pdfkit.standalone'
import blobStream from "blob-stream"
import axios from 'axios'
import QRCode from 'qrcode'
import vueSignature from "vue-signature"
import { serverBus } from '../main'
import privacy from './privacy'




export default {
    components: {
        vueSignature,
        privacy,
    },
    props: [
        "step",
        'globalDisable',
        'fromWebService',        

    ],
    data() {
        return {
            optionSignature:{
                penColor:"rgb(0, 0, 0)",
                //backgroundColor:"rgb(255,255,255)",
                backgroundColor:"transparent",
            },
            captchaResponse: null,
            showPreview: false,
            pdfData: null,
            visibleMobileQR: false,
            signVisible: true,
            completeVisible: false,
            imageURLQRToken: null,
            imagePdf: [],
            configPdf: {},
            surveyConfig: null,
            conferma: false,
            qrcode: null,
            disabledSend:true,
            disableMobileSignBtn:false,
            showSignAlert: false,
            btnLabel: 'Send',
            disableSignBtn: false,
            base64pdf: null,
            token: null,
            cronCheck: null,
            mobileSignUrl: null,
            alertMessage:'',
            defaultSignWidth:200,
            defaultSignHeight: 35,
            signFieldToShow:{},
            totalSignFieldToShow: 0,
            signToShow:null,
            signIdToShow:null,
            arrayGraphicSign: {},
        }
    },
    watch: {
        $route() {
            console.log(this.$route.params.data)
            if (this.$route.params.data)
                this.pdfData=this.$route.params.data.pdfData
            else 
                this.goHome()
        },
        "$refs.signature"() {
                console.log('signature.......')
        }

    },
    created() {
        console.log('mmmmmmmmmm')
        //this.getSurvey()
        console.log(this.$route.params.data)
            if (this.$route.params.data)
                this.pdfData=this.$route.params.data.pdfData
            else 
                this.goHome()
        this.startDownloadTemplate()
    },
    methods: {
        goHome() {
        //this.$router.push({ name: 'Survey'})
        this.$router.push('/')
        }, 
        onVerify: function (response) {
                console.log('Verify: ' + response)
                this.captchaResponse=response
        },
        onExpired: function () {
            console.log('Expired')
            this.captchaResponse=null
         },
        clear(){
            var _this = this;
            if (this.mobileSignUrl ==null) _this.$refs.signature.clear();
            this.showSignAlert=false
            this.disableSignBtn=false
            this.btnLabel='Sign'
            this.mobileSignUrl=null
        },
        undo(){
            var _this = this;
            _this.$refs.signature.undo();
        },
        _base64ToArrayBuffer(base64) {
            var binary_string = window.atob(base64);
            var len = binary_string.length;
            var bytes = new Uint8Array(len);
            for (var i = 0; i < len; i++) {
                bytes[i] = binary_string.charCodeAt(i);
            }
            return bytes.buffer;
        },  
        startDownloadTemplate() {
            if (localStorage.surveyConfig) this.surveyConfig=JSON.parse(localStorage.surveyConfig)
            else this.goHome()
            
            if (localStorage.configPdf) this.configPdf=JSON.parse(localStorage.configPdf)
            let pdfBack=JSON.parse(localStorage.pdfBack)
            for (let i=0; i<pdfBack.length;i++) { 
                //data:image/jpeg;base64,
                let data=pdfBack[i].substring(pdfBack[i].indexOf('base64') + 7)
                console.log(data)
                this.imagePdf.push(this._base64ToArrayBuffer(data))
            }
            this.image
            this.disableMobileSignBtn=false
            this.signVisible=true
            this.visibleMobileQR=false
            this.showPreview=true
            this.manageSigns()
            
        },
        manageSigns () {
            this.signFieldToShow={}
            this.arrayGraphicSign={}
            for (let page=0;page<this.configPdf.pages.length;page++) {
                let signElements=this.configPdf.pages[page].signElements
                for (let sign=0;sign<signElements.length;sign++) {
                    this.signFieldToShow[signElements[sign].id]=signElements[sign]
                }
            }
            this.totalSignFieldToShow=Object.keys(this.signFieldToShow).length
            if (this.totalSignFieldToShow==0) {
                console.log('there is no sign field in this form')
                this.createPdf()
            } else {
                this.signToShow=0
                this.signIdToShow=Object.keys(this.signFieldToShow)[this.signToShow]
                
            }
            console.log('signFieldToShow:')
            console.log(this.signFieldToShow)
        },
        goNextSign() {
            this.arrayGraphicSign[this.signIdToShow]=this.$refs.signature.save().substring(22)
            if (this.signToShow + 1==this.totalSignFieldToShow) {
                this.createPdf()
            } else {
                this.signToShow=this.signToShow + 1
                this.signIdToShow=Object.keys(this.signFieldToShow)[this.signToShow]
                this.clear()
            }

        },
        getTemplate(image) {
            this.showPreview=false
            axios
                .get(this.config.serviceBaseUrl + this.config.url.getImage,
                {
                params: {
                                type: 'pdf',
                                name:image,
                },                    
                responseType: 'arraybuffer',
                })
                .then(response => {
                    this.imagePdf.push(response.data)
                    this.showPreview=true
                    //this.createPdf()
                })
                .catch(e => {
                    this.error = e;
            })
        },
        createPdf() {
            this.showSignAlert=false
            this.signToShow=null
            console.log("createSimplePdf")
            if ((this.$refs.signature) && this.$refs.signature.isEmpty()==true ) {
                this.alertMessage='Per favore apporre la firma'
                this.showSignAlert=true
                return false
            } 
           /*if (this.captchaResponse == null) {
                this.alertMessage='Per favore accettare il captcha'
                this.showSignAlert=true
                return false

            }*/
            this.btnLabel='Firma Applicata'
            this.disableSignBtn=true
            // create a document the same way as above
            var d = new Date();
            var dd = String(d.getDate()).padStart(2, '0');
            var mm = String(d.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = d.getFullYear();
            let doc=null
            let stream=null
            const pdfChunks = [];
            var pdfBuffer=null
            for (let page=0;page<this.configPdf.pages.length;page++) {
                let objPage=this.configPdf.pages[page]
                if (page==0) {
                    doc = new PDFDocument( 
                                    {autoFirstPage: true,
                                    size: 'A4',
                                    layout: 'portrait',
                                    bufferPages: true,
                                    }

                                )
                    //stream = doc.pipe(blobStream())
                } else {
                    doc.addPage()
                }
                // add background image
                if (page<this.imagePdf.length)
                    doc.image(this.imagePdf[page], 0, 0, {width: 580});
                if (page==0) 
                    doc.fontSize(5).text(d, 5, 5)
                
                //coordinate for survey
                
                var wPre = 0;
                var xPre = -1;
                var yPre = -1
                for (let i=0;i<this.pdfData.length;i++){
                    let objData=this.pdfData[i]
                    let coordsKey=Object.keys(objPage.coords)
                    let id=this.surveyConfig[i].id
                    console.log(i + '<--->' + id)
                    //if (i < coordsKey.length) {
                    if (objPage.coords[id]) {
                        if (objPage.coords[id].type=='simpletext' 
                            || objPage.coords[id].type=='date'
                            || objPage.coords[id].type=='email'
                            || objPage.coords[id].type=='tel'                       
                            ) {
                            console.log(objPage.coords[id])
                            let objText=objPage.coords[id]
                            if (objText.append==false) {
                                doc.font(objText.font)
                                    .fontSize(objText.fontSize)
                                    .text(objData, objText.x, objText.y,{align:objText.align})
                            } else {
                                doc.font(objText.font)
                                    .fontSize(objText.fontSize)
                                    .text(objData, xPre + wPre + 5, yPre,{align:objText.align})
                            }
                            wPre=doc.widthOfString(objData)
                            xPre=objText.x
                            yPre=objText.y
                        } else if (objPage.coords[id].type=='multiplegrid') {
                            console.log('multiplegrid')
                             let objMultigrid=objPage.coords[id].coord
                            for (let k=0;k<objData.length;k++){
                                let coordSearch=k + '_' + objData[k]
                                let pos = objMultigrid.map(function(e) { return e.coord; }).indexOf(k + '_' + objData[k]);
                                console.log(coordSearch + '------>' + pos)
                                if (pos != -1) {
                                    console.log(objMultigrid[pos])
                                }
                                doc.font(objMultigrid[pos].font)
                                        .fontSize(objMultigrid[pos].fontSize)
                                        .text(objMultigrid[pos].checkChar, objMultigrid[pos].x, objMultigrid[pos].y,{align:objMultigrid[pos].align})
                                

                            }
                        }
                    }
                }
                //sign document
                console.log('start apply')
                for (let i=0;i<objPage.signElements.length;i++) {
                    let objSign=objPage.signElements[i]
                    var imageSign=null
                    imageSign=this._base64ToArrayBuffer(this.arrayGraphicSign[objSign.id])
                    let widthSign= this.defaultSignWidth
                    let heightSign= this.defaultSignHeight
                    if (objSign.width) widthSign
                    if (objSign.height) heightSign
                    doc.image(imageSign, objSign.x, objSign.y, { fit: [widthSign, heightSign] })
                }
                for (let i=0;i<objPage.todayElements.length;i++) {
                    let objToday=objPage.todayElements[i]
                    doc.font(objToday.font)
                        .fontSize(objToday.fontSize)
                        .text(dd + '/' + mm + '/' + yyyy, objToday.x, objToday.y,{align:objToday.align})
                }
            }

            doc.on('data', (data) => {
                pdfChunks.push(data);
            });
            doc.on('end', () => {
                console.log('end')
                pdfBuffer=Buffer.concat(pdfChunks)
                //this.$refs.iframe.src = stream.toBlobURL('application/pdf');
                /*this.toDataUrl(this.$refs.iframe.src, function(base64) {
                        //console.log(base64)
                        this.base64pdf=base64
                }.bind(this));*/
                setTimeout(function () {
                    this.bufferStreamPdf=pdfBuffer

                    this.sendPdf(pdfBuffer)
                }.bind(this), 100)

            })
            // Finalize PDF file
            doc.end();

        },
        toDataUrl(url, callback) {
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var reader = new FileReader();
                reader.onloadend = function() {
                    callback(reader.result);
                }
                reader.readAsDataURL(xhr.response);
            };
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
        },
        downloadPdf() {
                console.log(this.bufferStreamPdf)
                let blob = new Blob([this.bufferStreamPdf], {type: 'application/pdf'});
                var link=document.createElement('a');
                link.href=window.URL.createObjectURL(blob);
                link.download="documentio.pdf";
                link.click(); 
                console.log('end end ')
        },    

        sendPdf(pdfBuffer) {
                if (this.config.demo==true) {
                    this.processEnd=true
                    this.signVisible=false
                    this.completeVisible=true
                    this.$gtag.event('dynacert-online', { method: 'sign' })
                    return false
                }
                this.$recaptcha("login").then((token) => {
                    serverBus.$emit('showLoader',true)
                    let blob = new Blob([pdfBuffer], {type: 'application/pdf'});
                    let data = new FormData()
                    this.disabledSend=true
                    data.append('firstname', this.$route.params.data.firstName)
                    data.append('lastname', this.$route.params.data.lastName)
                    data.append('cat', 'all')
                    data.append('tokenV3',token)
                    data.append('file', blob)
                    let config = {
                            header : {
                            'Content-Type' : 'multipart/form-data'
                            }
                    }            
                    axios
                        .post(this.config.serviceBaseUrl +  
                            this.config.url.upload,data,config)
                        .then(response => {
                            serverBus.$emit('showLoader',false)
                            if (response.data.status=='ok') {
                                this.processEnd=true
                                this.signVisible=false
                                this.completeVisible=true
                                this.$gtag.event('dynacert-online', { method: 'sign' })

                            } else {
                                alert('Errore qualcosa è andato storto riprovare, più tardi')
                            }
                        })
                        .catch (e=> {
                                    this.error=e;
                    })

                });
        },
        gotoSurveyViewer() {
            this.$router.push({ name: 'Survey' })
        }

    }
    
}
</script>