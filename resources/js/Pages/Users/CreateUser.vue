<template>
  <bootstrap-authenticated-layout>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="alert alert-danger" role="alert" v-if="errorMsg.length > 0">
          Whoops! Something went wrong !
          <ul>
            <li v-for="error in errorMsg" :key="error">
              {{ error }}
            </li>
          </ul>
        </div>

        <form @submit.prevent="submit">
          <div class="row">
            <div class="mb-3 col-sm-12">
              <label for="name" class="form-label">User Name</label>
              <input
                type="text"
                class="form-control"
                id="name"
                aria-describedby="surveyHelp"
                v-model="form.name"
              />
            </div>
            <div class="mb-3 col-sm-6">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                aria-describedby="surveyHelp"
                v-model="form.email"
              />
            </div>
            <div class="mb-3 col-sm-6">
              <label for="role" class="form-label">Role</label>
              <select
                class="form-select"
                v-model="form.role"
                aria-label="Default select example"
                id="role"
              >
                <option value="student">Student</option>
                <option value="training_center">Training center</option>
                <option value="admin">Administrator</option>
              </select>
            </div>
            <div class="mb-3 col-sm-6">
              <label for="pass" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="pass"
                aria-describedby="surveyHelp"
                v-model="form.password"
              />
            </div>
            <div class="mb-3 col-sm-6">
              <label for="cpass" class="form-label">Confirm Password</label>
              <input
                type="password"
                class="form-control"
                id="cpass"
                aria-describedby="surveyHelp"
                v-model="form.password_confirmation"
              />
            </div>
          </div>
          <button
            type="submit"
            class="btn btn-dark ms-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="loading"
          >
            Submit
          </button>
        </form>
      </div>
    </div>
  </bootstrap-authenticated-layout>
</template>

<script>
import BootstrapAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeValidationErrors from "@/Components/ValidationErrors";

// import "bootstrap/dist/css/bootstrap.css";
export default {
  components: {
    BootstrapAuthenticatedLayout,
    BreezeValidationErrors,
  },
  data() {
    return {
      loading: false,
      form: this.$inertia.form({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        role: "",
      }),
      errorMsg: [],
    };
  },
  methods: {
    submit() {
      const self = this;
      this.loading = true;
      axios
        .post("/api/users", this.form)
        .then((response) => {
          if (response.status === 201) {
            //route.push("/users");
            this.dialog = false;
            this.loading = false;
            this.$root.snackbarMsg = response.data.message;
            window.location.replace(this.route("users"));
          } else {
            alert("Error while creating the survey");
          }
        })
        .catch((error) => {
          console.log("error", error.response.data.errors);
          for (const errorField in error.response.data.errors) {
            self.errorMsg.push(`${error.response.data.errors[errorField][0]}`);
          }
          self.loading = false;
        });
    },
    // submit() {
    //   this.form.post('/api/users', {
    // onFinish: () => this.route.push({name: "users"}),
    //   });
    // },
  },
};
</script>
<style scoped>
</style>
