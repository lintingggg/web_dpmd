<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { IconHome, IconFolder } from '@tabler/icons-vue';
import UpButton from '@/Components/UpButton.vue';

const props = defineProps<{
    albums: any;
}>();

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Galeri Kegiatan' },
];

const dataAlbums = computed(() => props.albums.data || []);

const FALLBACK_SVG = `<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'>
  <rect width='600' height='400' fill='#e5e7eb'/>
  <g fill='#9ca3af'>
    <path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/>
    <circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/>
  </g>
  <text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Cover tidak tersedia</text>
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
</script>

<template>
    <Head title="Album Galeri Kegiatan - DPMD Bangkalan" />

    <Navbar />

    <main class="max-w-7xl mx-auto flex-grow w-full px-4 md:px-8 pt-6 md:pt-8 pb-12">

        <PageHeader
                :breadcrumbs="breadcrumbItems"
                title="Galeri Kegiatan"
                description="Dokumentasi dan potret berbagai aktivitas, program kerja, serta acara resmi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
            />

        <!-- Kondisi 1: kosong -->
        <div v-if="dataAlbums.length === 0" class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-base font-medium">Belum ada album kegiatan</p>
            <p class="text-sm text-gray-400 mt-1">Galeri saat ini sedang kosong. Nantikan pembaruan aktivitas kami.</p>
        </div>

        <!-- Kondisi 2: tampilkan album (Grid) -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <Link
                v-for="album in dataAlbums"
                :key="album.id"
                :href="`/galeri/${album.id}`"
                class="group flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100"
            >
                <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                    <img
                        :src="getImageUrl(album.cover)"
                        :alt="album.nama"
                        @error="onImageError"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-300"></div>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-gray-900 font-bold text-lg leading-snug mb-2 group-hover:text-blue-600 transition-colors">
                            {{ album.nama }}
                        </h3>
                        <p class="text-gray-500 text-sm line-clamp-2">
                            {{ album.deskripsi || 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-blue-600 font-medium text-sm">
                        <span>Lihat Album</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </div>
            </Link>
        </div>

        <!-- Kontrol paginasi -->
        <div v-if="albums.links && albums.links.length > 3" class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
            <div class="flex flex-wrap items-center justify-center gap-1.5">
                <template v-for="(link, index) in albums.links" :key="index">
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

/* Page Header: sama persis dengan Berita/Dokumen/VisiMisi */
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