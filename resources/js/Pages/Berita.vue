<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import CardNews from '@/Components/CardNews.vue';
import CardAnnouncement from '@/Components/CardAnnouncement.vue';
import Footer from '@/Components/Footer.vue';
import UpButton from '@/Components/UpButton.vue';
import SearchBar from '@/Components/SearchBar.vue';
import { IconHome } from '@tabler/icons-vue';

// Import Domain Components
import BeritaHero from '@/Components/Berita/BeritaHero.vue';
import BeritaTagFilter from '@/Components/Berita/BeritaTagFilter.vue';

// Import Helpers
import { getImageUrl, getDescription, formatDateTime } from '@/Utils/helpers';

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Berita & Kegiatan' }
];

const props = defineProps({
    beritaList: Object,
    filters: Object,
    pengumuman: Object,
    availableTags: Array
});

// State pencarian
const searchQuery = ref(props.filters?.search || '');

// State keyword/tag aktif
const tagList = computed(() => ['Semua', ...(props.availableTags || [])]);
const activeTag = ref(props.filters?.tag || 'Semua');

// State per_page
const perPageOptions = [15, 25, 50];
const itemsPerPage = ref(props.filters?.per_page ? Number(props.filters.per_page) : null);

// Helper updateQuery
function updateQuery(overrides = {}) {
    router.get('/berita', {
        search: searchQuery.value || undefined,
        tag: activeTag.value !== 'Semua' ? activeTag.value : undefined,
        per_page: itemsPerPage.value || undefined,
        sort: 'terbaru',
        ...overrides,
    }, { preserveState: true, preserveScroll: true, replace: true });
}

// Otomatis trigger search saat ngetik (debounce)
let searchTimeout = null;
watch(searchQuery, () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateQuery({ page: undefined });
    }, 500);
});

function pilihTag(tag) {
    if (activeTag.value === tag) return;
    activeTag.value = tag;
    updateQuery({ page: undefined });
}

function pilihJumlahTampilan(jumlah) {
    itemsPerPage.value = itemsPerPage.value === jumlah ? null : jumlah;
    updateQuery({ page: undefined });
}

const dataBerita = computed(() => props.beritaList.data || []);

// Tampilan "hero" hanya di halaman pertama & tanpa pencarian/filter
const showHero = computed(() => props.beritaList.current_page === 1 && dataBerita.value.length > 0 && !searchQuery.value && activeTag.value === 'Semua');
const heroBerita = computed(() => showHero.value ? dataBerita.value[0] : null);
const sideBerita = computed(() => showHero.value ? dataBerita.value[1] : null);
const restBerita = computed(() => showHero.value ? dataBerita.value.slice(2) : dataBerita.value);

const showAnnouncement = computed(() => showHero.value && !!props.pengumuman);

const prevLink = computed(() => props.beritaList.links?.[0]);
const nextLink = computed(() => props.beritaList.links?.[props.beritaList.links.length - 1]);
const pageLinks = computed(() => props.beritaList.links?.slice(1, -1) || []);
</script>

<template>
    <Head title="Berita & Kegiatan - DPMD Bangkalan" />

    <Navbar />

    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>Berita & Kegiatan</h1>
        </div>
    </section>

    <main class="container page-content">
        <div class="py-2">

            <p class="text-left text-slate-700 mb-8 text-lg leading-relaxed">
                Informasi terkini seputar program kerja, kegiatan desa, dan pengumuman resmi dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan.
            </p>

            <div class="mb-10">
                <SearchBar
                    v-model="searchQuery"
                    placeholder="Cari berita atau kegiatan..."
                />
            </div>

            <!-- Domain Component Tag Filter -->
            <BeritaTagFilter 
                :tag-list="tagList" 
                :active-tag="activeTag" 
                @change="pilihTag" 
            />

            <!-- Kondisi 1: hasil pencarian kosong -->
            <div v-if="dataBerita.length === 0" class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Berita atau kegiatan tidak ditemukan</p>
                <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain.</p>
            </div>

            <!-- Kondisi 2: tampilkan berita -->
            <div v-else class="space-y-6">

                <!-- Domain Component Hero Berita -->
                <BeritaHero 
                    v-if="showHero"
                    :hero-berita="heroBerita" 
                    :side-berita="sideBerita" 
                />

                <!-- Baris berikutnya: grid 3 kolom -->
                <div v-if="restBerita.length || showAnnouncement" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <Link
                        v-if="showAnnouncement"
                        :href="pengumuman.link || '#'"
                        class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                    >
                        <CardAnnouncement
                            :title="pengumuman.judul || 'Pengumuman Penting'"
                            :description="pengumuman.deskripsi"
                            :image="pengumuman.foto_url ? getImageUrl(pengumuman.foto_url) : ''"
                        />
                    </Link>

                    <Link
                        v-for="berita in restBerita"
                        :key="berita.id"
                        :href="`/berita/${berita.slug}`"
                        class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                    >
                        <CardNews
                            :title="berita.judul"
                            :description="getDescription(berita)"
                            :date="formatDateTime(berita.published_at)"
                            :author="berita.penulis || 'Humas DPMD'"
                            :tags="berita.tags"
                            :image="getImageUrl(berita)"
                        />
                    </Link>
                </div>
            </div>

            <!-- Kontrol jumlah tampilan + paginasi -->
            <div v-if="beritaList.links && beritaList.links.length > 3" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">

                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-slate-900">{{ beritaList.from || 0 }}-{{ beritaList.to || 0 }}</span> dari <span class="font-semibold text-slate-900">{{ beritaList.total }}</span> berita
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4">
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
                        <Link
                            v-if="prevLink?.url"
                            :href="prevLink.url"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-slate-600 hover:bg-gray-100 transition-colors"
                            aria-label="Halaman sebelumnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </Link>
                        <span
                            v-else
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-slate-400 opacity-30 cursor-not-allowed"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </span>

                        <template v-for="(link, index) in pageLinks" :key="index">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'bg-slate-900 text-white font-semibold'
                                        : 'text-slate-600 hover:bg-gray-100',
                                    'w-9 h-9 flex items-center justify-center rounded-full text-sm transition-colors'
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="w-9 h-9 flex items-center justify-center rounded-full text-slate-400 opacity-50 cursor-not-allowed text-sm"
                                v-html="link.label"
                            ></span>
                        </template>

                        <Link
                            v-if="nextLink?.url"
                            :href="nextLink.url"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-slate-600 hover:bg-gray-100 transition-colors"
                            aria-label="Halaman berikutnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </Link>
                        <span
                            v-else
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-slate-400 opacity-30 cursor-not-allowed"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </span>
                    </div>
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
</style>