<template>
  <div>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
          d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
        ></path>
      </symbol>
    </svg>
    <div
      class="alert alert-success d-flex align-items-center"
      role="alert"
      v-if="msg.length > 0"
    >
      <svg
        class="bi flex-shrink-0 me-2"
        width="24"
        height="24"
        role="img"
        aria-label="Success:"
      >
        <use xlink:href="#check-circle-fill" />
      </svg>
      <div>Message : {{ msg }}</div>
    </div>
    <div id="surveyCreatorContainer"></div>
  </div>
</template>

<script>
import * as SurveyCreator from "survey-creator";
import "survey-creator/survey-creator.css";
import axios from "axios";
import * as SurveyKo from "survey-knockout";
import * as widgets from "surveyjs-widgets";
//import { init as customWidget } from "../components/customwidget";

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
//customWidget(SurveyKo);

SurveyKo.Serializer.addProperty("question", "tag:number");

var CkEditor_ModalEditor = {
  afterRender: function (modalEditor, htmlElement) {
    var editor = window["CKEDITOR"].replace(htmlElement);
    editor.on("change", function () {
      modalEditor.editingValue = editor.getData();
    });
    editor.setData(modalEditor.editingValue);
  },
  destroy: function (modalEditor, htmlElement) {
    var instance = window["CKEDITOR"].instances[htmlElement.id];
    if (instance) {
      instance.removeAllListeners();
      window["CKEDITOR"].remove(instance);
    }
  },
};
SurveyCreator.SurveyPropertyModalEditor.registerCustomWidget(
  "html",
  CkEditor_ModalEditor
);

SurveyCreator.StylesManager.applyTheme("bootstrap");

export default {
  props: ["survey"],
  data() {
    return {
      msg: "",
    };
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
    this.surveyCreator.text = JSON.stringify(this.survey.json);
    this.surveyCreator.saveSurveyFunc = function () {
      axios
        .put("/api/survey/" + self.survey.id, { json: JSON.parse(this.text) })
        .then((response) => {
          console.log(response.data.message);
          self.surveyCreator.text = JSON.stringify(response.data.data.json);
          self.msg = response.data.message;
          setTimeout(() => {
            self.msg = "";
          }, 3000);
        })
        .catch((error) => {
          console.error(error.response);
        });
    };
  },
};
</script>

<style scoped>
</style>
