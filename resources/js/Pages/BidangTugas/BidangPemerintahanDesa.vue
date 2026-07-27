<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
  IconHome,
  IconUser,
  IconMail,
  IconPhone,
  IconBuildingCommunity,
  IconFileDescription,
  IconChevronRight,
} from '@tabler/icons-vue';
import Navbar from '../../Components/Navbar/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import Breadcrumb from '../../Components/Breadcrumb.vue';
import Accordion from '../../Components/Accordion.vue';

const breadcrumbItems = [
  { label: 'Beranda', href: '/', icon: IconHome },
  { label: 'Bidang Tugas', href: '#' },
  { label: 'Pemerintahan Desa' },
];

const dasarFungsi = {
  deskripsi: "[Masukkan deskripsi tugas pokok Bidang Pemerintahan Desa di sini]",
  kalimatPengantar: "Dalam melaksanakan tugas tersebut, Bidang Pemerintahan Desa menyelenggarakan fungsi:",
  listFungsi: [
    "Merumuskan kebijakan teknis di bidang pembinaan aparatur pemerintahan desa, penataan wilayah desa, fasilitasi pemilihan kepala desa, dan administrasi pemerintahan desa.",
    "Mengoordinasikan dan memfasilitasi pembinaan kapasitas kepala desa, perangkat desa, dan Badan Permusyawaratan Desa (BPD) agar penyelenggaraan pemerintahan desa berjalan optimal.",
    "Melakukan fasilitasi dan evaluasi terhadap penyusunan produk hukum desa, serta pembinaan dalam pengelolaan keuangan dan aset desa."
  ]
};

const organisasi = [
  "Seksi Tata Pemerintahan Desa: Fasilitasi penataan desa, pilkades, peningkatan kapasitas aparatur desa, dan pengawasan administrasi pemerintahan desa.",
  "Seksi Keuangan & Aset Desa: Pembinaan dan pengawasan pengelolaan Dana Desa (DD), Alokasi Dana Desa (ADD), dan inventarisasi aset desa."
];
</script>

