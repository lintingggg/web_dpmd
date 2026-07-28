<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';
import UpButton from '@/Components/UpButton.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { IconHome } from '@tabler/icons-vue';

const props = defineProps<{
    berita: any;
    beritaTerkini: any[];
}>();

const pageTitle = computed(() => props.berita.judul);

// Breadcrumb: pola yang sama dengan Index.vue & VisiMisi.vue. Judul berita
// dipotong biar breadcrumb nggak kepanjangan kalau judulnya panjang.
const breadcrumbItems = computed(() => [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Berita & Kegiatan', href: '/berita' },
    {
        label: props.berita.judul?.length > 40
            ? props.berita.judul.slice(0, 40) + '…'
            : props.berita.judul,
    },
]);

// Formatting helpers
const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: '2-digit' });
};
// poin 4: sidebar "Berita Terkini" butuh jam juga, bukan cuma tanggal
const formatDateTime = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: '2-digit' })
        + ' • ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

// Placeholder inline SVG (data URI) -> tidak pernah 404 karena tidak minta
// apa pun ke server. Sama persis dengan yang dipakai di Index.vue & CardNews.vue,
// menggantikan via.placeholder.com yang sudah tidak reliable.
const FALLBACK_SVG = `<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'>
  <rect width='600' height='400' fill='#e5e7eb'/>
  <g fill='#9ca3af'>
    <path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/>
    <circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/>
  </g>
  <text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text>
</svg>`;
const FALLBACK_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(FALLBACK_SVG)}`;

const getImageUrl = (path: string) => {
    if (!path) return FALLBACK_IMAGE;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

function onImageError(event: Event) {
    const img = event.target as HTMLImageElement;
    if (img.src !== FALLBACK_IMAGE) {
        img.src = FALLBACK_IMAGE;
    }
}

const shareNews = async () => {
    const shareData = {
        title: props.berita.judul,
        text: 'Baca berita ini: ' + props.berita.judul,
        url: window.location.href,
    };
    try {
        if (navigator.share) {
            await navigator.share(shareData);
        } else {
            await navigator.clipboard.writeText(window.location.href);
            alert('Tautan berita berhasil disalin ke clipboard!');
        }
    } catch (err) {
        console.error('Gagal membagikan berita:', err);
    }
};
</script>

<template>
    <Head :title="pageTitle" />

    <Navbar />

    <!-- Header Section: pola sama persis dengan Index.vue, VisiMisi.vue & Galeri.vue.
         Breadcrumb dipindah kesini (sebelumnya dihitung di script tapi tidak
         pernah dirender), supaya konsisten dengan halaman lain -->
    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>{{ berita.judul }}</h1>
        </div>
    </section>

    <div class="bg-gray-50 pb-20 md:pb-32">
        <div class="container page-content">

            <!-- Baris atas: kembali & bagikan -->
            <div class="flex items-center justify-between mb-6">
                <Link
                    href="/berita"
                    class="inline-flex items-center gap-1.5 text-sm text-blue-700 font-medium hover:text-blue-900 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                    Kembali
                </Link>

                <button
                    type="button"
                    @click="shareNews"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-blue-700 transition-colors cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3" />
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="19" r="3" />
                        <line x1="8.6" y1="13.5" x2="15.4" y2="17.5" />
                        <line x1="15.4" y1="6.5" x2="8.6" y2="10.5" />
                    </svg>
                    Bagikan
                </button>
            </div>

            <!-- Grid utama: konten kiri (2/3) + sidebar kanan (1/3) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

                <!-- ===================== KONTEN UTAMA ===================== -->
                <div class="lg:col-span-2">

                    <!-- Meta: tanggal & views -->
                    <div class="flex items-center gap-4 text-xs text-gray-400 mb-3">
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            {{ formatDate(berita.published_at) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            {{ berita.views || 0 }} kali dibaca
                        </span>
                    </div>

                    <!-- Judul + garis aksen -->
                    <h1 class="font-['Plus_Jakarta_Sans'] text-3xl md:text-[2.15rem] leading-tight font-bold text-[#0f172a] mb-4">
                        {{ berita.judul }}
                    </h1>
                    <div class="w-14 h-1 bg-blue-600 rounded-full mb-6"></div>
                    <h2 class="font-serif text-3xl md:text-[2.15rem] leading-tight font-bold text-slate-900 mb-4">
                        {{ berita.judul }}
                    </h2>
                    <div class="w-14 h-1 bg-blue-700 rounded-full mb-6"></div>

                    <!-- Gambar utama -->
                    <div class="rounded-xl overflow-hidden mb-8 shadow-sm">
                        <img
                            :src="getImageUrl(berita.thumbnail)"
                            :alt="berita.judul"
                            @error="onImageError"
                            class="w-full h-auto object-cover aspect-[16/9]"
                        />
                    </div>

                    <!-- Isi berita -->
                    <article
                        class="prose prose-slate max-w-none prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-justify font-['Plus_Jakarta_Sans']"
                        v-html="berita.konten"
                    ></article>

                    <!-- Tags -->
                    <div v-if="berita.tags && berita.tags.length > 0" class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Tags Berita:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tag in berita.tags"
                                :key="tag"
                                class="px-3 py-1 bg-gray-100 text-blue-700 text-xs font-semibold rounded-full"
                            >
                                #{{ tag }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ===================== SIDEBAR: ARTIKEL TERKAIT ===================== -->
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
                                <h2 class="text-sm font-bold tracking-wide text-slate-800 uppercase font-['Plus_Jakarta_Sans']">
                                    Berita Terkait
                                </h2>
                            </div>
                            <Link href="/berita" class="text-sm text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                                Semua Berita &rarr;
                            </Link>
                        </div>

                        <!-- List vertikal, maksimal 4 berita, pakai komponen CardNews -->
                        <div class="flex flex-col gap-5">
                            <Link
                                v-for="item in beritaTerkini"
                                :key="item.id"
                                :href="`/berita/${item.slug}`"
                                class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                            >
                                <CardNews
                                    :title="item.judul"
                                    :date="formatDateTime(item.published_at)"
                                    :author="item.penulis || 'Humas DPMD'"
                                    :tags="item.tags"
                                    :image="getImageUrl(item.thumbnail)"
                                />
                            </Link>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <Footer />
    <UpButton />
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page Header: sama persis dengan Index.vue, VisiMisi.vue & Galeri.vue */
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
    /* Judul berita bisa panjang, batasi 2 baris biar header tidak
       terlalu tinggi di halaman detail */
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Main Layout */
.page-content {
    margin: 40px auto 60px;
}
</style>