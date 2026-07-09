<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

// Tambahkan ini
import BannerSection from '@/Components/BannerSection.vue';
import CardAnnouncement from '@/Components/CardAnnouncement.vue';
import CardNews from '@/Components/CardNews.vue';
</script>

<template>
    <Head title="Halaman Utama" />

    <!-- Container utama halaman -->
    <div class="min-h-screen bg-gray-50 p-4 md:p-8">

        <!-- Panggil Navbar di paling atas -->
        <Navbar />

        <!-- ========================= -->
        <!-- Component Baru -->
        <!-- ========================= -->
        <div class="mt-6">
            <BannerSection />
        </div>

        <div class="mt-6">
            <CardAnnouncement />
        </div>

        <div class="mt-6">
            <CardNews />
        </div>

        <!-- ========================= -->
        <!-- Konten Lama (Tidak Berubah) -->
        <!-- ========================= -->
        <div class="mt-6 bg-white rounded-xl border border-neutral-200 p-8 shadow-sm">
            <h1 class="text-2xl font-bold text-neutral-900 mb-2">Selamat Datang di Portal DPMD</h1>
            <p class="text-neutral-600 mb-6">Ini adalah contoh halaman yang sudah di-slicing menggunakan komponen Navbar dari INA Digital Design System (IDDS).</p>

            <div class="p-6 bg-blue-50 text-blue-800 rounded-lg border border-blue-100">
                Lanjut kerjain komponen lainnya bang!
            </div>

        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
