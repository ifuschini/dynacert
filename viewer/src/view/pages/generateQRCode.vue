<template>
    <div>
    <center>
              <img src="ws/getImage.php?type=image&name=logo.png" style="width:200px;height:200px"> 
    </center>
        <b-container class="bv-example-row">

        <b-row>
            <b-col v-for="(link,index) in links" v-bind:key="index">
                <br/>
                <br/>
                <center>
                <vue-qrcode :value="link.link" :scale="12" :quality="1" :margin="1"/><br/><br/>
                <h2>{{link.link}}</h2><br/>
                <h5>Double77 - DynaCert</h5>
                </center>
            </b-col>
        </b-row>
        </b-container>
    </div>
</template>
<style scoped>
.qrcode {
    width:400px;
    height:400px;
}
</style>
<script>
import axios from 'axios'
import VueQrcode from "vue-qrcode"

export default {
    components: {
        VueQrcode,
    },
    data() {
        return {
            links: [],
        }
    },
    created() {
        this.getLink()
    },
    methods: {
        getLink() {
            axios
                .get(this.config.serviceBaseUrl +  this.config.url.getLinks,{
                })
                .then(response => {
                    if (response.data.status == 'ok') {
                        this.links=response.data.links
                    } 
                })
                .catch(e => {
                    this.error = e;
            })
            
        },
        goto(link) {
            window.open(link.link, '_new')
        }
    }    
}
</script>