<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

// Tambahkan ini
import BannerSection from '@/Components/BannerSection.vue';
import CardAnnouncement from '@/Components/CardAnnouncement.vue';
import CardNews from '@/Components/CardNews.vue';
</script>

<template>
    <Head title="Publikasi Dokumen" />

    <!-- Container utama halaman -->
    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 md:p-4">
                <TableDokumen v-if="filteredDokumen.length > 0" :data="filteredDokumen" />
                <div v-else class="p-12 text-center text-gray-500">
                    <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                    <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                </div>
            </div>

        </div>
    </div>
</template>