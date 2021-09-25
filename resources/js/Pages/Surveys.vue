<template>
  <bootstrap-authenticated-layout>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <h2  class="pb-4">Surveys List</h2>
          </div>

          <div class="col-md-2 ms-auto">
            <button type="button" class="btn btn-dark">
              Create new Survey
            </button>
          </div>
        </div>
        <div class="">
          <table class="table table-striped">
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
                <td>{{ survey.name }}</td>
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
      surveys: [],
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
        axios.delete("/survey/" + item.id).then((response) => {
          if (response.status === 200) {
            this.$root.snackbarMsg = response.data.message;
            this.$root.snackbar = true;
          }
        });
        const index = this.surveys.indexOf(item);
        this.surveys.splice(index, 1);
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
