<template>
<div>
    <!--div  v-for="(question,index) in survey" v-bind:key="index" style="align-content: left;"-->
            <b-row >
                <b-col lg="6" v-for="(question,index) in survey" v-bind:key="index">
                <showSingleQuestion 
                    :question="question"
                    :index="index"
                />            
                </b-col>
            </b-row>
    <!--/div-->
    
</div>
    
</template>
<style scoped>

</style>
<script>
import showSingleQuestion from './showSingleQuestion'

export default {
    name: 'surveyPreview',
    props: [
        "survey",
    ],
    data() {
        return {
            showPreview: true
        }
    },
    created() {
        console.log('surveyPreviewCreated')
    },
    watch: {
   },
    methods: {
    },
    components: {
        showSingleQuestion
    }

}
</script>