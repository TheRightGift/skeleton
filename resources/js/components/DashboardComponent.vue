<template>
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-gray-600">Welcome back, {{ user.name }}!</p>
    </div>

    <!-- User Info Card -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
      <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="h-16 w-16 bg-indigo-100 rounded-full flex items-center justify-center">
              <span class="text-indigo-600 font-bold text-xl">{{ user.name.charAt(0) }}</span>
            </div>
          </div>
          <div class="ml-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ user.name }}</h3>
            <p class="text-sm text-gray-500">{{ user.email }}</p>
            <p class="text-sm text-gray-500">Member since {{ formatDate(user.created_at) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Account Status</dt>
                <dd class="text-lg font-medium text-gray-900">Active</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardComponent',
  props: {
    initialUser: Object,
    initialStats: Object
  },
  data() {
    return {
      user: this.initialUser,
      stats: this.initialStats,
      deviceName: '',
      newToken: '',
      loading: false
    }
  },

  mounted() {
    this.fetchUser();
  },

  methods: {
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    },
    formatRelativeTime(dateString) {
      const date = new Date(dateString);
      const now = new Date();
      const diffInSeconds = Math.floor((now - date) / 1000);

      if (diffInSeconds < 60) return 'Just now';
      if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
      if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
      return `${Math.floor(diffInSeconds / 86400)} days ago`;
    },
    async createToken() {
      if (!this.deviceName) return;

      this.loading = true;
      this.newToken = '';

      try {
        const response = await axios.post('/api/login', {
          email: this.user.email,
          password: 'password', // In real app, you'd prompt for password
          device_name: this.deviceName
        });

        if (response.data && response.data.token) {
          this.newToken = response.data.token;
          this.deviceName = '';
          this.stats.total_tokens += 1;
        } else {
          alert('Failed to create token: ' + (response.data.message || 'Unknown error'));
        }
      } catch (error) {
        let message = 'Network error';
        if (error.response && error.response.data && error.response.data.message) {
          message = error.response.data.message;
        }
        alert('Failed to create token: ' + message);
      } finally {
        this.loading = false;
      }
    },
    async copyToken() {
      try {
        await navigator.clipboard.writeText(this.newToken);
        alert('Token copied to clipboard!');
      } catch (err) {
        console.error('Failed to copy token:', err);
      }
    },
    async revokeAllTokens() {
      if (!confirm('Are you sure you want to revoke all tokens? This will log out all devices.')) {
        return;
      }

      this.loading = true;

      try {
        const response = await axios.post('/api/tokens/revoke-all');

        if (response.data) {
          alert('All tokens revoked successfully');
          this.stats.total_tokens = 0;
        } else {
          alert('Failed to revoke tokens: ' + (response.data.message || 'Unknown error'));
        }
      } catch (error) {
        let message = 'Network error';
        if (error.response && error.response.data && error.response.data.message) {
          message = error.response.data.message;
        }
        alert('Failed to revoke tokens: ' + message);
      } finally {
        this.loading = false;
      }
    },
    async fetchUser() {
        this.loading = true;

        try {
            const response = await axios.get('/api/user');

            if (response.data) {
                this.user = response.data;
            }
        } catch (error) {
            let message = 'Network error';
            if (error.response && error.response.data && error.response.data.message) {
                message = error.response.data.message;
            }
            alert('Failed to fetch user: ' + message);
        } finally {
            this.loading = false;
        }
    },
}
// onMounted(() => {
//     fetchUser();
// })
}
</script>
