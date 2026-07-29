<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue';
import UpButton from '@/Components/UpButton.vue';
import { IconHome, IconX } from '@tabler/icons-vue';
import { ref } from 'vue';

const props = defineProps<{
    album: any;
    galeriList: any;
}>();

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Galeri Kegiatan', href: '/galeri' },
    { label: props.album.nama },
];

const dataGaleri = computed(() => props.galeriList.data || []);

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: '2-digit' });
};

const getMediaUrl = (path: string) => {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

// State for lightbox
const selectedImage = ref<any>(null);

const openLightbox = (item: any) => {
    selectedImage.value = item;
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
};

const closeLightbox = () => {
    selectedImage.value = null;
    document.body.style.overflow = '';
};
</script>

<template>
    <Head :title="`${album.nama} - DPMD Bangkalan`" />

    <Navbar />

    <main class="max-w-7xl mx-auto flex-grow w-full px-4 md:px-8 pt-6 md:pt-8 pb-12">

        <PageHeader
            :breadcrumbs="breadcrumbItems"
            :title="album.nama"
            :description="album.deskripsi || 'Dokumentasi kegiatan untuk album ini.'"
        />

        <div v-if="dataGaleri.length === 0" class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-base font-medium">Belum ada media kegiatan</p>
            <p class="text-sm text-gray-400 mt-1">Album ini masih kosong. Silakan periksa kembali nanti.</p>
        </div>

        <div v-else class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
            <div
                v-for="item in dataGaleri"
                :key="item.id"
                class="break-inside-avoid relative group rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-white"
            >
                <div v-if="item.tipe === 'foto'" class="cursor-pointer" @click="openLightbox(item)">
                    <img
                        :src="getMediaUrl(item.foto)"
                        :alt="item.judul"
                        class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    <!-- Expand Icon Overlay -->
                    <div class="absolute top-4 right-4 bg-black/50 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <polyline points="9 21 3 21 3 15"></polyline>
                            <line x1="21" y1="3" x2="14" y2="10"></line>
                            <line x1="3" y1="21" x2="10" y2="14"></line>
                        </svg>
                    </div>
                </div>
                <div v-else-if="item.tipe === 'video'">
                    <video
                        :src="getMediaUrl(item.foto)"
                        controls
                        class="w-full h-auto rounded-2xl"
                    ></video>
                </div>

                <!-- Overlay Info -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 pointer-events-none">
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

        <!-- Lightbox Modal -->
        <div 
            v-if="selectedImage" 
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 sm:p-8 backdrop-blur-sm"
            @click="closeLightbox"
        >
            <button 
                @click="closeLightbox"
                class="absolute top-6 right-6 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 rounded-full p-2 transition-all z-10"
            >
                <IconX class="w-8 h-8" />
            </button>
            
            <div 
                class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center"
                @click.stop
            >
                <img 
                    :src="getMediaUrl(selectedImage.foto)" 
                    :alt="selectedImage.judul"
                    class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl"
                />
                
                <div class="mt-6 text-center">
                    <h3 class="text-white text-xl font-medium mb-2">{{ selectedImage.judul }}</h3>
                    <span class="text-blue-300 text-sm font-semibold uppercase tracking-wider flex items-center justify-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ formatDate(selectedImage.tanggal_kegiatan) }}
                    </span>
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
