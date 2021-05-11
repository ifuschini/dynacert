<template>
    <div>
            <b-form-input
                label="Put your text"
                :placeholder="language[config.currentLanguage].textFieldPlaceHolder"
                v-model="text"
                v-mask="'### ### ####'"
                type='tel'
            />   
    </div>
</template>
<script>

export default {
    name: 'mailType',
    props: [
        "question"
    ],
    watch: {
        question:function () {
            this.text=''
        }
    },
    data() {
        return {
          text:''      
        }
    },
    created() {
        let userData=JSON.parse(localStorage.userData)
        if (userData[this.question.id]) this.text=userData[this.question.id]
    },
    methods: {
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var objReturn=null
            if (this.text=='' && this.question.isMandatory==true) {
                objReturn= {
                    status: 'ko',
                    errorMessage: this.language[this.config.currentLanguage].errorIsMandatory
                }  
            }  else {
                console.log(this.text)
                let text=this.text.toString()
                objReturn= {
                    status: 'ok',
                    object: text.replace('/ /','')
                }            
            }
            console.log(objReturn)
            return objReturn 
        },
    },
}
</script>