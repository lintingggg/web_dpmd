<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';


defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Admin Login - DPMD Kabupaten Bangkalan" />

    <div class="page">

        <!-- LEFT PANEL  -->
        <div class="side">
            <div class="side-inner">
                <!-- <div class="side-brand">
                    <img
                        src="/assets/logo-dpmd-bangkalan.png"
                        alt="Logo Kabupaten Bangkalan"
                        class="side-logo"/>
                    <span>DPMD</span>
                </div> -->

                <!-- Logo -->
                <div class="side-logo-wrap">
                    <div class="side-logo-ring">
                        <img
                        src="/assets/logo-dpmd-bangkalan.png"
                        alt="Logo Kabupaten Bangkalan"
                        class="side-logo"
                        />
                    </div>
                </div>

                <div class="side-text">
                    <h2>Sistem Informasi DPMD</h2>
                    <p>Portal administrasi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan. Masuk untuk mengelola data desa dan program pemberdayaan masyarakat.</p>
                </div>
            </div>

            <div class="side-glow"></div>
        </div>

        <!-- RIGHT PANEL — form -->
        <div class="form-side">
            <div class="form-wrap">

                <div class="mobile-brand">
                    <img
                        alt="Logo Kabupaten Bangkalan"
                        src="/assets/logo-dpmd-bangkalan.png"
                    />
                    <span>DPMD Kabupaten Bangkalan</span>
                </div>

                <div class="form-head">
                    <h1>Selamat datang kembali</h1>
                    <p>Masuk ke panel admin untuk melanjutkan.</p>
                </div>

                <div v-if="status" class="notice">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="form">

                    <div class="field">
                        <label for="email">Username atau email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            placeholder="nama@dpmd.bangkalankab.go.id"
                            required
                            autofocus
                        />
                    </div>

                    <div class="field">
                        <label for="password">Kata sandi</label>
                        <div class="password-box">
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                placeholder="Masukkan kata sandi"
                                required
                            />
                            <button type="button" class="toggle" @click="togglePassword">
                                {{ showPassword ? 'Sembunyikan' : 'Lihat' }}
                            </button>
                        </div>
                    </div>

                    <p v-if="form.errors.email" class="error">{{ form.errors.email }}</p>
                    <p v-if="form.errors.password" class="error">{{ form.errors.password }}</p>

                    <div class="row">
                        <label class="remember">
                            <input v-model="form.remember" type="checkbox" />
                            <span class="box"></span>
                            <span>Ingat saya</span>
                        </label>

                        <Link :href="route('password.request')" class="forgot">
                            Lupa kata sandi?
                        </Link>
                    </div>

                    <button type="submit" class="submit" :disabled="form.processing || !form.email || !form.password">
                        {{ form.processing ? 'Memproses...' : 'Masuk' }}
                    </button>
                </form>

                <div class="help-row">
                    Mengalami kendala akses akun?
                    <a href="mailto:admin@dpmd.bangkalankab.go.id">Hubungi administrator</a>
                </div>

                <footer class="foot">
                    <nav class="foot-links">
                        <a href="#">Tentang Dinas</a>
                        <a href="#">Bantuan</a>
                        <a href="#">Kebijakan Privasi</a>
                    </nav>
                    <p>&copy; 2026 Dinas Pemberdayaan Masyarakat dan Desa &middot; Kabupaten Bangkalan</p>
                </footer>
            </div>
        </div>

    </div>
</template>

<style scoped>
.page {
    --bg: #fafafa;
    --card: #ffffff;
    --border: #e2e2e2;
    --text: #262626;
    --text-muted: #8e8e8e;
    --green: #1e56a0;
    --green-dark: #103973;
    --green-tint: #eaf1fb;
    --blue-light: #528be6;
    --blue-dark: #103973;

    min-height: 100vh;
    display: flex;
    font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
    color: var(--text);
    background: var(--bg);
}

