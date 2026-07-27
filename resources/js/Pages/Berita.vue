<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import SearchBar from '@/Components/SearchBar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';
import UpButton from '@/Components/UpButton.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { IconHome } from '@tabler/icons-vue';

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Berita & Kegiatan' }
];

const props = defineProps<{
    beritaList: any;
    filters: any;
}>();

// Daftar kategori untuk tombol filter (sesuaikan dengan kategori yang ada di backend)
const kategoriOptions = ['Semua', 'Kegiatan', 'Pemberdayaan', 'Informasi'];

// State pencarian & kategori aktif, diisi dari filter yang dikirim server
const searchQuery = ref(props.filters?.search || '');
const activeCategory = ref(props.filters?.kategori || 'Semua');

// State jumlah item per halaman (dikirim ke server sebagai per_page)
const perPageOptions = [15, 25, 50];
const itemsPerPage = ref(props.filters?.per_page ? Number(props.filters.per_page) : null);

// Helper: kirim ulang request ke server dengan kombinasi filter terbaru
function updateQuery(overrides: Record<string, any> = {}) {
    router.get('/berita', {
        search: searchQuery.value || undefined,
        kategori: activeCategory.value !== 'Semua' ? activeCategory.value : undefined,
        per_page: itemsPerPage.value || undefined,
        ...overrides,
    }, { preserveState: true, replace: true });
}

// Otomatis trigger search saat ngetik (debounce)
let searchTimeout: any = null;
watch(searchQuery, () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateQuery({ page: undefined });
    }, 500);
});

function pilihKategori(kat: string) {
    activeCategory.value = kat;
    updateQuery({ page: undefined });
}

function pilihJumlahTampilan(jumlah: number) {
    itemsPerPage.value = itemsPerPage.value === jumlah ? null : jumlah;
    updateQuery({ page: undefined });
}

// Potongan data untuk halaman yang sedang aktif
const dataBerita = computed(() => props.beritaList.data || []);

// Formatting helpers
const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: '2-digit' });
};
const getImageUrl = (path: string) => {
    if (!path) return 'https://via.placeholder.com/600x400?text=No+Image';
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};
const getDescription = (berita: any) => berita.ringkasan || berita.deskripsi || berita.excerpt || '';

// Tampilan "hero" (kartu unggulan besar) hanya di halaman pertama & tanpa pencarian
const showHero = computed(() => props.beritaList.current_page === 1 && dataBerita.value.length > 0 && !searchQuery.value);
const heroBerita = computed(() => showHero.value ? dataBerita.value[0] : null);
const sideBerita = computed(() => showHero.value ? dataBerita.value[1] : null);
const restBerita = computed(() => showHero.value ? dataBerita.value.slice(2) : dataBerita.value);

// Pisahkan link "Previous" / "Next" dari nomor halaman (bawaan paginator Laravel)
const prevLink = computed(() => props.beritaList.links?.[0]);
const nextLink = computed(() => props.beritaList.links?.[props.beritaList.links.length - 1]);
const pageLinks = computed(() => props.beritaList.links?.slice(1, -1) || []);
</script>

<template>
    <Head title="Berita & Kegiatan" />

    <Navbar />

    <div class="min-h-screen bg-gray-50 px-4 md:px-8 pt-6 md:pt-8 pb-20 md:pb-32">
        <div class="max-w-7xl mx-auto">
            <PageHeader
                :breadcrumbs="breadcrumbItems"
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
                        v-for="cat in kategoriOptions"
                        :key="cat"
                        @click="pilihKategori(cat)"
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
            <div v-if="dataBerita.length === 0" class="mt-8 p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Berita atau kegiatan tidak ditemukan</p>
                <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci atau kategori filter yang lain.</p>
            </div>

            <!-- Kondisi 2: tampilkan berita -->
            <div v-else class="mt-8 space-y-6">

                <div v-if="showHero" class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
                    <div v-if="heroBerita" class="lg:col-span-3">
                        <Link :href="`/berita/${heroBerita.slug}`" class="block h-full">
                            <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-md group aspect-[4/3] md:aspect-[16/9] flex flex-col justify-end p-6 md:p-8 transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-2xl cursor-pointer">
                                <img :src="getImageUrl(heroBerita.foto_utama)" class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-300 group-hover:scale-105" alt="featured">
                                <div class="relative z-10">
                                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-3">
                                        {{ heroBerita.kategori || 'BERITA UTAMA' }}
                                    </span>
                                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-3">
                                        {{ heroBerita.judul }}
                                    </h2>
                                    <p v-if="getDescription(heroBerita)" class="text-sm text-gray-300 max-w-2xl mb-4 line-clamp-3">
                                        {{ getDescription(heroBerita) }}
                                    </p>
                                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                                        <span>Oleh: <strong>{{ heroBerita.penulis || 'Humas DPMD' }}</strong></span>
                                        <span>•</span>
                                        <span>{{ formatDate(heroBerita.published_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div v-if="sideBerita" class="lg:col-span-2">
                        <Link :href="`/berita/${sideBerita.slug}`" class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer">
                            <CardNews
                                :title="sideBerita.judul"
                                :description="getDescription(sideBerita)"
                                :date="formatDate(sideBerita.published_at)"
                                :category="sideBerita.kategori || 'Berita'"
                                :image="getImageUrl(sideBerita.foto_utama)"
                            />
                        </Link>
                    </div>
                </div>

                <div v-if="restBerita.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="berita in restBerita"
                        :key="berita.id"
                        :href="`/berita/${berita.slug}`"
                        class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                    >
                        <CardNews
                            :title="berita.judul"
                            :description="getDescription(berita)"
                            :date="formatDate(berita.published_at)"
                            :category="berita.kategori || 'Berita'"
                            :image="getImageUrl(berita.foto_utama)"
                        />
                    </Link>
                </div>
            </div>

            <!-- Kontrol jumlah tampilan + paginasi -->
            <div v-if="beritaList.links && beritaList.links.length > 3" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">

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

    <Footer />
    <UpButton />
</template>