<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import PageHeader from '@/Components/PageHeader.vue';
</script>

<template>
    <Head title="Halaman Utama" />

    <div class="min-h-screen flex flex-col bg-gray-50">

        <!-- Navbar -->
        <Navbar />

        <!-- Konten -->
        <main class="flex-1 p-4 md:p-8">
            

            <!-- Breadcrumb -->
            <Breadcrumb />

            <div class="mt-6 bg-white rounded-xl border border-neutral-200 p-8 shadow-sm">
                <PageHeader 
            title="Halaman Utama" 
            description="Ini adalah halaman utama dari aplikasi." />
                <h1 class="text-2xl font-bold text-neutral-900 mb-2">
                    Selamat Datang di Portal DPMD
                </h1>

                <p class="text-neutral-600 mb-6">
                    Ini adalah contoh halaman yang sudah di-slicing menggunakan komponen Navbar dari INA Digital Design System (IDDS).
                </p>

                <div class="p-6 bg-blue-50 text-blue-800 rounded-lg border border-blue-100">
                    Lanjut kerjain komponen lainnya bangg
                </div>
            </div>

        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>