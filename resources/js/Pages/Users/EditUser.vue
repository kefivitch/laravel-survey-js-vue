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
        <div class="alert alert-success" role="alert" v-if="c">
          The profile is up to date !
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
  props: {
      user: {
          required: true,
          type: Object
      }
  },
  data() {
    return {
      loading: false,
      form: this.$inertia.form({
        name: this.user.name,
        email: this.user.email,
        password: "",
        password_confirmation: "",
        role: this.user.role,
      }),
      c: false,
      errorMsg: [],
    };
  },
  mounted() {
      this.confirmMessage()
  },
  methods: {
    submit() {
      const self = this;
      this.loading = true;
      axios
        .put(`/api/users/${this.user.id}`, this.form)
        .then((response) => {
          if (response.status === 200) {
              //console.log(self.$page.props.auth.user.role)
            if(self.$page.props.auth.user.role != "admin") {
                window.location.replace(this.route("edit-user", {user_id: self.$page.props.auth.user.id})+"?c=true");
            } else {
                window.location.replace(this.route("users"));
            }
          } else {
            alert("Error while updating the user");
          }
        })
        .catch((error) => {
          console.log("error", error);
          for (const errorField in error.response.data.errors) {
            self.errorMsg.push(`${error.response.data.errors[errorField][0]}`);
          }
          self.loading = false;
        });
    },
    confirmMessage() {
      const url = window.location.search
      const query = new URLSearchParams(url);

      this.c = query.has('c');

      setTimeout(() => this.c = false, 3000)
    },
  },
};
</script>
<style scoped>
</style>
