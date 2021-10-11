<template>
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
    <div class="mb-3 " :class="this.$page.props.auth.user.role == 'admin' ? 'col-sm-6' : 'col-sm-12'">
      <label for="email" class="form-label">Email</label>
      <input
        type="email"
        class="form-control"
        id="email"
        aria-describedby="surveyHelp"
        v-model="form.email"
      />
    </div>
    <div class="mb-3 col-sm-6"  v-if="this.$page.props.auth.user.role == 'admin'">
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
</template>
<script>
export default {
  props: {
    form: {
      required: true,
    },
  },
};
</script>
<style>
</style>
