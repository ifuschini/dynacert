<template>
    <div>
        <div v-if="question.colOption.length==0">
            <div v-for="(option, optionIndex) in question.rowOption" v-bind:key="optionIndex" >
                <label class="container">{{option}}
                    <input type="radio" :name="question.id" v-on:click="checked(optionIndex)" :value="optionIndex"/>
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div v-if="question.colOption.length>0">
            <b-table-simple>
                <b-thead>
                    <b-th/>
                    <b-th v-for="(coption, coptionIndex) in question.colOption" v-bind:key="coptionIndex">
                        {{coption}}
                    </b-th> 
                </b-thead>
                <b-tr  v-for="(roption, roptionIndex) in question.rowOption" v-bind:key="roptionIndex">
                    <b-td>{{roption}}</b-td>
                    <b-td v-for="(coption, coptionIndex) in question.colOption" v-bind:key="coptionIndex">
                      <div class="custom-control custom-radio b-custom-control-lg">
                            <input type="radio" :name="question.id + '-' + roptionIndex" class="custom-control-input" v-on:click="checked(roptionIndex,coptionIndex)" :id="'__BVID__' + question.id + '' + roptionIndex + '' + coptionIndex"/>
                            <label class="custom-control-label" :for="'__BVID__' + question.id + '' + roptionIndex + '' + coptionIndex">
                            </label>
                      </div>
                    </b-td>
                </b-tr>
            </b-table-simple>
        </div>
    </div>
</template>
<style scoped>
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
    name: 'multipleType',
    props: [
      "question"
    ],
        watch: {
        question: function (val) {
          this.arrayResult=[]
        },
    },

    data() {
        return {
            data: null,
            arrayResult: [],
        }
    },
    methods: {
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var max=1
            if (this.question.colOption.length!=0) max=this.question.colOption.length
            console.log(this.arrayResult)
            console.log(max)
            let count=0
            for (let i=0;i<this.arrayResult.length;i++) {
              if (this.arrayResult[i] != null) count++
            }
            var objReturn=null
            if (count<this.question.rowOption.length && this.question.isMandatory==true) {
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
        checked(x=0,y=null) {
          console.log('checked')
          if (y==null)
            this.arrayResult[0]=x
          else
            this.arrayResult[x]=y
        },
        scrollToTop () {
            this.$el.scrollTop = 0
        },


    },

    
}
</script>