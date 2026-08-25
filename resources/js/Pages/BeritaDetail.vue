<script setup lang="ts">
import { computed, ref } from 'vue';
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

const breadcrumbItems = computed(() => [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Berita & Kegiatan', href: '/berita' },
    {
        label: props.berita.judul?.length > 40
            ? props.berita.judul.slice(0, 40) + '…'
            : props.berita.judul,
    },
]);

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: '2-digit' });
};

const formatDateTime = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: '2-digit' })
        + ' • ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

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

// TAMBAHAN: sama persis dengan Index.vue, biar CardNews di sidebar juga
// menampilkan deskripsi (fallback ke konten kalau ringkasan/deskripsi/excerpt kosong)
const getDescription = (item: any) => {
    if (item.ringkasan) return item.ringkasan;
    if (item.deskripsi) return item.deskripsi;
    if (item.excerpt) return item.excerpt;
    if (item.konten) {
        return item.konten.replace(/<[^>]+>/g, '').substring(0, 120) + '...';
    }
    return '';
};
// Social Share Dropdown state & copy actions
const showShareMenu = ref(false);

const getShareUrl = () => {
    return typeof window !== 'undefined' ? window.location.href : '';
};

const fallbackCopyText = (text: string) => {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        alert('Tautan berita berhasil disalin ke clipboard!');
    } catch (err) {
        alert('Gagal menyalin tautan.');
    }
    document.body.removeChild(textArea);
};

const copyToClipboard = () => {
    const url = getShareUrl();
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url)
            .then(() => alert('Tautan berita berhasil disalin ke clipboard!'))
            .catch(() => fallbackCopyText(url));
    } else {
        fallbackCopyText(url);
    }
    showShareMenu.value = false;
};
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

                <div class="relative">
                    <button
                        type="button"
                        @click="showShareMenu = !showShareMenu"
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

                    <!-- Dropdown Share Menu -->
                    <div v-if="showShareMenu" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg border border-gray-200 py-1.5 text-sm z-50">
                        <a
                            :href="`https://api.whatsapp.com/send?text=${encodeURIComponent(berita.judul + ' ' + getShareUrl())}`"
                            target="_blank"
                            class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors"
                        >
                            <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            WhatsApp
                        </a>
                        <a
                            :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareUrl())}`"
                            target="_blank"
                            class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors"
                        >
                            <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            Facebook
                        </a>
                        <button
                            @click="copyToClipboard"
                            class="w-full flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors text-left"
                        >
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            Salin Tautan
                        </button>
                    </div>
                </div>
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
                            :src="getImageUrl(berita.thumbnail)"
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

.page-content {
    margin: 0 auto 60px;
}
</style>