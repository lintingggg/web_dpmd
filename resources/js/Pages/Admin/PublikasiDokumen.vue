<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const activeTab = ref('perencanaan');

// Dummy data for documents
const documents = ref([
    { id: 1, title: 'Rencana Strategis DPMD 2024–2029', year: '2024', type: 'perencanaan' },
    { id: 2, title: 'Laporan Kinerja Instansi Pemerintah (LAKIP) 2023', year: '2023', type: 'perencanaan' },
    { id: 3, title: 'Perjanjian Kinerja Tahun 2024', year: '2024', type: 'perencanaan' },
    { id: 4, title: 'Peraturan Daerah No. 5 Tahun 2023 tentang Desa', year: '2023', type: 'peraturan' },
    { id: 5, title: 'SOP Pelayanan Pencairan Dana Desa', year: '2024', type: 'lainnya' },
]);

const getFilteredDocuments = () => {
    return documents.value.filter(doc => doc.type === activeTab.value);
};
</script>

<template>
    <Head title="Publikasi Dokumen" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 text-[13px] font-medium text-[#646a79] mb-2">
                    <span>Beranda</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span>Publikasi Dokumen</span>
                </div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Publikasi Dokumen
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola file dokumen PDF publik seperti Renstra, Peraturan, dan SOP.</p>
            </div>
            
            <button class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Dokumen
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Tabs Navigation -->
            <div class="px-6 md:px-8 border-b border-[#e3e5e7] flex gap-8">
                <button 
                    @click="activeTab = 'perencanaan'"
                    class="py-4 text-[14px] font-bold border-b-2 transition-colors"
                    :class="activeTab === 'perencanaan' ? 'border-[#0f172a] text-[#0f172a]' : 'border-transparent text-[#9499a3] hover:text-[#0f172a]'"
                >
                    Dokumen Perencanaan
                </button>
                <button 
                    @click="activeTab = 'peraturan'"
                    class="py-4 text-[14px] font-bold border-b-2 transition-colors"
                    :class="activeTab === 'peraturan' ? 'border-[#0f172a] text-[#0f172a]' : 'border-transparent text-[#9499a3] hover:text-[#0f172a]'"
                >
                    Produk Peraturan
                </button>
                <button 
                    @click="activeTab = 'lainnya'"
                    class="py-4 text-[14px] font-bold border-b-2 transition-colors"
                    :class="activeTab === 'lainnya' ? 'border-[#0f172a] text-[#0f172a]' : 'border-transparent text-[#9499a3] hover:text-[#0f172a]'"
                >
                    Dokumen Lainnya
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#f9f9f9]">
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Judul Dokumen</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-center w-32">Tahun</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-right w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        <tr v-for="doc in getFilteredDocuments()" :key="doc.id" class="hover:bg-[#f9f9f9]/50 transition-colors group">
                            <!-- Judul -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#ffebee] flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[#d32f2f] text-[22px]">picture_as_pdf</span>
                                    </div>
                                    <p class="font-bold text-[#0f172a] leading-tight group-hover:text-[#1976d2] transition-colors cursor-pointer">{{ doc.title }}</p>
                                </div>
                            </td>
                            
                            <!-- Tahun -->
                            <td class="py-4 px-6 text-center text-[#646a79] font-semibold">
                                {{ doc.year }}
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button class="w-8 h-8 rounded-full hover:bg-[#e3e5e7] text-[#646a79] flex items-center justify-center transition-colors" title="Download">
                                        <span class="material-symbols-outlined text-[18px]">download</span>
                                    </button>
                                    <button class="w-8 h-8 rounded-full hover:bg-[#e3e5e7] text-[#646a79] flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Empty State -->
                        <tr v-if="getFilteredDocuments().length === 0">
                            <td colspan="3" class="py-12 text-center text-[#646a79]">
                                <span class="material-symbols-outlined text-[48px] text-[#c8cbd0] mb-2 block">folder_off</span>
                                Belum ada dokumen di kategori ini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#e3e5e7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f9f9f9]">
                <p class="text-[13px] font-medium text-[#646a79]">Menampilkan <span class="font-bold text-[#0f172a]">1-3</span> dari <span class="font-bold text-[#0f172a]">24</span> dokumen</p>
                
                <div class="flex items-center gap-1">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9499a3] hover:text-[#0f172a] hover:bg-[#e3e5e7] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#0f172a] text-white font-bold text-[13px]">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#646a79] font-medium text-[13px] hover:bg-[#e3e5e7] transition-colors">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#646a79] font-medium text-[13px] hover:bg-[#e3e5e7] transition-colors">3</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9499a3] hover:text-[#0f172a] hover:bg-[#e3e5e7] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
