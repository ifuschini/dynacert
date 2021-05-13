<template>
    <div>
        <b-card v-if="question.type!='note'">
            <template #header>
                    <strong>{{question.title}}</strong> 
                        <span v-if="question.minValue!=null" style="float:center">
                            Min: {{question.minValue}}  
                        </span>
                        <span v-if="question.maxValue!=null" style="float:center">
                            Max: {{question.maxValue}}  
                         </span>
                        <span v-if="question.isMandatory==true" style="float:right">
                            {{language[config.currentLanguage].mandatory}}  
                        </span>
            </template>
            <b-card-text>
                <simpleTextType
                    :ref="question.type"
                    v-if="question.type=='simpletext'"
                    :question="question"
                />
                <multilineTextType
                    :ref="question.type"
                    v-if="question.type=='multilinetext'"
                    :question="question"
                />
                <multipleType
                    :ref="question.type"
                    v-if="question.type=='multiple' || question.type=='multiplegrid'"
                    :question="question"
                />
                <checkboxType
                    :ref="question.type"
                    v-if="question.type=='checkbox' || question.type=='checkboxgrid'"
                    :question="question"
                />
                <dropdownType
                    :ref="question.type"
                    v-if="question.type=='dropdown'"
                    :question="question"
                />
                <linearscalarType
                    :ref="question.type"
                    v-if="question.type=='linearscalar'"
                    :question="question"
                />
                <dateType
                    :ref="question.type"
                    v-if="question.type=='date'"
                    :question="question"
                />
                <clockType
                    :ref="question.type"
                    v-if="question.type=='time'"
                    :question="question"
                />
                <emailType
                    :ref="question.type"
                    v-if="question.type=='email'"
                    :question="question"
                />
                <telType
                    :ref="question.type"
                    v-if="question.type=='tel'"
                    :question="question"
                />
            </b-card-text>
        </b-card>
        <div v-if="question.type=='note'" class="note">
                <noteType
                    :ref="question.type"
                    v-if="question.type=='note'"
                    :question="question"
                />
        </div>
    </div>            
    
</template>
<style scoped>
.note {
    border-style: solid;
}
</style>
<script>
import simpleTextType from './typeOfQuestion/simpleTextType'
import multilineTextType from './typeOfQuestion/multilineTextType'
import multipleType from './typeOfQuestion/multipleType'
import checkboxType from './typeOfQuestion/checkboxType'
import dropdownType from './typeOfQuestion/dropdownType'
import linearscalarType from './typeOfQuestion/linearscalarType'
import dateType from './typeOfQuestion/dateType'
import clockType from './typeOfQuestion/clockType'
import noteType from './typeOfQuestion/noteType'
import emailType from './typeOfQuestion/emailType'
import telType from './typeOfQuestion/telType'

export default {
    name: 'showSingleQuestion',
    props: [
        "question",
        "index"
    ],
    data() {
        return {

        }
    },
    methods: {
        getInfoFromObject () {
            console.log(this.question.type)
            return this.$refs[this.question.type].getInfoFromObject()
        }

    },
    components: {
        multipleType,
        checkboxType,
        dropdownType,
        linearscalarType,
        dateType,
        clockType,
        simpleTextType,
        multilineTextType,
        noteType,
        emailType,
        telType,
    }

}
</script>