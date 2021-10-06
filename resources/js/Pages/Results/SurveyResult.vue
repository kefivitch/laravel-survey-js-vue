<template>
  <bootstrap-authenticated-layout>
      <survey-result-show :survey="$page.props.survey"></survey-result-show>
  </bootstrap-authenticated-layout>
</template>

<script>
import BootstrapAuthenticatedLayout from "@/Layouts/Authenticated";
import SurveyResultShow from "@/components/Results/SurveyResultShow";
// import "bootstrap/dist/css/bootstrap.css";
export default {
  components: {
    BootstrapAuthenticatedLayout,
    SurveyResultShow,
  },
  data() {
    return {};
  },
};
</script>
<style scoped>
svd-splitter {
  width: auto !important;
}
</style>
