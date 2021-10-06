<template>
  <div>
    <survey :survey="survey"></survey>
  </div>
</template>

<script>
import * as SurveyVue from "survey-vue";

const Survey = SurveyVue.Survey;
//SurveyVue.StylesManager.applyTheme("bootstrap");

import * as widgets from "surveyjs-widgets";
import { init as customWidget } from "../customwidget";

// widgets.icheck(SurveyVue);
widgets.select2(SurveyVue);
widgets.inputmask(SurveyVue);
widgets.jquerybarrating(SurveyVue);
widgets.jqueryuidatepicker(SurveyVue);
widgets.nouislider(SurveyVue);
widgets.select2tagbox(SurveyVue);
widgets.sortablejs(SurveyVue);
widgets.ckeditor(SurveyVue);
widgets.autocomplete(SurveyVue);
widgets.bootstrapslider(SurveyVue);
customWidget(SurveyVue);

export default {
  components: {
    Survey,
  },
  props: ["surveyData"],
  data() {
    return {
      survey: {},
    };
  },
  created() {
    this.survey = new SurveyVue.Model(this.surveyData.json);
  },
  mounted() {
    this.survey.onComplete.add((result) => {
      let url = `/survey/${this.surveyData.id}/result`;
      axios.post(url, { json: result.data }).then((response) => {
        console.log(response);
      });
    });
  },
};
</script>
