<template>
    <div class="container">
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Login</span>
                        <div class="input-field">
                            <input v-model="form.email" type="email" id="email">
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input v-model="form.password" type="password" id="password">
                            <label for="password">Password</label>
                        </div>
                        <button class="btn" @click="login">Login</button>
                        <div v-if="twoFactorRequired" class="input-field">
                            <input v-model="form.twoFactorCode" type="text" id="twoFactorCode">
                            <label for="twoFactorCode">2FA Code</label>
                            <button class="btn" @click="verify2FA">Verify 2FA</button>
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
    name: 'Login',
    data() {
        return {
            form: { email: '', password: '', twoFactorCode: '' },
            twoFactorRequired: false,
        };
    },
    mounted() {
        M.AutoInit();
    },
    methods: {
        login() {
            axios.post('/api/login', this.form)
                .then(response => {
                    if (response.data.message === '2FA required') {
                        this.twoFactorRequired = true;
                        localStorage.setItem('auth_token', response.data.token);
                    } else {
                        localStorage.setItem('auth_token', response.data.token);
                        window.location.href = '/dashboard';
                    }
                })
                .catch(err => M.toast({ html: err.response?.data?.message || 'Login failed' }));
        },
        verify2FA() {
            axios.post('/api/2fa/verify', { code: this.form.twoFactorCode })
                .then(response => {
                    localStorage.setItem('auth_token', response.data.token);
                    window.location.href = '/dashboard';
                })
                .catch(err => M.toast({ html: err.response?.data?.message || '2FA verification failed' }));
        },
    },
}
</script>