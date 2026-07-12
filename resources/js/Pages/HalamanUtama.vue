<<<<<<< HEAD
<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

// Tambahkan ini
import BannerSection from '@/Components/BannerSection.vue';
import CardAnnouncement from '@/Components/CardAnnouncement.vue';
import CardNews from '@/Components/CardNews.vue';
</script>

<template>
    <Head title="Publikasi Dokumen" />

    <!-- Container utama halaman -->
    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
        <Navbar />

        <!-- ========================= -->
        <!-- Component Baru -->
        <!-- ========================= -->
        <div class="mt-6">
            <BannerSection />
        </div>

        <div class="mt-6">
            <CardAnnouncement />
        </div>

        <div class="mt-6">
            <CardNews />
        </div>

        <!-- ========================= -->
        <!-- Konten Lama (Tidak Berubah) -->
        <!-- ========================= -->
        <div class="mt-6 bg-white rounded-xl border border-neutral-200 p-8 shadow-sm">
            <h1 class="text-2xl font-bold text-neutral-900 mb-2">Selamat Datang di Portal DPMD</h1>
            <p class="text-neutral-600 mb-6">Ini adalah contoh halaman yang sudah di-slicing menggunakan komponen Navbar dari INA Digital Design System (IDDS).</p>

            <div class="p-6 bg-blue-50 text-blue-800 rounded-lg border border-blue-100">
                Lanjut kerjain komponen lainnya bang!
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 md:p-4">
                <TableDokumen v-if="filteredDokumen.length > 0" :data="filteredDokumen" />
                <div v-else class="p-12 text-center text-gray-500">
                    <p class="text-base font-medium">Dokumen tidak ditemukan</p>
                    <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau periksa kategori dokumen lainnya.</p>
                </div>
            </div>

        </div>
    </div>
</template>
=======
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Header from '../Components/Header.vue';
import { IconMapPin, IconMail, IconPhone, IconBrandFacebook, IconBrandInstagram, IconBrandX, IconBrandYoutube } from '@tabler/icons-vue';

interface Pengumuman {
  id: number;
  judul: string;
  tanggal: string;
}

interface Berita {
  id: number;
  judul: string;
  ringkasan: string;
  tanggal: string;
  penulis: string;
  gambar: string;
  slug: string;
}

const pengumumanList: Pengumuman[] = [
  { id: 1, judul: 'Pembukaan Pendaftaran Musyawarah Desa Tahun 2026', tanggal: '05 Juli 2026' },
  { id: 2, judul: 'Jadwal Pelatihan Aparatur Desa Semester II', tanggal: '01 Juli 2026' },
  { id: 3, judul: 'Sosialisasi Dana Desa Tahap II', tanggal: '28 Juni 2026' },
];

const beritaList: Berita[] = [
  {
    id: 1,
    judul: 'DPMD Gelar Rapat Koordinasi Pemberdayaan Desa',
    ringkasan: 'Rapat koordinasi membahas strategi percepatan pemberdayaan masyarakat desa di seluruh kecamatan.',
    tanggal: '06 Juli 2026',
    penulis: 'Admin DPMD',
    gambar: '/images/berita/berita-1.jpg',
    slug: 'rapat-koordinasi-pemberdayaan-desa',
  },
  {
    id: 2,
    judul: 'Pelatihan Peningkatan Kapasitas Perangkat Desa',
    ringkasan: 'Kegiatan pelatihan diikuti oleh perwakilan perangkat desa dari seluruh wilayah kabupaten.',
    tanggal: '03 Juli 2026',
    penulis: 'Admin DPMD',
    gambar: '/images/berita/berita-2.jpg',
    slug: 'pelatihan-kapasitas-perangkat-desa',
  },
  {
    id: 3,
    judul: 'Monitoring dan Evaluasi Penggunaan Dana Desa',
    ringkasan: 'Tim DPMD melakukan monitoring langsung ke beberapa desa terkait realisasi penggunaan dana desa.',
    tanggal: '30 Juni 2026',
    penulis: 'Admin DPMD',
    gambar: '/images/berita/berita-3.jpg',
    slug: 'monitoring-evaluasi-dana-desa',
  },
];

const kontak = {
  alamat: 'Jl. Contoh Alamat Kantor DPMD No. 1, Bangkalan, Jawa Timur',
  email: 'dpmd@bangkalankab.go.id',
  telepon: '(031) 1234567',
  wa: '0812-3456-7890',
};

