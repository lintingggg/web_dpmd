<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import SearchBar from '@/Components/SearchBar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';

// State kategori yang aktif (Semua, Kegiatan, Pemberdayaan, Informasi)
const activeCategory = ref('Semua');

// State untuk pencarian berita/kegiatan
const searchQuery = ref('');

// Jumlah berita per halaman: null berarti masih tampilan default (5 item awal)
const itemsPerPage = ref(null);
const perPageOptions = [15, 25, 50];

// Ukuran halaman pertama (hero + samping + 3 kartu) & halaman berikutnya (kelipatan 3, biar rapi)
const DEFAULT_FIRST_PAGE_SIZE = 5;
const DEFAULT_OTHER_PAGE_SIZE = 6;

// Halaman aktif
const currentPage = ref(1);

// Data dummy berita & kegiatan (±20 item, termasuk "Pengumuman Penting" sebagai berita biasa)
const kategoriSiklus = ['Kegiatan', 'Pemberdayaan', 'Informasi'];

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
        description: 'Tim evaluasi turun langsung memantau perkembangan pembangunan fisik yang dibiayai bantuan provinsi.',
        category: 'Kegiatan',
        date: '10 Okt 2024',
        image: 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=400',
        isFeatured: false
    },
    {
        id: 3,
        title: 'Sosialisasi Penggunaan Sistem Informasi Desa Terpadu',
        description: 'Mendorong percepatan digitalisasi administrasi desa melalui penerapan aplikasi terpadu.',
        category: 'Informasi',
        date: '08 Okt 2024',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=400',
        isFeatured: false
    },
    {
        id: 4,
        title: 'Pameran Inovasi Teknologi Tepat Guna Tingkat Kabupaten',
        description: 'Menampilkan karya inovatif masyarakat desa yang berpotensi meningkatkan nilai ekonomi lokal.',
        category: 'Pemberdayaan',
        date: '05 Okt 2024',
        image: 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?q=80&w=400',
        isFeatured: false
    },
    {
        id: 5,
        title: 'Pengumuman Penting: Jadwal Pencairan Dana Desa Tahap II Tahun Anggaran 2024',
        description: 'DPMD Bangkalan menetapkan jadwal resmi pencairan Dana Desa tahap II untuk seluruh desa se-Kabupaten Bangkalan.',
        category: 'Informasi',
        date: '04 Okt 2024',
        image: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=400',
        isFeatured: false
    },
];

// Tambahkan berita dummy lanjutan sampai total ±20 item
for (let i = 6; i <= 20; i++) {
    const kategori = kategoriSiklus[i % kategoriSiklus.length];
    beritaList.push({
        id: i,
        title: `Kegiatan Desa dan Program ${kategori} Edisi ke-${i}`,
        description: `Ringkasan singkat kegiatan atau program ${kategori.toLowerCase()} yang dilaksanakan DPMD Kabupaten Bangkalan pada periode ini.`,
        category: kategori,
        date: `0${(i % 9) + 1} Okt 2024`,
        image: `https://images.unsplash.com/photo-15${17000000 + i * 1111}?q=80&w=400`,
        isFeatured: false
    });
}

// Filter kategori + live search
const filteredBerita = computed(() => {
    return beritaList.filter(item => {
        const matchesCategory = activeCategory.value === 'Semua' || item.category === activeCategory.value;
        const matchesSearch = item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              item.description.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesCategory && matchesSearch;
    });
});

// Reset ke halaman 1 setiap kali filter/pencarian berubah
watch([activeCategory, searchQuery], () => {
    currentPage.value = 1;
});

// Total halaman:
// - Mode default (belum pilih 15/25/50): halaman 1 = 5 item, halaman berikutnya = 6 item per halaman
// - Mode dipilih (15/25/50): dibagi rata sesuai jumlah yang dipilih
const totalPages = computed(() => {
    const total = filteredBerita.value.length;
    if (itemsPerPage.value) {
        return Math.max(1, Math.ceil(total / itemsPerPage.value));
    }
    if (total <= DEFAULT_FIRST_PAGE_SIZE) return 1;
    const sisa = total - DEFAULT_FIRST_PAGE_SIZE;
    return 1 + Math.ceil(sisa / DEFAULT_OTHER_PAGE_SIZE);
});

// Potongan data untuk halaman yang sedang aktif
const paginatedBerita = computed(() => {
    if (itemsPerPage.value) {
        const start = (currentPage.value - 1) * itemsPerPage.value;
        return filteredBerita.value.slice(start, start + itemsPerPage.value);
    }
    if (currentPage.value === 1) {
        return filteredBerita.value.slice(0, DEFAULT_FIRST_PAGE_SIZE);
    }
    const start = DEFAULT_FIRST_PAGE_SIZE + (currentPage.value - 2) * DEFAULT_OTHER_PAGE_SIZE;
    return filteredBerita.value.slice(start, start + DEFAULT_OTHER_PAGE_SIZE);
});

