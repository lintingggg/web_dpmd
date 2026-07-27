<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { IconHome } from '@tabler/icons-vue';

const props = defineProps<{
    galeriList: any;
}>();

// Breadcrumb: pola yang sama dengan Berita/Dokumen/VisiMisi
const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Galeri Kegiatan' },
];

const dataGaleri = computed(() => props.galeriList.data || []);

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: '2-digit' });
};

// Placeholder inline SVG (data URI) -> tidak pernah 404 karena tidak minta
// apa pun ke server. Sama persis dengan yang dipakai di halaman Berita,
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
</script>

<template>
    <Head title="Galeri Kegiatan - DPMD Bangkalan" />

    <Navbar />

    <main class="max-w-7xl mx-auto flex-grow w-full px-4 md:px-8 pt-6 md:pt-8 pb-12">

        <PageHeader
                :breadcrumbs="breadcrumbItems"
                title="Galeri Kegiatan"
                description="Dokumentasi dan potret berbagai aktivitas, program kerja, serta acara resmi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
            />

        <p class="text-left text-slate-700 mb-8 text-lg leading-relaxed">
            Dokumentasi dan potret berbagai aktivitas, program kerja, serta acara resmi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan.
        </p>

        <!-- Kondisi 1: kosong -->
        <div v-if="dataGaleri.length === 0" class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-base font-medium">Belum ada foto kegiatan</p>
            <p class="text-sm text-gray-400 mt-1">Galeri saat ini sedang kosong. Nantikan pembaruan aktivitas kami.</p>
        </div>

        <!-- Kondisi 2: tampilkan galeri (Masonry / Grid) -->
        <div v-else class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">

            <!-- Item Galeri -->
            <div
                v-for="item in dataGaleri"
                :key="item.id"
                class="break-inside-avoid relative group rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-white"
            >
                <img
                    :src="getImageUrl(item.foto)"
                    :alt="item.judul"
                    @error="onImageError"
                    class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105"
                />

                <!-- Overlay Info -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="text-blue-300 text-xs font-semibold uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ formatDate(item.tanggal_kegiatan) }}
                    </span>
                    <h3 class="text-white font-medium text-lg leading-snug">
                        {{ item.judul }}
                    </h3>
                </div>
            </div>

        </div>

        <!-- Kontrol paginasi -->
        <div v-if="galeriList.links && galeriList.links.length > 3" class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
            <div class="flex flex-wrap items-center justify-center gap-1.5">
                <template v-for="(link, index) in galeriList.links" :key="index">
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