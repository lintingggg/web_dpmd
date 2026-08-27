<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { IconChevronRight } from '@tabler/icons-vue';

// Import Domain Components
import DokumenViewer from '@/Components/Dokumen/DokumenViewer.vue';
import DokumenSidebar from '@/Components/Dokumen/DokumenSidebar.vue';

// Import Helpers
import { formatDokumenCategory } from '@/Utils/helpers';

const props = defineProps({
    dokumen: Object
});

const pdfUrl = computed(() => {
    return props.dokumen?.file_dokumen ? `/storage/${props.dokumen.file_dokumen}` : '';
});

const formattedCategory = computed(() => {
    return formatDokumenCategory(props.dokumen?.kategori);
});
</script>

<template>
    <Head :title="`${dokumen.judul} - DPMD Bangkalan`" />

    <div class="min-h-screen flex flex-col bg-[#F8FAFC] font-['Plus_Jakarta_Sans',sans-serif]">
        <Navbar />

        <main class="flex-grow pt-10 pb-20 px-4 md:px-8">
            <div class="max-w-7xl mx-auto w-full">
                
                <!-- Breadcrumbs & Category -->
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
                    <Link href="/dokumen-dan-peraturan" class="hover:text-blue-600 transition-colors">Dokumen</Link>
                    <IconChevronRight :size="14" class="text-gray-400" />
                    <span>{{ formattedCategory }}</span>
                    <IconChevronRight :size="14" class="text-gray-400" />
                    <span class="font-medium text-gray-700">Tahun {{ dokumen.tahun }}</span>
                </div>

                <!-- Title & Description -->
                <div class="mb-10">
                    <h1 class="text-3xl md:text-5xl font-bold text-[#0F172A] leading-tight mb-4 tracking-tight">
                        {{ dokumen.judul }}
                    </h1>
                    <div class="w-20 h-1 bg-blue-600 mb-6"></div>
                    <p v-if="dokumen.deskripsi" class="text-base md:text-lg text-gray-600 max-w-3xl leading-relaxed">
                        {{ dokumen.deskripsi }}
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Domain Component PDF Viewer -->
                    <div class="lg:col-span-2">
                        <DokumenViewer :pdf-url="pdfUrl" />
                    </div>

                    <!-- Right Column: Domain Component Sidebar Actions & Info -->
                    <div class="lg:col-span-1">
                        <DokumenSidebar :dokumen="dokumen" :pdf-url="pdfUrl" />
                    </div>
                </div>

            </div>
        </main>

        <Footer />
    </div>
</template>
