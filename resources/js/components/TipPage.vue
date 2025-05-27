<template>
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="flex justify-center">
            <div class="w-full md:w-2/3 lg:w-1/2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-4">Send a Tip to {{ user.name }}</h2>
                        <p class="mb-1">Email: {{ user.email }}</p>
                        <p class="mb-4">Tipping URL: {{ tipping_url }}</p>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                id="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Tip Amount (₦)</label>
                            <input
                                v-model="form.amount"
                                type="number"
                                id="amount"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                            <input
                                v-model="form.account_number"
                                type="text"
                                id="account_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="bank_code" class="block text-sm font-medium text-gray-700 mb-1">Bank</label>
                            <select
                                v-model="form.bank_code"
                                id="bank_code"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option v-for="bank in banks" :value="bank.code" :key="bank.code">{{ bank.name }}</option>
                            </select>
                        </div>

                        <button
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            @click="initiateTip"
                        >
                            Send Tip
                        </button>

                        <div v-if="otpRequired" class="mt-4">
                            <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Paystack OTP</label>
                            <input
                                v-model="form.otp"
                                type="text"
                                id="otp"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3"
                            >
                            <button
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                                @click="verifyOtp"
                            >
                                Verify OTP
                            </button>
                        </div>
                    </div>
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
        key: String
    },
    data() {
        return {
            form: {
                email: '',
                amount: 0,
                account_number: '',
                bank_code: '',
                otp: '',
                reference: ''
            },
            banks: [],
            otpRequired: false,
        };
    },
    mounted() {
        this.fetchBanks();
    },
    methods: {
        fetchBanks() {
            axios.get('/api/banks').then(response => {
                this.banks = response.data.banks;
            }).catch(err => this.showToast('Failed to load banks'));
        },
        initiateTip() {
            axios.post(`/api/tip/${this.key}`, {
                email: this.form.email,
                amount: this.form.amount,
                account_number: this.form.account_number,
                bank_code: this.form.bank_code,
            }).then(response => {
                if (response.data.message === 'OTP required') {
                    this.form.reference = response.data.reference;
                    this.otpRequired = true;
                    this.showToast('Please enter the OTP sent by your bank');
                } else {
                    this.showToast('Tip sent successfully');
                }
            }).catch(err => this.showToast(err.response?.data?.message || 'Failed to initiate tip'));
        },
        verifyOtp() {
            axios.post(`/api/tip/${this.key}/verify`, {
                reference: this.form.reference,
                otp: this.form.otp
            }).then(() => {
                this.showToast('Tip processed successfully');
                this.otpRequired = false;
                this.form = { email: '', amount: 0, account_number: '', bank_code: '', otp: '', reference: '' };
            }).catch(err => this.showToast(err.response?.data?.message || 'OTP verification failed'));
        },
        showToast(message) {
            // Implement a toast notification system compatible with your app
            // For example, you could use a simple alert for now
            alert(message);

            // Or you could integrate a Tailwind-compatible toast library
            // like toast from '@headlessui/vue' or create a custom toast component
        }
    }
}
</script>
