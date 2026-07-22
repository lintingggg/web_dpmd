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
  { label: 'Pemerintahan Desa' },
];
</script>

<template>
  <Head title="Bidang Pemerintahan Desa" />

  <div class="min-h-screen flex flex-col bg-slate-50 font-sans">
    <Navbar />
    
    <div class="pt-24 pb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <Breadcrumb :items="breadcrumbItems" class="mb-6" />
      </div>

      <PageHeader 
        title="Bidang Pemerintahan Desa" 
        description="Informasi seputar tugas, fungsi, dan program kerja Bidang Pemerintahan Desa."
      />

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
          
          <div v-if="bidang.pemdes_gambar" class="w-full">
            <img :src="bidang.pemdes_gambar.startsWith('http') ? bidang.pemdes_gambar : '/storage/' + bidang.pemdes_gambar" alt="Gambar Bidang Pemerintahan Desa" class="w-full h-auto object-cover max-h-[500px]" />
          </div>

          <div class="p-8 md:p-12">
            <div class="prose max-w-none text-slate-700 leading-relaxed" v-html="bidang.pemdes_konten || '<p>Belum ada informasi Bidang Pemerintahan Desa.</p>'"></div>
          </div>

        </div>
      </main>
    </div>

    <Footer class="mt-auto" />
  </div>
</template>
