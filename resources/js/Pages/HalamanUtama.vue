<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { 
  IconMapPin, 
  IconMail, 
  IconBrandWhatsapp, 
  IconBrandFacebook, 
  IconBrandInstagram, 
  IconBrandX, 
  IconBrandYoutube,
  IconMenu2, 
  IconX, 
  IconChevronDown, 
  IconSend, 
  IconBell, 
  IconFileDescription, 
  IconArrowRight, 
  IconCalendar 
} from '@tabler/icons-vue';
import { initHomeAnimations } from '../animations/homeAnimations';
import Navbar from '../Components/Navbar/Navbar.vue';
import Footer from '../Components/Footer.vue';

import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
  pengumumanList: any[];
  beritaTerkini?: any[];
  galeriHighlight?: any[];
}>();

const page = usePage();
const kontak = computed(() => (page.props.kontak as any) || {});

// Format date helper
const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(date);
};

// State for mobile menu
const isMobileMenuOpen = ref(false);

// State for dropdowns (desktop)
const isProfilOpen = ref(false);
const isBidangOpen = ref(false);

// State for mobile accordions
const isMobileProfilOpen = ref(false);
const isMobileBidangOpen = ref(false);

// Data BeritaTerkini sekarang berasal dari props database
const form = ref({
  nama: '',
  email: '',
  subjek: '',
  pesan: ''
});

const submitForm = () => {
  // Handle form submission (validasi ringan)
  if(!form.value.nama || !form.value.email || !form.value.pesan) {
    alert('Mohon lengkapi form kontak!');
    return;
  }
  alert('Pesan berhasil dikirim!');
  form.value = { nama: '', email: '', subjek: '', pesan: '' };
};

// Close dropdowns on outside click for better UX
const closeDropdowns = (e: Event) => {
  if (!(e.target as HTMLElement).closest('.dropdown-container')) {
    isProfilOpen.value = false;
    isBidangOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdowns);
  // Initialize GSAP animations
  initHomeAnimations();
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});
</script>

