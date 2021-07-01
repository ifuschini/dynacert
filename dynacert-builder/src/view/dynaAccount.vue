<template>
  <div>
        <b-row>
        <b-col sm="6">
        </b-col>
        <b-col sm="4"></b-col>
        <b-col sm="2">
            <b-button
                size="sm"
                variant="primary"
                v-on:click="showModal(null,'new')"
            >New Account</b-button>
        </b-col>
        </b-row>
        <hr/>
        <center>
            <table width="50%" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Account</th>
                    <th scope="col">Roles</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(account,index) in arrayAccounts" v-bind:key="index">
                    <td>{{account.id}}</td>
                    <td>
                        <span v-if="idSelected != account.id" style="min-width:100%">{{account.username}}</span>
                    </td>
                    <td>
                        <div v-for="role in account.roles"   v-bind:key="role">{{role}} </div>
                    </td>
                    <td><b-button size="sm" variant="warning" v-on:click="showModal(index,'modify')">modify</b-button></td>
                    <td><b-button size="sm" variant="danger" :disabled="account.username == 'admin'" v-on:click="deleteAccount(account.id)">delete</b-button></td>
                </tr>
                </tbody>
            </table>
        </center>
        <modalModifyAccount 
            ref="modifyModal"
            :arrayAccounts="arrayAccounts"
            v-on:updateData="updateData"
        />
  </div>
</template>
<script>
import axios from 'axios'
import modalModifyAccount from './dynaAccount/modalModifyAccount.vue'

export default {
    created() {
        this.getAccounts()
        this.$gtag.event('dynacert-builder', { method: 'account' })

    },
    watch: {
        $route() {
            this.$gtag.event('dynacert-builder', { method: 'account' })
        }
    },  
    data () {
        return {
            newaccount:null,
            arrayAccounts:[],
            idSelected: null,
            accountToModify:null,

        }
    },
    methods: {
        modifyAccount() {
                if (this.config.demo==true) {
                    alert('is a demo')
                    return false
                }
                axios
                    .put(this.config.serviceBaseUrl + this.config.url.modifyAccount,{
                        id: this.idSelected,
                        name: this.accountToModify
                    },{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        this.accountToModify=null
                        this.idSelected=null
                        this.arrayAccounts=response.data.response
                    })
                    .catch (e=> {
                                this.dynaLogout(this)
                                this.error=e;
                    })
            
        },



        modify(id,name) {
            this.idSelected=id
            this.accountToModify=name
        },
        modifyRoles(id,name) {
            this.idSelected=id
            this.rolesToModify=name
        },

        deleteAccount(id) {
                if (this.config.demo==true) {
                    alert('is a demo')
                    return false
                }
                if (confirm('Do you want to delete this item ?') == false) return false
                axios
                    .delete(this.config.serviceBaseUrl + this.config.url.deleteAccount + id,{
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',            
                        }
                    }
                    )
                    .then(response => {
                        this.arrayAccounts=response.data.response
                    })
                    .catch (e=> {
                        this.dynaLogout(this)
                        this.error=e;
                    })
        },
        getAccounts() {
            axios
                .get(this.config.serviceBaseUrl + this.config.url.listAccount ,{
                params: {
                }
                }
                )
                .then(response => {
                    this.arrayAccounts=response.data.response
                })
                .catch(e => {
                    this.dynaLogout(this)
                    this.error = e;
            })
        },
        insertAccount(data) {
            if (this.config.demo==true) {
                    alert('is a demo')
                    return false
            }
                axios
                    .post(this.config.serviceBaseUrl + this.config.url.insertAccount,{
                        username: data.username,
                        password: data.password,
                        role: data.selectedRole,
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
                            this.arrayAccounts=response.data.response

                    })
                    .catch (e=> {
                        this.dynaLogout(this)
                        this.error=e;
                    })
        },
        updateAccount(data) {
            if (this.config.demo==true) {
                    alert('is a demo')
                    return false
            }
                axios
                    .put(this.config.serviceBaseUrl + this.config.url.updateAccount,{
                        id: data.id,
                        password: data.password,
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
                            this.arrayAccounts=response.data.response

                    })
                    .catch (e=> {
                        this.dynaLogout(this)
                        this.error=e;
                    })
        },
        showModal(index,type) {
            this.$refs.modifyModal.showModal(this.arrayAccounts[index],type)
        },
        updateData (data) {
            console.log('updateData:')
            console.log(data)
            if (data.type=='new') {
                this.insertAccount(data)
            } else {
                this.updateAccount(data)
            }
        }
        
    },
    components: {
        modalModifyAccount
    }

}
</script>

