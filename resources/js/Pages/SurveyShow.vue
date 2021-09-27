<template>
  <bootstrap-authenticated-layout>
    <h2>Welcome to: {{$page.props.survey.name}} survey.</h2>
    <survey :survey="survey"></survey>
  </bootstrap-authenticated-layout>
</template>

<script>
import * as SurveyVue from "survey-vue";
import "bootstrap/dist/css/bootstrap.css";
var Survey = SurveyVue.Survey;
Survey.cssType = "bootstrap";

import * as widgets from "surveyjs-widgets";
import BootstrapAuthenticatedLayout from "@/Layouts/Authenticated";


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
//customWidget(SurveyVue);

SurveyVue.Serializer.addProperty("question", "tag:number");

export default {
  components: {
    BootstrapAuthenticatedLayout,
    Survey,
  },

  data() {
    return {
      survey: this.$page.props.survey,
    };
  },
  created() {
    this.survey = new SurveyVue.Model(this.$page.props.survey.json);
  },
  mounted() {
    this.survey.onComplete.add((result) => {
      let url = `/api/survey/${this.$page.props.survey.id}/result`;
      axios.post(url, { json: result.data,user_id: this.$page.props.auth.user.id }).then((response) => {
        console.log(response);
      });
    });
  },
};
</script>
