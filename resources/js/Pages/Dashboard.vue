<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button, TextField, Badge } from '@idds/vue';

// 1. Data Dummy untuk Metrik (Nanti datanya dinamis dari Controller)
const metrics = ref([
    { 
        label: 'Total Berita', 
        value: '128', 
        subtitle: '+12 bulan ini', 
        colorClass: 'bg-blue-500 text-white',
        textColor: 'text-blue-600'
    },
    { 
        label: 'Dokumen Publik', 
        value: '345', 
        subtitle: '+5 dokumen baru', 
        colorClass: 'bg-green-500 text-white',
        textColor: 'text-green-600'
    },
    { 
        label: 'Pengumuman Aktif', 
        value: '3', 
        subtitle: 'Terpublikasi', 
        colorClass: 'bg-orange-500 text-white',
        textColor: 'text-orange-600'
    }
]);

// 2. Data Dummy untuk Tabel Aktivitas Terakhir
const recentActivities = ref([
    { id: 1, title: 'Penyaluran Dana Desa Tahap 3 Tahun 2026', type: 'Berita', status: 'Published', date: '10/07/2026' },
    { id: 2, title: 'SK Bupati No. 45 Tentang Pemdes', type: 'Dokumen (Peraturan)', status: 'Published', date: '09/07/2026' },
    { id: 3, title: 'Lomba Desa Tingkat Kabupaten', type: 'Pengumuman', status: 'Draft', date: '08/07/2026' },
    { id: 4, title: 'LAKIP DPMD 2025', type: 'Dokumen (Perencanaan)', status: 'Published', date: '05/07/2026' },
]);

// Helper untuk warna status
const getStatusVariant = (status) => {
    if (status === 'Published') return 'success';
    if (status === 'Draft') return 'warning';
    return 'neutral';
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <!-- Bungkus Layout Admin Sementara (Bisa dipisah ke komponen Layout nanti) -->
    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Dashboard DPMD
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Ringkasan statistik dan aktivitas pengelolaan konten portal publik.
                    </p>
                </div>
                <!-- Tombol Aksi Cepat -->
                <div class="flex gap-2">
                    <Button hierarchy="secondary" size="sm">Lihat Web Portal</Button>
                    <Button hierarchy="primary" size="sm">+ Tulis Berita</Button>
                </div>
            </div>

            <!-- Metrics Cards Section (Persis seperti template abang) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div v-for="metric in metrics" :key="metric.label" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">
                                {{ metric.label }}
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mb-2">
                                {{ metric.value }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" :class="metric.textColor" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <span class="text-xs font-medium" :class="metric.textColor">
                                    {{ metric.subtitle }}
                                </span>
                            </div>
                        </div>
                        <div :class="['p-3 rounded-lg', metric.colorClass]">
                            <!-- Icon Placeholder -->
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section: Aktivitas Terakhir -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Aktivitas Konten Terbaru
                    </h2>
                    <!-- Search placeholder -->
                    <div class="w-full sm:w-64">
                        <TextField placeholder="Cari konten..." size="sm" />
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 font-medium border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Judul Konten</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal Diperbarui</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="item in recentActivities" :key="item.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ item.title }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ item.type }}
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :variant="getStatusVariant(item.status)" type="soft" size="sm">
                                        {{ item.status }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4">
                                    {{ item.date }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Button hierarchy="link" size="sm" class="text-blue-600 px-0">Edit</Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>