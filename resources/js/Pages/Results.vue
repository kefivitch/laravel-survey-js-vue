<template>
  <bootstrap-authenticated-layout>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <h2 class="pb-4">Results List of {{ $page.props.survey_name }}</h2>
          </div>
        </div>
        <div class="">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">IP Address</th>
                <th scope="col">User</th>
                <th scope="col">Created date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="survey in results" :key="survey.id">
                <td>{{ survey.id }}</td>
                <td>
                  {{ survey.ip_address }}
                </td>
                <td>
                  {{ survey.user.name }}
                </td>
                <td>{{ survey.created_at }}</td>

                <td>
                  <div class="btn-group" role="group">
                    <button
                      id="btnGroupDrop1"
                      type="button"
                      class="btn btn-outline-secondary dropdown-toggle"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <li>
                        <a class="dropdown-item" href="#">Run Survey</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="#">Get Results</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="#">Edit Survey</a>
                      </li>
                      <li>
                        <a class="dropdown-item bg-danger text-white" href="#"
                          >Delete Survey</a
                        >
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav aria-label="Page navigation ">
            <ul class="pagination justify-content-end pt-2">
              <li
                class="page-item"
                :class="page === 1 ? 'disabled' : ''"
                @click="page > 1 && page--"
              >
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true"
                  >Previous</a
                >
              </li>

              <li
                class="page-item"
                v-for="pagen in pageLength"
                :class="page === pagen ? 'active' : ''"
                :key="pagen"
                @click="page = pagen"
              >
                <a class="page-link" href="#">{{ pagen }}</a>
              </li>
              <li
                class="page-item"
                :class="page === pageLength ? 'disabled' : ''"
                @click="page < pageLength && page++"
              >
                <a class="page-link" href="#">Next</a>
              </li>
            </ul>
          </nav>
          <div class="text-xs-center pt-2"></div>
        </div>
      </div>
    </div>
  </bootstrap-authenticated-layout>
</template>

<script>
import BootstrapAuthenticatedLayout from "@/Layouts/Authenticated";
export default {
  name: "survey-list",
  components: { BootstrapAuthenticatedLayout },
  data() {
    return {
      results: [],
      page: 1,
      pageLength: 1,
      dialog: false,
      loading: false,
      formTitle: "New Survey",
      editedItem: {
        name: "",
      },
    };
  },
  mounted() {
    this.getResults();
  },
  watch: {
    page() {
      this.getResults();
    },
  },
  methods: {
    getResults() {
      this.loading = true;

      axios
        .get("/api/survey/" + this.$page.props.survey_id + "/result?page=" + this.page)
        .then((response) => {
          this.results = response.data.data;
          this.survey = response.data.meta.survey;
          this.pageLength = Math.ceil(
            response.data.meta.total / response.data.meta.per_page
          );
          this.loading = false;
          this.surveyData = new SurveyVue.Model(response.data.meta.survey.json);
          this.surveyData.mode = "display";
        })
        .catch((error) => {
          console.info(error.response);
          this.loading = false;
        });
    },
    deleteItem(item) {
      if (confirm("Are you sure you want to delete this survey?")) {
        this.snackbar = true;
        axios.delete("/survey/" + item.id).then((response) => {
          if (response.status === 200) {
            this.$root.snackbarMsg = response.data.message;
            this.$root.snackbar = true;
          }
        });
        const index = this.results.indexOf(item);
        this.results.splice(index, 1);
      }
    },
    runSurvey(slug) {
      window.open("/" + SurveyConfig.route_prefix + "/" + slug, "_blank");
    },
  },
};
</script>

<style scoped>
.remove-overflow {
  overflow: inherit;
}

table {
  background: white;
  font-size: larger;
}
</style>
