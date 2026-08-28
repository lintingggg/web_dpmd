<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import TableDokumen from '@/Components/TableDokumen.vue';
import UpButton from '@/Components/UpButton.vue';
import { IconHome } from '@tabler/icons-vue';

// Import Domain Components
import DokumenSearchHistory from '@/Components/Dokumen/DokumenSearchHistory.vue';

const props = defineProps({
    dokumenList: Object,
    filters: Object,
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Publikasi Dokumen' },
];

// --- STATE UTAMA ---
const activeCategory = ref(props.filters?.kategori || '');
const searchQuery = ref(props.filters?.search || '');

// Mapping data ke TableDokumen
const dataDokumen = computed(() => {
    return (props.dokumenList.data || []).map((doc, index) => {
        return {
            id: doc.id,
            no: index + 1 + ((props.dokumenList.current_page - 1) * props.dokumenList.per_page),
            judul: doc.judul,
            deskripsi: doc.deskripsi,
            tanggal: doc.tahun,
            tahun: doc.tahun,
            link: doc.file_dokumen ? `/storage/${doc.file_dokumen}` : (doc.file_path ? `/storage/${doc.file_path}` : '#'),
        };
    });
});

let searchTimeout = null;

// --- FETCH DATA KE BACKEND ---
const fetchFiltered = (page = null) => {
    router.get('/dokumen-dan-peraturan', {
        kategori: activeCategory.value !== 'Semua' && activeCategory.value !== '' ? activeCategory.value : undefined,
        search: searchQuery.value || undefined,
        page: page,
    }, { preserveState: true, preserveScroll: true, replace: true });
};

watch(searchQuery, () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchFiltered(1);
    }, 500);
});

// --- KONTROL PAGINASI BULAT ---
const currentPage = computed(() => props.dokumenList.current_page || 1);
const lastPage = computed(() => props.dokumenList.last_page || 1);

const pageNumbers = computed(() => {
    const arr = [];
    for (let i = 1; i <= lastPage.value; i++) arr.push(i);
    return arr;
});

function gotoPage(page) {
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

        <div class="w-full">
            <div class="w-full">

                <!-- Domain Component Search dengan History -->
                <DokumenSearchHistory 
                    v-model="searchQuery" 
                    @search="fetchFiltered(1)" 
                />

                <p class="text-sm text-gray-500 mb-4">
                    Ditemukan <span class="font-semibold text-slate-900">{{ dokumenList.total || 0 }}</span> dokumen
                </p>

                <!-- Document Table -->
                <div class="doc-table-wrapper p-2 md:p-4 doc-table">
                    <TableDokumen v-if="dataDokumen.length > 0" :dataDokumen="dataDokumen" />
                    <div v-else class="p-12 text-center text-gray-500">
                        <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                        <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="dataDokumen.length > 0" class="mt-8 flex items-center justify-center gap-1.5">
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

.doc-table-wrapper {
    background: #ffffff;
    border-radius: 24px;
    box-shadow:
        0 -8px 20px -12px rgba(15, 23, 42, 0.08),
        0 24px 48px -20px rgba(15, 23, 42, 0.14),
        0 4px 12px -4px rgba(15, 23, 42, 0.05);
}
</style>