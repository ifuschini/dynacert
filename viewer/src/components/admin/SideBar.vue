<template>
  <div>
      <div class="bg-light border-right" id="sidebar-wrapper" ref="sidebarWrapper">
      <div class="sidebar-heading">Menu 
      </div>
      <div class="list-group list-group-flush">
        <a v-for="(item,index) in items" v-bind:key="index" href="#" class="list-group-item list-group-item-action bg-light" v-on:click="goto(index)" :active="item.path=='/' + currentTemplate">{{item.name}}</a>
      </div>
      <div class="license" v-if="licenseExpired==true">
            <center>
                Licenza scaduta
            </center>
        </div> 
    </div>
       <div class="dynacert-footer d-flex bg-dark text-light align-items-center px-3 py-2" >
        <strong class="mr-auto">v. {{config.version}}</strong>
       </div>
     <vue-context ref="menu" v-slot="{ data }">
        <li>
            <a href="#" @click="showSingleViewer(data)">Survey Single Page</a>
        </li>
        <li>
            <a href="#" @click="showAllViewer(data)">Survey all</a>
        </li>
        <li>
           id: {{listSurveys[data]}}
        </li>
    </vue-context>



  </div>
</template>
<style scoped>
.license {
    color:red;
    font-style: italic;
    border-color: red;
    border: 1px;
    border-style: solid;
    margin-left: 5px;
    margin-right: 5px;
}
@media only screen and (max-width: 600px) {
.dynacert-footer {
    display: none!important;
}
}


</style>
<script>
import { serverBus } from '../../main'
import { navigationRoutes } from './data/menu'

export default {

    data() {
        return {
            isSideBarOpen: false,
            items: navigationRoutes.items,
            currentTemplate: null,
            options: [],
            licenseExpired: false,
            projectSelected: {
                value: '',
                text: ''
            }
        }
    },
    watch: {
        $route() {
            console.log('route')
            this.currentTemplate=this.$route.fullPath.split('/')[2]
        },
        projectSelected() {
            console.log(this.projectSelected)
        }
    },
    created(){
        this.currentTemplate=this.$route.fullPath.split('/')[2]
        serverBus.$on('reloadListProjects', () => {
            this.getProjects()
        })
        serverBus.$on('licenseExpired', ($msg) => {
            this.licenseExpired=$msg

        })

    },
    components: {
    },
    methods: {
        goto(index) {
                this.$router.push({ path: this.items[index].path}).catch(err => {})
        },

    }
}
</script>