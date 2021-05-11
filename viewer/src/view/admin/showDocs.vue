<template>
    <div style="top:50px">
        Categoria 
        <b-form-select v-model="selectedCat" :options="listCat"></b-form-select>
        <div v-if="listDate.length != 0">
            Data
            <b-form-select v-model="selectedDate" :options="listDate" ></b-form-select>
        </div>
        <div v-if="listCertificate.length != 0">
            <hr/>
            <b-input type="text" placeholder="Cerca un nominativo" v-model.trim="search"/>
            <span v-on:click="getListCertificate()" style="float:right;margin-left:10px"><b-icon icon="arrow-clockwise" aria-hidden="true"></b-icon></span>
            <span v-on:click="downloadCSV('autocertificazioni')" style="float:right;margin-left:10px"><b-icon icon="file-spreadsheet" aria-hidden="true"></b-icon><br/></span>
            <span v-on:click="downloadZip()" style="float:right;margin-left:10px"><b-icon icon="file-zip" aria-hidden="true"></b-icon></span>

            <table class="table table-striped table-hover" id="autocertificazioni">
            <thead>
                <tr>
                <th scope="col">Cognome</th>
                <th scope="col">Nome</th>
                <th scope="col">Giorno</th>
                <th scope="col">Ora</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="(item,index) in listCertificateToShow" v-bind:key="index" v-on:click="downloadDock(item.Documento)">
                <td>{{item.Cognome}}</td>
                <td>{{item.Nome}}</td>
                <td>{{item.Giorno}}</td>
                <td>{{item.Ora}}</td>
            </tr>
            </tbody>
            </table>

        </div>

    </div>
</template>
<script>
import axios from 'axios'
import { serverBus } from '../../main'

export default {
    name: 'pdfTest',
    data() {
        return {
            listCat:[],
            listDate:[],
            listCertificate: [],
            listCertificateToShow: [],
            selectedCat: null,
            selectedDate: null,
            search: '',
        }
    },
    created() {
        this.getCategory()
        },
    watch: {
        selectedCat() {
            this.listCertificate=[]
            this.selectedDate=null
            this.getDate()
        },
        selectedDate() {
            this.getListCertificate()
        },
        search() {
            if (this.search.length == 0){
                this.listCertificateToShow=this.listCertificate
            } else {
                 this.listCertificateToShow=this.listCertificate.filter(v => v.Cognome.toUpperCase().includes(this.search.toUpperCase()) || v.Nome.toUpperCase().includes(this.search.toUpperCase()));
            }
        }
    },
    methods:{
        getCategory () {
            axios
                .get(this.config.serviceBaseUrl +  this.config.url.getCategory,{
                })
                .then(response => {
                    if (response.data.status == 'ok') {
                        this.listCat=response.data.category
                        if (response.data.licenseExpired==true) {
                            serverBus.$emit('licenseExpired',true)
                            alert('Attenzione licenza scaduta, puoi comunque continuare ad usare la parte amministrativa')
                        }
                        if (this.listCat.length > 0) {
                            this.selectedCat=this.listCat[0].value
                        } else {
                            this.listCat.splice(0, 0, {text:'non ci sono categorie configurate',value: null});
                        }
                    } else {
                        this.$router.push({ path: '/pages/login'})

                    }
                })
                .catch(e => {
                    this.error = e;
            })
        },
        getDate () {
            axios
                .get(this.config.serviceBaseUrl +  this.config.url.getDate,{ 
                    params: {
                        cat: this.selectedCat 
                    }
                })
                .then(response => {
                    if (response.data.status == 'ok') {
                        this.listDate=response.data.date
                        if (this.listDate.length > 0) {
                            this.selectedDate=this.listDate[0].value
                        } else {
                            this.listDate.splice(0, 0, {text:'non ci sono date disponibili',value: null});
                        }
                    } else {
                        this.$router.push({ path: '/pages/login'})
                    }

                    console.log(test)
                })
                .catch(e => {
                    this.error = e;
            })
        },
        getListCertificate () {
            console.log('selectedDate')
            axios
                .get(this.config.serviceBaseUrl +  this.config.url.getListCertificate,{ 
                    params: {
                        cat: this.selectedCat, 
                        date: this.selectedDate, 
                    }
                })
                .then(response => {                  
                    if (response.data.status == 'ok') {
                        this.listCertificate = response.data.certificate.sort(function(a, b) {
                        var aFirstChar = a.Cognome.charAt(0);
                        var bFirstChar = b.Cognome.charAt(0);
                        if (aFirstChar > bFirstChar) {
                            return 1;
                        } else if (aFirstChar < bFirstChar) {
                            return -1;
                        } else {
                            var aLastChar = a.Nome.charAt(0);
                            var bLastChar = b.Nome.charAt(0);
                            if (aLastChar > bLastChar) {
                            return 1;
                            } else if (aLastChar < bLastChar) {
                            return -1;
                            } else {
                            return 0;
                            }    
                        }
                        });
                        this.listCertificateToShow=this.listCertificate
                    } else {
                        this.$router.push({ path: '/pages/login'})
                    }


                })
                .catch(e => {
                    this.error = e;
            })
        },
        downloadCSV(table_id) {
            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cols.length; j++) {
                    // Clean innertext to remove multiple spaces and jumpline (break csv)
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(';'));
            }
            var csv_string = csv.join('\n');
            // Download it
            var filename = "autocertificazioni_"+ this.selectedCat + "_" + this.selectedDate +".csv";
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        downloadDock(item) {
            window.location.href=this.config.serviceBaseUrl +  this.config.url.download + "?cat=" + this.selectedCat + "&date=" + this.selectedDate + "&name=" + item
        },
        downloadZip() {
            window.location.href=this.config.serviceBaseUrl +  this.config.url.zip + "?cat=" + this.selectedCat + "&date=" + this.selectedDate
        }
    }
}
</script>