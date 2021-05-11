<template>
    <div>
        <b-form-textarea placeholder="Input your text"  cols="100"  rows="10" v-model="text"/>
    </div>
</template>
<script>
export default {
    name: 'multilineTextType',
    props: [
        "question"
    ],
    watch: {
        id () {
            this.text=''
        }
    },
    data() {
        return {
          text:''
            
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