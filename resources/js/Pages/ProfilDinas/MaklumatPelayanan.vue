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
    { label: 'Maklumat Pelayanan' }
];
</script>

<template>
    <Head title="Maklumat Pelayanan - DPMD Bangkalan" />

    <Navbar />

    <main class="container page-content pt-8">
        <PageHeader 
            :breadcrumbs="breadcrumbItems"
            title="Maklumat Pelayanan"
            description="Maklumat pelayanan dan komitmen dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan."
        />

        <div class="py-2">
            <!-- Content Block -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <!-- Teks Maklumat -->
                <ProseViewer :html="props.profil.maklumat_teks" placeholder="(Teks Maklumat Pelayanan Belum Tersedia)" class="mb-10" />
                
                <!-- Dokumen PDF Maklumat -->
                <div v-if="props.profil.maklumat_dokumen" class="mt-8 border-t border-slate-200 pt-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6">Dokumen Resmi Maklumat Pelayanan</h3>
                    <PdfViewer :pdf-url="'/storage/' + props.profil.maklumat_dokumen" title="PDF Maklumat Pelayanan" />
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
