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
  { label: 'Lembaga Kemasyarakatan' },
];

const dasarFungsi = {
  deskripsi: "Bidang Kelembagaan dan Adat Istiadat mempunyai tugas melaksanakan penyusunan bahan perumusan kebijakan teknis, pembinaan kelembagaan desa, dan pelestarian adat istiadat serta budaya masyarakat desa.",
  kalimatPengantar: "Dalam melaksanakan tugas tersebut, Bidang Kelembagaan dan Adat Istiadat menyelenggarakan fungsi:",
  listFungsi: [
    "Penyusunan bahan perumusan kebijakan teknis kelembagaan desa dan adat istiadat;",
    "Pelaksanaan fasilitasi pembentukan dan pengembangan lembaga adat desa;",
    "Pelaksanaan pembinaan nilai-nilai sosial budaya dan adat istiadat masyarakat desa;",
    "Pelaksanaan inventarisasi dan pelestarian aset budaya serta adat istiadat desa;",
    "Pelaksanaan koordinasi dengan tokoh adat dan lembaga terkait; dan",
    "Pelaksanaan tugas kedinasan lain yang diberikan Kepala Dinas sesuai dengan bidang tugasnya."
  ]
};

const organisasi = [
  "Seksi Kelembagaan Desa: Membina dan mengembangkan kelembagaan pemerintahan dan kemasyarakatan desa.",
  "Seksi Adat Istiadat dan Budaya: Melestarikan nilai-nilai adat istiadat dan budaya lokal masyarakat desa."
];
</script>

<template>
  <Head title="Bidang Pemberdayaan Lembaga Kemasyarakatan" />

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
                    <IconUsersGroup size="22" stroke-width="1.75" />
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
                    <IconHearts size="22" stroke-width="1.75" />
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
          
          <div v-if="bidang.lembaga_gambar" class="w-full">
            <img :src="bidang.lembaga_gambar.startsWith('http') ? bidang.lembaga_gambar : '/storage/' + bidang.lembaga_gambar" alt="Gambar Bidang Pemberdayaan Lembaga Kemasyarakatan" class="w-full h-auto object-cover max-h-[500px]" />
          </div>

          <div class="p-8 md:p-12">
            <div class="prose max-w-none text-slate-700 leading-relaxed" v-html="bidang.lembaga_konten || '<p>Belum ada informasi Bidang Pemberdayaan Lembaga Kemasyarakatan.</p>'"></div>
          </div>

        </div>
      </main>
    </div>

    <Footer class="mt-auto" />
  </div>
</template>
