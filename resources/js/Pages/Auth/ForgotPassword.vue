<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import '../../../css/layout.css';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Lupa Kata Sandi - DPMD Kabupaten Bangkalan" />

    <div class="page">

        <!-- LEFT PANEL — brand -->
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
                    <h2>Lupa Kata Sandi?</h2>
                    <p>Tidak masalah. Kami akan bantu Anda mengatur ulang kata sandi agar bisa kembali mengakses Sistem Informasi DPMD Kabupaten Bangkalan.</p>
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

                <Link :href="route('login')" class="back-link">
                    <span class="back-arrow">&larr;</span> Kembali ke Login
                </Link>

                <div class="form-head">
                    <h1>Atur Ulang Kata Sandi</h1>
                    <p>Masukkan alamat email Anda yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
                </div>

                <div v-if="status" class="notice">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="form">

                    <div class="field">
                        <label for="email">Alamat email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            placeholder="nama@dpmd.bangkalankab.go.id"
                            required
                            autofocus
                        />
                    </div>

                    <p v-if="form.errors.email" class="error">{{ form.errors.email }}</p>

                    <button type="submit" class="submit" :disabled="form.processing || !form.email">
                        {{ form.processing ? 'Mengirim...' : 'Kirim Tautan Reset Password' }}
                    </button>
                </form>

                <footer class="foot">
                    <p>&copy; 2026 Dinas Pemberdayaan Masyarakat dan Desa &middot; Kabupaten Bangkalan</p>
                </footer>
            </div>
        </div>

    </div>
</template>