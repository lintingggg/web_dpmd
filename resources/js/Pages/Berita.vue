<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Navbar from '@/Components/Navbar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import SearchBar from '@/Components/SearchBar.vue';
import CardNews from '@/Components/CardNews.vue';
import CardAnnouncement from '@/Components/CardAnnouncement.vue';
import DataPagination from '@/Components/DataPagination.vue';
import Footer from '@/Components/Footer.vue';

// State kategori yang aktif (Semua, Kegiatan, Pemberdayaan, Informasi)
const activeCategory = ref('Semua');

// State untuk pencarian berita/kegiatan
const searchQuery = ref('');

// Data dummy berita & kegiatan sesuai isi gambar
const beritaList = [
    {
        id: 1,
        title: 'Pelatihan Kewirausahaan untuk BUMDes Mandiri Se-Kabupaten',
        description: 'DPMD Bangkalan menggelar pelatihan intensif bagi pengurus BUMDes untuk meningkatkan kapasitas manajerial dan inovasi produk lokal guna mendorong kemandirian ekonomi desa.',
        category: 'Pemberdayaan',
        date: '12 Okt 2024',
        author: 'Humas DPMD',
        image: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600',
        isFeatured: true
    },
    {
        id: 2,
        title: 'Peninjauan Proyek Infrastruktur Desa Bantuan Provinsi 2024',
        description: 'Tim evaluasi turun langsung memantau perkembangan pembangunan fisik yang...',
        category: 'Kegiatan',
        date: '10 Okt 2024',
        image: 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=400',
        isFeatured: false
    },
    {
        id: 3,
        title: 'Sosialisasi Penggunaan Sistem Informasi Desa Terpadu',
        description: 'Mendorong percepatan digitalisasi administrasi desa melalui penerapan aplikasi...',
        category: 'Informasi',
        date: '08 Okt 2024',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=400',
        isFeatured: false
    },
    {
        id: 4,
        title: 'Pameran Inovasi Teknologi Tepat Guna Tingkat Kabupaten',
        description: 'Menampilkan karya inovatif masyarakat desa yang berpotensi meningkatkan nilai ekonomi...',
        category: 'Pemberdayaan',
        date: '05 Okt 2024',
        image: 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?q=80&w=400',
        isFeatured: false
    }
];

// Computed properti untuk filter kategori dan live search berita
const filteredBerita = computed(() => {
    return beritaList.filter(item => {
        const matchesCategory = activeCategory.value === 'Semua' || item.category === activeCategory.value;
        const matchesSearch = item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              item.description.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesCategory && matchesSearch;
    });
});

// Ambil berita utama (Featured) jika ada di hasil filter, atau gunakan fallback jika dicari
const featuredBerita = computed(() => {
    return filteredBerita.value.find(b => b.isFeatured) || filteredBerita.value[0];
});

// Ambil berita sisa untuk grid kecil
const regulerBerita = computed(() => {
    const featuredId = featuredBerita.value?.id;
    return filteredBerita.value.filter(b => b.id !== featuredId);
});
</script>

<template>
    <Head title="Berita & Kegiatan" />

    <Header />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
        <Navbar />

        <div class="max-w-7xl mx-auto mt-8">
            <PageHeader
                title="Berita & Kegiatan"
                description="Informasi terkini seputar program kerja, kegiatan desa, dan pengumuman resmi dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
            />

            <div class="mt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="w-full md:w-96 order-2 md:order-1">
                    <SearchBar
                        v-model="searchQuery"
                        placeholder="Cari berita atau kegiatan..."
                    />
                </div>

                <div class="flex flex-wrap gap-2 order-1 md:order-2">
                    <button
                        v-for="cat in ['Semua', 'Kegiatan', 'Pemberdayaan', 'Informasi']"
                        :key="cat"
                        @click="activeCategory = cat"
                        :class="[
                            activeCategory === cat
                                ? 'bg-slate-900 text-white font-semibold'
                                : 'bg-gray-200/70 text-slate-700 hover:bg-gray-300',
                            'px-4 py-1.5 rounded-full text-sm font-medium transition-colors'
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>

            <div v-if="filteredBerita.length > 0" class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div v-if="featuredBerita" class="lg:col-span-2">
                    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-md group h-full min-h-[400px] flex flex-col justify-end p-6 md:p-8">
                        <img :src="featuredBerita.image" class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-300 group-hover:scale-105" alt="featured">
                        <div class="relative z-10">
                            <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-3">
                                {{ featuredBerita.category }}
                            </span>
                            <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-3">
                                {{ featuredBerita.title }}
                            </h2>
                            <p class="text-sm text-gray-300 max-w-2xl mb-4 line-clamp-3">
                                {{ featuredBerita.description }}
                            </p>
                            <div class="flex items-center space-x-2 text-xs text-gray-400">
                                <span>Oleh: <strong>{{ featuredBerita.author || 'Humas' }}</strong></span>
                                <span>•</span>
                                <span>{{ featuredBerita.date }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 flex flex-col justify-between">
                    <CardNews
                        v-for="berita in regulerBerita.slice(0, 2)"
                        :key="berita.id"
                        :title="berita.title"
                        :description="berita.description"
                        :date="berita.date"
                        :category="berita.category"
                        :image="berita.image"
                    />

                    <CardAnnouncement
                        title="Pengumuman Penting"
                        description="Jadwal Pencairan Dana Desa Tahap II Tahun Anggaran 2024."
                        theme="dark"
                        class="bg-slate-950 text-white rounded-3xl p-6 flex flex-col justify-between min-h-[180px] shadow-sm"
                    />
                </div>
            </div>

            <div v-else class="p-16 text-center text-gray-500 mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Berita atau kegiatan tidak ditemukan</p>
                <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci atau kategori filter yang lain.</p>
            </div>

            <div class="mt-8 flex justify-center">
                <DataPagination />
            </div>

        </div>
    </div>

    <Footer />
</template>