// Tampilan "hero" (kartu unggulan besar) hanya di halaman pertama
const showHero = computed(() => currentPage.value === 1 && paginatedBerita.value.length > 0);
const heroBerita = computed(() => showHero.value ? paginatedBerita.value[0] : null);
const sideBerita = computed(() => showHero.value ? paginatedBerita.value[1] : null);
const restBerita = computed(() => showHero.value ? paginatedBerita.value.slice(2) : paginatedBerita.value);

function pilihJumlahTampilan(jumlah) {
    if (itemsPerPage.value === jumlah) {
        // Klik ulang angka yang sudah aktif -> kembali ke tampilan default
        itemsPerPage.value = null;
    } else {
        itemsPerPage.value = jumlah;
    }
    currentPage.value = 1;
}

function gotoPage(page) {
    if (page < 1 || page > totalPages.value) return;
    currentPage.value = page;
}

// Daftar nomor halaman yang ditampilkan di kontrol paginasi
const pageNumbers = computed(() => {
    const total = totalPages.value;
    const arr = [];
    for (let i = 1; i <= total; i++) arr.push(i);
    return arr;
});
</script>

<template>
    <Head title="Berita & Kegiatan" />

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

            <!-- Kondisi 1: hasil pencarian/filter kosong -->
            <div v-if="filteredBerita.length === 0" class="mt-8 p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Berita atau kegiatan tidak ditemukan</p>
                <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci atau kategori filter yang lain.</p>
            </div>

            <!-- Kondisi 2: tampilkan berita -->
            <div v-else class="mt-8 space-y-6">

                <div v-if="showHero" class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
                    <div v-if="heroBerita" class="lg:col-span-3">
                        <Link :href="`/berita/${heroBerita.id}`" class="block h-full">
                            <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-md group aspect-[4/3] md:aspect-[16/9] flex flex-col justify-end p-6 md:p-8 transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-2xl cursor-pointer">
                                <img :src="heroBerita.image" class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-300 group-hover:scale-105" alt="featured">
                                <div class="relative z-10">
                                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-3">
                                        {{ heroBerita.category }}
                                    </span>
                                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-3">
                                        {{ heroBerita.title }}
                                    </h2>
                                    <p class="text-sm text-gray-300 max-w-2xl mb-4 line-clamp-3">
                                        {{ heroBerita.description }}
                                    </p>
                                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                                        <span>Oleh: <strong>{{ heroBerita.author || 'Humas' }}</strong></span>
                                        <span>•</span>
                                        <span>{{ heroBerita.date }}</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div v-if="sideBerita" class="lg:col-span-2">
                        <Link :href="`/berita/${sideBerita.id}`" class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer">
                            <CardNews
                                :title="sideBerita.title"
                                :description="sideBerita.description"
                                :date="sideBerita.date"
                                :category="sideBerita.category"
                                :image="sideBerita.image"
                            />
                        </Link>
                    </div>
                </div>

                <div v-if="restBerita.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="berita in restBerita"
                        :key="berita.id"
                        :href="`/berita/${berita.id}`"
                        class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                    >
                        <CardNews
                            :title="berita.title"
                            :description="berita.description"
                            :date="berita.date"
                            :category="berita.category"
                            :image="berita.image"
                        />
                    </Link>
                </div>
            </div>

            <!-- Kontrol jumlah tampilan + paginasi -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">

                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>Tampilkan</span>
                    <div class="flex gap-1.5">
                        <button
                            v-for="n in perPageOptions"
                            :key="n"
                            @click="pilihJumlahTampilan(n)"
                            :class="[
                                itemsPerPage === n
                                    ? 'bg-slate-900 text-white font-semibold'
                                    : 'bg-gray-100 text-slate-700 hover:bg-gray-200',
                                'w-9 h-9 flex items-center justify-center rounded-full text-sm transition-colors'
                            ]"
                        >
                            {{ n }}
                        </button>
                    </div>
                    <span>per halaman</span>
                </div>

                <div class="flex items-center gap-1.5">
                    <button
                        @click="gotoPage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-slate-600 hover:bg-gray-100 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-colors"
                        aria-label="Halaman sebelumnya"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>

                    <button
                        v-for="page in pageNumbers"
                        :key="page"
                        @click="gotoPage(page)"
                        :class="[
                            currentPage === page
                                ? 'bg-slate-900 text-white font-semibold'
                                : 'text-slate-600 hover:bg-gray-100',
                            'w-9 h-9 flex items-center justify-center rounded-full text-sm transition-colors'
                        ]"
                    >
                        {{ page }}
                    </button>

                    <button
                        @click="gotoPage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-slate-600 hover:bg-gray-100 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-colors"
                        aria-label="Halaman berikutnya"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <Footer />
</template>