<template>
  <div class="login-screen">
    <h2>Login or Register</h2>

    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>

    <p>— or —</p>

    <form @submit.prevent="register">
      <input v-model="name" type="text" placeholder="Name" required />
      <input v-model="newEmail" type="email" placeholder="Email" required />
      <input v-model="newPassword" type="password" placeholder="Password" required />
      <input v-model="confirmPassword" type="password" placeholder="Confirm Password" required />
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
      name: "",
      newEmail: "",
      newPassword: "",
      confirmPassword: "",
    };
  },
  methods: {
    async login() {
      try {
        const res = await axios.post("/login", {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem("token", res.data.token);
        this.$router.push("/map");
      } catch (e) {
        alert("Login failed");
      }
    },
    async register() {
      if (this.newPassword !== this.confirmPassword) {
        return alert("Passwords do not match");
      }
      try {
        const res = await axios.post("/register", {
          name: this.name,
          email: this.newEmail,
          password: this.newPassword,
          password_confirmation: this.confirmPassword,
        });
        localStorage.setItem("token", res.data.token);
        this.$router.push("/map");
      } catch (e) {
        alert("Registration failed");
      }
    },
  },
};
</script>
