<template>
        <div class="scroll-area">
            <div v-for="(question,index) in localSurvey" v-bind:key="index">
                <div class="title-question" v-if="question.obj.type !='note'" v-on:click="setField(index)" :style="question.backGroundColor" @contextmenu.prevent="$refs.menu.open($event,index)">
                    {{question.obj.title}} <span v-if="question.concatenate==true" class="concatenate">Concatenated</span>
                </div>
                    <div v-if="question.obj.colOption.length==0 && question.obj.type != 'dropdown'">
                        <div v-for="(option, optionIndex) in question.obj.rowOption" v-bind:key="optionIndex" >
                            <label class="container">{{option}}
                                <input type="checkbox" :name="option" :checked='index==indexBefore && optionIndex==rowOptionSelected' @change="setField(index,false,optionIndex,null)">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div v-if="question.obj.colOption.length>0">
                        <b-table-simple>
                            <b-thead>
                                <th/>
                                <b-th v-for="(coption, coptionIndex) in question.obj.olOption" v-bind:key="coptionIndex">
                                    {{coption}}
                                </b-th> 
                            </b-thead>
                            <b-tr  v-for="(roption, roptionIndex) in question.obj.rowOption" v-bind:key="roptionIndex">
                                <b-td>{{roption}}</b-td>
                                <b-td v-for="(coption, coptionIndex) in question.obj.colOption" v-bind:key="coptionIndex">
                                    <label class="container">
                                        <input type="checkbox" @change="setField(index,false,roptionIndex,coptionIndex)" :checked='index==indexBefore && roptionIndex==rowOptionSelected && coptionIndex==colOptionSelected'>
                                        <span class="checkmark"></span>
                                    </label>
                                </b-td>
                            </b-tr>
                        </b-table-simple>
                    </div>
            </div>
            <vue-context ref="menu" v-slot="{ data }">
                <li>
                    <a href="#"  @click="concatenate(data)">Concatenate</a>
                </li>
            </vue-context>


    </div>

</template>
<style scoped>
.title-question {
    border-style: dashed;
    margin-top: 5px;
}
.scroll-area{
  width:100%;
  height:100vh;
  float:left;
  overflow-y:scroll;
}
.concatenate {
    font-size:0.5rem;
    vertical-align: top;
}
</style>
<script>
import VueContext from 'vue-context'


export default {
    name: 'surveyListComponents',
    props: [
        "survey",
        "canSelect",
    ],
    watch: {
        survey: function () {
            this.createSurvey()
        },
    },
    created() {
        this.createSurvey()
    } ,   
    data() {
        return {
            localSurvey:null,
            indexBefore: -1,
            colOptionSelected: null,
            rowOptionSelected: null,
        }
    },
    methods: {
        createSurvey() {
            this.localSurvey=[]
            this.visible=[]
            for (let i=0;i<this.survey.length;i++) {
                this.localSurvey.push({
                    backGroundColor: '',
                    obj: this.survey[i],
                    concatenate: false
                })
            }
            console.log('changechange')
            console.log(this.localSurvey)
        },
        newSurvey(e) {
            this.$emit('updatePosition',
                this.localSurvey
            )

        },
        clearSelections () {
            if (this.indexBefore != -1)
            this.localSurvey[this.indexBefore].backGroundColor=''
        },
        setField(index, isFromFather=false,rowOption=null,colOption=null) {
                let indexReal=index
                if (this.localSurvey[indexReal]==undefined && rowOption==null) {
                    alert('Please choose a page')
                }
                if ((rowOption==null &&
                    colOption==null) 
                    &&
                    (this.localSurvey[indexReal].obj.type=='multiplegrid' ||
                    this.localSurvey[indexReal].obj.type=='multiple')
                    ) {
                    return false
                }
                if (isNaN(index)==true) {
                    let arrayElement=index.split('_')
                    indexReal=parseInt(arrayElement[0])
                }
                if (this.canSelect==true) {
                    if (isNaN(index)==true && (index.substring(0,4)=='sign' || index.substring(0,5)=='today')) {
                        if(this.indexBefore != -1) this.localSurvey[this.indexBefore].backGroundColor=''
                        this.indexBefore=-1
                    } else if (this.localSurvey[indexReal].concatenate==false) {
                        console.log('---setField')
                        console.log(isFromFather)
                        if(this.indexBefore != -1) this.localSurvey[this.indexBefore].backGroundColor=''
                        this.localSurvey[indexReal].backGroundColor='background-color: khaki!important;'
                        this.indexBefore=indexReal
                        this.rowOptionSelected=rowOption
                        this.colOptionSelected=colOption
                        if (isFromFather==false)
                            this.$emit('setField',{
                                index: index,
                                rowOption: rowOption,
                                colOption: colOption,
                                coords: null,
                            })  
                    } 
                } else if (this.localSurvey[index].concatenate==true) {
                        alert('the item is concatenated')
                } else {
                        alert('Please choose a page')
                }
        },
        concatenate(index) {
            console.log('concatenate')
                if (this.localSurvey[index].concatenate==false) 
             this.localSurvey[index].concatenate=true
            else 
             this.localSurvey[index].concatenate=false
            console.log(this.localSurvey[index])
        }

        
    },
    components: {
        VueContext,
    }


}
</script>