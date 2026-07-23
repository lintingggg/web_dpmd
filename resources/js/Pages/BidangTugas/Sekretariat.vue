<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { IconHome, IconBriefcase } from '@tabler/icons-vue';
import Navbar from '../../Components/Navbar/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import Breadcrumb from '../../Components/Breadcrumb.vue';
import PageHeader from '../../Components/PageHeader.vue';

const props = defineProps<{
  bidang: any;
}>();

const breadcrumbItems = [
  { label: 'Beranda', href: '/', icon: IconHome },
  { label: 'Bidang Tugas', href: '#' },
  { label: 'Sekretariat' },
];

const dasarFungsi = {
  deskripsi: "Sekretariat mempunyai tugas melaksanakan pengelolaan surat menyurat, kearsipan, administrasi kepegawaian, keuangan, perlengkapan dan rumah tangga kantor serta pengkoordinasian penyusunan rencana program, evaluasi dan pelaporan.",
  kalimatPengantar: "Dalam melaksanakan tugas tersebut, Sekretariat menyelenggarakan fungsi:",
  listFungsi: [
    "Pelaksanaan pengkoordinasian penyusunan rencana program dan kegiatan;",
    "Pelayanan administrasi umum, ketatausahaan, kearsipan dan dokumentasi dalam rangka menunjang kelancaran pelaksanaan tugas;",
    "Pengelolaan administrasi keuangan dan urusan kepegawaian;",
    "Pengelolaan urusan rumah tangga, perlengkapan dan inventaris kantor;",
    "Pelayanan administrasi perjalanan dinas;",
    "Pelaksanaan pengkoordinasian bidang-bidang di lingkup Dinas Pemberdayaan Masyarakat dan Desa;",
    "Pengkoordinasian dan penyusunan laporan hasil pelaksanaan program dan kegiatan; dan",
    "Pelaksanaan tugas kedinasan lain yang diberikan Kepala Dinas sesuai dengan bidang tugasnya."
  ]
};

const organisasi = [
  "Sub Bagian Umum dan Kepegawaian: Melaksanakan urusan surat menyurat, kearsipan, perlengkapan, rumah tangga, dan administrasi kepegawaian.",
  "Sub Bagian Keuangan dan Perencanaan: Menyusun rencana program, mengelola administrasi keuangan, serta evaluasi dan pelaporan kinerja dinas."
];
</script>

<template>
  <Head title="Sekretariat" />

  <div class="min-h-screen flex flex-col bg-slate-50 font-sans">
    <Navbar />
    
    <div class="pt-24 pb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <Breadcrumb :items="breadcrumbItems" class="mb-6" />
      </div>

      <!-- ============ MAIN CONTENT ============ -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-8">
        <div class="flex flex-col-reverse lg:grid lg:grid-cols-12 gap-8 lg:gap-12">

          <!-- Left Column -->
          <div class="lg:col-span-8 space-y-10">

            <!-- Tugas Pokok & Fungsi -->
            <section id="tupoksi" class="relative">
              <div class="bg-white rounded-2xl shadow-[0_1px_2px_rgba(11,37,69,0.04),0_12px_32px_-16px_rgba(11,37,69,0.15)] border border-neutral-200/70 p-7 sm:p-9">
                <div class="flex items-center gap-4 mb-8 pb-5 border-b border-neutral-100">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#103973] to-[#528be6] flex items-center justify-center text-white shrink-0 shadow-sm">
                    <IconFileDescription size="22" stroke-width="1.75" />
                  </div>
                  <div>
                    <p class="text-[11px] font-semibold tracking-[0.16em] text-[#528be6] uppercase mb-1">Dasar Fungsi</p>
                    <h2 class="font-display text-2xl font-semibold text-[#103973]">Tugas Pokok &amp; Fungsi</h2>
                  </div>
                </div>

                <div class="space-y-4 text-neutral-600 leading-relaxed">
                  <p class="mb-4">{{ dasarFungsi.deskripsi }}</p>
                  <p class="mb-3 font-medium">{{ dasarFungsi.kalimatPengantar }}</p>
                  <ol class="list-decimal pl-5 space-y-2">
                    <li v-for="(fungsi, index) in dasarFungsi.listFungsi" :key="index">
                      {{ fungsi }}
                    </li>
                  </ol>
                </div>
              </div>
            </section>

            <!-- Struktur Sub Bagian -->
            <section id="struktur">
              <div class="bg-white rounded-2xl shadow-[0_1px_2px_rgba(11,37,69,0.04),0_12px_32px_-16px_rgba(11,37,69,0.15)] border border-neutral-200/70 p-7 sm:p-9">
                <div class="flex items-center gap-4 mb-8 pb-5 border-b border-neutral-100">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#103973] to-[#528be6] flex items-center justify-center text-white shrink-0 shadow-sm">
                    <IconBriefcase size="22" stroke-width="1.75" />
                  </div>
                  <div>
                    <p class="text-[11px] font-semibold tracking-[0.16em] text-[#528be6] uppercase mb-1">Organisasi</p>
                    <h2 class="font-display text-2xl font-semibold text-[#103973]">Struktur Sub Bagian</h2>
                  </div>
                </div>

                <div class="space-y-4 text-neutral-600 leading-relaxed">
                  <ul class="list-disc pl-5 space-y-2">
                    <li v-for="(item, index) in organisasi" :key="index">
                      {{ item }}
                    </li>
                  </ul>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
          
          <div v-if="bidang.sekretariat_gambar" class="w-full">
            <img :src="bidang.sekretariat_gambar.startsWith('http') ? bidang.sekretariat_gambar : '/storage/' + bidang.sekretariat_gambar" alt="Gambar Sekretariat" class="w-full h-auto object-cover max-h-[500px]" />
          </div>

          <div class="p-8 md:p-12">
            <div class="prose max-w-none text-slate-700 leading-relaxed" v-html="bidang.sekretariat_konten || '<p>Belum ada informasi kesekretariatan.</p>'"></div>
          </div>

        </div>
      </main>
    </div>

    <Footer class="mt-auto" />
  </div>
</template>