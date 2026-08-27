<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';
import UpButton from '@/Components/UpButton.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { IconHome } from '@tabler/icons-vue';

// Import Domain Components
import BeritaShare from '@/Components/Berita/BeritaShare.vue';

// Import Helpers
import { getImageUrl, getDescription, formatDate, formatDateTime } from '@/Utils/helpers';

const props = defineProps({
    berita: Object,
    beritaTerkini: Array
});

const pageTitle = computed(() => props.berita.judul);

const breadcrumbItems = computed(() => [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Berita & Kegiatan', href: '/berita' },
    {
        label: props.berita.judul?.length > 40
            ? props.berita.judul.slice(0, 40) + '…'
            : props.berita.judul,
    },
]);

function onImageError(event) {
    const img = event.target;
    const fallback = 'data:image/svg+xml;utf8,' + encodeURIComponent(`<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'><rect width='600' height='400' fill='#e5e7eb'/><g fill='#9ca3af'><path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/><circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/></g><text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text></svg>`);
    if (img.src.indexOf('data:image/svg+xml') === -1) {
        img.src = fallback;
    }
}
</script>

<template>
    <Head :title="pageTitle" />

    <Navbar />

    <div class="bg-gray-50 pt-8 pb-20 md:pb-32">
        <div class="container page-content">
            <Breadcrumb :items="breadcrumbItems" class="mb-8" />

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

                <!-- Domain Component Share -->
                <BeritaShare :judul="berita.judul" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

                <!-- ===================== KONTEN UTAMA ===================== -->
                <div class="lg:col-span-2">

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

                    <h1 class="font-['Plus_Jakarta_Sans'] text-3xl md:text-[2.15rem] leading-tight font-bold text-[#0f172a] mb-4">
                        {{ berita.judul }}
                    </h1>
                    <div class="w-14 h-1 bg-blue-600 rounded-full mb-6"></div>

                    <div class="rounded-xl overflow-hidden mb-8 shadow-sm">
                        <img
                            :src="getImageUrl(berita)"
                            :alt="berita.judul"
                            @error="onImageError"
                            class="w-full h-auto object-cover aspect-[16/9]"
                        />
                    </div>

                    <article
                        class="prose prose-slate max-w-none text-justify prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-justify prose-div:text-justify font-['Plus_Jakarta_Sans']"
                        v-html="berita.konten"
                    ></article>

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

                        <div class="flex flex-col gap-5">
                            <Link
                                v-for="item in beritaTerkini"
                                :key="item.id"
                                :href="`/berita/${item.slug}`"
                                class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                            >
                                <CardNews
                                    :title="item.judul"
                                    :description="getDescription(item)"
                                    :date="formatDateTime(item.published_at)"
                                    :author="item.penulis || 'Humas DPMD'"
                                    :tags="item.tags"
                                    :image="getImageUrl(item)"
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

.page-content {
    margin: 0 auto 60px;
}
</style>