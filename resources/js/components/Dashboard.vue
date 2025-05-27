<template>
    <div class="container">
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">User Profile</span>
                        <p><i class="fas fa-user"></i> {{ user.name }}</p>
                        <p><i class="fas fa-envelope"></i> {{ user.email }}</p>
                        <p><i class="fas fa-phone"></i> {{ user.phone }}</p>
                        <button class="btn" @click="showEditForm = true">Edit Profile</button>
                        <button class="btn" @click="toggle2FA">{{ user.two_factor_enabled ? 'Disable' : 'Enable' }} 2FA</button>
                        <a href="/logout" class="btn red">Logout</a>
                    </div>
                </div>
                <div v-if="showEditForm" class="card">
                    <div class="card-content">
                        <span class="card-title">Edit Profile</span>
                        <div class="input-field">
                            <input v-model="editForm.name" type="text" id="name">
                            <label for="name">Name</label>
                        </div>
                        <div class="input-field">
                            <input v-model="editForm.email" type="email" id="email">
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input v-model="editForm.phone" type="text" id="phone">
                            <label for="phone">Phone</label>
                        </div>
                        <div class="input-field">
                            <input v-model="editForm.password" type="password" id="password">
                            <label for="password">Password</label>
                        </div>
                        <button class="btn" @click="updateProfile">Update</button>
                    </div>
                </div>
                <div v-if="twoFactorQr" class="card">
                    <div class="card-content">
                        <span class="card-title">2FA Setup</span>
                        <img :src="twoFactorQr" alt="2FA QR Code" />
                        <p>Secret: {{ twoFactorSecret }}</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Wallet</span>
                        <p>Balance: ₦{{ wallet.balance }}</p>
                        <p>Tipping URL: {{ wallet.tipping_url }}</p>
                        <img v-if="tippingQrCode" :src="tippingQrCode" alt="Tipping QR Code" />
                        <button class="btn" @click="fetchTippingQrCode">Generate Tipping QR Code</button>
                        <button class="btn" @click="showWithdrawForm = true">Withdraw</button>
                    </div>
                </div>
                <div v-if="showWithdrawForm" class="card">
                    <div class="card-content">
                        <span class="card-title">Withdraw Funds</span>
                        <div class="input-field">
                            <input v-model="withdrawForm.amount" type="number" id="amount">
                            <label for="amount">Amount (₦)</label>
                        </div>
                        <div class="input-field">
                            <input v-model="withdrawForm.account_number" type="text" id="account_number">
                            <label for="account_number">Account Number</label>
                        </div>
                        <div class="input-field">
                            <select v-model="withdrawForm.bank_code" id="bank_code">
                                <option v-for="bank in banks" :value="bank.code" :key="bank.code">{{ bank.name }}</option>
                            </select>
                            <label for="bank_code">Bank</label>
                        </div>
                        <button class="btn" @click="withdraw">Confirm Withdrawal</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <table class="striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="transaction in transactions" :key="transaction.id">
                            <td>{{ transaction.id }}</td>
                            <td>₦{{ transaction.amount }}</td>
                            <td>{{ transaction.type }}</td>
                            <td>{{ transaction.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
// import M from 'materialize-css';

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
            withdrawForm: { amount: 0, account_number: '', bank_code: '' },
            banks: [],
        };
    },
    mounted() {
        this.fetchUserData();
        this.fetchBanks();
        M.AutoInit();
    },
    methods: {
        fetchUserData() {
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data.user;
                    this.wallet = response.data.wallet;
                    this.transactions = response.data.transactions;
                    this.editForm = { name: this.user.name, email: this.user.email, phone: this.user.phone, password: '' };
                })
                .catch(error => {
                    console.error('Fetch user error:', error.response?.status, error.response?.data);
                    if (error.response?.status === 401) {
                        localStorage.removeItem('auth_token');
                        window.location.href = '/login';
                    } else {
                        M.toast({ html: 'Failed to load user data' });
                    }
                });
        },
        fetchBanks() {
            axios.get('/api/banks')
                .then(response => {
                    this.banks = response.data.banks;
                    this.$nextTick(() => {
                        M.FormSelect.init(document.querySelectorAll('select'));
                    });
                })
                .catch(() => M.toast({ html: 'Failed to load banks' }));
        },
        fetchTippingQrCode() {
            axios.get('/api/wallet/qr-code')
                .then(response => {
                    this.tippingQrCode = response.data.qr_code;
                    M.toast({ html: 'Tipping QR code generated' });
                })
                .catch(err => M.toast({ html: err.response?.data?.message || 'Failed to generate QR code' }));
        },
        updateProfile() {
            axios.post('/api/profile/update', this.editForm)
                .then(() => {
                    M.toast({ html: 'Profile updated' });
                    this.showEditForm = false;
                    this.fetchUserData();
                })
                .catch(err => M.toast({ html: err.response?.data?.message || 'Failed to update profile' }));
        },
        toggle2FA() {
            if (this.user.two_factor_enabled) {
                axios.post('/api/2fa/disable')
                    .then(() => {
                        M.toast({ html: '2FA disabled' });
                        this.fetchUserData();
                    })
                    .catch(err => M.toast({ html: err.response?.data?.message || 'Failed to disable 2FA' }));
            } else {
                axios.post('/api/2fa/enable')
                    .then(response => {
                        this.twoFactorQr = response.data.qr_code;
                        this.twoFactorSecret = response.data.secret;
                        M.toast({ html: 'Scan the QR code with your authenticator app' });
                    })
                    .catch(err => M.toast({ html: err.response?.data?.message || 'Failed to enable 2FA' }));
            }
        },
        withdraw() {
            const amount = parseFloat(this.withdrawForm.amount);
            if (amount + 300 > this.wallet.balance) {
                M.toast({ html: 'Insufficient balance (including 300 NGN fee)' });
                return;
            }
            axios.post('/api/wallet/withdraw', this.withdrawForm)
                .then(() => {
                    M.toast({ html: 'Withdrawal initiated' });
                    this.showWithdrawForm = false;
                    this.fetchUserData();
                })
                .catch(err => M.toast({ html: err.response?.data?.message || 'Withdrawal failed' }));
        },
    }
}
</script>

<style scoped>
.card { margin-bottom: 20px; }
.btn { margin-right: 10px; }
</style>