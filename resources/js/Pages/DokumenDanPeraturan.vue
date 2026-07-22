<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue'; // Dipakai untuk judul halaman
import SearchBar from '@/Components/SearchBar.vue';   // Dipakai untuk pencarian + history
import TableDokumen from '@/Components/TableDokumen.vue'; // Dipakai untuk list tabel
import UpButton from '@/Components/UpButton.vue';

// State untuk mendeteksi tab yang aktif
const activeTab = ref('perencanaan');

// State untuk input pencarian dan dropdown history
const searchQuery = ref('');
const searchHistory = ref([]);
const isDropdownOpen = ref(false);

// Jumlah dokumen per halaman: null berarti tampilan default (10 item)
const itemsPerPage = ref(null);
const perPageOptions = [20, 35, 50];
const DEFAULT_PAGE_SIZE = 10;

// Halaman aktif
const currentPage = ref(1);

// --- DATA DUMMY DOKUMEN ---
// Route halaman ini render statis (tanpa props dari controller), jadi data
// ditulis langsung di sini. Ganti dengan props dari backend kalau nanti
// controller-nya dibuat untuk kirim data lewat Inertia::render(..., [...]).
const dokumenPerencanaan = [
    { no: 1, judul: 'Rencana Strategis (Renstra) DPMD 2024–2029', tahun: 2024, link: '#' },
    { no: 2, judul: 'Laporan Kinerja Instansi Pemerintah (LKjIP) 2023', tahun: 2024, link: '#' },
    { no: 3, judul: 'Rencana Kerja (Renja) DPMD Tahun 2024', tahun: 2023, link: '#' },
    { no: 4, judul: 'Indikator Kinerja Utama (IKU) Revisi 2023', tahun: 2023, link: '#' },
];

const produkPeraturan = [
    { no: 1, judul: 'Peraturan Bupati Bangkalan Nomor 12 Tahun 2025 tentang Pengelolaan Dana Desa', tahun: 2025, link: '#' },
    { no: 2, judul: 'Peraturan Daerah Kabupaten Bangkalan Nomor 3 Tahun 2024 tentang Kedudukan Keuangan Kepala Desa', tahun: 2024, link: '#' },
];

const dokumenLainnya = [
    { no: 1, judul: 'Panduan Teknis Aplikasi Administrasi Desa Digital 2026', tahun: 2026, link: '#' },
    { no: 2, judul: 'Surat Edaran DPMD Terkait Alokasi Hari Kerja Perangkat Desa', tahun: 2025, link: '#' },
];

// --- LOGIKA HISTORY PENCARIAN (Maksimal 3) ---
onMounted(() => {
    const savedHistory = localStorage.getItem('search_history_dpmd');
    if (savedHistory) {
        searchHistory.value = JSON.parse(savedHistory);
    }
});

const saveToHistory = () => {
    const query = searchQuery.value.trim();
    if (!query) return;

    let history = searchHistory.value.filter(item => item !== query);
    history.unshift(query);

    if (history.length > 3) {
        history = history.slice(0, 3);
    }

    searchHistory.value = history;
    localStorage.setItem('search_history_dpmd', JSON.stringify(history));
    isDropdownOpen.value = false;
};

const selectHistory = (item) => {
    searchQuery.value = item;
    isDropdownOpen.value = false;
};

const deleteHistoryItem = (index) => {
    searchHistory.value.splice(index, 1);
    localStorage.setItem('search_history_dpmd', JSON.stringify(searchHistory.value));
};

// --- COMPUTED FILTER DATA ---
const filteredDokumen = computed(() => {
    let data = [];
    if (activeTab.value === 'perencanaan') data = dokumenPerencanaan;
    else if (activeTab.value === 'peraturan') data = produkPeraturan;
    else if (activeTab.value === 'lainnya') data = dokumenLainnya;

    if (searchQuery.value.trim() !== '') {
        return data.filter(dokumen =>
            dokumen.judul.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    return data;
});

const changeTab = (tabName) => {
    activeTab.value = tabName;
    searchQuery.value = '';
    isDropdownOpen.value = false;
    currentPage.value = 1;
};

// Reset ke halaman 1 setiap kali pencarian berubah
watch(searchQuery, () => {
    currentPage.value = 1;
});

// Ukuran halaman yang dipakai: sesuai pilihan user (15/25/50) atau default 10
const activePageSize = computed(() => itemsPerPage.value || DEFAULT_PAGE_SIZE);

// Total halaman berdasarkan hasil filter/pencarian saat ini
const totalPages = computed(() => {
    return Math.max(1, Math.ceil(filteredDokumen.value.length / activePageSize.value));
});

// Potongan data dokumen untuk halaman yang sedang aktif
const paginatedDokumen = computed(() => {
    const start = (currentPage.value - 1) * activePageSize.value;
    return filteredDokumen.value.slice(start, start + activePageSize.value);
});

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
    const arr = [];
    for (let i = 1; i <= totalPages.value; i++) arr.push(i);
    return arr;
});
</script>

