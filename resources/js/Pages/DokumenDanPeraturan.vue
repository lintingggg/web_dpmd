<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue';
import SearchBar from '@/Components/SearchBar.vue';
import TableDokumen from '@/Components/TableDokumen.vue';

const props = defineProps<{
    dokumenList: any;
    filters: any;
}>();

const activeTab = ref(props.filters.kategori || 'Perencanaan');
const searchQuery = ref(props.filters.search || '');
const searchHistory = ref<string[]>([]);
const isDropdownOpen = ref(false);

const dataDokumen = computed(() => {
    // Mapping from DB field names to TableDokumen expected format if needed
    // TableDokumen uses: no, judul, tahun, link
    return (props.dokumenList.data || []).map((doc: any, index: number) => {
        return {
            id: doc.id,
            no: index + 1 + ((props.dokumenList.current_page - 1) * props.dokumenList.per_page),
            judul: doc.judul,
            tahun: doc.tahun,
            link: doc.file_path ? `/storage/${doc.file_path}` : '#',
        };
    });
});

let searchTimeout: any = null;

const fetchFiltered = () => {
    router.get('/dokumen-dan-peraturan', { 
        kategori: activeTab.value,
        search: searchQuery.value 
    }, { preserveState: true, replace: true });
};

watch(searchQuery, (newVal) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchFiltered();
    }, 500);
});

const changeTab = (tabName: string) => {
    activeTab.value = tabName;
    searchQuery.value = '';
    isDropdownOpen.value = false;
    fetchFiltered();
};

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

const selectHistory = (item: string) => {
    searchQuery.value = item;
    isDropdownOpen.value = false;
    fetchFiltered();
};

const deleteHistoryItem = (index: number) => {
    searchHistory.value.splice(index, 1);
    localStorage.setItem('search_history_dpmd', JSON.stringify(searchHistory.value));
};

const handleBlur = () => {
    setTimeout(() => {
        isDropdownOpen.value = false;
    }, 200);
};

</script>

<template>
    <Head title="Publikasi Dokumen" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8 flex flex-col">
        <Navbar />

        <div class="max-w-7xl mx-auto mt-8 flex-grow w-full">

            <PageHeader
                title="Publikasi Dokumen"
                description="Akses berbagai dokumen resmi, peraturan, dan laporan perencanaan strategis DPMD Kabupaten Bangkalan secara publik."
            />

            <div class="border-b border-gray-200 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-6">
                <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                    <button
                        @click="changeTab('Perencanaan')"
                        :class="[activeTab === 'Perencanaan' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors']"
                    >
                        Dokumen Perencanaan
                    </button>
                    <button
                        @click="changeTab('Peraturan')"
                        :class="[activeTab === 'Peraturan' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors']"
                    >
                        Produk Peraturan
                    </button>
                    <button
                        @click="changeTab('Lainnya')"
                        :class="[activeTab === 'Lainnya' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 text-sm md:text-base font-medium transition-colors']"
                    >
                        Dokumen Lainnya
                    </button>
                </nav>

                <div class="w-full md:w-80 mb-2 md:mb-0 relative">
                    <SearchBar
                        v-model="searchQuery"
                        @focus="isDropdownOpen = true"
                        @blur="handleBlur"
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
                <TableDokumen v-if="dataDokumen.length > 0" :data="dataDokumen" />
                <div v-else class="p-12 text-center text-gray-500">
                    <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                    <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                </div>
            </div>

            <!-- Kontrol paginasi -->
            <div v-if="dokumenList.links && dokumenList.links.length > 3" class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <div class="flex flex-wrap items-center justify-center gap-1.5">
                    <template v-for="(link, index) in dokumenList.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                link.active
                                    ? 'bg-blue-600 text-white font-semibold'
                                    : 'text-slate-600 hover:bg-gray-100 border border-gray-200',
                                'px-3 py-1.5 min-w-[36px] flex items-center justify-center rounded-lg text-sm transition-colors'
                            ]"
                            v-html="link.label"
                        />
                        <span 
                            v-else
                            class="px-3 py-1.5 min-w-[36px] flex items-center justify-center rounded-lg border border-gray-200 text-slate-400 opacity-50 cursor-not-allowed text-sm"
                            v-html="link.label"
                        ></span>
                    </template>
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