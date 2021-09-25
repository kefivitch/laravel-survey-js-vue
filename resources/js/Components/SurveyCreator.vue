<template>
  <div id="surveyCreatorContainer"></div>
</template>

<script>
import * as SurveyCreator from "survey-creator";
import "survey-creator/survey-creator.css";
import axios from "axios";
import * as SurveyKo from "survey-knockout";
import * as widgets from "surveyjs-widgets";
import { init as customWidget } from "../components/customwidget";

// widgets.icheck(SurveyKo);
widgets.select2(SurveyKo);
widgets.inputmask(SurveyKo);
widgets.jquerybarrating(SurveyKo);
widgets.jqueryuidatepicker(SurveyKo);
widgets.nouislider(SurveyKo);
widgets.select2tagbox(SurveyKo);
widgets.sortablejs(SurveyKo);
widgets.ckeditor(SurveyKo);
widgets.autocomplete(SurveyKo);
widgets.bootstrapslider(SurveyKo);
customWidget(SurveyKo);

SurveyKo.Serializer.addProperty("question", "tag:number");

var CkEditor_ModalEditor = {
  afterRender: function(modalEditor, htmlElement) {
    var editor = window["CKEDITOR"].replace(htmlElement);
    editor.on("change", function() {
      modalEditor.editingValue = editor.getData();
    });
    editor.setData(modalEditor.editingValue);
  },
  destroy: function(modalEditor, htmlElement) {
    var instance = window["CKEDITOR"].instances[htmlElement.id];
    if (instance) {
      instance.removeAllListeners();
      window["CKEDITOR"].remove(instance);
    }
  }
};
SurveyCreator.SurveyPropertyModalEditor.registerCustomWidget(
  "html",
  CkEditor_ModalEditor
);

export default {
  name: "survey-creator",
  props: ["json"],
  data() {
    return {};
  },
  mounted() {
    // CSS FIXER
    $(document).ready(function () {
      $(".svd_survey_designer").removeClass("row");
    });

    let options = { showEmbededSurveyTab: false };
    this.surveyCreator = new SurveyCreator.SurveyCreator(
      "surveyCreatorContainer",
      options
    );
    let self = this;
    //this.surveyCreator.text = JSON.stringify(this.json);
    this.surveyCreator.saveSurveyFunc = function() {
      axios
        .put("api/survey", { json: JSON.parse(this.text) })
        .then((response) => {
          self.editor.text = JSON.stringify(response.data.data.json);
          self.$root.snackbar = true;
          self.$root.snackbarMsg = response.data.message;
        })
        .catch((error) => {
          console.error(error.response);
        });
    };
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
