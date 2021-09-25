<template>
  <bootstrap-authenticated-layout>
      <survey-creator></survey-creator>
  </bootstrap-authenticated-layout>
</template>

<script>
import BootstrapAuthenticatedLayout from "@/Layouts/Authenticated";
import SurveyCreator from "../components/SurveyCreator";
// import "bootstrap/dist/css/bootstrap.css";
export default {
  components: {
    BootstrapAuthenticatedLayout,
    SurveyCreator,
  },
  data() {
    return {};
  },
};
</script>
<style scoped>
</style>