const medsosLinks = [
  { label: 'Facebook', icon: IconBrandFacebook, href: 'https://facebook.com/dpmdkab' },
  { label: 'Instagram', icon: IconBrandInstagram, href: 'https://instagram.com/dpmdkab' },
  { label: 'X (Twitter)', icon: IconBrandX, href: 'https://x.com/dpmdkab' },
  { label: 'YouTube', icon: IconBrandYoutube, href: 'https://youtube.com/@dpmdkab' },
];
</script>

<template>
  <Head title="Beranda - DPMD" />

  <div class="min-h-screen bg-white">
    <!-- ================= HEADER / NAVIGASI ================= -->
    <div class="max-w-7xl mx-auto px-4 pt-4">
      <Header />
    </div>

    <!-- ================= HERO / PENGUMUMAN ================= -->
    <section class="max-w-7xl mx-auto px-4 mt-8">
      <div class="rounded-xl border border-neutral-200 p-6 bg-neutral-50">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-neutral-900">Pengumuman</h2>
          <a href="/pengumuman" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <ul class="flex flex-col divide-y divide-neutral-200">
          <li v-for="item in pengumumanList" :key="item.id" class="py-3 flex items-center justify-between gap-4">
            <span class="text-sm text-neutral-800">{{ item.judul }}</span>
            <span class="text-xs text-neutral-500 whitespace-nowrap">{{ item.tanggal }}</span>
          </li>
        </ul>
      </div>
    </section>

    <!-- ================= BERITA TERKINI ================= -->
    <section class="max-w-7xl mx-auto px-4 mt-12">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-neutral-900">Berita Terkini</h2>
        <a href="/berita" class="text-sm text-blue-600 hover:underline">Lihat semua berita</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a
          v-for="berita in beritaList"
          :key="berita.id"
          :href="`/berita/${berita.slug}`"
          class="rounded-xl border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow"
        >
          <img :src="berita.gambar" :alt="berita.judul" class="w-full h-40 object-cover bg-neutral-100" />
          <div class="p-4">
            <p class="text-xs text-neutral-500 mb-1">{{ berita.tanggal }} · {{ berita.penulis }}</p>
            <h3 class="text-sm font-semibold text-neutral-900 mb-2 line-clamp-2">{{ berita.judul }}</h3>
            <p class="text-sm text-neutral-600 line-clamp-2">{{ berita.ringkasan }}</p>
          </div>
        </a>
      </div>
    </section>

    <!-- ================= WIDGET MEDSOS ================= -->
    <section class="max-w-7xl mx-auto px-4 mt-12">
      <div class="rounded-xl border border-neutral-200 p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4">Ikuti Kami di Media Sosial</h2>
        <div class="flex flex-wrap gap-4">
          <a
            v-for="medsos in medsosLinks"
            :key="medsos.label"
            :href="medsos.href"
            target="_blank"
            rel="noopener noreferrer"
            class="flex items-center gap-2 px-4 py-2 rounded-lg border border-neutral-200 text-sm text-neutral-800 hover:bg-neutral-50"
          >
            <component :is="medsos.icon" :size="18" />
            {{ medsos.label }}
          </a>
        </div>
      </div>
    </section>

    <!-- ================= KONTAK ================= -->
    <section class="max-w-7xl mx-auto px-4 mt-12 mb-16">
      <div class="rounded-xl border border-neutral-200 overflow-hidden grid grid-cols-1 md:grid-cols-2">
        <!-- Map -->
        <div class="w-full h-64 md:h-full bg-neutral-100">
          <iframe
            src="https://maps.google.com/maps?q=Bangkalan&t=&z=13&ie=UTF8&iwloc=&output=embed"
            class="w-full h-full border-0"
            loading="lazy"
            title="Lokasi Kantor DPMD"
          />
        </div>

        <!-- Info kontak -->
        <div class="p-6 flex flex-col gap-4">
          <h2 class="text-lg font-semibold text-neutral-900">Kontak Kami</h2>
          <div class="flex items-start gap-3">
            <IconMapPin :size="18" class="text-neutral-500 mt-0.5 shrink-0" />
            <span class="text-sm text-neutral-700">{{ kontak.alamat }}</span>
          </div>
          <div class="flex items-center gap-3">
            <IconMail :size="18" class="text-neutral-500 shrink-0" />
            <a :href="`mailto:${kontak.email}`" class="text-sm text-neutral-700 hover:underline">{{ kontak.email }}</a>
          </div>
          <div class="flex items-center gap-3">
            <IconPhone :size="18" class="text-neutral-500 shrink-0" />
            <span class="text-sm text-neutral-700">{{ kontak.telepon }} / WA {{ kontak.wa }}</span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
>>>>>>> 71bf203bfe13a065f72175758c3bdbaaa95a6f88
