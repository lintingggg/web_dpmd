<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import CardNews from '@/Components/CardNews.vue';
import CardAnnouncement from '@/Components/CardAnnouncement.vue';
import Footer from '@/Components/Footer.vue';
import UpButton from '@/Components/UpButton.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { IconHome } from '@tabler/icons-vue';

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Berita & Kegiatan' }
];

const props = defineProps<{
    beritaList: any;
    filters: any;
    // opsional: pengumuman yang di-pin, dikirim dari controller kalau ada
    pengumuman?: any;
    // daftar keyword/tag yang benar-benar dipakai di berita (sumbernya sama
    // dengan available_tags di halaman admin), dipakai buat pill filter di
    // kanan search bar -> BUKAN kategori hardcode
    availableTags?: string[];
}>();

// Placeholder di-generate sebagai inline SVG (data URI), BUKAN path file
// seperti /images/no-image.png. Kenapa: kalau fallback berupa path file dan
// file-nya lupa di-upload / kehapus, fallback-nya sendiri ikut 404, browser
// jadi nampilin ikon broken-image + teks alt (persis yang bikin tampilan
// "kotak putih aneh" di kartu kemarin). Inline SVG tidak pernah butuh
// request ke server, jadi tidak akan pernah gagal dimuat.
const FALLBACK_SVG = `<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'>
  <rect width='600' height='400' fill='#e5e7eb'/>
  <g fill='#9ca3af'>
    <path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/>
    <circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/>
  </g>
  <text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text>
</svg>`;
const FALLBACK_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(FALLBACK_SVG)}`;

// State pencarian, diisi dari filter yang dikirim server
const searchQuery = ref(props.filters?.search || '');

// State keyword/tag aktif untuk pill filter (poin 3: Support Tagging).
// Daftar pill-nya diambil dari availableTags (data asli dari database lewat
// controller), bukan daftar kategori tetap. "Semua" = tanpa filter tag.
const tagList = computed(() => ['Semua', ...(props.availableTags || [])]);
const activeTag = ref(props.filters?.tag || 'Semua');

// Kalau tag kebanyakan buat ditaruh semua sejajar di dalam search bar,
// cuma tampilkan beberapa dulu, sisanya disembunyikan di balik tombol
// titik-titik (dropdown).
const TAG_VISIBLE_LIMIT = 4;
const visibleTags = computed(() => tagList.value.slice(0, TAG_VISIBLE_LIMIT));
const overflowTags = computed(() => tagList.value.slice(TAG_VISIBLE_LIMIT));

const showTagDropdown = ref(false);
const tagDropdownWrapper = ref<HTMLElement | null>(null);

function toggleTagDropdown() {
    showTagDropdown.value = !showTagDropdown.value;
}

function pilihTagDariDropdown(tag: string) {
    pilihTag(tag);
    showTagDropdown.value = false;
}

function handleClickOutsideTagDropdown(event: MouseEvent) {
    if (tagDropdownWrapper.value && !tagDropdownWrapper.value.contains(event.target as Node)) {
        showTagDropdown.value = false;
    }
}

onMounted(() => document.addEventListener('click', handleClickOutsideTagDropdown));
onUnmounted(() => document.removeEventListener('click', handleClickOutsideTagDropdown));

// State jumlah item per halaman (dikirim ke server sebagai per_page)
const perPageOptions = [15, 25, 50];
const itemsPerPage = ref(props.filters?.per_page ? Number(props.filters.per_page) : null);

// Helper: kirim ulang request ke server dengan kombinasi filter terbaru
// sort=terbaru dikirim eksplisit supaya urutan selalu terbaru -> terlama (poin 1)
function updateQuery(overrides: Record<string, any> = {}) {
    router.get('/berita', {
        search: searchQuery.value || undefined,
        tag: activeTag.value !== 'Semua' ? activeTag.value : undefined,
        per_page: itemsPerPage.value || undefined,
        sort: 'terbaru',
        ...overrides,
    }, { preserveState: true, preserveScroll: true, replace: true });
}

// Otomatis trigger search saat ngetik (debounce) -> poin 2: fitur pencarian (keywords)
let searchTimeout: any = null;
watch(searchQuery, () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateQuery({ page: undefined });
    }, 500);
});

function pilihTag(tag: string) {
    if (activeTag.value === tag) return;
    activeTag.value = tag;
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
// poin 3: tanggal & jam
const formatDateTime = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: '2-digit' })
        + ' • ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

// Gambar berita pakai accessor `thumbnail_url` dari model Berita (accessor
// yang sama dipakai di tabel admin), jadi di sini tinggal fallback kalau
// null/kosong. Fallback gambar sendiri lokal (inline SVG), bukan domain
// eksternal yang bisa mati.
const getImageUrl = (url: string | null | undefined) => {
    if (!url) return FALLBACK_IMAGE;
    return url;
};

// Dipakai lewat @error di tag <img> supaya kalau file di storage ternyata
// tidak ada / 404, otomatis diganti placeholder alih-alih menampilkan
// broken image icon + alt text menumpuk di layout.
function onImageError(event: Event) {
    const img = event.target as HTMLImageElement;
    if (img.src.indexOf(FALLBACK_IMAGE) === -1) {
        img.src = FALLBACK_IMAGE;
    }
}

const getDescription = (berita: any) => berita.ringkasan || berita.deskripsi || berita.excerpt || '';

// Tampilan "hero" (kartu unggulan besar) hanya di halaman pertama & tanpa pencarian/filter
const showHero = computed(() => props.beritaList.current_page === 1 && dataBerita.value.length > 0 && !searchQuery.value && activeTag.value === 'Semua');
const heroBerita = computed(() => showHero.value ? dataBerita.value[0] : null);
const sideBerita = computed(() => showHero.value ? dataBerita.value[1] : null);
const restBerita = computed(() => showHero.value ? dataBerita.value.slice(2) : dataBerita.value);

// Pengumuman ditampilkan sebagai satu kartu gelap di baris kedua, hanya di halaman pertama
const showAnnouncement = computed(() => showHero.value && !!props.pengumuman);

// Pisahkan link "Previous" / "Next" dari nomor halaman (bawaan paginator Laravel)
const prevLink = computed(() => props.beritaList.links?.[0]);
const nextLink = computed(() => props.beritaList.links?.[props.beritaList.links.length - 1]);
const pageLinks = computed(() => props.beritaList.links?.slice(1, -1) || []);
</script>

<template>
    <Head title="Berita & Kegiatan - DPMD Bangkalan" />

    <Navbar />

    <main class="min-h-screen bg-gray-50 px-4 md:px-8 pt-6 md:pt-8 pb-20 md:pb-32">
        <div class="max-w-7xl mx-auto">
            <PageHeader
                :breadcrumbs="breadcrumbItems"
                title="Berita & Kegiatan"
                description="Informasi terkini seputar program kerja, kegiatan desa, dan pengumuman resmi dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
            />

            <p class="text-left text-slate-700 mb-8 text-lg leading-relaxed">
                Informasi terkini seputar program kerja, kegiatan desa, dan pengumuman resmi dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan.
            </p>

            <div
                ref="tagDropdownWrapper"
                class="relative flex items-center gap-2 w-full bg-white border border-gray-200 rounded-full shadow-sm pl-5 pr-2 py-2 mb-8"
            >
                <!-- Search: dibuat manual (bukan component SearchBar) supaya
                     nyatu jadi satu shape sama pill keyword di kanannya,
                     sesuai referensi desain -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari berita atau kegiatan..."
                    class="flex-1 min-w-0 bg-transparent border-none outline-none text-sm text-slate-700 placeholder:text-gray-400 py-1.5"
                />

                <!-- Pill keyword/tag: isinya dari tagList (data asli dari database),
                     bukan kategori hardcode. Baru muncul kalau controller
                     mengirim prop availableTags yang tidak kosong. -->
                <div v-if="tagList.length > 1" class="flex items-center gap-1.5 flex-shrink-0">
                    <button
                        v-for="tag in visibleTags"
                        :key="tag"
                        @click="pilihTag(tag)"
                        :class="[
                            activeTag === tag
                                ? 'bg-slate-900 text-white font-semibold'
                                : 'bg-gray-100 text-slate-700 hover:bg-gray-200',
                            'whitespace-nowrap px-4 py-2 rounded-full text-sm transition-colors'
                        ]"
                    >
                        {{ tag }}
                    </button>

                    <!-- Overflow: kalau tag kebanyakan buat sejajar, sisanya
                         disembunyikan di balik tombol titik-titik -->
                    <div v-if="overflowTags.length" class="relative">
                        <button
                            @click="toggleTagDropdown"
                            :class="[
                                overflowTags.includes(activeTag) ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-500 hover:bg-gray-200',
                                'w-9 h-9 flex items-center justify-center rounded-full transition-colors'
                            ]"
                            aria-label="Lihat keyword lainnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="5" cy="12" r="2" />
                                <circle cx="12" cy="12" r="2" />
                                <circle cx="19" cy="12" r="2" />
                            </svg>
                        </button>

                        <div
                            v-if="showTagDropdown"
                            class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-20"
                        >
                            <button
                                v-for="tag in overflowTags"
                                :key="tag"
                                @click="pilihTagDariDropdown(tag)"
                                :class="[
                                    activeTag === tag ? 'text-slate-900 font-semibold bg-gray-50' : 'text-slate-600 hover:bg-gray-50',
                                    'block w-full text-left px-4 py-2 text-sm transition-colors'
                                ]"
                            >
                                {{ tag }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kondisi 1: hasil pencarian kosong -->
            <div v-if="dataBerita.length === 0" class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Berita atau kegiatan tidak ditemukan</p>
                <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain.</p>
            </div>

            <!-- Kondisi 2: tampilkan berita -->
            <div v-else class="space-y-6">

                <!-- Baris 1: hero (2/3) + kartu samping (1/3), rasio mengikuti contoh desain -->
                <div v-if="showHero" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div v-if="heroBerita" class="lg:col-span-2">
                        <Link :href="`/berita/${heroBerita.slug}`" class="block h-full">
                            <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-md group aspect-[4/3] md:aspect-[16/9] flex flex-col justify-end p-6 md:p-8 transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-2xl cursor-pointer">
                                <img
                                    :src="getImageUrl(heroBerita.thumbnail_url)"
                                    @error="onImageError"
                                    class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-300 group-hover:scale-105"
                                    alt="Gambar berita utama"
                                >
                                <div class="relative z-10">
                                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-3">
                                        BERITA UTAMA
                                    </span>
                                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-3">
                                        {{ heroBerita.judul }}
                                    </h2>
                                    <p v-if="getDescription(heroBerita)" class="text-sm text-gray-300 max-w-2xl mb-4 line-clamp-3">
                                        {{ getDescription(heroBerita) }}
                                    </p>

                                    <!-- Tagging: hanya tampil kalau berita punya array tags dari backend -->
                                    <div v-if="heroBerita.tags?.length" class="flex flex-wrap gap-1.5 mb-3">
                                        <span
                                            v-for="tag in heroBerita.tags"
                                            :key="tag"
                                            class="text-[11px] px-2 py-0.5 rounded-full bg-white/10 text-gray-200"
                                        >#{{ tag }}</span>
                                    </div>

                                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                                        <span>Oleh: <strong>{{ heroBerita.penulis || 'Humas DPMD' }}</strong></span>
                                        <span>•</span>
                                        <span>{{ formatDateTime(heroBerita.published_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div v-if="sideBerita" class="lg:col-span-1">
                        <Link :href="`/berita/${sideBerita.slug}`" class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer">
                            <CardNews
                                :title="sideBerita.judul"
                                :description="getDescription(sideBerita)"
                                :date="formatDateTime(sideBerita.published_at)"
                                :author="sideBerita.penulis || 'Humas DPMD'"
                                :tags="sideBerita.tags"
                                :image="getImageUrl(sideBerita.thumbnail_url)"
                            />
                        </Link>
                    </div>
                </div>

                <!-- Baris berikutnya: grid 3 kolom, termasuk 1 kartu pengumuman (gelap) kalau ada -->
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
                            :image="getImageUrl(berita.thumbnail_url)"
                        />
                    </Link>
                </div>
            </div>

            <!-- Kontrol jumlah tampilan + paginasi (poin 5: mitigasi jumlah konten yang banyak) -->
            <div v-if="beritaList.links && beritaList.links.length > 3" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">

                <!-- Total count: "Menampilkan X-Y dari Z berita" -> sebelumnya
                     cuma ada di halaman admin, sekarang dibawa juga ke publik
                     supaya user tau ada berapa banyak berita total -->
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

/* Page Header */
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

/* Main Layout */
.page-content {
    margin-bottom: 60px;
}
</style>