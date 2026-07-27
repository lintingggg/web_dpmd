<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { IconHome } from '@tabler/icons-vue';

const props = defineProps<{
    profil: any;
}>();

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Profil Dinas', href: '/error404'  },
    { label: 'Maklumat Pelayanan' }
];
</script>

<template>
    <Head title="Maklumat Pelayanan - DPMD Bangkalan" />

    <Navbar />

    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>Maklumat Pelayanan</h1>
        </div>
    </section>

    <main class="container page-content">
        <!-- Content Area -->
        <div class="py-2">
            
            <p class="text-left text-slate-700 mb-10 text-lg leading-relaxed">
                Maklumat pelayanan dan komitmen dari Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan.
            </p>

            <!-- Content Block -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <div class="prose max-w-none text-slate-700 leading-relaxed text-lg prose-li:my-2 prose-ul:list-disc prose-ol:list-decimal prose-ul:pl-6 prose-ol:pl-6 text-left" v-if="props.profil.maklumat_teks" v-html="props.profil.maklumat_teks"></div>
                <div class="text-slate-600 text-lg text-left" v-else>
                    <p>(Data Maklumat Pelayanan Belum Tersedia)</p>
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

/* Prose styles */
.prose :deep(ol), .prose :deep(ul) {
    padding-left: 1.5rem;
    list-style-position: outside;
}
.prose :deep(ol) {
    list-style-type: decimal;
}
.prose :deep(ul) {
    list-style-type: disc;
}
.prose :deep(li) {
    margin-bottom: 0.75rem;
}
.prose :deep(p) {
    margin-bottom: 1rem;
}
.prose :deep(h2), .prose :deep(h3), .prose :deep(h4) {
    color: #0F172A;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
</style>
