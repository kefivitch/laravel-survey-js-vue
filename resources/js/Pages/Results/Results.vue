<template>
  <bootstrap-authenticated-layout>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h2 class="pb-4">Results List of "{{ $page.props.survey.name }}" survey</h2>
          </div>
        </div>
        <div class="">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">IP Address</th>
                <th scope="col">User</th>
                <th scope="col">Correct Answers</th>
                <th scope="col">Result</th>
                <th scope="col">Created date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="result in results" :key="result.id">
                <td>{{ result.id }}</td>
                <td>
                  {{ result.ip_address }}
                </td>
                <td>
                  {{ result.user.name }}
                </td>
                <td>
                  {{ getCorrectAnswers(result) }} out of {{ countQuestions }}
                </td>
                <td :class="$page.props.survey.success_rate >answers[result.id] ? 'text-danger' : 'text-success'">
                  {{  $page.props.survey.success_rate >answers[result.id] ? 'Failed' : 'Accepted' }}
                </td>
                <td>{{ result.created_at }}</td>

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
                        <Link class="dropdown-item" :href="route('show-survey-result', {survey_id:$page.props.survey.id, result_id: result.id})">Get Results</Link>
                      </li>
                      <li @click="deleteItem(result)">
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
                <a class="page-link " href="#">{{ pagen }}</a>
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
import * as SurveyVue from "survey-vue";
import { Link } from "@inertiajs/inertia-vue";
export default {
  name: "survey-list",
  components: { BootstrapAuthenticatedLayout,Link },
  data() {
    return {
      results: [],
      questions: {},
      answers: {},
      page: 1,
      countQuestions: 0,
      pageLength: 1,
      dialog: {},
      loading: false,
      formTitle: "New Survey",
      editedItem: {
        name: "",
      },
    };
  },
  mounted() {
    this.getResults();
    this.getQuestions();
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
        .get(
          "/api/survey/" +
            this.$page.props.survey.id +
            "/result?page=" +
            this.page
        )
        .then((response) => {
          this.results = response.data.data;
          this.survey = response.data.meta.survey;
          this.pageLength = Math.ceil(
            response.data.meta.total / response.data.meta.per_page
          );
          this.loading = false;
          //   this.surveyData = new SurveyVue.Model(response.data.meta.survey.json);
          //   this.surveyData.mode = "display";
        })
        .catch((error) => {
          console.info(error.response);
          this.loading = false;
        });
    },
    deleteItem(item) {
      if (confirm("Are you sure you want to delete this survey result?")) {
        this.snackbar = true;
        axios.delete(`/api/survey/${this.$page.props.survey.id}/result/${item.id}`).then((response) => {
          if (response.status === 200) {
            this.$root.snackbarMsg = response.data.message;
            this.$root.snackbar = true;
          }
        });
        this.getResults();
      }
    },
    runSurvey(slug) {
      window.open("/" + SurveyConfig.route_prefix + "/" + slug, "_blank");
    },
    getQuestions() {
      const survey = this.$page.props.survey.json;
      const self = this;
      survey.pages.forEach((page) => {
        page.elements.forEach((question) => {
          self.questions[question.name] = question.correctAnswer;
          self.countQuestions++;
        });
      });
    },
    getCorrectAnswers(survey) {
      let count = 0;
      const self = this;
      for (const [key, value] of Object.entries(survey.json)) {
          const questions_string = Array.isArray(self.questions[key]) && `${self.questions[key]}`;
          const answer_string = Array.isArray(value) && `${value}`;
          if (answer_string === questions_string) count++;

      }
      this.answers[survey.id] = count;
      return count;
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
