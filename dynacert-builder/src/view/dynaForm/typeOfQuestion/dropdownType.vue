<template>
    <div>
              <b-select
                size="lg"
                horizontal
                :value.sync="selected"
                :options="question.rowOption"
                placeholder="Please select"
              />
    </div>
</template>
<style scoped>

</style>
<script>
import { ModelSelect } from 'vue-search-select'

export default {
    name: 'dropdownType',
    props: [
        "question"
    ],
        watch: {
        question: function (val) {
            this.selected=null
        },
    },

    data() {
        return {
            selected: null,
            
        }
    },
    methods: {
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var objReturn=null
            if (this.selected==null && this.question.isMandatory==true) {
                objReturn= {
                    status: 'ko',
                    errorMessage: this.language[this.config.currentLanguage].errorIsMandatory
                }            
            } else {
                objReturn= {
                    status: 'ok',
                    object: this.selected
                }            
            }
            return objReturn
        },
    },
    components: {
      ModelSelect,

    }

    
}
</script>