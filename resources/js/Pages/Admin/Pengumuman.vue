<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Dummy Pengumuman Data
const announcements = ref([
    { 
        id: 1, 
        title: 'Pemberitahuan Cuti Bersama Idul Fitri 2026', 
        date: '10 Mar 2026',
        status: 'published'
    },
    { 
        id: 2, 
        title: 'Hasil Seleksi Administrasi Pendamping Desa Tingkat Kabupaten', 
        date: '05 Mar 2026',
        status: 'published'
    },
    { 
        id: 3, 
        title: 'Draft Pengumuman Penyaluran Dana Desa Tahap II', 
        date: '28 Feb 2026',
        status: 'draft'
    }
]);

</script>

<template>
    <Head title="Manajemen Pengumuman" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Pengumuman
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola daftar pengumuman penting yang tampil di halaman depan website.</p>
            </div>
            
            <button class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Pengumuman
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Filter & Search Bar -->
            <div class="p-6 md:p-8 border-b border-[#e3e5e7] flex flex-col md:flex-row gap-6 justify-between items-start md:items-center bg-[#f9f9f9]">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                    <input type="text" placeholder="Cari judul pengumuman..." class="w-full bg-white border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                </div>
                
                <!-- Status Filter -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <span class="text-[11px] font-bold text-[#9499a3] uppercase tracking-[1px]">Status:</span>
                    <select class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c8cbd0]">
                        <option>Semua Status</option>
                        <option>Published</option>
                        <option>Draft</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Judul Pengumuman</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-40">Tanggal Posting</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-32">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        <tr v-for="item in announcements" :key="item.id" class="hover:bg-[#f9f9f9]/50 transition-colors group">
                            
                            <!-- Judul -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#fff8e1] flex items-center justify-center flex-shrink-0 border border-[#ffecb3]">
                                        <span class="material-symbols-outlined text-[#f57f17] text-[20px]">campaign</span>
                                    </div>
                                    <p class="font-bold text-[#0f172a] leading-tight group-hover:text-[#1976d2] transition-colors cursor-pointer line-clamp-2">
                                        {{ item.title }}
                                    </p>
                                </div>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-4 px-6">
                                <p class="text-[13px] font-bold text-[#646a79]">{{ item.date }}</p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span v-if="item.status === 'published'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Published
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f0f1f1] text-[#646a79] border border-[#e3e5e7]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#9499a3]"></span> Draft
                                </span>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button class="w-8 h-8 rounded-full hover:bg-[#e3e5e7] text-[#646a79] flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#e3e5e7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f9f9f9]">
                <p class="text-[13px] font-medium text-[#646a79]">Menampilkan <span class="font-bold text-[#0f172a]">1-3</span> dari <span class="font-bold text-[#0f172a]">12</span> pengumuman</p>
                
                <div class="flex items-center gap-1">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9499a3] hover:text-[#0f172a] hover:bg-[#e3e5e7] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#0f172a] text-white font-bold text-[13px]">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#646a79] font-medium text-[13px] hover:bg-[#e3e5e7] transition-colors">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9499a3] hover:text-[#0f172a] hover:bg-[#e3e5e7] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
