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
          <user-form :form.sync="form"/>
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
import UserForm from "@/Components/Users/UserForm";

// import "bootstrap/dist/css/bootstrap.css";
export default {
  components: {
    BootstrapAuthenticatedLayout,
    BreezeValidationErrors,
    UserForm,
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
