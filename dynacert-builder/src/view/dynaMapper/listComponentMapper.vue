<template>
        <div>
            <div v-for="(question,index) in localForm" v-bind:key="index">
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
                        <b-table-simple outlined>
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
    border-style: solid;
    margin-top: 5px;
    border-color: #ced4da;
    border-width: 1px;
}
.concatenate {
    font-size:0.5rem;
    vertical-align: top;
}
</style>
<script>
import VueContext from 'vue-context'


export default {
    name: 'formListComponents',
    props: [
        "form",
        "canSelect",
    ],
    watch: {
        form: function () {
            this.createForm()
        },
    },
    created() {
        this.createForm()
    } ,   
    data() {
        return {
            localForm:null,
            indexBefore: -1,
            colOptionSelected: null,
            rowOptionSelected: null,
        }
    },
    methods: {
        createForm() {
            this.localForm=[]
            this.visible=[]
            for (let i=0;i<this.form.length;i++) {
                this.localForm.push({
                    backGroundColor: '',
                    obj: this.form[i],
                    concatenate: false
                })
            }
            console.log('changechange')
            console.log(this.localForm)
        },
        newForm(e) {
            this.$emit('updatePosition',
                this.localForm
            )

        },
        clearSelections () {
            if (this.indexBefore != -1)
            this.localForm[this.indexBefore].backGroundColor=''
        },
        setField(index, isFromFather=false,rowOption=null,colOption=null) {
                let indexReal=index
                if (this.localForm[indexReal]==undefined && rowOption==null) {
                    alert('Please choose a page')
                }
                if ((rowOption==null &&
                    colOption==null) 
                    &&
                    (this.localForm[indexReal].obj.type=='multiplegrid' ||
                    this.localForm[indexReal].obj.type=='multiple')
                    ) {
                    return false
                }
                if (isNaN(index)==true) {
                    let arrayElement=index.split('_')
                    indexReal=parseInt(arrayElement[0])
                }
                if (this.canSelect==true) {
                    if (isNaN(index)==true && (index.substring(0,4)=='sign' || index.substring(0,5)=='today')) {
                        if(this.indexBefore != -1) this.localForm[this.indexBefore].backGroundColor=''
                        this.indexBefore=-1
                    } else if (this.localForm[indexReal].concatenate==false) {
                        console.log('---setField')
                        console.log(isFromFather)
                        if(this.indexBefore != -1) this.localForm[this.indexBefore].backGroundColor=''
                        this.localForm[indexReal].backGroundColor='background-color: khaki!important;'
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
                } else if (this.localForm[index].concatenate==true) {
                        alert('the item is concatenated')
                } else {
                        alert('Please choose a page')
                }
        },
        concatenate(index) {
            console.log('concatenate')
                if (this.localForm[index].concatenate==false) 
             this.localForm[index].concatenate=true
            else 
             this.localForm[index].concatenate=false
            console.log(this.localForm[index])
        }

        
    },
    components: {
        VueContext,
    }


}
</script>