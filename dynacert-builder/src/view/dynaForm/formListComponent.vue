<template>
        <table style="width:100%">
                            <draggable v-model="localForm" @end="newForm" tag="tbody">
                            <tr v-for="(question,index) in form" v-bind:key="index">
                                <td>
                                    <b-card>
                                    <template #header>
                                        <strong>{{index + 1}} - {{question.title}} ({{question.labelType}}) </strong> 
                                        <span v-if="question.minValue != null" style="float:center">
                                            Min: {{question.minValue}}  
                                        </span>
                                        <span v-if="question.maxValue != null" style="float:center">
                                            Max: {{question.maxValue}}  
                                        </span>
                                        <span v-on:click="form.splice(index,1)" style="float:right">
                                            <b-icon icon="trash-fill"/>
                                        </span>
                                        <span v-if="question.isMandatory==true" style="float:right">
                                            mandatory  
                                        </span>
                                    </template>
                                    <b-card-text v-if="question.rowOption.length == 0 && question.colOption.length==0">
                                        <b-row>
                                            <b-col>
                                                <b-table-simple>
                                                    <b-thead>
                                                        <b-th v-if="question.rowOption.length != 0"> Raw </b-th>
                                                        <b-th v-if="question.colOption.length != 0"> Column </b-th>
                                                    </b-thead>
                                                    <b-tr>
                                                        <b-td>
                                                            <b-list-group>
                                                                <b-list-group-item v-for="(option,index3) in question.rowOption" v-bind:key="index3">
                                                                    {{option}}
                                                                </b-list-group-item>
                                                            </b-list-group>
                                                        </b-td>
                                                        <b-td v-if="question.colOption.length != 0">
                                                            <b-list-group>
                                                                <b-list-group-item v-for="(option,index3) in question.colOption" v-bind:key="index3">
                                                                    {{option}}
                                                                    </b-list-group-item>
                                                            </b-list-group>
                                                        </b-td>                
                                                    </b-tr>
                                                </b-table-simple>
                                            </b-col>
                                        </b-row>                        
                                    </b-card-text>
                                    </b-card>
                                </td>
                            </tr>
                            </draggable>
        </table>

</template>
<style scoped>

</style>
<script>
import draggable from 'vuedraggable'
import { cilTrash } from '@coreui/icons'

export default {
    name: 'formListComponents',
    trash: cilTrash,
    props: [
        "form",
    ],
    watch: {
        form: function (val) {
            this.localForm=this.form
            console.log('change')
        },
    },
    data() {
        return {
            localForm:null,
        }
    },
    methods: {
        newForm(e) {
            console.log(this.localForm)
            this.$emit('updatePosition',
                this.localForm
                
            )

        }
        
    },
    components: {
        draggable,
    }


}
</script>