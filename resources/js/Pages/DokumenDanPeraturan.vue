<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import SearchBar from '@/Components/SearchBar.vue';
import UpButton from '@/Components/UpButton.vue';
import { IconHome } from '@tabler/icons-vue';

const props = defineProps<{
    dokumenList: any;
    filters: any;
    kategoriList?: string[];
}>();

// Breadcrumb: pola yang sama dengan Berita/Index.vue & VisiMisi.vue
const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Publikasi Dokumen' },
];

// --- STATE UTAMA ---
const activeCategory = ref(props.filters?.kategori || 'Semua Kategori');
const searchQuery = ref(props.filters?.search || '');
const searchHistory = ref<string[]>([]);
const isDropdownOpen = ref(false);

// Sidebar kategori: "Semua Kategori" + daftar asli dari backend
const categoryList = computed(() => ['Semua Kategori', ...(props.kategoriList || [])]);

// --- STATE PAGINATION ---
const itemsPerPage = ref<number | null>(props.filters?.per_page ? Number(props.filters.per_page) : null);
const perPageOptions = [20, 35, 50];

// Mapping data dari backend ke format list
const dataDokumen = computed(() => {
    return (props.dokumenList.data || []).map((doc: any, index: number) => {
        return {
            id: doc.id,
            no: index + 1 + ((props.dokumenList.current_page - 1) * props.dokumenList.per_page),
            judul: doc.judul,
            kategori: doc.kategori || 'Umum',
            subkategori: doc.sub_kategori || doc.subkategori || doc.kategori || 'Umum',
            tahun: doc.tahun,
            link: doc.file_path ? `/storage/${doc.file_path}` : '#',
        };
    });
});

let searchTimeout: ReturnType<typeof setTimeout> | null = null;

// --- FETCH DATA KE BACKEND ---
const fetchFiltered = (page: number | null = null) => {
    router.get('/dokumen-dan-peraturan', {
        kategori: activeCategory.value !== 'Semua Kategori' ? activeCategory.value : undefined,
        search: searchQuery.value || undefined,
        per_page: itemsPerPage.value || undefined,
        page: page,
    }, { preserveState: true, preserveScroll: true, replace: true });
};

watch(searchQuery, () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchFiltered(1);
    }, 500);
});

const pilihKategori = (kategori: string) => {
    if (activeCategory.value === kategori) return;
    activeCategory.value = kategori;
    fetchFiltered(1);
};

// --- HISTORY PENCARIAN (maksimal 3) ---
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
    fetchFiltered(1);
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

// --- KONTROL "TAMPILKAN N PER HALAMAN" ---
function pilihJumlahTampilan(jumlah: number) {
    itemsPerPage.value = itemsPerPage.value === jumlah ? null : jumlah;
    fetchFiltered(1);
}

// --- KONTROL PAGINASI BULAT ---
const currentPage = computed(() => props.dokumenList.current_page || 1);
const lastPage = computed(() => props.dokumenList.last_page || 1);

const pageNumbers = computed(() => {
    const arr = [];
    for (let i = 1; i <= lastPage.value; i++) arr.push(i);
    return arr;
});

function gotoPage(page: number) {
    if (page < 1 || page > lastPage.value) return;
    fetchFiltered(page);
}
</script>

<template>
    <Head title="Publikasi Dokumen - DPMD Bangkalan" />

    <Navbar />

    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>Publikasi Dokumen</h1>
        </div>
    </section>

    <main class="container page-content">

        <p class="text-left text-slate-700 mb-8 text-lg leading-relaxed">
            Akses berbagai dokumen resmi, peraturan, dan laporan perencanaan strategis DPMD Kabupaten Bangkalan secara publik.
        </p>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

            <div class="lg:col-span-3">

                <div class="relative w-full mb-5">
                    <SearchBar
                        v-model="searchQuery"
                        placeholder="Cari dokumen..."
                        @focus="isDropdownOpen = true"
                        @blur="handleBlur"
                        @keyup.enter="saveToHistory"
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

                <p class="text-sm text-gray-500 mb-2">
                    Ditemukan <span class="font-semibold text-slate-900">{{ dokumenList.total || 0 }}</span> dokumen
                </p>

                <div v-if="dataDokumen.length > 0" class="divide-y divide-gray-100 border-t border-gray-100">
                    <a
                        v-for="doc in dataDokumen"
                            :key="doc.id"
                            :href="doc.link"
                            target="_blank"
                            class="group flex items-start gap-3 py-3 px-3 -mx-3 hover:bg-[#0F1B3D] transition-colors rounded-lg"
                        >
                            <span class="text-sm text-gray-400 group-hover:text-white/60 w-6 flex-shrink-0 pt-0.5 transition-colors">{{ doc.no }}.</span>
                            <div class="min-w-0">
                                <h3 class="text-sm md:text-base font-semibold text-slate-900 group-hover:text-white transition-colors">
                                    {{ doc.judul }}
                                </h3>
                                <p class="text-xs text-gray-400 group-hover:text-white/70 mt-1 transition-colors">
                                    {{ doc.kategori }} • {{ doc.subkategori }} • {{ doc.tahun }}
                                </p>
                            </div>
                        </a>
                </div>

                <div v-else class="p-12 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                    <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                </div>

                <div v-if="dataDokumen.length > 0" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">

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
                            :disabled="currentPage === lastPage"
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

            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-8 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold tracking-wide text-slate-800 uppercase mb-3">
                        Kategori
                    </h2>
                    <ul class="flex flex-col">
                        <li v-for="kategori in categoryList" :key="kategori">
                            <button
                                type="button"
                                @click="pilihKategori(kategori)"
                                :class="[
                                    activeCategory === kategori
                                        ? 'text-[#2563eb] font-semibold border-[#2563eb]'
                                        : 'text-slate-600 border-transparent hover:text-slate-900',
                                    'w-full text-left py-2.5 text-sm border-l-2 pl-3 -ml-px transition-colors'
                                ]"
                            >
                                {{ kategori }}
                            </button>
                        </li>
                    </ul>
                </div>
            </aside>

        </div>

    </main>

    <Footer />
    <UpButton />
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-header {
    background-color: #FFFFFF;
    padding: 20px 0 30px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
}

.page-content {
    margin-bottom: 60px;
}
</style>