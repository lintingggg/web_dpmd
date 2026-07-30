<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import '../../../css/layout.css';

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

        <!-- LEFT PANEL — brand / illustration -->
        <div class="side">
            <div class="side-inner">

                <!-- Logo -->
                <div class="side-logo-wrap">
                    <div class="side-logo-ring">
                        <img
                            alt="Logo DPMD Kabupaten Bangkalan"
                            class="side-logo-big"
                            src="/assets/logo-dpmd-bangkalan.png"
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

                <footer class="foot">
                    <p>&copy; 2026 Dinas Pemberdayaan Masyarakat dan Desa &middot; Kabupaten Bangkalan</p>
                </footer>
            </div>
        </div>

    </div>
</template>