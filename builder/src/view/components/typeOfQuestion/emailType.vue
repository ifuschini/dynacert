<template>
    <div>
            <b-form-input
                label="Put your text"
                :placeholder="language[config.currentLanguage].textFieldPlaceHolder"
                v-model="text"
                type='email'
            />   
    </div>
</template>
<script>
import * as EmailValidator from 'email-validator'

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
            } else if (this.text!='' && EmailValidator.validate(this.text) == false ) {
                objReturn= {
                    status: 'ko',
                    errorMessage: this.language[this.config.currentLanguage].errorIsNotEmail
                }  
            } else {
                objReturn= {
                    status: 'ok',
                    object: this.text
                }            
            }
            console.log(objReturn)
            return objReturn 
        },
    },
}
</script>