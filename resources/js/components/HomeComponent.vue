<template>
  <div class="text-center">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Welcome to Laravel App</h1>

    <div v-if="user" class="bg-green-50 border border-green-200 rounded-lg p-6 max-w-md mx-auto mb-8">
      <h2 class="text-lg font-semibold text-green-800 mb-2">Hello, {{ user.name }}!</h2>
      <p class="text-green-600">You are logged in.</p>
      <a href="/dashboard" class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
        Go to Dashboard
      </a>
    </div>

    <div v-else class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-md mx-auto mb-8">
      <h2 class="text-lg font-semibold text-blue-800 mb-2">Get Started</h2>
      <p class="text-blue-600 mb-4">Please log in to access your dashboard.</p>
      <a href="/login" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        Login
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
      <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold mb-2">Secure Authentication</h3>
        <p class="text-gray-600">Built with Laravel Sanctum for both SPA and mobile applications.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold mb-2">API Ready</h3>
        <p class="text-gray-600">RESTful API endpoints for mobile app integration.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold mb-2">Modern UI</h3>
        <p class="text-gray-600">Clean and responsive design with Vue.js and Tailwind CSS.</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeComponent',
  props: {
    user: {
      type: Object,
      default: null
    }
  }
}
</script>
