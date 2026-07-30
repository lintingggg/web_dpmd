<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { IconHome } from '@tabler/icons-vue';
import Navbar from '../../Components/Navbar/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import Breadcrumb from '../../Components/Breadcrumb.vue';

const breadcrumbItems = [
  { label: 'Beranda', href: '/', icon: IconHome },
  { label: 'Bidang Tugas', href: '/error404' },
  { label: 'Lembaga Kemasyarakatan' },
];

const props = defineProps<{
    bidang: any;
}>();
</script>

<template>
    <Head title="Lembaga Kemasyarakatan - DPMD Bangkalan" />

    <Navbar />

    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>Pemberdayaan Lembaga Kemasyarakatan</h1>
        </div>
    </section>

    <main class="container page-content">
        <div class="py-2">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <!-- Garis kecil -->
                <hr class="w-16 h-1 bg-blue-500 border-0 rounded mb-6">
                
                <!-- Gambar setelah garis kecil -->
                <img :src="bidang?.lembaga_gambar ? '/storage/' + bidang.lembaga_gambar : 'https://placehold.co/800x400?text=Pemberdayaan+Lembaga+Kemasyarakatan'" alt="Pemberdayaan Lembaga Kemasyarakatan" class="w-full h-auto rounded-xl mb-8 object-cover bg-slate-100 min-h-[200px]" />

                <div class="text-slate-700 leading-relaxed text-lg text-left prose max-w-none" v-html="bidang?.lembaga_konten || '<p>Belum ada konten untuk bidang ini.</p>'">
                </div>
            </div>

        </div>
    </main>

    <Footer />
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page Header */
.page-header {
    background-color: #FFFFFF;
    padding: 20px 0 30px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
}

/* Main Layout */
.page-content {
    margin-bottom: 60px;
}
</style>