<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 to-purple-700 text-white">
      <div class="container mx-auto px-4 py-16 text-center">
        <h1 class="text-5xl font-bold mb-4">Welcome to Tippaz</h1>
        <p class="text-xl mb-8 opacity-90">Digital tipping made easy for waiters, riders, and service providers</p>

        <div v-if="user" class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg p-6 max-w-md mx-auto mb-8">
          <h2 class="text-lg font-semibold mb-2">Hello, {{ user.name }}!</h2>
          <p class="mb-4 opacity-90">Welcome back to your tipping dashboard.</p>
          <a href="/dashboard" class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Go to Dashboard
          </a>
        </div>

        <div v-else class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg p-6 max-w-md mx-auto mb-8">
          <h2 class="text-lg font-semibold mb-2">Get Started</h2>
          <p class="mb-4 opacity-90">Join thousands who are already earning tips digitally.</p>
          <div class="space-x-4">
            <a href="/auth/login" class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
              Login
            </a>
            <a href="#" onclick="alert('Registration feature coming soon!')" class="inline-block bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
              Sign Up
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="container mx-auto px-4 py-16">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Tippaz?</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Experience the future of tipping with our secure, fast, and user-friendly platform.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-xl shadow-sm border hover:shadow-md transition">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-gray-900">QR Code Tipping</h3>
          <p class="text-gray-600">Generate unique QR codes for instant tipping. Perfect for restaurants, delivery services, and any service provider.</p>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border hover:shadow-md transition">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-gray-900">Secure Payments</h3>
          <p class="text-gray-600">Powered by Paystack for secure and reliable payment processing. Your money is always safe with us.</p>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border hover:shadow-md transition">
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-gray-900">Instant Withdrawals</h3>
          <p class="text-gray-600">Withdraw your earnings instantly to your bank account. Track all transactions in your personal wallet.</p>
        </div>
      </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-gray-100 py-16">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
          <p class="text-gray-600">Get started in just three simple steps</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
            <h3 class="text-lg font-semibold mb-2">Sign Up</h3>
            <p class="text-gray-600">Create your free account and set up your profile in minutes.</p>
          </div>
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
            <h3 class="text-lg font-semibold mb-2">Share Your Link</h3>
            <p class="text-gray-600">Generate and share your personalized tipping QR code or link.</p>
          </div>
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
            <h3 class="text-lg font-semibold mb-2">Receive Tips</h3>
            <p class="text-gray-600">Start receiving tips instantly and withdraw to your bank account.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-16">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Earning?</h2>
        <p class="text-xl mb-8 opacity-90">Join thousands of service providers already using Tippaz</p>
        <a href="/auth/login" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition">
          Get Started Today
        </a>
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
