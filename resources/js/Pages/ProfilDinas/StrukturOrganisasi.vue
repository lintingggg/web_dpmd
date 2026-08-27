<script setup>
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ProseViewer from "@/Components/ProseViewer.vue";
import { IconHome } from '@tabler/icons-vue';

const props = defineProps({
    profil: Object
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Profil Dinas', href: '/error404'  },
    { label: 'Struktur Organisasi' }
];
</script>

<template>
    <Head title="Struktur Organisasi - DPMD Bangkalan" />

    <Navbar />

    <main class="container page-content pt-8">
        <PageHeader 
            :breadcrumbs="breadcrumbItems"
            title="Struktur Organisasi"
            description="Struktur organisasi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
        />

        <div class="py-2">
            <!-- Struktur Organisasi Block -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <!-- Image -->
                <div v-if="props.profil.struktur_gambar" class="mb-10 text-center">
                    <img :src="props.profil.struktur_gambar.startsWith('http') ? props.profil.struktur_gambar : '/storage/' + props.profil.struktur_gambar" 
                         alt="Bagan Struktur Organisasi DPMD" 
                         class="max-w-full h-auto rounded-xl shadow-md mx-auto border border-slate-100">
                </div>
                
                <!-- Keterangan -->
                <ProseViewer :html="props.profil.struktur_keterangan" placeholder="(Data Struktur Organisasi Belum Tersedia)" />
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
