<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="login">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address"
            >
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password"
            >
          </div>
        </div>

        <!-- Error Messages -->
        <div v-if="errors.length > 0" class="bg-red-50 border border-red-200 rounded-md p-4">
          <div class="flex">
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                There were errors with your submission
              </h3>
              <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                  <li v-for="error in errors" :key="error">{{ error }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Message -->
        <div v-if="success" class="bg-green-50 border border-green-200 rounded-md p-4">
          <div class="flex">
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">{{ success }}</p>
            </div>
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            :disabled="loading"
          >
            <span v-if="!loading">Sign in</span>
            <span v-else>Signing in...</span>
          </button>
        </div>

        <div class="text-center">
          <p class="text-sm text-gray-600">
            Test credentials: <strong>test@example.com</strong> / <strong>password123</strong>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginComponent',
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: [],
      success: '',
      loading: false
    }
  },
  methods: {
    async login() {
      this.loading = true;
      this.errors = [];
      this.success = '';

      try {
        // Get CSRF token first
        await axios.get('/sanctum/csrf-cookie');

        const response = await axios.post('/auth/login', this.form);

        if (response.data) {
          this.success = 'Login successful! Redirecting...';
          setTimeout(() => {
            window.location.href = '/dashboard';
          }, 1000);
        }
      } catch (error) {
        if (error.response && error.response.data) {
          if (error.response.data.errors) {
            this.errors = Object.values(error.response.data.errors).flat();
          } else if (error.response.data.message) {
            this.errors = [error.response.data.message];
          } else {
            this.errors = ['An error occurred. Please try again.'];
          }
        } else {
          this.errors = ['Network error. Please try again.'];
        }
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>
