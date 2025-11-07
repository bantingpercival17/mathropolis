<template>
  <div class="card text-center text-white bg-dark bg-opacity-75 shadow-lg p-4 rounded-4">
    <a @click="$router.push('/home')" class="btn btn btn-outline-light">Back to Home Page</a>
    <template v-if="isLogin">
      <h1 class="display-6 fw-bold mb-3 text-shadow">LOGIN ACCOUNT</h1>
      <form @submit.prevent="handleLogin" class="mb-4">
        <div class="">
          <div class="form-group mb-3">
            <input type="email" class="form-control" placeholder="Email" v-model="loginForm.email" required>
          </div>
          <div class="form-group mb-3">
            <input type="password" class="form-control" placeholder="Password" v-model="loginForm.password" required>
          </div>
          <div class="orm-group mb-3">
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </div>
          <div>
            <button type="button" class="btn btn-secondary w-100" @click="loginWithGoogle">Login with Google</button>
          </div>
          <p class="text-white-50 mb-3">— OR —</p>
          <div>
            <button type="button" class="btn btn-success w-100" @click="isLogin = false">CREATE ACCOUNT</button>
          </div>
        </div>
      </form>
    </template>
    <template v-else>
      <h1 class="display-6 fw-bold mb-3 text-shadow">REGISTER ACCOUNT</h1>
      <form @submit.prevent="handleRegister">
        <div class="">
          <div class="form-group mb-3">
            <input type="text" class="form-control" placeholder="Name" v-model="registerForm.name" required>
          </div>
          <div class="form-group mb-3">
            <input type="email" class="form-control" placeholder="Email" v-model="registerForm.email" required>
          </div>
        </div>
        <div class="row g-2 align-items-center">
          <div class="form-group mb-3">
            <input type="password" class="form-control" placeholder="Password" v-model="registerForm.password" required>
          </div>
          <div class="form-group mb-3">
            <input type="password" class="form-control" placeholder="Confirm Password"
              v-model="registerForm.confirmPassword" required>
          </div>
          <div>
            <button type="submit" class="btn btn-success w-100">Register</button>
          </div>
          <div>
            <button type="button" class="btn btn-primary w-100" @click="isLogin = true">Back to Login</button>
          </div>
        </div>
      </form>
    </template>
  </div>
</template>

<script>
export default {
  name: 'LoginRegisterForm',
  data() {
    return {
      isLogin: true,
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        confirmPassword: ''
      }
    };
  },
  methods: {
    handleLogin() {
      // Basic validation and action placeholder
      console.log('Attempting Login with:', this.loginForm);
      // **TODO: Implement API call for Login**
      alert(`Logging in as ${this.loginForm.email}`);
    },
    handleRegister() {
      // Basic validation and action placeholder
      if (this.registerForm.password !== this.registerForm.confirmPassword) {
        alert('Passwords do not match!');
        return;
      }
      console.log('Attempting Registration with:', this.registerForm);
      // **TODO: Implement API call for Registration**
      alert(`Registering user ${this.registerForm.name}`);
    },
    loginWithGoogle() {
      window.location.href = '/auth/google'
    }
  },
};
</script>

<style scoped>
/* Scoped styles for the component */
/* You would replace 'your-background-image-url.jpg' in the template style 
   with the actual image from the prompt to truly match the background. */

.card {
  /* This helps the form stand out against the busy background */
  border-radius: 10px;
}

.btn-primary,
.btn-success {
  /* Style adjustments to better fit the visual look */
  min-width: 100px;
}
</style>