/* LEFT PANEL */
.side {
    position: relative;
    flex: 1 1 42%;
    max-width: 560px;
    background: linear-gradient(160deg, var(--blue-dark) 0%, var(--blue-light) 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
    overflow: hidden;
}
.side-glow {
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
    top: -140px;
    right: -140px;
    pointer-events: none;
}
.side-inner {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 380px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.side-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    align-self: flex-start;
    margin-bottom: 36px;
}
.side-logo {
    width: 200px;
    height: 200px;
    object-fit: contain;
}
.side-brand span {
    font-weight: 800;
    font-size: 17px;
    letter-spacing: 1px;
}
.side-logo-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 32px;
}
.side-logo-ring {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}
.side-logo-big {
    width: 120px;
    height: 120px;
    object-fit: contain;
    filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.25));
}
.side-text h2 {
    font-size: 21px;
    font-weight: 800;
    margin-bottom: 10px;
}
.side-text p {
    font-size: 13.5px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.85);
}

/* RIGHT PANEL */
.form-side {
    flex: 1 1 58%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
}
.form-wrap {
    width: 100%;
    max-width: 380px;
}

.mobile-brand {
    display: none;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
}
.mobile-brand img {
    width: 64px;
    height: 64px;
    object-fit: contain;
}
.mobile-brand span {
    font-weight: 800;
    font-size: 14px;
}

.form-head {
    margin-bottom: 24px;
}
.form-head h1 {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 6px;
}
.form-head p {
    font-size: 13.5px;
    color: var(--text-muted);
}

.notice {
    background: var(--green-tint);
    border: 1px solid #cfe9db;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 13px;
    color: var(--green-dark);
    margin-bottom: 16px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.field label {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 6px;
}
.field input {
    width: 100%;
    height: 46px;
    padding: 0 14px;
    background: #fafafa;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text);
    font-family: inherit;
    outline: none;
    transition: border-color 0.15s ease, background 0.15s ease;
}
.field input::placeholder {
    color: #b3b3b3;
    font-weight: 400;
}
.field input:focus {
    border-color: var(--green);
    background: #ffffff;
}

.password-box {
    position: relative;
}
.password-box input {
    padding-right: 72px;
}
.toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 12px;
    font-weight: 700;
    color: var(--green);
    cursor: pointer;
    padding: 4px;
}

.error {
    font-size: 12.5px;
    color: #ed4956;
    margin: -8px 0 0;
}

.row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: -4px;
}

.remember {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    user-select: none;
}
.remember input {
    position: absolute;
    opacity: 0;
    width: 16px;
    height: 16px;
    margin: 0;
    cursor: pointer;
}
.box {
    width: 16px;
    height: 16px;
    border: 1.5px solid #c7c7c7;
    border-radius: 4px;
    background: #fff;
    flex-shrink: 0;
    position: relative;
    transition: background 0.15s ease, border-color 0.15s ease;
}
.remember input:checked + .box {
    background: var(--green);
    border-color: var(--green);
}
.remember input:checked + .box::after {
    content: '';
    position: absolute;
    left: 4px;
    top: 1px;
    width: 4px;
    height: 8px;
    border: solid #fff;
    border-width: 0 1.6px 1.6px 0;
    transform: rotate(40deg);
}
.remember span:last-child {
    font-size: 13px;
    font-weight: 500;
    color: var(--text);
}

.forgot {
    font-size: 13px;
    font-weight: 600;
    color: var(--green);
    text-decoration: none;
}
.forgot:hover {
    text-decoration: underline;
}

.submit {
    height: 46px;
    margin-top: 6px;
    background: var(--green);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s ease, opacity 0.15s ease;
}
.submit:hover:not(:disabled) {
    background: var(--green-dark);
}
.submit:disabled {
    opacity: 0.5;
    cursor: default;
}

.help-row {
    text-align: center;
    font-size: 13px;
    color: var(--text-muted);
    margin-top: 24px;
}
.help-row a {
    color: var(--green);
    font-weight: 700;
    text-decoration: none;
}
.help-row a:hover {
    text-decoration: underline;
}

.foot {
    margin-top: 32px;
    text-align: center;
}
.foot-links {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.foot-links a {
    font-size: 12px;
    color: var(--text-muted);
    text-decoration: none;
}
.foot-links a:hover {
    text-decoration: underline;
}
.foot p {
    font-size: 11px;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 860px) {
    .side {
        display: none;
    }
    .mobile-brand {
        display: flex;
        justify-content: center;
        text-align: center;
    }
    .form-side {
        padding: 40px 16px;
    }
}
</style>