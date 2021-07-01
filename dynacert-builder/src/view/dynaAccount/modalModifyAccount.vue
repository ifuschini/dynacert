<template>
  <div>
    <b-modal ref="my-modal" hide-footer :title="titleModal">
        <label for="input-live">Username:</label>
      <div class="d-block text-center">
        <b-form-input id="input-none"  
            :disabled="isModifyType"  
            placeholder="username" 
            v-model="username" 
            style="margin-top:5px"
            :state="usernameCheck"
            aria-describedby="input-live-help input-live-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="input-live-feedback">
          username is duplicated
        </b-form-invalid-feedback>
        <b-form-input id="input-none" :state="checkPassword" placeholder="password" v-model="password" style="margin-top:5px"></b-form-input>     
        <b-form-input id="input-none" :state="password == confirmPassword && confirmPassword != ''" :disabled="!checkPassword"  placeholder="confirm password" v-model="confirmPassword" style="margin-top:5px"></b-form-input>
        <b-form-select v-model="selectedRole" :options="roles" :disabled="isModifyType" style="margin-top:5px"></b-form-select>
      </div>
      <b-button size="sm" variant="warning" block @click="sendData()" :disabled="disableButton" style="flow:right;margin-top:5px;">{{labelButtonAction}}</b-button>
      
    </b-modal>
  </div>
</template>

<script>
  export default {
    props: [
      "arrayAccounts"
    ],

    data () {
        return {
            password: '',
            confirmPassword: '',
            checkPassword: false,
            type: '',
            titleModal:'',
            isModifyType: true,
            username: '',
            selectedRole:null,
            labelButtonAction: null,
            disableButton:true,
            usernameCheck:true,
            dataAccount: {
                id: null,
                username: '',
                roles:[]
            },
            roles: [
              {value: null,text: 'Please chose a role'},
              {value: 'ROLE_ADMIN',text: 'ROLE_ADMIN'},
              {value: 'ROLE_USER',text: 'ROLE_USER'}
            ]
        }
    },
    watch: {
        password() {
            this.validPassword()
            this.activateButton()
        },
        confirmPassword() {
            this.activateButton()
        },
        username() {
            this.activateButton()
            
        },
        selectedRole() {
            this.activateButton()
        }

    },
    methods: {
        validPassword() {
            var regex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})")
            this.checkPassword=regex.test(this.password)
            console.log(this.checkPassword)
        },
        activateButton() {
          if (this.type=='modify') {
            if (this.selectedRole==null || this.checkPassword==false || this.password != this.confirmPassword) {
             this.disableButton=true
            } else {
              this.disableButton=false
            }
          } else {
            if (this.selectedRole==null || this.checkPassword==false || this.password != this.confirmPassword || this.username.length<3 || this.selectedRole==null) {
             this.disableButton=true
            } else {
              this.disableButton=false
            }  
            let find = this.arrayAccounts.find( ({ username }) => username === this.username );
            if (find) {
              this.disableButton=true
              this.usernameCheck=false
            } else {
              this.usernameCheck=true
            }
          
          }
        },
        showModal(dataAccount,type) {
          this.type=type
          this.password=''
          this.confirmPassword=''

          if (type=='modify') { 
            this.isModifyType=true
            this.dataAccount=dataAccount
            this.titleModal='Modify password'
            this.selectedRole=this.dataAccount.roles[0]
            this.username=this.dataAccount.username
            this.labelButtonAction='modify user'
          } else {
            this.isModifyType=false
            this.selectedRole=null
            this.isModifyType=false
            this.username=''
            this.dataAccount= {
                id: null,
                username: '',
                roles:[]
            }
            this.labelButtonAction='add user'
          }
            this.$refs['my-modal'].show()
        },
        sendData () {
            let sendData={
               username: this.username,
               password: this.password,
               selectedRole: this.selectedRole,
               id: this.dataAccount.id,
               type: this.type
            }
            this.$emit( 'updateData',
              sendData
            )
            this.$refs['my-modal'].hide()
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        toggleModal() {
            // We pass the ID of the button that we want to return focus to
            // when the modal has hidden
            this.$refs['my-modal'].toggle('#toggle-btn')
        }
    }
  }
</script>