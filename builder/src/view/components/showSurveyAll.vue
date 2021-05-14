<template>
<div>
    <div  v-for="(question,index) in survey" v-bind:key="index" style="align-content: left;">
            <showSingleQuestion 
                ref="question"
                :question="question"
                :index="index"
            />
    </div>
</div>
    
</template>
<style scoped>

</style>
<script>
import showSingleQuestion from './showSingleQuestion'
export default {
    name: 'showSurveySinglePage',
    props: [
        "survey",
    ],
    watch: {
        survey: function (val) {
        },
    },
    data() {
        return {
        }
    },
    created() {
    },
    methods: {
    },
    components: {
        showSingleQuestion
    }

}
</script>