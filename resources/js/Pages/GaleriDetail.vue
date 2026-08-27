<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import PageHeader from '@/Components/PageHeader.vue';
import UpButton from '@/Components/UpButton.vue';
import { IconHome } from '@tabler/icons-vue';

// Import Domain Components
import GaleriMediaCard from '@/Components/Galeri/GaleriMediaCard.vue';
import GaleriLightbox from '@/Components/Galeri/GaleriLightbox.vue';

const props = defineProps({
    album: Object,
    galeriList: Object
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Galeri Kegiatan', href: '/galeri' },
    { label: props.album.nama },
];

const dataGaleri = computed(() => props.galeriList.data || []);

const selectedImage = ref(null);

const openLightbox = (item) => {
    selectedImage.value = item;
};

const closeLightbox = () => {
    selectedImage.value = null;
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
            <GaleriMediaCard 
                v-for="item in dataGaleri" 
                :key="item.id" 
                :item="item" 
                @click="openLightbox(item)" 
            />
        </div>

        <!-- Lightbox Modal -->
        <GaleriLightbox 
            v-if="selectedImage" 
            :selected-image="selectedImage" 
            @close="closeLightbox" 
        />

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
</style>
