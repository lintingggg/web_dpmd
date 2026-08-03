<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import '../../../css/layout.css';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const showPasswordConfirmation = ref(false);
const togglePasswordConfirmation = () => {
    showPasswordConfirmation.value = !showPasswordConfirmation.value;
};

const submit = () => {
    form.clearErrors();
    if (form.password !== form.password_confirmation) {
        form.setError('password_confirmation', 'Konfirmasi kata sandi tidak cocok.');
        return;
    }

    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Kata Sandi - DPMD Kabupaten Bangkalan" />

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
                    <h2>Amankan Akun Anda</h2>
                    <p>Buat kata sandi baru yang kuat untuk melindungi akses ke Sistem Informasi DPMD Kabupaten Bangkalan.</p>
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
                    <h1>Reset Kata Sandi</h1>
                    <p>Silakan masukkan kata sandi baru Anda di bawah ini untuk mengatur ulang akses ke sistem.</p>
                </div>

                <form @submit.prevent="submit" class="form">

                    <div class="field">
                        <label for="email">Alamat email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            readonly
                            class="field-readonly"
                        />
                        <p v-if="form.errors.email" class="error">{{ form.errors.email }}</p>
                    </div>

                    <div class="field">
                        <label for="password">Kata sandi baru</label>
                        <div class="password-box">
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                placeholder="Masukkan kata sandi baru"
                                required
                                autofocus
                            />
                            <button type="button" class="toggle" @click="togglePassword">
                                {{ showPassword ? 'Sembunyikan' : 'Lihat' }}
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="error">{{ form.errors.password }}</p>
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi kata sandi</label>
                        <div class="password-box">
                            <input
                                v-model="form.password_confirmation"
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                id="password_confirmation"
                                placeholder="Ulangi kata sandi baru"
                                required
                            />
                            <button type="button" class="toggle" @click="togglePasswordConfirmation">
                                {{ showPasswordConfirmation ? 'Sembunyikan' : 'Lihat' }}
                            </button>
                        </div>
                        <p v-if="form.errors.password_confirmation" class="error">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <button type="submit" class="submit" :disabled="form.processing || !form.password || !form.password_confirmation">
                        {{ form.processing ? 'Memproses...' : 'Simpan Kata Sandi Baru' }}
                    </button>
                </form>

                <footer class="foot">
                    <p>&copy; 2026 Dinas Pemberdayaan Masyarakat dan Desa &middot; Kabupaten Bangkalan</p>
                </footer>
            </div>
        </div>

    </div>
</template>