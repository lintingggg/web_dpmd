<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps<{
    galeriList: any;
}>();

const dataGaleri = computed(() => props.galeriList.data || []);

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: '2-digit' });
};

const getImageUrl = (path: string) => {
    if (!path) return 'https://via.placeholder.com/600x400?text=No+Image';
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};
</script>

<template>
    <Head title="Galeri Kegiatan" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8 flex flex-col">
        <Navbar />

        <div class="max-w-7xl mx-auto mt-8 flex-grow w-full">

            <PageHeader
                title="Galeri Kegiatan"
                description="Dokumentasi dan potret berbagai aktivitas, program kerja, serta acara resmi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
            />

            <!-- Kondisi 1: kosong -->
            <div v-if="dataGaleri.length === 0" class="mt-12 p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Belum ada foto kegiatan</p>
                <p class="text-sm text-gray-400 mt-1">Galeri saat ini sedang kosong. Nantikan pembaruan aktivitas kami.</p>
            </div>

            <!-- Kondisi 2: tampilkan galeri (Masonry / Grid) -->
            <div v-else class="mt-12 columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                
                <!-- Item Galeri -->
                <div 
                    v-for="item in dataGaleri" 
                    :key="item.id"
                    class="break-inside-avoid relative group rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-white"
                >
                    <img 
                        :src="getImageUrl(item.foto)" 
                        :alt="item.judul"
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

        </div>

        <Footer class="mt-16" />
    </div>
</template>