<template>
    <Head title="Publikasi Dokumen" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8 pb-20 md:pb-32">
        <Navbar />

        <div class="max-w-7xl mx-auto mt-8">

            <PageHeader
                title="Publikasi Dokumen"
                description="Akses berbagai dokumen resmi, peraturan, dan laporan perencanaan strategis DPMD Kabupaten Bangkalan secara publik."
            />

            <div class="border-b border-gray-200 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button
                        @click="changeTab('perencanaan')"
                        :class="[activeTab === 'perencanaan' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors']"
                    >
                        Dokumen Perencanaan
                    </button>
                    <button
                        @click="changeTab('peraturan')"
                        :class="[activeTab === 'peraturan' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors']"
                    >
                        Produk Peraturan
                    </button>
                    <button
                        @click="changeTab('lainnya')"
                        :class="[activeTab === 'lainnya' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors']"
                    >
                        Dokumen Lainnya
                    </button>
                </nav>

                <div class="w-full md:w-80 mb-2 md:mb-0 relative">
                    <SearchBar
                        v-model="searchQuery"
                        @focus="isDropdownOpen = true"
                        @blur="setTimeout(() => isDropdownOpen = false, 200)"
                        @keydown.enter="saveToHistory"
                        placeholder="Cari dokumen..."
                    />

                    <div v-if="isDropdownOpen && searchHistory.length > 0" class="absolute z-50 mt-1 w-full rounded-md bg-white shadow-lg border border-gray-200 py-1 text-sm">
                        <div class="px-3 py-1 text-xs font-semibold text-gray-400 select-none">Pencarian Terakhir</div>
                        <ul>
                            <li
                                v-for="(item, index) in searchHistory"
                                :key="index"
                                class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 transition-colors"
                                @mousedown="selectHistory(item)"
                            >
                                <div class="flex items-center space-x-2 truncate">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="truncate">{{ item }}</span>
                                </div>
                                <button @mousedown.stop="deleteHistoryItem(index)" class="text-gray-400 hover:text-red-500 p-1 rounded">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="doc-table-wrapper p-2 md:p-4 doc-table">
                <TableDokumen v-if="filteredDokumen.length > 0" :data="paginatedDokumen" />
                <div v-else class="p-12 text-center text-gray-500">
                    <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                    <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                </div>
            </div>

            <!-- Kontrol jumlah tampilan + paginasi -->
            <div v-if="filteredDokumen.length > 0" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">

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
    <UpButton />
</template>

<style scoped>
/* Container tabel dokumen: disamakan dengan gaya card footer
   (radius besar + shadow soft 3-layer, tanpa border solid) supaya
   konsisten dengan elemen lain di halaman. */
.doc-table-wrapper {
    background: #ffffff;
    border-radius: 24px;
    box-shadow:
        0 -8px 20px -12px rgba(15, 23, 42, 0.08),
        0 24px 48px -20px rgba(15, 23, 42, 0.14),
        0 4px 12px -4px rgba(15, 23, 42, 0.05);
}

/* Animasi hover pada baris dokumen: border memerah, judul berubah warna,
   dan tombol download berubah dari outline jadi solid terisi. */
.doc-table :deep(tbody tr) {
    position: relative;
    transition: background-color 0.25s ease, box-shadow 0.25s ease;
}

.doc-table :deep(tbody tr:hover) {
    background-color: #fef2f2;
    box-shadow: inset 0 0 0 1px #fecdd3;
    cursor: pointer;
}

.doc-table :deep(tbody tr td) {
    transition: color 0.25s ease;
}

.doc-table :deep(tbody tr:hover td:nth-child(2)) {
    color: #dc2626;
    font-weight: 600;
}

.doc-table :deep(tbody tr td:last-child a) {
    transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
}

.doc-table :deep(tbody tr:hover td:last-child a) {
    background-color: #dc2626;
    border-color: #dc2626;
    color: #ffffff;
    transform: scale(1.05);
}
</style>