<template>
    <div class="container">
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Send a Tip to {{ user.name }}</span>
                        <p>Email: {{ user.email }}</p>
                        <p>Tipping URL: {{ tipping_url }}</p>
                        <div class="input-field">
                            <input v-model="form.email" type="email" id="email">
                            <label for="email">Your Email</label>
                        </div>
                        <div class="input-field">
                            <input v-model="form.amount" type="number" id="amount">
                            <label for="amount">Tip Amount (₦)</label>
                        </div>
                        <div class="input-field">
                            <input v-model="form.account_number" type="text" id="account_number">
                            <label for="account_number">Account Number</label>
                        </div>
                        <div class="input-field">
                            <select v-model="form.bank_code" id="bank_code">
                                <option v-for="bank in banks" :value="bank.code" :key="bank.code">{{ bank.name }}</option>
                            </select>
                            <label for="bank_code">Bank</label>
                        </div>
                        <button class="btn" @click="initiateTip">Send Tip</button>
                        <div v-if="otpRequired" class="input-field">
                            <input v-model="form.otp" type="text" id="otp">
                            <label for="otp">Paystack OTP</label>
                            <button class="btn" @click="verifyOtp">Verify OTP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
// import M from 'materialize-css';

export default {
    name: 'TipPage',
    data() {
        return {
            user: window.tippingData.user,
            tipping_url: window.tippingData.tipping_url,
            key: window.tippingData.key,
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
        M.AutoInit();
    },
    methods: {
        fetchBanks() {
            axios.get('/api/banks').then(response => {
                this.banks = response.data.banks;
                this.$nextTick(() => {
                    M.FormSelect.init(document.querySelectorAll('select'));
                });
            }).catch(err => M.toast({ html: 'Failed to load banks' }));
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
                    M.toast({ html: 'Please enter the OTP sent by your bank' });
                } else {
                    M.toast({ html: 'Tip sent successfully' });
                }
            }).catch(err => M.toast({ html: err.response?.data?.message || 'Failed to initiate tip' }));
        },
        verifyOtp() {
            axios.post(`/api/tip/${this.key}/verify`, {
                reference: this.form.reference,
                otp: this.form.otp
            }).then(() => {
                M.toast({ html: 'Tip processed successfully' });
                this.otpRequired = false;
                this.form = { email: '', amount: 0, account_number: '', bank_code: '', otp: '', reference: '' };
            }).catch(err => M.toast({ html: err.response?.data?.message || 'OTP verification failed' }));
        }
    }
}
</script>