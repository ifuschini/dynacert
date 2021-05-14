<template>
  <div>
        <b-row>
        <b-col sm="6">
        </b-col>
        <b-col sm="4">
        <b-input
            v-model="newcategory"
            size="sm"
            placeholder="insert new category"
            style="float:right;margin-left:10px;margin-right:10px;"
        />
        </b-col>
        <b-col sm="2">
            <b-button
                size="sm"
                variant="primary"
                v-on:click="saveCategory()"
            >New Category</b-button>
        </b-col>
        </b-row>
        <hr/>
        <center>
            <table width="50%" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(category,index) in arrayCategory" v-bind:key="index">
                    <td>{{category.ID}}</td>
                    <td>
                        <span v-if="idSelected != category.ID" @dblclick="modify(category.ID,category.name)">{{category.name}}</span>
                        <b-form-input size="sm" v-if="idSelected == category.ID" v-model="categoryToModify" v-on:keyup.enter="modifyCategory()" v-on:keyup.esc="idSelected = null"/>
                    </td>
                    <td><b-button size="sm" variant="danger" v-on:click="deleteCategory(category.ID)">delete</b-button></td>
                </tr>
                </tbody>
            </table>
        </center>
  </div>
</template>
<script>
import { serverBus } from '../main'
import axios from 'axios'

export default {
    created() {
        this.getCategories()
    },
    data () {
        return {
            newcategory:null,
            arrayCategory:[],
            idSelected: null,
            categoryToModify:null,

        }
    },
    methods: {
        modifyCategory() {
                axios
                    .post(this.config.serviceBaseUrl + this.config.url.modifyCategory,{
                        id: this.idSelected,
                        name: this.categoryToModify
                    },{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        this.categoryToModify=null
                        this.idSelected=null
                        this.getCategories()
                    })
                    .catch (e=> {
                                this.error=e;
                    })
            
        },
        modify(id,name) {
            this.idSelected=id
            this.categoryToModify=name
        },

        deleteCategory(id) {
                if (confirm('Do you want to delete this item ?') == false) return false
                axios
                    .post(this.config.serviceBaseUrl + this.config.url.deleteCategory,{
                        id: id,
                    },{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        this.getCategories()
                    })
                    .catch (e=> {
                                this.error=e;
                    })
        },
        getCategories() {
            axios
                .get(this.config.serviceBaseUrl + this.config.url.listCategory ,{
                params: {
                }
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
        saveCategory() {
            if (this.newcategory==null) {
                alert('new category is empty' + this.newcategory)
                return false
            }
                axios
                    .post(this.config.serviceBaseUrl + this.config.url.saveCategory,{
                        category: this.newcategory,
                    },{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        //serverBus.$emit('showLoader',false)
                        if (response.data.status=='ko') 
                            alert(response.data.message)
                        else
                            this.newcategory=null
                        console.log(response)

                        this.getCategories()
                    })
                    .catch (e=> {
                                this.error=e;
                    })
        }  
        
    }

}
</script>