<template>
  <Head title="Pemerintahan Desa - DPMD Bangkalan" />

  <div class="min-h-screen bg-[#F7F9FC] flex flex-col font-body">
    <Navbar />

    <main class="flex-grow w-full pb-24">

      <!-- ============ HERO ============ -->
      <div class="relative w-full overflow-hidden bg-gradient-to-br from-[#0F1B3D] to-[#528be6]">

        <div class="pointer-events-none absolute -top-24 -right-16 w-[420px] h-[420px] rounded-full bg-[#528be6]/40 blur-3xl float-slow"></div>
        <div class="pointer-events-none absolute bottom-0 left-[8%] w-64 h-64 rounded-full bg-[#F7F9FC]/[0.1] blur-2xl float-slower"></div>

        <svg class="pointer-events-none absolute inset-0 w-full h-full opacity-40" viewBox="0 0 1440 520" preserveAspectRatio="none" fill="none">
          <path class="thread-draw" d="M -40 90 C 260 30, 520 210, 780 130 S 1300 40, 1500 160"
                stroke="url(#threadGradientHero)" stroke-width="1.5" stroke-linecap="round" fill="none" />
          <defs>
            <linearGradient id="threadGradientHero" x1="0" y1="0" x2="1" y2="0">
              <stop offset="0%" stop-color="#ffffff" stop-opacity="0" />
              <stop offset="20%" stop-color="#ffffff" stop-opacity="0.9" />
              <stop offset="80%" stop-color="#ffffff" stop-opacity="0.9" />
              <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
            </linearGradient>
          </defs>
        </svg>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-10 pb-20 lg:pt-16 lg:pb-28">
          <div class="mb-8 custom-breadcrumb-wrapper">
            <Breadcrumb :items="breadcrumbItems" />
          </div>

          <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.07] backdrop-blur-sm border border-white/15 mb-7">
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
              <span class="text-[11px] font-semibold tracking-[0.18em] text-white uppercase">
                Dinas Pemberdayaan Masyarakat dan Desa
              </span>
            </div>

            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-semibold text-white tracking-tight leading-[1.05] mb-7">
              Pemerintahan Desa
            </h1>

            <div class="flex items-start gap-5">
              <span class="mt-2 w-10 h-[3px] rounded-full bg-white shrink-0"></span>
              <p class="text-lg sm:text-xl text-blue-50/90 leading-relaxed font-light max-w-2xl">
                Mempunyai tugas melaksanakan sebagian tugas Kepala Dinas dalam merumuskan kebijakan teknis, mengoordinasikan, memfasilitasi, dan mengevaluasi penyelenggaraan pemerintahan desa dan kelurahan.
              </p>
            </div>

            <div class="flex flex-wrap gap-3 mt-10">
              <a href="#tupoksi" class="px-4 py-2 rounded-full bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-sm font-medium text-white transition-colors">
                Tugas &amp; Fungsi
              </a>
              <a href="#struktur" class="px-4 py-2 rounded-full bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-sm font-medium text-white transition-colors">
                Struktur Sub Bagian
              </a>
              <a href="#profil" class="px-4 py-2 rounded-full bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-sm font-medium text-white transition-colors">
                Profil Kepala Bidang
              </a>
            </div>
          </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-b from-transparent to-[#F7F9FC]"></div>
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
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#0F1B3D] to-[#528be6] flex items-center justify-center text-white shrink-0 shadow-sm">
                    <IconFileDescription size="22" stroke-width="1.75" />
                  </div>
                  <div>
                    <p class="text-[11px] font-semibold tracking-[0.16em] text-[#528be6] uppercase mb-1">Dasar Fungsi</p>
                    <h2 class="font-display text-2xl font-semibold text-[#0F1B3D]">Tugas Pokok &amp; Fungsi</h2>
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
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#0F1B3D] to-[#528be6] flex items-center justify-center text-white shrink-0 shadow-sm">
                    <IconBuildingCommunity size="22" stroke-width="1.75" />
                  </div>
                  <div>
                    <p class="text-[11px] font-semibold tracking-[0.16em] text-[#528be6] uppercase mb-1">Organisasi</p>
                    <h2 class="font-display text-2xl font-semibold text-[#0F1B3D]">Struktur Sub Bagian</h2>
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

          <!-- Right Column — Executive Profile -->
          <div class="lg:col-span-4" id="profil">
            <div class="lg:sticky lg:top-8">
              <div class="relative rounded-2xl overflow-hidden border border-neutral-200/70 shadow-[0_1px_2px_rgba(11,37,69,0.04),0_20px_48px_-20px_rgba(11,37,69,0.2)] bg-white">

                <div class="h-40 relative bg-gradient-to-br from-[#0F1B3D] to-[#528be6] overflow-hidden">
                  <svg class="absolute inset-0 w-full h-full opacity-50" viewBox="0 0 300 160" preserveAspectRatio="none">
                    <path d="M -10 40 C 80 10, 140 90, 230 55 S 320 20, 340 60" stroke="#ffffff" stroke-width="1" fill="none" />
                  </svg>
                  <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20">
                    <span class="text-[10px] font-semibold tracking-widest text-white/90 uppercase">Pejabat Struktural</span>
                  </div>
                </div>

                <div class="px-7 pb-8 relative">
                  <div class="w-32 h-32 bg-white rounded-2xl p-1.5 shadow-lg mx-auto -mt-16 relative z-10 border border-neutral-100">
                    <div class="w-full h-full bg-gradient-to-br from-neutral-100 to-neutral-50 rounded-xl flex items-center justify-center overflow-hidden">
                      <IconUser size="52" class="text-neutral-400" stroke-width="1.5" />
                    </div>
                  </div>

                  <div class="text-center mt-6">
                    <h3 class="font-display text-xl font-semibold text-[#0F1B3D]">[Nama Kepala Bidang]</h3>
                    <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full bg-[#528be6]/10">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#528be6]"></span>
                      <p class="text-[#0F1B3D] font-semibold text-xs uppercase tracking-wide">Kepala Bidang Pemdes</p>
                    </div>
                    <p class="text-neutral-400 text-xs mt-4 font-mono tracking-wide">NIP. 19750101 200001 1 002</p>
                  </div>

                  <div class="h-px bg-gradient-to-r from-transparent via-neutral-200 to-transparent my-7"></div>

                  <div class="space-y-2.5">
                    <a href="tel:0311234568" class="flex items-center gap-4 p-3 rounded-xl hover:bg-[#F7F9FC] border border-transparent hover:border-neutral-200 transition-colors group">
                      <div class="w-10 h-10 rounded-lg bg-[#528be6]/10 text-[#0F1B3D] flex items-center justify-center shrink-0 group-hover:bg-[#0F1B3D] group-hover:text-white transition-colors">
                        <IconPhone size="19" stroke-width="1.5" />
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-neutral-400 uppercase tracking-wide">Telepon</p>
                        <span class="font-semibold text-[#0F1B3D] text-sm">(031) 1234568</span>
                      </div>
                    </a>

                    <a href="mailto:pemdes@bangkalankab.go.id" class="flex items-center gap-4 p-3 rounded-xl hover:bg-[#F7F9FC] border border-transparent hover:border-neutral-200 transition-colors group">
                      <div class="w-10 h-10 rounded-lg bg-[#528be6]/10 text-[#0F1B3D] flex items-center justify-center shrink-0 group-hover:bg-[#0F1B3D] group-hover:text-white transition-colors">
                        <IconMail size="19" stroke-width="1.5" />
                      </div>
                      <div class="overflow-hidden">
                        <p class="text-[10px] font-semibold text-neutral-400 uppercase tracking-wide">Email</p>
                        <span class="font-semibold text-[#0F1B3D] text-sm truncate block">pemdes@bangkalankab.go.id</span>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </main>

    <Footer class="mt-16" />
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

.font-display {
  font-family: 'Fraunces', ui-serif, serif;
  font-optical-sizing: auto;
}
.font-body {
  font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
}

@keyframes floatSlow {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(-14px, 18px); }
}
@keyframes floatSlower {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(10px, -14px); }
}
.float-slow { animation: floatSlow 10s ease-in-out infinite; }
.float-slower { animation: floatSlower 14s ease-in-out infinite; }

.thread-draw {
  stroke-dasharray: 1400;
  stroke-dashoffset: 1400;
  animation: drawThread 2.4s ease-out forwards 0.2s;
}
@keyframes drawThread {
  to { stroke-dashoffset: 0; }
}

@media (prefers-reduced-motion: reduce) {
  .float-slow,
  .float-slower,
  .thread-draw {
    animation: none !important;
    transition: none !important;
    transform: none !important;
    stroke-dashoffset: 0 !important;
  }
}
</style>

<style scoped>
.custom-breadcrumb-wrapper :deep(*) {
  color: rgba(255, 255, 255, 0.9) !important;
}
.custom-breadcrumb-wrapper :deep(a:hover) {
  color: #ffffff !important;
}
.custom-breadcrumb-wrapper :deep(svg) {
  stroke: rgba(255, 255, 255, 0.9) !important;
}
</style>