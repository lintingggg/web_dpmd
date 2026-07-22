<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import CardNews from '@/Components/CardNews.vue';
import Footer from '@/Components/Footer.vue';

// =========================================================================
// CATATAN: Ini baru tampilan (UI) saja. Data masih dummy/statis.
// Nanti kalau backend Laravel sudah siap, tinggal ganti bagian dummy di
// bawah ini dengan props yang dikirim dari controller lewat Inertia, misal:
//
//   const props = defineProps({
//       berita: Object,          // data berita utama
//       beritaTerkait: Array,    // 4 berita terbaru untuk sidebar
//   });
//
// lalu hapus variabel `berita` & `beritaTerkait` dummy di bawah.
// =========================================================================

// Dummy data berita utama
const berita = {
    id: 1,
    title: 'UU Desa Tidak Boleh Dibengkokkan: Pentingnya Rekognisi Negara dan Wewenang Desa',
    date: '18 Desember 2024',
    views: 1751,
    image: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=1200',
    content: `
        <p>Undang-Undang Nomor 6 Tahun 2014 tentang Desa memberikan dasar hukum bagi desa untuk mengatur dan mengurus kepentingan masyarakatnya secara mandiri. Namun, dalam praktiknya, sering muncul pembatasan atau penyimpangan UU Desa yang dapat mengancam kedaulatan desa dan efektivitas pembangunan lokal. Dalam konteks ini, asas rekognisi negara dan subsidiaritas kewenangan desa menjadi prinsip penting yang harus dijaga.</p>
        <p>Asas rekognisi negara menegaskan bahwa negara mengakui eksistensi dan hak desa sebagai entitas hukum dan pemerintahan lokal. Desa memiliki kewenangan untuk mengatur dan mengurus kepentingan masyarakatnya sesuai dengan potensi dan kebutuhan lokal. Asas ini menekankan bahwa desa tidak hanya menjadi pelaksana kebijakan pusat, tetapi juga subjek yang memiliki hak dan kewajiban untuk mengelola pemerintahan dan pembangunan secara mandiri.</p>
        <p>Sementara itu, asas subsidiaritas menegaskan bahwa kewenangan sebaiknya diberikan pada tingkat pemerintahan yang paling dekat dengan masyarakat, yaitu desa, kecuali jika penyelesaian di tingkat tersebut tidak memungkinkan. Prinsip ini memastikan desa memiliki otonomi dalam mengambil keputusan yang berdampak langsung pada masyarakatnya.</p>
        <p>Dengan memahami dan menerapkan asas rekognisi negara dan subsidiaritas kewenangan desa, diharapkan pembangunan desa dapat berjalan lebih efektif dan berkelanjutan, sekaligus memperkuat kepercayaan masyarakat terhadap pemerintahan desa. (Tim/*)</p>
    `,
};

// Dummy data artikel terkait — tampil vertikal di sidebar kanan, maksimal 4
const beritaTerkait = [
    {
        id: 2,
        title: 'Pembangunan Desa dan Kelestarian Lingkungan Mewujudkan Desa...',
        description: 'Upaya menjaga keseimbangan antara pembangunan infrastruktur desa dengan pelestarian lingkungan sekitar.',
        category: 'Kegiatan',
        date: '15 Des 2024',
        image: 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=300',
    },
    {
        id: 3,
        title: 'Desa Mandiri dan Berkelanjutan: Membangun Potensi Lokal untuk...',
        description: 'Strategi pengembangan potensi lokal desa guna mewujudkan kemandirian ekonomi yang berkelanjutan.',
        category: 'Pemberdayaan',
        date: '13 Des 2024',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=300',
    },
    {
        id: 4,
        title: 'Sosialisasi Penggunaan Sistem Informasi Desa Terpadu',
        description: 'Mendorong percepatan digitalisasi administrasi desa melalui penerapan aplikasi terpadu.',
        category: 'Informasi',
        date: '08 Des 2024',
        image: 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?q=80&w=300',
    },
    {
        id: 5,
        title: 'Pelatihan Kewirausahaan untuk BUMDes Mandiri Se-Kabupaten',
        description: 'Pelatihan intensif bagi pengurus BUMDes untuk meningkatkan kapasitas manajerial dan inovasi produk lokal.',
        category: 'Pemberdayaan',
        date: '05 Des 2024',
        image: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=300',
    },
];

const pageTitle = computed(() => berita.title);
</script>

<template>
    <Head :title="pageTitle" />

    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
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
                            {{ berita.date }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            {{ berita.views }} kali dibaca
                        </span>
                    </div>

                    <!-- Judul + garis aksen -->
                    <h1 class="font-serif text-3xl md:text-[2.15rem] leading-tight font-bold text-slate-900 mb-4">
                        {{ berita.title }}
                    </h1>
                    <div class="w-14 h-1 bg-blue-700 rounded-full mb-6"></div>

                    <!-- Gambar utama -->
                    <div class="rounded-xl overflow-hidden mb-8 shadow-sm">
                        <img
                            :src="berita.image"
                            :alt="berita.title"
                            class="w-full h-auto object-cover aspect-[16/9]"
                        />
                    </div>

                    <!-- Isi berita -->
                    <article
                        class="prose prose-slate max-w-none prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-justify"
                        v-html="berita.content"
                    ></article>
                </div>

                <!-- ===================== SIDEBAR: ARTIKEL TERKAIT ===================== -->
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8">
                        <div class="flex items-center gap-2 mb-5">
                            <span class="w-1.5 h-5 bg-blue-700 rounded-full"></span>
                            <h2 class="text-sm font-bold tracking-wide text-slate-800 uppercase">
                                Artikel Terkait
                            </h2>
                        </div>

                        <!-- List vertikal, maksimal 4 berita, pakai komponen CardNews -->
                        <div class="flex flex-col gap-5">
                            <Link
                                v-for="item in beritaTerkait.slice(0, 4)"
                                :key="item.id"
                                :href="`/berita/${item.id}`"
                                class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer"
                            >
                                <CardNews
                                    :title="item.title"
                                    :description="item.description"
                                    :date="item.date"
                                    :category="item.category"
                                    :image="item.image"
                                />
                            </Link>
                        </div>

                        <!-- Item ke-5: Lihat berita lainnya -->
                        <Link
                            href="/berita"
                            class="flex items-center justify-center gap-1.5 mt-2 py-3 rounded-lg bg-blue-700 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
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
</template>