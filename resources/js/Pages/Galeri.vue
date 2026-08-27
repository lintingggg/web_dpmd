<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Footer from '@/Components/Footer.vue';
import { IconHome } from '@tabler/icons-vue';

// Import Domain Components
import GaleriAlbumCard from '@/Components/Galeri/GaleriAlbumCard.vue';

const props = defineProps({
    albums: Object
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Galeri Kegiatan' },
];

const dataAlbums = computed(() => props.albums.data || []);
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
        <div v-if="dataAlbums.length === 0" class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm mt-8">
            <p class="text-base font-medium">Belum ada album kegiatan</p>
            <p class="text-sm text-gray-400 mt-1">Galeri saat ini sedang kosong. Nantikan pembaruan aktivitas kami.</p>
        </div>

        <!-- Kondisi 2: tampilkan album (Grid) -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            <GaleriAlbumCard 
                v-for="album in dataAlbums" 
                :key="album.id" 
                :album="album" 
            />
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
</template>

<style scoped>
</style>