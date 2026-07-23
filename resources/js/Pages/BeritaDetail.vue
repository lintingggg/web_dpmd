<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps<{
    berita: any;
    beritaTerkini: any[];
}>();

const pageTitle = computed(() => props.berita.judul);

// Formatting helpers
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
    <Head :title="pageTitle" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8 pb-20 md:pb-32">
        <Navbar />

        <div class="max-w-6xl mx-auto mt-8">

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
                    class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors"
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
                    <h1 class="font-serif text-3xl md:text-[2.15rem] leading-tight font-bold text-slate-900 mb-4">
                        {{ berita.judul }}
                    </h1>
                    <div class="w-14 h-1 bg-blue-700 rounded-full mb-6"></div>

                    <!-- Gambar utama -->
                    <div class="rounded-xl overflow-hidden mb-8 shadow-sm">
                        <img
                            :src="getImageUrl(berita.foto_utama)"
                            :alt="berita.judul"
                            class="w-full h-auto object-cover aspect-[16/9]"
                        />
                    </div>

                    <!-- Isi berita -->
                    <article
                        class="prose prose-slate max-w-none prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-justify"
                        v-html="berita.konten"
                    ></article>
                </div>

                <!-- ===================== SIDEBAR: ARTIKEL TERKAIT ===================== -->
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8">
                        <div class="flex items-center gap-2 mb-5">
                            <span class="w-1.5 h-5 bg-blue-700 rounded-full"></span>
                            <h2 class="text-sm font-bold tracking-wide text-slate-800 uppercase">
                                Berita Terkini
                            </h2>
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
                                    :date="formatDate(item.published_at)"
                                    category="Berita"
                                    :image="getImageUrl(item.foto_utama)"
                                />
                            </Link>
                        </div>

                        <!-- Item ke-5: Lihat berita lainnya -->
                        <Link
                            href="/berita"
                            class="flex items-center justify-center gap-1.5 mt-5 py-3 rounded-lg bg-blue-700 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
                        >
                            Lihat Berita Lainnya
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </Link>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <Footer />
    <UpButton />
</template>