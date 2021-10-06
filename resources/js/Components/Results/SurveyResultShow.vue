<template>
<div>
  <survey :survey="survey" />
</div>
</template>

<script>
import * as Survey from "survey-vue";

import "survey-vue/survey.css";


export default {
    name: "surveyjs-component",
  data() {
      return {
        json: this.$page.props.survey.json,
        survey: {},
    };
  },
  mounted() {
    this.survey = new Survey.Model(this.json);

    this.survey.onComplete.add(function (sender) {
      document.querySelector("#surveyResult").textContent =
        "Result JSON:\n" + JSON.stringify(sender.data, null, 3);
    });

    this.survey.data = this.$page.props.result.json;
    this.survey.onTimer.add(() => {
        this.survey.stopTimer()
    })
    this.survey.mode = "display";
  },
};
</script>

<style scoped>
</style>
