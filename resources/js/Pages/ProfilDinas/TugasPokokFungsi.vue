<script setup>
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ProseViewer from "@/Components/ProseViewer.vue";
import PdfViewer from "@/Components/PdfViewer.vue";
import { IconHome } from '@tabler/icons-vue';

const props = defineProps({
    profil: Object
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Profil Dinas', href: '/error404'  },
    { label: 'Tugas Pokok & Fungsi' }
];
</script>

<template>
    <Head title="Tugas Pokok & Fungsi - DPMD Bangkalan" />

    <Navbar />

    <main class="container page-content pt-8">
        <PageHeader 
            :breadcrumbs="breadcrumbItems"
            title="Tugas Pokok & Fungsi"
            description="Informasi mengenai tugas pokok dan fungsi Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
        />

        <div class="py-2">
            <!-- Tupoksi Content Block -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <!-- Teks Tupoksi -->
                <ProseViewer :html="props.profil.tupoksi_teks" placeholder="(Data Tugas Pokok & Fungsi Belum Tersedia)" class="mb-10" />

                <!-- Dokumen PDF Tupoksi -->
                <div v-if="props.profil.tupoksi_dokumen" class="mt-8 border-t border-slate-200 pt-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6">Dokumen Tugas Pokok & Fungsi</h3>
                    <PdfViewer :pdf-url="'/storage/' + props.profil.tupoksi_dokumen" title="PDF Tugas Pokok & Fungsi" />
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

.page-content {
    margin-bottom: 60px;
}
</style>
