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
                <!-- Teks Maklumat -->
                <div class="prose max-w-none text-slate-700 leading-relaxed text-lg prose-li:my-2 prose-ul:list-disc prose-ol:list-decimal prose-ul:pl-6 prose-ol:pl-6 text-left mb-10" v-if="props.profil.maklumat_teks" v-html="props.profil.maklumat_teks"></div>
                <div class="text-slate-600 text-lg text-left mb-10" v-else>
                    <p>(Teks Maklumat Pelayanan Belum Tersedia)</p>
                </div>
                
                <!-- Dokumen PDF Maklumat -->
                <div v-if="props.profil.maklumat_dokumen" class="mt-8 border-t border-slate-200 pt-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6">Dokumen Resmi Maklumat Pelayanan</h3>
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-semibold text-gray-700 text-sm">PDF Maklumat Pelayanan</span>
                            </div>
                            <a :href="'/storage/' + props.profil.maklumat_dokumen" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">Buka di Tab Baru</a>
                        </div>
                        <div class="flex-grow bg-gray-100 relative" style="min-height: 600px;">
                            <iframe 
                                :src="'/storage/' + props.profil.maklumat_dokumen" 
                                class="absolute inset-0 w-full h-full border-0" 
                                title="PDF Viewer"
                            ></iframe>
                        </div>
                    </div>
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
