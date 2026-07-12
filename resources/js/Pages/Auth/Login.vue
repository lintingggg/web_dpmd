<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

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

    <div style="font-family: 'Plus Jakarta Sans', sans-serif;" class="min-h-screen flex flex-col md:flex-row bg-white">

        <!-- ===== LEFT SIDE: Login Form ===== -->
        <main class="w-full md:w-[50%] min-h-screen flex flex-col bg-white relative z-10">

            <!-- Top: Header Logo -->
            <header class="flex items-center gap-3 px-8 md:px-16 pt-8 md:pt-10">
                <img
                    alt="Logo Kabupaten Bangkalan"
                    class="h-11 w-11 object-contain flex-shrink-0"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjLxilI_cSxKSo4h1aaJThm8S1k7DCg0KOnsOqQej3IGqZKpnsNWvd84YWHm39prWNuO9EvpBxHT1MMlFEPWm0CkecLFX8wg9l-gmbibd8G3PXgfRCJJijOJLIPct-XmvjcHgLffB-8BG_HWEnlVeoAO3M__d83gQPynBWrHP7C3V3gXDCrwODkZCKXeI1B9zO1U7Ex1upUhKwP23p1VDep4naCOqSbWXi-P7s2tQDYK33NKS-U0Ga"
                />
                <div class="flex flex-col leading-tight">
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a; letter-spacing: -0.3px;">Pemerintah Kabupaten Bangkalan</span>
                    <span style="font-size: 10px; font-weight: 500; color: #646a79; letter-spacing: 1.5px;" class="uppercase">Dinas Pemberdayaan Masyarakat dan Desa</span>
                </div>
            </header>

            <!-- Middle: Form (takes remaining vertical space, centered) -->
            <div class="flex-1 flex flex-col justify-center px-8 md:px-16 py-10">
                <div class="w-full max-w-[430px]">

                    <!-- Heading -->
                    <div class="mb-8">
                        <h1 style="font-size: 36px; font-weight: 700; color: #0f172a; letter-spacing: -0.75px; line-height: 1.15;" class="mb-2">
                            Admin Portal
                        </h1>
                        <p style="font-size: 16px; font-weight: 500; color: #646a79; line-height: 1.5;">
                            Silakan masuk menggunakan kredensial admin Anda.
                        </p>
                    </div>

                    <!-- Status message -->
                    <div v-if="status" class="mb-5 p-4 rounded-2xl flex items-start gap-3" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                        <p style="font-size: 14px; font-weight: 500; color: #1e40af; line-height: 1.5;">
                            {{ status }}
                        </p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-4">

                        <!-- Email Field -->
                        <div>
                            <label
                                for="email"
                                style="display: block; font-size: 14px; font-weight: 500; color: #373f50; margin-bottom: 6px;"
                            >
                                Username atau Email
                            </label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined"
                                    style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 20px; color: #9499a3; pointer-events: none;"
                                >person</span>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    id="email"
                                    placeholder="Masukkan username"
                                    required
                                    style="
                                        width: 100%;
                                        height: 48px;
                                        padding-left: 48px;
                                        padding-right: 16px;
                                        background: #ffffff;
                                        border: 1.5px solid #e3e5e7;
                                        border-radius: 16px;
                                        font-size: 14px;
                                        font-weight: 500;
                                        color: #0f172a;
                                        outline: none;
                                        transition: border-color 0.2s ease;
                                        box-shadow: 0 1px 3px rgba(15,23,42,0.06);
                                        font-family: 'Plus Jakarta Sans', sans-serif;
                                    "
                                    @focus="$event.target.style.borderColor = '#0f172a'"
                                    @blur="$event.target.style.borderColor = '#e3e5e7'"
                                />
                            </div>
                            <p v-if="form.errors.email" style="margin-top: 6px; font-size: 13px; color: #ba1a1a;">{{ form.errors.email }}</p>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label
                                for="password"
                                style="display: block; font-size: 14px; font-weight: 500; color: #373f50; margin-bottom: 6px;"
                            >
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined"
                                    style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 20px; color: #9499a3; pointer-events: none;"
                                >lock</span>
                                <input
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    placeholder="••••••••"
                                    required
                                    style="
                                        width: 100%;
                                        height: 48px;
                                        padding-left: 48px;
                                        padding-right: 52px;
                                        background: #ffffff;
                                        border: 1.5px solid #e3e5e7;
                                        border-radius: 16px;
                                        font-size: 14px;
                                        font-weight: 500;
                                        color: #0f172a;
                                        outline: none;
                                        transition: border-color 0.2s ease;
                                        box-shadow: 0 1px 3px rgba(15,23,42,0.06);
                                        font-family: 'Plus Jakarta Sans', sans-serif;
                                    "
                                    @focus="$event.target.style.borderColor = '#0f172a'"
                                    @blur="$event.target.style.borderColor = '#e3e5e7'"
                                />
                                <button
                                    type="button"
                                    @click="togglePassword"
                                    style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #9499a3; transition: color 0.2s ease; padding: 4px;"
                                    @mouseenter="$event.currentTarget.style.color = '#0f172a'"
                                    @mouseleave="$event.currentTarget.style.color = '#9499a3'"
                                >
                                    <span class="material-symbols-outlined" style="font-size: 20px;">
                                        {{ showPassword ? 'visibility' : 'visibility_off' }}
                                    </span>
                                </button>
                            </div>
                            <p v-if="form.errors.password" style="margin-top: 6px; font-size: 13px; color: #ba1a1a;">{{ form.errors.password }}</p>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between" style="padding: 4px 0;">
                            <label class="flex items-center gap-2 cursor-pointer" style="user-select: none;">
                                <div class="relative flex items-center justify-center">
                                    <input
                                        v-model="form.remember"
                                        type="checkbox"
                                        class="peer"
                                        style="
                                            appearance: none;
                                            -webkit-appearance: none;
                                            width: 18px;
                                            height: 18px;
                                            border: 1.5px solid #c8cbd0;
                                            border-radius: 4px;
                                            background: #ffffff;
                                            cursor: pointer;
                                            transition: all 0.2s ease;
                                            flex-shrink: 0;
                                        "
                                        @change="$event.target.style.background = $event.target.checked ? '#0f172a' : '#ffffff'; $event.target.style.borderColor = $event.target.checked ? '#0f172a' : '#c8cbd0';"
                                    />
                                    <span
                                        class="material-symbols-outlined peer-checked:opacity-100 opacity-0 pointer-events-none absolute"
                                        style="font-size: 13px; color: #ffffff; font-variation-settings: 'FILL' 1; transition: opacity 0.15s ease;"
                                    >check</span>
                                </div>
                                <span style="font-size: 14px; font-weight: 500; color: #4d5464;">Ingat Saya</span>
                            </label>
                            <a
                                href="#"
                                style="font-size: 14px; font-weight: 700; color: #0f172a; text-decoration: none;"
                                @mouseenter="$event.target.style.textDecoration = 'underline'"
                                @mouseleave="$event.target.style.textDecoration = 'none'"
                            >
                                Lupa kata sandi?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <div style="padding-top: 4px;">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                style="
                                    width: 100%;
                                    height: 48px;
                                    background: #0f172a;
                                    color: #ffffff;
                                    border: none;
                                    border-radius: 9999px;
                                    font-size: 15px;
                                    font-weight: 700;
                                    letter-spacing: -0.3px;
                                    cursor: pointer;
                                    transition: background 0.2s ease, opacity 0.2s ease;
                                    box-shadow: 0 4px 16px rgba(15,23,42,0.18);
                                    font-family: 'Plus Jakarta Sans', sans-serif;
                                "
                                @mouseenter="!form.processing && ($event.target.style.background = '#222a3d')"
                                @mouseleave="!form.processing && ($event.target.style.background = '#0f172a')"
                            >
                                {{ form.processing ? 'Memproses...' : 'Masuk' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Bottom: Footer -->
            <footer class="px-8 md:px-16 pb-8 md:pb-10" style="border-top: 1px solid #e3e5e7; padding-top: 20px;">
                <p style="font-size: 11px; font-weight: 500; color: #9499a3; letter-spacing: 0.2px; line-height: 1.6;">
                    © 2024 Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan. All rights reserved.
                </p>
            </footer>

        </main>

        <!-- ===== RIGHT SIDE: Brand Visual ===== -->
        <aside
            class="hidden md:flex md:w-[50%] relative overflow-hidden flex-col items-center justify-center"
            style="background: #0f172a; padding: 64px;"
        >
            <!-- Dot grid pattern -->
            <div
                class="absolute inset-0"
                style="
                    background-image: radial-gradient(circle at 1.5px 1.5px, rgba(255,255,255,0.12) 1.5px, transparent 0);
                    background-size: 28px 28px;
                    pointer-events: none;
                "
            ></div>

            <!-- Subtle radial glow -->
            <div
                class="absolute pointer-events-none"
                style="
                    top: 20%;
                    right: -15%;
                    width: 600px;
                    height: 600px;
                    background: radial-gradient(ellipse, rgba(55,63,80,0.7) 0%, transparent 70%);
                    border-radius: 50%;
                    filter: blur(60px);
                "
            ></div>

            <!-- Center content -->
            <div class="relative z-10 flex flex-col items-center text-center" style="max-width: 420px;">

                <!-- Logo card -->
                <div
                    style="
                        margin-bottom: 40px;
                        padding: 24px;
                        background: rgba(255,255,255,0.07);
                        backdrop-filter: blur(16px);
                        -webkit-backdrop-filter: blur(16px);
                        border-radius: 28px;
                        border: 1px solid rgba(255,255,255,0.12);
                        box-shadow: 0 24px 64px rgba(0,0,0,0.4);
                    "
                >
                    <img
                        alt="Logo DPMD"
                        style="width: 120px; height: 120px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtTE-ijsuPQ00aJdFJP59wqnt1BdYFiXyoZxYLDEcQpDYxrfayaFCzPrz6e_2R2Eso74ixSdN2rIs1ialJtxKXlMNxIU6AIZpYvBuQBtYn5BL9sEuydWhNaB0VNJWY4WXFHUEUpZ7vdsjsXz9x3ABvl0SMtTHKnT3vj55FEu_adf_Wp9OI6q5t0w98H6_uKN3EB_aPGpLwXWRfukBRpc3H5njJA8bUU05L64HGhSzUYhjPk9RSP7GJ"
                    />
                </div>

                <!-- Tagline -->
                <h2
                    style="
                        font-size: 48px;
                        font-weight: 700;
                        color: #ffffff;
                        letter-spacing: -1px;
                        line-height: 1.15;
                        margin-bottom: 16px;
                    "
                >
                    Membangun Desa Bangkalan yang Mandiri &amp; Sejahtera.
                </h2>

                <p style="font-size: 16px; font-weight: 500; color: #9499a3; line-height: 1.65;">
                    Sistem Informasi Manajemen Terpadu untuk kemajuan masyarakat dan desa di Kabupaten Bangkalan.
                </p>

                <!-- Decorative dots -->
                <div class="flex items-center gap-1.5" style="margin-top: 36px;">
                    <div style="width: 40px; height: 3px; background: #4d5464; border-radius: 9999px;"></div>
                    <div style="width: 8px; height: 3px; background: #4d5464; border-radius: 9999px;"></div>
                    <div style="width: 8px; height: 3px; background: #4d5464; border-radius: 9999px;"></div>
                </div>

            </div>
        </aside>

    </div>
</template>