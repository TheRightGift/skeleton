<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-md mx-auto px-4 py-8">
            <!-- Success/Error Messages -->
            <div v-if="statusMessage" class="mb-4 p-4 rounded-lg" :class="{
                'bg-green-100 text-green-800': statusType === 'success',
                'bg-red-100 text-red-800': statusType === 'error'
            }">
                {{ statusMessage }}
            </div>

            <!-- Thank You Message -->
            <div v-if="showThankYouPage" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold">Thank You!</h1>
                    <p class="text-green-100 mt-1">Your tip has been sent to {{ user.name }}</p>
                </div>

                <div class="p-6 text-center">
                    <p class="text-gray-700 mb-6">{{ user.name }} appreciates your generosity and support!</p>

                    <button
                        @click="replayTransaction"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]"
                    >
                        Send Another Tip
                    </button>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 text-center">
                    <p class="text-xs text-gray-500">
                        Powered by <span class="font-semibold">Tippaz</span> & Paystack
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div v-if="!showThankYouPage" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold">Send a Tip</h1>
                    <p class="text-blue-100 mt-1">to {{ user.name }}</p>
                </div>

                <!-- Form -->
                <div class="p-6">
                    <form @submit.prevent="initiateTip">
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                id="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="your@email.com"
                            >
                        </div>

                        <div class="mb-6">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Tip Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-medium">₦</span>
                                <input
                                    v-model="form.amount"
                                    type="number"
                                    id="amount"
                                    min="100"
                                    step="50"
                                    required
                                    class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="1000"
                                >
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum amount: ₦100</p>
                        </div>

                        <button
                            type="submit"
                            :disabled="loading || !form.email || !form.amount || form.amount < 100"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02] disabled:scale-100"
                        >
                            <span v-if="loading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                            <span v-else>Pay with Paystack</span>
                        </button>
                    </form>

                    <!-- Quick Amount Buttons -->
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Quick amounts:</p>
                        <div class="flex gap-2 flex-wrap">
                            <button
                                v-for="quickAmount in quickAmounts"
                                :key="quickAmount"
                                @click="form.amount = quickAmount"
                                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full transition"
                            >
                                ₦{{ quickAmount.toLocaleString() }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 text-center">
                    <p class="text-xs text-gray-500">
                        Powered by <span class="font-semibold">Tippaz</span> & Paystack
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'TipPage',
    props: {
        user: Object,
        tipping_url: String,
        keyering: String
    },
    data() {
        return {
            form: {
                email: '',
                amount: null,
            },
            quickAmounts: [100, 500, 1000, 2000, 5000, 10000],
            loading: false,
            statusMessage: '',
            statusType: '', // 'success' or 'error'
            showThankYouPage: false,
        };
    },
    mounted() {
        this.checkPaymentStatus();
    },
    methods: {
        checkPaymentStatus() {
            const urlParams = new URLSearchParams(window.location.search);
            const paymentStatus = urlParams.get('payment');
            console.log('Payment status:', paymentStatus);

            if (paymentStatus === 'success') {
                this.showThankYouPage = true;
                this.showMessage('Payment completed successfully! The tip has been sent.', 'success');
            } else if (paymentStatus === 'failed') {
                this.showMessage('Payment failed. Please try again.', 'error');
            }
        },
        replayTransaction() {
            // Remove the payment status from URL and show the form again
            const url = new URL(window.location.href);
            url.searchParams.delete('payment');
            window.history.pushState({}, '', url);

            this.showThankYouPage = false;
            this.statusMessage = '';
            this.statusType = '';

            // Reset form
            this.form.email = '';
            this.form.amount = null;
        },
        async initiateTip() {
            if (!this.form.email || !this.form.amount || this.form.amount < 100) {
                this.showMessage('Please fill in all required fields with valid values.', 'error');
                return;
            }

            this.loading = true;
            this.statusMessage = '';

            try {
                const response = await axios.post(`/api/tip/${this.keyering}`, {
                    email: this.form.email,
                    amount: this.form.amount,
                });

                if (response.data.authorization_url) {
                    // Redirect to Paystack payment page
                    window.location.href = response.data.authorization_url;
                } else {
                    this.showMessage('Failed to initialize payment. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Payment initialization error:', error);
                this.showMessage(
                    error.response?.data?.message || 'Failed to initialize payment. Please try again.',
                    'error'
                );
            } finally {
                this.loading = false;
            }
        },
        showMessage(message, type) {
            this.statusMessage = message;
            this.statusType = type;

            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    this.statusMessage = '';
                    this.statusType = '';
                }, 5000);
            }
        }
    }
}
</script>
