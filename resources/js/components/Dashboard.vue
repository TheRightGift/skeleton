<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ user.name }}!</p>
          </div>
          <div class="flex space-x-3">
            <button @click="logout" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Wallet Balance</h3>
                <p class="text-2xl font-bold text-gray-900">₦{{ formatMoney(wallet.balance || 0) }}</p>
              </div>
            </div>
            <button
              @click="refreshBalance"
              class="text-blue-600 hover:text-blue-800 focus:outline-none"
              :class="{ 'animate-spin': refreshingBalance }"
              :disabled="refreshingBalance"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Tips Received</h3>
                <p class="text-2xl font-bold text-gray-900">₦{{ formatMoney(totalTipsReceived) }}</p>
              </div>
            </div>
            <button
              @click="refreshBalance"
              class="text-blue-600 hover:text-blue-800 focus:outline-none"
              :class="{ 'animate-spin': refreshingBalance }"
              :disabled="refreshingBalance"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Transactions</h3>
                <p class="text-2xl font-bold text-gray-900">{{ transactions.length }}</p>
              </div>
            </div>
            <button
              @click="refreshBalance"
              class="text-blue-600 hover:text-blue-800 focus:outline-none"
              :class="{ 'animate-spin': refreshingBalance }"
              :disabled="refreshingBalance"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow">
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
              <button @click="showEditForm = !showEditForm" class="text-blue-600 hover:text-blue-700">
                {{ showEditForm ? 'Cancel' : 'Edit' }}
              </button>
            </div>

            <div v-if="!showEditForm" class="space-y-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ user.name }}</p>
                  <p class="text-sm text-gray-600">Full Name</p>
                </div>
              </div>

              <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ user.email }}</p>
                  <p class="text-sm text-gray-600">Email Address</p>
                </div>
              </div>

              <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ user.phone }}</p>
                  <p class="text-sm text-gray-600">Phone Number</p>
                </div>
              </div>

              <div class="pt-4 border-t">
                <button @click="toggle2FA" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                  {{ user.two_factor_enabled ? 'Disable' : 'Enable' }} 2FA
                </button>
              </div>
            </div>

            <!-- Edit Profile Form -->
            <div v-if="showEditForm" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input v-model="editForm.name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input v-model="editForm.email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input v-model="editForm.phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input v-model="editForm.password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter current password to confirm changes">
              </div>
              <div class="flex space-x-3">
                <button @click="updateProfile" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                  Update Profile
                </button>
                <button @click="showEditForm = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Wallet Card -->
        <div class="bg-white rounded-lg shadow">
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Tipping Wallet</h2>
              <button @click="showWithdrawForm = !showWithdrawForm" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                {{ showWithdrawForm ? 'Cancel' : 'Withdraw Funds' }}
              </button>
            </div>

            <div v-if="!showWithdrawForm" class="space-y-4">
              <div class="text-center py-4">
                <p class="text-3xl font-bold text-green-600">₦{{ formatMoney(wallet.balance || 0) }}</p>
                <p class="text-gray-600">Available Balance</p>
              </div>

              <div class="border-t pt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Your Tipping Link:</p>
                <div class="flex items-center space-x-2">
                  <input :value="wallet.tipping_url" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm">
                  <button @click="copyTippingUrl" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300 transition">
                    Copy
                  </button>
                </div>
              </div>

              <div class="text-center">
                <button @click="fetchTippingQrCode" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">
                  Generate QR Code
                </button>
              </div>

              <div v-if="tippingQrCode" class="text-center">
                <img :src="tippingQrCode" alt="Tipping QR Code" class="mx-auto border rounded-lg" />
                <p class="text-sm text-gray-600 mt-2">Share this QR code to receive tips</p>
              </div>
            </div>

            <!-- Withdraw Form -->
            <div v-if="showWithdrawForm" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₦)</label>
                <input v-model="withdrawForm.amount" type="number" min="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimum: ₦1,000">
                <p class="text-sm text-gray-500 mt-1">Withdrawal fee: ₦300</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                <input v-model="withdrawForm.account_number" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank</label>
                <select v-model="withdrawForm.bank_code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="">Select Bank</option>
                  <option v-for="bank in banks" :value="bank.code" :key="bank.code">{{ bank.name }}</option>
                </select>
              </div>
              <div class="flex space-x-3">
                <button @click="withdraw" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                  Confirm Withdrawal
                </button>
                <button @click="showWithdrawForm = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Notifications Card -->
        <div class="lg:row-span-2">
          <notifications-component />
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="mt-8 bg-white rounded-lg shadow">
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Transactions</h2>

          <div v-if="transactions.length === 0" class="text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gray-600">No transactions yet</p>
            <p class="text-sm text-gray-500">Your transaction history will appear here</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ transaction.id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₦{{ formatMoney(transaction.amount) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getTransactionTypeClass(transaction.type)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ formatTransactionType(transaction.type) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(transaction.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ formatStatus(transaction.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(transaction.created_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- 2FA Modal -->
    <div v-if="twoFactorQr" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg font-medium text-gray-900">Setup 2FA</h3>
          <div class="mt-4">
            <img :src="twoFactorQr" alt="2FA QR Code" class="mx-auto" />
            <p class="text-sm text-gray-600 mt-2">Secret: {{ twoFactorSecret }}</p>
            <p class="text-sm text-gray-500 mt-2">Scan this QR code with your authenticator app</p>
          </div>
          <div class="mt-5">
            <button @click="twoFactorQr = ''" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notifications -->
    <div v-if="toast.show" :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'" class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg">
      {{ toast.message }}
    </div>
  </div>
</template>

<script>
// import axios from 'axios';

export default {
    name: 'Dashboard',
    data() {
        return {
            user: {},
            wallet: {},
            transactions: [],
            tippingQrCode: '',
            twoFactorQr: '',
            twoFactorSecret: '',
            showEditForm: false,
            showWithdrawForm: false,
            editForm: { name: '', email: '', phone: '', password: '' },
            withdrawForm: { amount: '', account_number: '', bank_code: '' },
            banks: [],
            loading: false,
            refreshingBalance: false,
            toast: {
                show: false,
                message: '',
                type: 'success'
            }
        };
    },
    computed: {
        totalTipsReceived() {
            return this.transactions
                .filter(t => t.type === 'tip' && t.status === 'completed')
                .reduce((sum, t) => sum + parseFloat(t.amount), 0);
        }
    },
    mounted() {
        this.fetchUserData();
        this.fetchBanks();
    },
    methods: {
        async fetchUserData() {
            try {
                this.loading = true;
                const response = await axios.get('/api/user');
                this.user = response.data.user;
                this.wallet = response.data.wallet || {};
                this.transactions = response.data.transactions || [];
                this.editForm = {
                    name: this.user.name,
                    email: this.user.email,
                    phone: this.user.phone,
                    password: ''
                };
            } catch (error) {
                console.error('Fetch user error:', error);
                if (error.response?.status === 401) {
                    window.location.href = '/auth/login';
                } else {
                    this.showToast('Failed to load user data', 'error');
                }
            } finally {
                this.loading = false;
            }
        },

        async fetchBanks() {
            try {
                const response = await axios.get('/api/banks');
                this.banks = response.data.banks || [];
            } catch (error) {
                this.showToast('Failed to load banks', 'error');
            }
        },

        async refreshBalance() {
            try {
                this.refreshingBalance = true;
                const response = await axios.get('/api/wallet/refresh-balance');

                // Update wallet balance
                this.wallet.balance = response.data.balance;

                // Update transactions if returned
                if (response.data.transactions) {
                    this.transactions = response.data.transactions;
                }

                this.showToast('Dashboard data refreshed successfully', 'success');
            } catch (error) {
                this.showToast('Failed to refresh data', 'error');
            } finally {
                this.refreshingBalance = false;
            }
        },

        async fetchTippingQrCode() {
            try {
                const response = await axios.get('/api/wallet/qr-code');
                this.tippingQrCode = response.data.qr_code;
                this.showToast('Tipping QR code generated successfully');
            } catch (error) {
                this.showToast(error.response?.data?.message || 'Failed to generate QR code', 'error');
            }
        },

        async updateProfile() {
            if (!this.editForm.password) {
                this.showToast('Please enter your current password to confirm changes', 'error');
                return;
            }

            try {
                await axios.post('/api/profile/update', this.editForm);
                this.showToast('Profile updated successfully');
                this.showEditForm = false;
                this.editForm.password = '';
                await this.fetchUserData();
            } catch (error) {
                this.showToast(error.response?.data?.message || 'Failed to update profile', 'error');
            }
        },

        async toggle2FA() {
            try {
                if (this.user.two_factor_enabled) {
                    await axios.post('/api/2fa/disable');
                    this.showToast('2FA disabled successfully');
                    await this.fetchUserData();
                } else {
                    const response = await axios.post('/api/2fa/enable');
                    this.twoFactorQr = response.data.qr_code;
                    this.twoFactorSecret = response.data.secret;
                    this.showToast('Scan the QR code with your authenticator app');
                }
            } catch (error) {
                this.showToast(error.response?.data?.message || 'Failed to toggle 2FA', 'error');
            }
        },

        async withdraw() {
            const amount = parseFloat(this.withdrawForm.amount);
            const fee = 300;
            const total = amount + fee;

            if (!amount || amount < 1000) {
                this.showToast('Minimum withdrawal amount is ₦1,000', 'error');
                return;
            }

            if (total > this.wallet.balance) {
                this.showToast(`Insufficient balance. You need ₦${this.formatMoney(total)} (including ₦300 fee)`, 'error');
                return;
            }

            if (!this.withdrawForm.account_number || !this.withdrawForm.bank_code) {
                this.showToast('Please fill in all withdrawal details', 'error');
                return;
            }

            try {
                await axios.post('/api/wallet/withdraw', this.withdrawForm);
                this.showToast('Withdrawal initiated successfully');
                this.showWithdrawForm = false;
                this.withdrawForm = { amount: '', account_number: '', bank_code: '' };
                await this.fetchUserData();
            } catch (error) {
                this.showToast(error.response?.data?.message || 'Withdrawal failed', 'error');
            }
        },

        async logout() {
            try {
                await axios.post('/auth/logout');
                window.location.href = '/';
            } catch (error) {
                // If logout fails, still redirect to home
                window.location.href = '/';
            }
        },

        copyTippingUrl() {
            navigator.clipboard.writeText(this.wallet.tipping_url).then(() => {
                this.showToast('Tipping URL copied to clipboard');
            }).catch(() => {
                this.showToast('Failed to copy URL', 'error');
            });
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('en-NG').format(amount);
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-NG', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        formatTransactionType(type) {
            const types = {
                'tip': 'Tip Received',
                'withdrawal': 'Withdrawal',
                'deposit': 'Deposit'
            };
            return types[type] || type;
        },

        formatStatus(status) {
            const statuses = {
                'pending': 'Pending',
                'completed': 'Completed',
                'failed': 'Failed',
                'cancelled': 'Cancelled'
            };
            return statuses[status] || status;
        },

        getTransactionTypeClass(type) {
            const classes = {
                'tip': 'bg-green-100 text-green-800',
                'withdrawal': 'bg-red-100 text-red-800',
                'deposit': 'bg-blue-100 text-blue-800'
            };
            return classes[type] || 'bg-gray-100 text-gray-800';
        },

        getStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'completed': 'bg-green-100 text-green-800',
                'failed': 'bg-red-100 text-red-800',
                'cancelled': 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => {
                this.toast.show = false;
            }, 5000);
        }
    }
}
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
