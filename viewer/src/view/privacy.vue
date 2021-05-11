<template>
    <div>
        <b-button block v-b-toggle.accordion-10 variant="info" size="sm" style="margin-top:10px">Privacy information
<br/>(cliccami per leggere)
</b-button>
        <b-collapse id="accordion-10"  accordion="gdpr-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text>
        <div class="textInformativa">
          Put here your text
        </div>
          </b-card-text>
<b-button block v-b-toggle.accordion-10 variant="info" size="sm">chiudi</b-button>
        </b-card-body>
      </b-collapse>
    </div>
</template>
<style scoped>
.titleInformativa {
    text-align: center;
}
.textInformativa {
  font-size: 0.8rem;
}
</style>
<script>
export default {
  data() {
    return {
      costumer: null,
      emailCostumer:null,
      survey: []
    }
  },
  created() {
    this.costumer=localStorage.costumer
    this.survey=JSON.parse(localStorage.surveyConfig)
    this.emailCostumer=localStorage.emailCostumer
    console.log(this.survey)
  }
}
</script>
