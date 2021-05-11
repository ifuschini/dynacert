<template>
    <div>
            <table>
                <thead>
                    <th v-for="(option, optionIndex) in value" v-bind:key="optionIndex">
                        {{option}}
                    </th> 
                </thead>
                <tr>
                    <td v-for="(option, optionIndex) in value" v-bind:key="optionIndex" style="text-align: center;">
                      <center>
                        <label class="container">
                            <input type="radio" :name="question.id" v-on:click="checked(optionIndex)"/>
                            <span class="checkmark"></span>
                        </label>
                      </center>
                    </td>
                </tr>
            </table>
    </div>
</template>
<style scoped>
/* Customize the label (the container) */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 47%;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container .checkmark:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;
}
table, td, th {  
  text-align: center;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}

</style>
<script>
export default {
    name: 'linearScalarType',
    props: [
      "question"
    ],
        watch: {
        question: function (val) {
        },
    },
    created() {
        for (var i=0;i<this.question.maxValue - this.question.minValue + 1;i++) {
                this.value[i]=  parseInt(this.question.minValue) + i
        }
    },
    data() {
        return {
            value: [],
            arrayResult: [],       
        }
    },
    methods: {
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var max=1
            console.log(this.arrayResult)
            var objReturn=null
            if (this.arrayResult.length<max && this.question.isMandatory==true) {
                objReturn= {
                    status: 'ko',
                    errorMessage: this.language[this.config.currentLanguage].errorIsMandatory
                }            
            } else {
                objReturn= {
                    status: 'ok',
                    object: this.arrayResult
                }            
            }
            return objReturn
        },
        checked(x=0) {
            this.arrayResult[0]=x
        },
    },

    
}
</script>