<template>
  <Head title="Beranda - DPMD Bangkalan" />

  <div class="min-h-screen bg-gray-50 font-sans text-gray-800">
    <!-- NAVBAR -->
    <Navbar />

    <!-- HERO SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
      <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden flex flex-col lg:flex-row border border-gray-100">
        <div class="p-8 lg:p-16 lg:w-1/2 flex flex-col justify-center">
          <div class="inline-block px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold text-gray-600 mb-6 w-max hero-fade-up">
            PEMDA Kabupaten Bangkalan
          </div>
          <h1 class="text-4xl lg:text-5xl font-extrabold text-[#0F1B3D] leading-tight mb-6 overflow-hidden">
            <span class="inline-block hero-title-inner">Membangun Desa Bangkalan yang Mandiri dan Sejahtera.</span>
          </h1>
          <p class="text-gray-600 text-lg mb-8 leading-relaxed hero-fade-up">
            Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berkomitmen penuh dalam mendorong kemajuan potensi desa di seluruh wilayah Bangkalan.
          </p>
          <div class="hero-fade-up">
            <a href="#pelajari" class="inline-flex items-center justify-center gap-2 bg-[#0F1B3D] text-white px-8 py-4 rounded-full font-medium hover:bg-opacity-90 transition-all">
              Pelajari Lebih Lanjut
              <IconArrowRight class="w-5 h-5" />
            </a>
          </div>
        </div>
        <div class="lg:w-1/2 relative h-64 sm:h-96 lg:h-auto overflow-hidden rounded-b-[2rem] lg:rounded-r-[2rem] lg:rounded-bl-none">
          <img src="https://images.unsplash.com/photo-1596489370008-0414ab262071?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Pemandangan Desa" class="absolute inset-0 w-full h-full object-cover hero-image" />
        </div>
      </div>
    </section>

    <!-- PENGUMUMAN TERKINI -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 section-pengumuman">
      <!-- Header Pengumuman -->
      <div class="flex flex-col mb-8 section-header">
        <div class="flex items-center">
          <div class="flex-shrink-0 bg-gray-100 p-2.5 rounded-xl flex items-center justify-center mr-4">
            <IconBell class="w-6 h-6 text-[#0F1B3D]" />
          </div>
          <div class="flex-shrink-0">
            <h2 class="text-sm font-extrabold text-[#0F1B3D] uppercase tracking-widest mb-1">Pengumuman Terkini</h2>
            <p class="text-xs text-gray-500">Informasi terbaru seputar layanan dan program</p>
          </div>
        </div>
        <div class="mt-4 w-16 h-1 bg-[#0F1B3D] rounded-full"></div>
      </div>

      <!-- Layout Opsi A -->
      <div v-if="pengumumanList && pengumumanList.length > 0" class="flex flex-col lg:flex-row gap-6 card-container">
        
        <!-- Highlight Kiri -->
        <div class="lg:w-1/2 group bg-white rounded-2xl border border-gray-100 shadow-sm p-8 hover:shadow-md transition-all flex flex-col relative overflow-hidden card-item hover:-translate-y-1">
          <div class="flex-grow flex flex-col justify-center items-center text-center py-8 mb-4 border-b border-gray-100 border-dashed">
            <IconFileDescription class="w-20 h-20 text-[#0F1B3D] opacity-10 mb-4 group-hover:scale-110 transition-transform duration-500" stroke-width="1" />
            <div class="flex items-center gap-2 mb-4 justify-center">
              <span class="inline-block border border-[#0F1B3D] text-[#0F1B3D] text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-md">
                {{ formatDate(pengumumanList[0].tanggal || pengumumanList[0].created_at) }}
              </span>
            </div>
            <h3 class="text-2xl font-bold text-[#0F1B3D] mb-4 line-clamp-3 group-hover:text-blue-700 transition-colors">
              {{ pengumumanList[0].judul }}
            </h3>
            <p class="text-gray-500 text-sm line-clamp-3">
              {{ pengumumanList[0].cuplikan || (pengumumanList[0].konten ? pengumumanList[0].konten.replace(/<[^>]+>/g, '').substring(0, 100) + '...' : '') }}
            </p>
          </div>
          <div class="pt-4 flex justify-end">
            <a :href="`/pengumuman/${pengumumanList[0].slug}`" class="inline-flex items-center text-xs font-bold text-gray-600 uppercase tracking-wide group-hover:text-[#0F1B3D] transition-colors">
              <span class="mr-3">Baca Selengkapnya</span>
              <div class="bg-[#0F1B3D] text-white p-2 rounded-full group-hover:translate-x-1 transition-transform">
                <IconArrowRight class="w-4 h-4" />
              </div>
            </a>
          </div>
        </div>

        <!-- List Kanan -->
        <div class="lg:w-1/2 flex flex-col gap-4">
          <div v-for="pengumuman in pengumumanList.slice(1, 4)" :key="pengumuman.id" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 card-item hover:-translate-x-1">
            <div class="flex-grow">
              <div class="flex items-center gap-2 mb-2">
                <span class="inline-block bg-gray-100 text-[#0F1B3D] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md">
                  {{ formatDate(pengumuman.tanggal || pengumuman.created_at) }}
                </span>
              </div>
              <h3 class="text-base font-bold text-[#0F1B3D] mb-1 line-clamp-2 group-hover:text-blue-700 transition-colors">
                {{ pengumuman.judul }}
              </h3>
            </div>
            <div class="flex-shrink-0 mt-2 sm:mt-0">
              <a :href="`/pengumuman/${pengumuman.slug}`" class="inline-flex items-center justify-center bg-gray-50 group-hover:bg-[#0F1B3D] text-[#0F1B3D] group-hover:text-white p-3 rounded-full transition-colors border border-gray-100">
                <IconArrowRight class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- BERITA TERKINI -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 section-berita">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 border-b border-gray-200 pb-4 section-header">
        <div>
          <h2 class="text-3xl font-bold text-[#0F1B3D] mb-2">Berita Terkini</h2>
          <p class="text-gray-500">Informasi terbaru seputar kegiatan dan program DPMD Bangkalan.</p>
        </div>
        <a href="/berita" class="mt-4 sm:mt-0 flex items-center text-sm font-semibold text-[#0F1B3D] hover:underline">
          Lihat Semua
          <IconArrowRight class="ml-1 w-4 h-4" />
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 card-container" v-if="beritaTerkini && beritaTerkini.length > 0">
        <div v-for="berita in beritaTerkini" :key="berita.id" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col card-item hover:-translate-y-1">
          <div class="relative h-48 overflow-hidden bg-gray-100">
            <img v-if="berita.thumbnail" :src="berita.thumbnail_url || (berita.thumbnail.startsWith('http') ? berita.thumbnail : '/storage/' + berita.thumbnail)" :alt="berita.judul" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
              <span class="material-symbols-outlined text-4xl">image</span>
            </div>
          </div>
          <div class="p-6 flex-1 flex flex-col">
            <div class="flex items-center text-gray-500 text-xs mb-3 font-medium">
              <IconCalendar class="w-4 h-4 mr-1.5" />
              {{ formatDate(berita.published_at) }}
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2">
              <a :href="`/berita/${berita.slug}`" class="hover:text-blue-600 transition-colors">{{ berita.judul }}</a>
            </h3>
            <p class="text-gray-600 text-sm line-clamp-2 mb-4 flex-1">
              {{ (berita.konten ? berita.konten.replace(/<[^>]+>/g, '').substring(0, 120) + '...' : '') }}
            </p>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-12 text-gray-500 border border-dashed border-gray-200 rounded-xl">
        Belum ada berita terbaru.
      </div>
    </section>

    <!-- HUBUNGI KAMI -->
    <section id="kontak" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 section-kontak overflow-hidden">
      <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden flex flex-col lg:flex-row border border-gray-100">
        <!-- Kolom Kiri: Info Kontak -->
        <div class="bg-[#0F1B3D] text-white p-10 lg:p-14 lg:w-2/5 flex flex-col justify-center kontak-kiri">
          <h2 class="text-3xl font-bold mb-4">Hubungi Kami</h2>
          <p class="text-blue-100 mb-10 leading-relaxed text-sm">
            Kami siap membantu menjawab pertanyaan dan memberikan informasi seputar layanan DPMD.
          </p>
          
          <div class="space-y-6">
            <div class="flex items-start" v-if="kontak.alamat">
              <div class="mt-1 bg-white/10 p-2 rounded-lg mr-4">
                <IconMapPin class="w-6 h-6 text-white" />
              </div>
              <div>
                <h4 class="font-semibold text-sm mb-1">Alamat Kantor</h4>
                <p class="text-blue-100 text-sm leading-relaxed whitespace-pre-line">{{ kontak.alamat }}</p>
              </div>
            </div>
            
            <div class="flex items-start" v-if="kontak.email">
              <div class="mt-1 bg-white/10 p-2 rounded-lg mr-4">
                <IconMail class="w-6 h-6 text-white" />
              </div>
              <div>
                <h4 class="font-semibold text-sm mb-1">Email</h4>
                <a :href="`mailto:${kontak.email}`" class="text-blue-100 text-sm hover:underline">{{ kontak.email }}</a>
              </div>
            </div>
            
            <div class="flex items-start" v-if="kontak.whatsapp">
              <div class="mt-1 bg-white/10 p-2 rounded-lg mr-4">
                <IconBrandWhatsapp class="w-6 h-6 text-white" />
              </div>
              <div>
                <h4 class="font-semibold text-sm mb-1">WhatsApp</h4>
                <a :href="`https://wa.me/${kontak.whatsapp.replace(/[^0-9]/g, '')}`" target="_blank" class="text-blue-100 text-sm hover:underline">{{ kontak.whatsapp }}</a>
              </div>
            </div>
            <div class="flex items-start" v-else-if="kontak.telepon">
              <div class="mt-1 bg-white/10 p-2 rounded-lg mr-4">
                <IconBrandWhatsapp class="w-6 h-6 text-white" />
              </div>
              <div>
                <h4 class="font-semibold text-sm mb-1">Telepon</h4>
                <a :href="`tel:${kontak.telepon}`" class="text-blue-100 text-sm hover:underline">{{ kontak.telepon }}</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Kolom Kanan: Form -->
        <div class="p-10 lg:p-14 lg:w-3/5 bg-white kontak-kanan">
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                <input v-model="form.nama" type="text" placeholder="Masukkan nama Anda" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input v-model="form.email" type="email" placeholder="email@contoh.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm" required>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek</label>
              <input v-model="form.subjek" type="text" placeholder="Perihal pesan" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm">
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
              <textarea v-model="form.pesan" rows="4" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm resize-none" required></textarea>
            </div>
            
            <button type="submit" class="inline-flex items-center gap-2 bg-[#0F1B3D] text-white px-8 py-3.5 rounded-full font-semibold hover:bg-opacity-90 transition-colors shadow-sm">
              Kirim Pesan
              <IconSend class="w-4 h-4" />
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- PETA LOKASI -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 section-peta">
      <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-[#0F1B3D] mb-4">Lokasi Kantor DPMD Kabupaten Bangkalan</h2>
      </div>
      
      <div class="bg-white p-2 rounded-[2rem] shadow-md border border-gray-100 peta-container">
        <div class="rounded-[1.5rem] overflow-hidden h-[400px]">
          <!-- Iframe ini dapat diganti dengan kode embed Google Maps asli -->
          <!-- Contoh dari tombol "Share > Embed a map" -->
          <iframe 
            src="https://maps.google.com/maps?q=-7.037149,112.753303&hl=id&z=15&output=embed" 
            class="w-full h-full border-0" 
            allowfullscreen="true" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
        <div class="p-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50 rounded-b-[1.5rem] mt-2">
          <div class="flex items-center text-gray-700 text-sm">
            <IconMapPin class="w-5 h-5 mr-2 text-[#0F1B3D]" />
            Jl. Halim Perdana Kusuma No. 1, Bangkalan, Jawa Timur
          </div>
          <a href="https://maps.app.goo.gl/mNXFSBVcWa8DYWhx6" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0F1B3D] bg-white border border-gray-200 px-6 py-2.5 rounded-full hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap">
            Buka di Google Maps
          </a>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <Footer />
  </div>
</template>
