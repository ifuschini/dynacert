<template>
<div class="scroll-area2">
    <!-- div  v-for="(question,index) in form" v-bind:key="index" style="align-content: left;" -->
            <b-row>
                <!--b-col v-for="(question,index) in form" v-bind:key="index"-->
                <b-col>
                            <showFormAll 
        :form="form" />

                <!--showSingleQuestion 
                    :question="question"
                    :index="index"
                /-->            
                </b-col>
            </b-row>
    <!--/div-->
    
</div>
    
</template>
<script>
import showSingleQuestion from './showSingleQuestion'
import  showFormAll from './showFormAll'

export default {
    name: 'formPreview',
    props: [
        "form",
    ],
    data() {
        return {
            showPreview: true
        }
    },
    created() {
        console.log('formPreviewCreated')
    },
    watch: {
   },
    methods: {
    },
    components: {
        showFormAll,
        showSingleQuestion
    }

}
</script>