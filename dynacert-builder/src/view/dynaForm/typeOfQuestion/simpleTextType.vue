<template>
    <div>
            <b-input
                label="Put your text"
                :placeholder="language[config.currentLanguage].textFieldPlaceHolder"
                v-model="text"
            />   
    </div>
</template>
<script>
export default {
    name: 'simpleTextType',
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
        if (localStorage.userData) {
            let userData=JSON.parse(localStorage.userData)
            if (userData[this.question.id]) this.text=userData[this.question.id]
            if (userData[this.question.nameParameterForLocalstorage]) this.text=userData[this.question.nameParameterForLocalstorage]
        }
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