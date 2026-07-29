<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { IconChevronRight, IconDownload } from '@tabler/icons-vue';

const props = defineProps<{
    dokumen: any;
}>();

const pdfUrl = computed(() => {
    return props.dokumen?.file_dokumen ? `/storage/${props.dokumen.file_dokumen}` : '';
});

const formattedCategory = computed(() => {
    if (props.dokumen?.kategori === 'perencanaan') return 'Dokumen Perencanaan';
    if (props.dokumen?.kategori === 'peraturan') return 'Produk Peraturan';
    return 'Dokumen Lainnya';
});

const downloadDoc = () => {
    if (!pdfUrl.value) return;
    const a = document.createElement('a');
    a.href = pdfUrl.value;
    a.download = props.dokumen.judul + '.pdf';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
};
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
                    <!-- Left Column: PDF Viewer -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-semibold text-gray-700 text-sm">PDF Document</span>
                            </div>
                            <a v-if="pdfUrl" :href="pdfUrl" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">Buka di Tab Baru</a>
                        </div>
                        <div class="flex-grow bg-gray-100 relative">
                            <iframe 
                                v-if="pdfUrl" 
                                :src="pdfUrl" 
                                class="absolute inset-0 w-full h-full border-0" 
                                title="PDF Viewer"
                            ></iframe>
                            <div v-else class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p>File dokumen belum tersedia.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sidebar Actions & Info -->
                    <div class="space-y-6">
                        <!-- Download Box -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-sm font-bold text-[#0F172A] mb-4 uppercase tracking-wider">Download</h3>
                            <button 
                                @click="downloadDoc"
                                :disabled="!pdfUrl"
                                class="w-full flex items-center justify-center gap-3 bg-[#0F172A] hover:bg-blue-900 text-white py-3.5 px-4 rounded-xl font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed group"
                            >
                                <IconDownload :size="18" class="group-hover:-translate-y-0.5 transition-transform" />
                                Download Dokumen
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-3 font-medium">Format: PDF</p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-sm font-bold text-[#0F172A] mb-5 uppercase tracking-wider">Informasi Dokumen</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Kategori</p>
                                    <p class="font-semibold text-gray-800">{{ formattedCategory }}</p>
                                </div>
                                <div class="w-full h-px bg-gray-100"></div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Jenis</p>
                                    <p class="font-semibold text-gray-800">{{ dokumen.kategori === 'peraturan' ? 'Produk Hukum' : 'Laporan Resmi' }}</p>
                                </div>
                                <div class="w-full h-px bg-gray-100"></div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Tahun</p>
                                    <p class="font-semibold text-gray-800">{{ dokumen.tahun }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <Footer />
    </div>
</template>
