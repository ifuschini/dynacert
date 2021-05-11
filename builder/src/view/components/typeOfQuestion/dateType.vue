<template>
    <div>
        <b-input-group class="mb-3">
        <b-form-input
            id="example-input"
            v-model="dateToShow"
            type="text"
            placeholder="DD/MM/YYYY"
             v-mask="'##/##/####'"
            autocomplete="off"
        ></b-form-input>
        <b-input-group-append>
            <b-form-datepicker
            v-model="date"
            button-only
            right
            locale="it-IT"
            aria-controls="example-input"
            @context="onContext"
            ></b-form-datepicker>
        </b-input-group-append>
        </b-input-group>


          <!--date-picker v-model="time" 
                       valueType="format"></date-picker-->
    </div>
</template>
<script>

export default {
    name: 'dateType',
    props: [
        "question"
    ],
    watch: {
        question: function() {
            this.date=null
        },
        dateToShow() {
            this.correctDate()
        }
    },
    data() {
        return {
          date:'',
          dateToShow: '',
            
        }
    },
    created() {
        let userData=JSON.parse(localStorage.userData)
        if (userData[this.question.id]) 
        {
            this.dateToShow=userData[this.question.id]
            this.correctDate()
        }
    },
    methods: {
        correctDate() {
            if (this.dateToShow.length==10) {
                let arrayDate=this.dateToShow.split("/")
                this.date=arrayDate[2] + '-' + arrayDate[1] + '-' + arrayDate[0] 
                console.log(this.date)
            }

        },
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var objReturn=null
            if (this.date==null && this.question.isMandatory==true) {
                objReturn= {
                    status: 'ko',
                    errorMessage: this.language[this.config.currentLanguage].errorIsMandatory
                }            
            } else {
                objReturn= {
                    status: 'ok',
                    object: this.dateToShow
                }            
            }
            return objReturn
        },
        onContext(ctx) {
            if (ctx.selectedYMD.length != 0) {
                let arrayDate=ctx.selectedYMD.split("-")
                this.dateToShow=arrayDate[2] + '/' + arrayDate[1] + '/' + arrayDate[0] 
            }
            console.log(ctx)
        },    
    },
  //  components: { DatePicker },


    
}
</script>