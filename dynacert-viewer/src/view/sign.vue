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
                        <b-button  @click="clear" style="margin-right:20px;min-width:120px;margin-top:10px;margin-bottom:5px;">{{language[config.currentLanguage].cancel}}</b-button>                                            
                        <b-button v-on:click="goNextSign()"   variant="success" style="min-width:120px;margin-top:10px;margin-bottom:5px;" :disabled='disableSignBtn'>{{btnLabel}}</b-button><br/>
                        <b-alert v-if="showSignAlert" variant="danger" class="alertMessage" show>{{alertMessage}}</b-alert>
                    </b-collapse>
                    </div>
                <b-collapse id="collapse-4" v-model="completeVisible" class="mt-2">
                <h3>{{language[config.currentLanguage].thankYou}}</h3><br/>
                <h3>{{language[config.currentLanguage].pdfCreated}}</h3>
                 <b-button v-on:click="downloadPdf()" style="margin-bottom:10px" size="sm">Download pdf<b-icon icon="file-earmark-arrow-down"></b-icon></b-button><br/>

                </b-collapse>
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
import dynacert from '../dynacertLib/dynacert'
import axios from 'axios'
import vueSignature from "vue-signature"
import { serverBus } from '../main'
import privacy from './privacy'
import base64 from '../dynacertLib/_base64ToArrayBuffer'

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
            formConfig: null,
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
        console.log(this.$route.params.data)
            if (this.$route.params.data)
                this.pdfData=this.$route.params.data.pdfData
            else 
                this.goHome()
        this.startDownloadTemplate()
    },
    methods: {
        goHome() {
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
        startDownloadTemplate() {
            if (localStorage.formConfig) this.formConfig=JSON.parse(localStorage.formConfig)
            else this.goHome()
            
            if (localStorage.configPdf) this.configPdf=JSON.parse(localStorage.configPdf)
            let pdfBack=JSON.parse(localStorage.pdfBack)
            for (let i=0; i<pdfBack.length;i++) { 
                let data=pdfBack[i].substring(pdfBack[i].indexOf('base64') + 7)
                this.imagePdf.push(base64._base64ToArrayBuffer(data))
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
        async createPdf() {
            this.showSignAlert=false
            this.signToShow=null
            console.log("createSimplePdf")
            if ((this.$refs.signature) && this.$refs.signature.isEmpty()==true ) {
                this.alertMessage='Per favore apporre la firma'
                this.showSignAlert=true
                return false
            } 
            let buffer=await (dynacert.createDynacert(this.configPdf,
                                                     this.imagePdf,
                                                     this.pdfData,
                                                     this.formConfig,
                                                     this.arrayGraphicSign,
                                                     this.defaultSignWidth,
                                                     this.defaultSignHeight))
            this.sendPdf(buffer)
            this.bufferStreamPdf=buffer
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
                link.download="dyna-cert.pdf";
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
            //this is only an example if you want to send the pdf to a service
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
        gotoFormViewer() {
            this.$router.push({ name: 'Form' })
        }

    }
    
}
</script>