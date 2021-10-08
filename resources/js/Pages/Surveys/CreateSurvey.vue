<template>
  <bootstrap-authenticated-layout>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-sm-6">
            <label for="exampleInputSurvey" class="form-label"
              >Survey Name</label
            >
            <input
              type="text"
              class="form-control"
              id="exampleInputSurvey"
              aria-describedby="surveyHelp"
              v-model="name"
            />
            <div id="surveyHelp" class="form-text">
              We'll use this name to list the surveys.
            </div>
          </div>
          <div class="mb-3 col-sm-6">
            <label for="exampleInputSurvey" class="form-label"
              >Success Rate</label
            >
            <input
              type="number"
              class="form-control"
              id="exampleInputSurvey"
              aria-describedby="surveyHelp"
              v-model="successRate"
            />
            <div id="surveyHelp" class="form-text">
              The number of correct answers allowed to pass the exam.
            </div>
          </div>
        </div>
        <button
          @click="saveSurvey"
          class="btn btn-dark"
          :class="loading ? 'disabled' : ''"
        >
          Submit
        </button>
      </div>
    </div>
  </bootstrap-authenticated-layout>
</template>

<script>
import BootstrapAuthenticatedLayout from "@/Layouts/Authenticated";
// import "bootstrap/dist/css/bootstrap.css";
export default {
  components: {
    BootstrapAuthenticatedLayout,
  },
  data() {
    return {
      loading: false,
      name: "",
      successRate: 0,
      json: {}
    };
  },
  methods: {
    saveSurvey() {
      this.loading = true;
      let data = {
        name: this.name,
        success_rate: this.successRate,
        json: {
          pages: [],
        },
      };
      axios.post("api/survey", data).then((response) => {
        if (response.status === 201) {
          window.location.replace('/surveys/' + response.data.data.id);
          this.dialog = false;
          this.loading = false;
          this.$root.snackbarMsg = response.data.message;
          this.$root.snackbar = true;
          this.editedItem = Object.assign({}, { name: "" });
        }
        else {
            alert('Error while creating the survey')
        }
      });
    },
  },
};
</script>
<style scoped>
</style>
