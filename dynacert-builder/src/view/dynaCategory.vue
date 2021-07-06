<template>
  <div>
        <b-row>
                <b-col sm="1"/>
                <b-col sm="10">
                    <div class="categoryClass" >
                    <b-button
                        size="sm"
                        variant="primary"
                        v-on:click="saveCategory()"
                    >New Category</b-button>
                    </div>
                    <div class="categoryClass" style="min-width:25rem;margin-right:5px;">
                    <b-input
                        v-model="newcategory"
                        size="sm"
                        placeholder="insert new category"
                        
                    />
                    </div>
                </b-col>
                <b-col sm="1"/>
        </b-row>
        <b-row>
        <b-col sm="1"/>
        <b-col>

            <table width="50%" class="table table-striped" >
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
        </b-col>
        <b-col sm="1"/>
        </b-row>
  </div>
</template>
<style scoped>
.categoryClass {
    float:right;
    align-items: flex-end;
    margin-bottom: 5px;
}
</style>

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
                serverBus.$emit('showLoader',true)
                axios
                    .delete(this.config.serviceBaseUrl + this.config.url.deleteCategory + id,{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        serverBus.$emit('showLoader',false)
                        this.arrayCategory=response.data.response
                    })
                    .catch (e=> {
                        this.dynaLogout(this)
                        this.error=e;
                    })
        },
        getCategories() {
            serverBus.$emit('showLoader',true)
            axios
                .get(this.config.serviceBaseUrl + this.config.url.listCategory ,{
                params: {
                }
                }
                )
                .then(response => {
                    serverBus.$emit('showLoader',false)
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
            serverBus.$emit('showLoader',true)
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
                        serverBus.$emit('showLoader',false)
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

