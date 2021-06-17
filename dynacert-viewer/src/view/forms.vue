<template>
  <div>
            <div role="group" class="form-group">
                <select  v-model="categorySelected" class="form-control" v-on:change="getForms()">
                    <option :value="null"> {{language[config.currentLanguage].selectCategory}}</option>
                    <option v-for="(category,index) in arrayCategory" v-bind:key="index" :value="category.id">{{category.name}}</option>
                </select>   
            </div> 
            <b-list-group>

            <b-list-group-item v-for="(form,index) in listForms" v-bind:key="index" button v-on:click="gotoform(form.id)">
                {{form.title}}
            </b-list-group-item>                        
            </b-list-group>
        </div>
  </div>
</template>
<script>
import axios from 'axios'
export default {
    data() {
        return {
            arrayCategory:[],
            listForms: [],
            categorySelected: null,
        }
    },
    created () {
        console.log('starts forms')
        this.getCategories()
    },
    methods: {
        getForms () {
            axios
                .get(this.config.serviceBaseUrl + this.config.url.listForms + this.categorySelected, {
                })
                .then(response => {
                    this.listForms = response.data.response
                })
                .catch(e => {
                    this.error = e;
            })
        },
        getCategories() {
            axios
                .get(this.config.serviceBaseUrl + this.config.url.listCategory ,{
                }
                )
                .then(response => {
                    console.log(response)
                    this.arrayCategory=response.data.response
                
                })
                .catch(e => {
                    this.error = e;
            })
        },
        gotoform(id) {
            console.log('goto ' + id)
            this.$router.push('/form/' + id)

        }
    }
}
</script>
