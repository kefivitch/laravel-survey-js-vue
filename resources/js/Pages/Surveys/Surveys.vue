<template>
  <bootstrap-authenticated-layout>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <h2 class="pb-4">Surveys List</h2>
          </div>

          <div class="col-md-2 ms-auto">
            <Link
              :href="route('create-survey')"
              as="button"
              type="button"
              class="btn btn-dark"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
              </svg>
              Create new Survey
            </Link>
          </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
          <symbol
            id="check-circle-fill"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path
              d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
            />
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
          <div>{{ msg }}</div>
        </div>
        <div class="">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Created date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="survey in surveys" :key="survey.id">
                <td>{{ survey.id }}</td>
                <td>
                  {{ survey.name }}
                  <small style="font-size: small">({{ survey.slug }})</small>
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
                        <a class="dropdown-item" target="_blank" :href="route('survey-show', {survey_slug: survey.slug})" >Run Survey</a>
                      </li>
                      <li>
                        <Link
                          class="dropdown-item"
                          :href="route('results', {survey_id: survey.id})"
                          >Show Results</Link
                        >
                        <!--<a class="" href="#">Get Results</a>-->
                      </li>
                      <li>
                        <Link
                          class="dropdown-item"
                          :href="route('survey', {survey_id: survey.id})"
                          >Edit Survey</Link
                        >
                      </li>
                      <li @click="deleteItem(survey)">
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
                class="page-item "
                :class="page === 1 ? 'disabled' : ''"
                @click="page > 1 && page--"
              >
                <a class="page-link " href="#" tabindex="-1" aria-disabled="true"
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
import { Link } from "@inertiajs/inertia-vue";

export default {
  name: "survey-list",
  components: { BootstrapAuthenticatedLayout, Link },
  data() {
    return {
      surveys: [],
      page: 1,
      pageLength: 1,
      dialog: false,
      loading: false,
      msg: "",
      editedItem: {
        name: "",
      },
    };
  },
  mounted() {
    this.getSurveys();
  },
  watch: {
    page() {
      this.getSurveys();
    },
  },
  methods: {
    getSurveys() {
      this.loading = true;
      axios
        .get("api/survey", {
          params: {
            page: this.page,
          },
        })
        .then((response) => {
          if (response.status === 200) {
            this.surveys = response.data.data;
            this.pageLength = Math.ceil(
              response.data.meta.total / response.data.meta.per_page
            );
            this.loading = false;
          }
        })
        .catch((error) => {
          this.loading = false;
          console.info(error.response);
        });
    },
    editItem(id) {
      this.$router.push({ name: "editor", params: { id: id } });
    },
    deleteItem(item) {
      if (confirm("Are you sure you want to delete this survey?")) {
        this.snackbar = true;
        axios.delete("/api/survey/" + item.id).then((response) => {
          if (response.status === 200) {
            this.msg = response.data.message;
            setTimeout(() => {
              this.msg = "";
            }, 3000);
            this.getSurveys();
          }
        });
      }
    },
    onCloseModal() {
      this.dialog = false;
      this.editedItem = Object.assign({}, { name: "" });
    },
    onSaveModal(name) {
      this.loading = true;
      let data = {
        name: name,
        json: {
          pages: [],
        },
      };
      axios.post("/survey", data).then((response) => {
        if (response.status === 201) {
          this.dialog = false;
          this.loading = false;
          this.$root.snackbarMsg = response.data.message;
          this.$root.snackbar = true;
          this.editedItem = Object.assign({}, { name: "" });
          this.getSurveys();
        }
      });
    },
    runSurvey(slug) {
      window.open("/" + SurveyConfig.route_prefix + "/" + slug, "_blank");
    },
    showResults(id) {
      this.$router.push({ name: "result", params: { id: id } });
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
