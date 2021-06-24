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
                    <td>{{category.id}}</td>
                    <td>
                        <span v-if="idSelected != category.id" @dblclick="modify(category.id,category.name)" style="min-width:100%">{{category.name}}</span>
                        <b-form-input size="sm" v-if="idSelected == category.id" v-model="categoryToModify" v-on:keyup.enter="modifyCategory()" v-on:keyup.esc="idSelected = null"/>
                    </td>
                    <td><b-button size="sm" variant="danger" v-on:click="deleteCategory(category.id)">delete</b-button></td>
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
        this.$gtag.event('dynacert-builder', { method: 'category' })

    },
    watch: {
        $route() {
            this.$gtag.event('dynacert-builder', { method: 'category' })
        }
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
                if (this.config.demo==true) {
                    alert('is a demo')
                    return false
                }
                axios
                    .put(this.config.serviceBaseUrl + this.config.url.modifyCategory,{
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
                        this.arrayCategory=response.data.response
                    })
                    .catch (e=> {
                                this.dynaLogout(this)
                                this.error=e;
                    })
            
        },
        modify(id,name) {
            this.idSelected=id
            this.categoryToModify=name
        },

        deleteCategory(id) {
                if (this.config.demo==true) {
                    alert('is a demo')
                    return false
                }
                if (confirm('Do you want to delete this item ?') == false) return false
                axios
                    .delete(this.config.serviceBaseUrl + this.config.url.deleteCategory + id,{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        this.arrayCategory=response.data.response
                    })
                    .catch (e=> {
                        this.dynaLogout(this)
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
                    this.arrayCategory=response.data.response
                })
                .catch(e => {
                    this.dynaLogout(this)
                    this.error = e;
            })
        },
        saveCategory() {
            if (this.config.demo==true) {
                    alert('is a demo')
                    return false
            }
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
                            this.arrayCategory=response.data.response

                    })
                    .catch (e=> {
                        this.dynaLogout(this)
                        this.error=e;
                    })
        }  
        
    }

}
</script>

