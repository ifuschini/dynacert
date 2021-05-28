<template>
    <span v-html="question.title">
    </span>
 </template>
<script>

export default {
    name: 'dateType',
    props: [
        "question"
    ],
    watch: {
        question: function() {
        }
    },
    data() {
        return {
        }
    },
    methods: {
        getInfoFromObject () {
            console.log('Component' +  this._name)
            var objReturn=null
            objReturn= {
                    status: 'ok',
                    object: null,
            }            
            return objReturn
        },
    },
}
</script>