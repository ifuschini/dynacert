<template>
<div>
    <div  v-if="question != null" style="align-content: left;">
            <showSingleQuestion 
                ref="question"
                :question="question"
                :index="index"
            />
             <b-alert show variant="danger" v-if="alertFormMessage.length!=0">{{alertFormMessage}}</b-alert>
            <b-button type="submit" size="sm" style="float:right;margin-right: 5px;" color="success" v-on:click="showQuestion()" v-if="index<form.length"><b-icon icon="check-square"/> Next Step</b-button>
    </div>
                <div v-if="index==form.length"><h3>Thank you</h3></div>
    {{responses}}
</div>
    
</template>
<style scoped>

</style>
<script>
import showSingleQuestion from './showSingleQuestion'
export default {
    name: 'showFormSinglePage',
    props: [
        "form",
    ],
    watch: {
        form: function (val) {
            this.index=0
            this.showQuestion()
            this.responses=[]
            this.alertFormMessage=''
            console.log('change')
        },
    },
    data() {
        return {
            index:0,
            responses:[],
            alertFormMessage: ''
        }
    },
    created() {
        this.showQuestion(0)
    },
    methods: {
       showQuestion() {
            console.log('showQuestion')
            if (this.$refs.question) {
                let objectToStore=this.$refs.question.getInfoFromObject()
                console.log(this.$refs['question'])
                if (objectToStore.status=='ok') {
                        this.responses.push(objectToStore.object)
                        this.alertFormMessage=''
                        this.index=this.index + 1
                    }   else {
                        this.alertFormMessage=objectToStore.errorMessage
                    }
            }
            this.question=this.form[this.index]
       },
    },
    components: {
        showSingleQuestion
    }

}
</script>