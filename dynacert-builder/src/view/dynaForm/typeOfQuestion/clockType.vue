<template>
    <div>
          <!--date-picker v-model="time"
                      type="time"
                      valueType="format"></date-picker-->
    </div>
</template>
<script>
//import DatePicker from 'vue2-datepicker';
//import 'vue2-datepicker/index.css';

export default {
    name: 'dateType',
    props: [
        "question"
    ],
    watch: {
        question: function() {
            this.time=null
        }
    },
    data() {
        return {
          time:null        
        }
    },
    methods: {
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var objReturn=null
            if (this.time==null && this.question.isMandatory==true) {
                objReturn= {
                    status: 'ko',
                    errorMessage: this.language[this.config.currentLanguage].errorIsMandatory
                }            
            } else {
                objReturn= {
                    status: 'ok',
                    object: this.time
                }            
            }
            return objReturn
        },
    },
   // components: { DatePicker },


    
}
</script>