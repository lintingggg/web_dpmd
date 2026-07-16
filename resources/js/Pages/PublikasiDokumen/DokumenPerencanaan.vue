<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue'; // Dipakai untuk judul halaman
import SearchBar from '@/Components/SearchBar.vue';   // Dipakai untuk pencarian + history
import TableDokumen from '@/Components/TableDokumen.vue'; // Dipakai untuk list tabel

// State untuk input pencarian dan dropdown history
const searchQuery = ref('');
const searchHistory = ref([]);
const isDropdownOpen = ref(false);

// --- DATA DUMMY DOKUMEN PERENCANAAN ---
// Route halaman ini render statis (tanpa props dari controller), jadi data
// ditulis langsung di sini. Ganti dengan props dari backend kalau nanti
// controller-nya dibuat untuk kirim data lewat Inertia::render(..., [...]).
const dokumenPerencanaan = [
    { no: 1, judul: 'Rencana Strategis (Renstra) DPMD 2024–2029', tahun: 2024, link: '#' },
    { no: 2, judul: 'Laporan Kinerja Instansi Pemerintah (LKjIP) 2023', tahun: 2024, link: '#' },
    { no: 3, judul: 'Rencana Kerja (Renja) DPMD Tahun 2024', tahun: 2023, link: '#' },
    { no: 4, judul: 'Indikator Kinerja Utama (IKU) Revisi 2023', tahun: 2023, link: '#' },
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
    if (searchQuery.value.trim() !== '') {
        return dokumenPerencanaan.filter(dokumen =>
            dokumen.judul.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    return dokumenPerencanaan;
});
</script>

<template>
    <Head title="Publikasi Dokumen - Dokumen Perencanaan" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8">

        <div class="max-w-7xl mx-auto mt-8">

            <PageHeader
                title="Publikasi Dokumen"
                description="Akses berbagai dokumen resmi, peraturan, dan laporan perencanaan strategis DPMD Kabupaten Bangkalan secara publik."
            />

            <div class="border-b border-gray-200 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <Link
                        href="/publikasi-dokumen/dokumen-perencanaan"
                        class="whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors border-blue-600 text-blue-600 font-semibold"
                    >
                        Dokumen Perencanaan
                    </Link>
                    <Link
                        href="/publikasi-dokumen/produk-peraturan"
                        class="whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                    >
                        Produk Peraturan
                    </Link>
                    <Link
                        href="/publikasi-dokumen/dokumen-lainnya"
                        class="whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                    >
                        Dokumen Lainnya
                    </Link>
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 md:p-4 doc-table">
                <TableDokumen v-if="filteredDokumen.length > 0" :data="filteredDokumen" />
                <div v-else class="p-12 text-center text-gray-500">
                    <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                    <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                </div>
            </div>

        </div>

        <Footer class="mt-10" />
    </div>
</template>

<style scoped>
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