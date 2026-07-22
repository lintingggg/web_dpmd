<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import SearchBar from '@/Components/SearchBar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps<{
    beritaList: any;
    filters: any;
}>();

// State pencarian
const searchQuery = ref(props.filters.search || '');

// Otomatis trigger search saat ngetik (debounce)
let searchTimeout: any = null;
watch(searchQuery, (newVal) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/berita', { search: newVal }, { preserveState: true, replace: true });
    }, 500);
});

// Potongan data untuk halaman yang sedang aktif
const dataBerita = computed(() => props.beritaList.data || []);

// Formatting helpers
const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: '2-digit' });
};
const getImageUrl = (path: string) => {
    if (!path) return 'https://via.placeholder.com/600x400?text=No+Image';
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

// Tampilan "hero" (kartu unggulan besar) 
const showHero = computed(() => props.beritaList.current_page === 1 && dataBerita.value.length > 0 && !searchQuery.value);
const heroBerita = computed(() => showHero.value ? dataBerita.value[0] : null);
const sideBerita = computed(() => showHero.value ? dataBerita.value[1] : null);
const restBerita = computed(() => showHero.value ? dataBerita.value.slice(2) : dataBerita.value);

</script>

<template>
    <Head title="Berita & Kegiatan" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
        <Navbar />

        <div class="max-w-7xl mx-auto mt-8">
            <PageHeader
                title="Berita & Kegiatan"
                description="Informasi terkini seputar program kerja, kegiatan desa, dan pengumuman resmi dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
            />

            <div class="mt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="w-full md:w-96 order-2 md:order-1">
                    <SearchBar
                        v-model="searchQuery"
                        placeholder="Cari judul atau isi berita..."
                    />
                </div>
            </div>

            <!-- Kondisi 1: hasil pencarian/filter kosong -->
            <div v-if="dataBerita.length === 0" class="mt-8 p-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-base font-medium">Berita atau kegiatan tidak ditemukan</p>
                <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>

            <!-- Kondisi 2: tampilkan berita -->
            <div v-else class="mt-8 space-y-6">

                <div v-if="showHero" class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
                    <div v-if="heroBerita" class="lg:col-span-3">
                        <Link :href="`/berita/${heroBerita.slug}`" class="block h-full">
                            <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-md group aspect-[4/3] md:aspect-[16/9] flex flex-col justify-end p-6 md:p-8 transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-2xl cursor-pointer">
                                <img :src="getImageUrl(heroBerita.foto_utama)" class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-300 group-hover:scale-105" alt="featured">
                                <div class="relative z-10">
                                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-3">
                                        BERITA UTAMA
                                    </span>
                                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-3">
                                        {{ heroBerita.judul }}
                                    </h2>
                                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                                        <span>Oleh: <strong>Humas DPMD</strong></span>
                                        <span>•</span>
                                        <span>{{ formatDate(heroBerita.published_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div v-if="sideBerita" class="lg:col-span-2">
                        <Link :href="`/berita/${sideBerita.slug}`" class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer">
                            <CardNews
                                :title="sideBerita.judul"
                                :date="formatDate(sideBerita.published_at)"
                                category="Berita"
                                :image="getImageUrl(sideBerita.foto_utama)"
                            />
                        </Link>
                    </div>
                </div>

                <div v-if="restBerita.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="berita in restBerita"
                        :key="berita.id"
                        :href="`/berita/${berita.slug}`"
                        class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                    >
                        <CardNews
                            :title="berita.judul"
                            :date="formatDate(berita.published_at)"
                            category="Berita"
                            :image="getImageUrl(berita.foto_utama)"
                        />
                    </Link>
                </div>
            </div>

            <!-- Kontrol paginasi -->
            <div v-if="beritaList.links.length > 3" class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <div class="flex flex-wrap items-center justify-center gap-1.5">
                    <template v-for="(link, index) in beritaList.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                link.active
                                    ? 'bg-slate-900 text-white font-semibold'
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
    </div>

    <Footer />
</template>