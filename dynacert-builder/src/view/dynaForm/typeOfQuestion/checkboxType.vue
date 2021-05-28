<template>
    <div>
        <div v-if="question.colOption.length==0">
            <div v-for="(option, optionIndex) in question.rowOption" v-bind:key="optionIndex" >
                <label class="container">{{option}}
                    <input type="checkbox" :name="option" @change="checked(optionIndex)">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div v-if="question.colOption.length>0">
            <b-table-simple>
                <b-thead>
                    <th/>
                    <b-th v-for="(coption, coptionIndex) in question.olOption" v-bind:key="coptionIndex">
                        {{coption}}
                    </b-th> 
                </b-thead>
                <b-tr  v-for="(roption, roptionIndex) in question.rowOption" v-bind:key="roptionIndex">
                    <b-td>{{roption}}</b-td>
                    <b-td v-for="(coption, coptionIndex) in question.colOption" v-bind:key="coptionIndex">
                        <label class="container">
                            <input type="checkbox" @change="checked(roptionIndex,coptionIndex)">
                            <span class="checkmark"></span>
                        </label>
                    </b-td>
                </b-tr>
            </b-table-simple>
        </div>
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

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: rgb(145, 226, 236);
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: rgb(245, 7, 7);
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}


table, td, th {  
  text-align: left;
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
    name: 'multipleChoice',
    props: [
      "question"
    ],
        watch: {
        question: function (val) {
          this.init()
          console.log('change')
        },
    },

    data() {
        return {
            data: null,
            arrayResult: [],
        }
    },
    created() {
        this.init()
    },
    methods: {
          init() {
              console.log("Init")
              this.arrayResult=Array(this.question.rowOption.length)
              for (let i=0;i<this.question.rowOption.length;i++) {
                if (this.question.colOption.length > 0) {
                  this.arrayResult[i]=Array(this.question.colOption.length)
                } else {
                  this.arrayResult[i]=false
                }
              }
              if (this.question.colOption.length > 0) {
                for (let i=0;i<this.question.rowOption.length;i++) {
                  for (let x=0;x<this.question.colOption.length;x++) {
                    this.arrayResult[i][x]=false
                  }
                }
              }
          console.log(this.arrayResult)

        },
        getInfoFromObject () {
            console.log('Component' +  this._name)
            console.log(this.arrayResult)
            let count=0
            if (this.question.colOption.length == 0) {
                for (let i=0;i<this.question.rowOption.length;i++) {
                    if (this.arrayResult[i]==true) count++
                }
            } else {
                for (let i=0;i<this.question.rowOption.length;i++) {
                  for (let x=0;x<this.question.colOption.length;x++) {
                    if (this.arrayResult[i][x]==true) count++
                  }
                }
            }
            var objReturn=null
            if (count < this.question.minValue || this.question.maxValue < count) {
                objReturn= {
                    status: 'ko',
                    errorMessage: 'Choose minimum ' + this.question.minValue + ' and maximum ' + this.question.maxValue + " choices"
                }
            } else if (count == 0 && this.question.isMandatory==true) {           
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
        checked(x,y=null) {
          console.log(this.arrayResult[x][y])
          if (y==null) {
              if (this.arrayResult[x]==true) this.arrayResult[x]=false
              else  this.arrayResult[x]=true
          } else {
            if (this.arrayResult[x][y]==true) this.arrayResult[x][y]=false
            else  this.arrayResult[x][y]=true
          }
        },
    },
    
}
</script>