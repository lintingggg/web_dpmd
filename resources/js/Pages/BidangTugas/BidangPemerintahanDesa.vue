<script setup>
import { Head } from '@inertiajs/vue3';
import { IconHome } from '@tabler/icons-vue';
import Navbar from '../../Components/Navbar/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import PageHeader from '../../Components/PageHeader.vue';
import ProseViewer from '../../Components/ProseViewer.vue';

const breadcrumbItems = [
  { label: 'Beranda', href: '/', icon: IconHome },
  { label: 'Bidang Tugas', href: '/error404' },
  { label: 'Pemerintahan Desa' },
];

const props = defineProps({
    bidang: Object
});
</script>

<template>
    <Head title="Pemerintahan Desa - DPMD Bangkalan" />

    <Navbar />

    <main class="container page-content pt-8">
        <PageHeader 
            :breadcrumbs="breadcrumbItems"
            title="Pemerintahan Desa"
        />

        <div class="py-2">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <!-- Garis kecil -->
                <hr class="w-16 h-1 bg-blue-500 border-0 rounded mb-6">
                
                <!-- Gambar setelah garis kecil -->
                <img :src="bidang?.pemdes_gambar ? '/storage/' + bidang.pemdes_gambar : 'https://placehold.co/800x400?text=Pemerintahan+Desa'" alt="Pemerintahan Desa" class="w-full h-auto rounded-xl mb-8 object-cover bg-slate-100 min-h-[200px]" />

                <ProseViewer :html="bidang?.pemdes_konten" placeholder="Belum ada konten untuk bidang ini." />
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

.page-content {
    margin-bottom: 60px;
}
</style>