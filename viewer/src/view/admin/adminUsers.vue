<template>
    <div>
      <b-row>
    <b-col cols="4">
        Users
        <b-button variant="success" size="sm" style="float:right" v-on:click="newUser()">Nuovo Utente</b-button>

        <b-input type="text" placeholder="Cera un utente" v-model.trim="search"/>
        <b-list-group>
        <b-list-group-item v-for="(user,key) in usersToShow" v-bind:key="key" v-on:click="goto(key)" :active="emailToShow==user.username && isNewUser==false">
                    {{user.username}} ({{user.category}})
        </b-list-group-item>
        </b-list-group>
    </b-col>
    <b-col>
          <b-card bg-variant="light" v-if="emailToShow!=null || isNewUser==true">
                <b-form-group
                label-cols-lg="3"
                label="Dettaglio Utente"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                class="mb-0"
                >
                <b-alert size="sm" variant="danger" :show="errorPassword">{{messageErrorPassword}}</b-alert>
                <b-form-group
                    label-cols-sm="3"
                    label="utente:"
                    label-align-sm="right"
                    label-for="nested-street"
                >
                    <b-form-input type="email" id="nested-street" v-model='emailToShow' :disabled="isNewUser==false" placeholder="Inserire un email"></b-form-input>
                </b-form-group>
                <b-form-group
                    label-cols-sm="3"
                    label="Nuova Password:"
                    label-align-sm="right"
                    label-for="nested-street"
                >
                    <b-form-input id="nested-street" v-model='newpassword' :disabled="disablePassword"></b-form-input>
                </b-form-group>
                <b-form-group
                    label-cols-sm="3"
                    label="Conferma Password:"
                    label-align-sm="right"
                    label-for="nested-street"
                >

                    <b-form-input id="nested-street" v-model='confirmnewpassword' :disabled="disableConfirmPassword" ></b-form-input>
                </b-form-group>

                <b-form-group
                    label-cols-sm="3"
                    label="Categoria:"
                    label-align-sm="right"
                    label-for="nested-city"
                >
                <b-form-checkbox-group
                    v-model="selected"
                    :options="categories"
                    name="flavour-2a"
                    stacked
                    v-if="emailToShow!='admin'"
                ></b-form-checkbox-group>
                <span v-if="emailToShow=='admin'">Tutte le categorie</span>
                <hr/>
                    <b-button variant="danger" style="float:left"  v-on:click="save(true)" v-if="isNewUser==false && emailToShow != 'admin'">Elimina</b-button>
                    <b-button variant="success" style="float:right" :disabled="disableSave" v-on:click="save()">Salva</b-button>
                </b-form-group>

                </b-form-group>
        </b-card>
    </b-col>
  </b-row>
    </div>
</template>
<script>
import axios from 'axios'

export default {
    data() {
        return {
            users: [],
            usersToShow: [],
            emailToShow: null,
            categories: [],
            selected: [],
            newpassword: null,
            confirmnewpassword: null,
            errorPassword:null,
            disableSave: true,
            disablePassword: false,
            disableConfirmPassword: true,
            messageErrorPassword: '',
            isNewUser:false,
            search:'',
        }
    },
    created () {
        this.getUsers()
    },
    watch: {
        selected () {
            if (this.newpassword==null) {
                this.disableSave=false
            }
        },
        emailToShow() {
            if (this.isNewUser==true) {
                if (this.validateEmail(this.emailToShow)) this.disablePassword=false 
                else {
                    this.disablePassword=true 
                    this.disableConfirmPassword=true
                    this.messageErrorPassword='Il nome utente deve essere una email'
                    this.newpassword=null
                    this.confirmnewpassword=null
                    this.selected=[]
                    setTimeout(function () { this.disableSave=true }.bind(this), 100)
                    
                }
                if (this.users.filter(v => v.username.toUpperCase()==this.emailToShow.toUpperCase()).length==1) {
                    this.messageErrorPassword='Il nome utente già esiste'
                    this.disablePassword=true 
                    this.disableConfirmPassword=true
                    this.newpassword=null
                    this.confirmnewpassword=null
                    this.selected=[]
                }
                this.errorPassword=this.disablePassword
            }
        },
        newpassword() {
            if (this.newpassword.length == null || this.newpassword.length < 5) {
                this.messageErrorPassword='La password deve essere minimo 5 lettere'
                this.errorPassword=true
                this.confirmnewpassword=null
                this.disableConfirmPassword=true
                if (this.newpassword.length==0) this.newpassword=null
                return false
            } else {
                this.disableConfirmPassword=false
            }
            if (this.confirmnewpassword != this.newpassword) {
                this.messageErrorPassword='Le password non coincidono'
                this.errorPassword=true
            } else {
                this.errorPassword=false
            }
            this.disableSave=this.errorPassword
        },
        confirmnewpassword() {
            if (this.confirmnewpassword != this.newpassword) {
                this.messageErrorPassword='Le password non coincidono'
                this.errorPassword=true
            } else {
                this.errorPassword=false
            }
            this.disableSave=this.errorPassword
            if (this.newpassword==null) {
                this.disableSave=true
            }
        },
        search() {
            if (this.search.length == 0){
                this.usersToShow=this.users
            } else {
                 this.usersToShow=this.users.filter(v => v.username.toUpperCase().includes(this.search.toUpperCase()));

            }
        },
    },
    methods: {
        validateEmail(mail) {
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail))
            {
                return (true)
            }
                return (false)
        },
        newUser () {
            this.isNewUser=true
            this.emailToShow=null
            this.newpassword=null
            this.confirmnewpassword=null
            this.disablePassword=true
            this.selected=[]
            setTimeout(function () { this.disableSave=true }.bind(this), 100)
        },
        save(isDelete=false) {
            this.disableSave=true
            if (isDelete==true) {
                if (!confirm("Confermi di voler cancellare " + this.emailToShow + " ?")) {
                    return false
                }
            }
            axios
            .post(this.config.serviceBaseUrl +  this.config.url.saveUser,{
                            categories: this.selected,
                            password: this.newpassword,
                            username: this.emailToShow,
                            isNewUser: this.isNewUser,
                            isDelete: isDelete,
            })
                    .then(response => {
                        if (response.data.status=="ok") {
                            this.getUsers()
                            this.disablePassword=true 
                            this.disableConfirmPassword=true
                            this.newpassword=null
                            this.confirmnewpassword=null
                            this.emailToShow=null
                            this.selected=[]
                        } 
                    })
                    .catch (e=> {
                                this.error=e;
                                this.disableSave=false
                    })

        },
        getUsers() {
            axios
                .get(this.config.serviceBaseUrl +  this.config.url.getUsers,{
                })
                .then(response => {
                    if (response.data.status == 'ok') {
                        this.users=response.data.users
                        this.usersToShow=this.users
                        this.categories=response.data.categories
                        console.log(this.categories)
                    } else {
                        this.$router.push({ path: '/pages/login'})

                    }
                })
                .catch(e => {
                    this.error = e;
            })
        },
        goto(key) {
            this.isNewUser=false
            this.disablePassword=false
            this.errorPassword=false
            this.emailToShow=this.usersToShow[key].username
            this.selected=this.usersToShow[key].category.split(',')
            setTimeout(function () { 
                        this.disableSave=true
            }.bind(this), 100)

        },

    }
    
}
</script>