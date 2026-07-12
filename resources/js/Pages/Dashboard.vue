<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">Dashboard Ringkasan</h2>
                <p class="text-[14px] font-medium text-[#646a79]">Overview performa dan aktivitas CMS DPMD Bangkalan</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">
                <button class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Berita
                </button>
                <button class="bg-transparent border-2 border-[#0f172a] text-[#0f172a] hover:bg-[#e3e5e7] font-bold py-2 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">campaign</span>
                    Pengumuman
                </button>
                <button class="bg-transparent border-2 border-[#0f172a] text-[#0f172a] hover:bg-[#e3e5e7] font-bold py-2 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">upload_file</span>
                    Dokumen
                </button>
            </div>
        </div>

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat Card 1 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Total Berita</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">24</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">newspaper</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[#646a79] text-[11px] font-medium tracking-[2.75px] uppercase relative z-10">
                    <span class="flex items-center text-[#000000]"><span class="material-symbols-outlined text-[14px]">arrow_upward</span> +2</span> bulan ini
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">newspaper</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Pengumuman Aktif</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">5</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">campaign</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[#646a79] text-[11px] font-medium tracking-[2.75px] uppercase relative z-10">
                    <span class="flex items-center text-[#7c818e]"><span class="material-symbols-outlined text-[14px]">horizontal_rule</span> Stabil</span>
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform -rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">campaign</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Total Dokumen</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">120</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">folder</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[#646a79] text-[11px] font-medium tracking-[2.75px] uppercase relative z-10">
                    <span class="flex items-center text-[#000000]"><span class="material-symbols-outlined text-[14px]">arrow_upward</span> +15</span> bulan ini
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">folder</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Pengunjung</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">1,240</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">groups</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[#646a79] text-[11px] font-medium tracking-[2.75px] uppercase relative z-10">
                    <span class="flex items-center text-[#000000]"><span class="material-symbols-outlined text-[14px]">arrow_upward</span> +12%</span> vs bulan lalu
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform -rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">groups</span>
                </div>
            </div>
        </div>

        <!-- Activity Table -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden mt-8">
            <div class="p-6 border-b border-[#e3e5e7] flex justify-between items-center">
                <h3 class="text-[18px] font-bold text-[#0f172a]">Aktivitas Terbaru</h3>
                <button class="text-[#646a79] hover:text-[#0f172a] text-[11px] font-bold tracking-[2.75px] uppercase px-4 py-2 rounded-full hover:bg-[#e3e5e7] transition-colors">Lihat Semua</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#e3e5e7]/50">
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px]">Konten</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px]">Aksi</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px]">Admin</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        
                        <!-- Row 1 -->
                        <tr class="hover:bg-[#e3e5e7]/30 transition-colors group cursor-pointer">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#dae2fd]/50 text-[#0f172a] flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">newspaper</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0f172a] mb-0.5">Rapat Koordinasi Pembangunan Desa</p>
                                        <p class="text-[#646a79] text-[11px] tracking-[1px] uppercase">Berita</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#e3e5e7] text-[#0f172a]">
                                    Dipublikasikan
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[#4d5464]">Budi Santoso</td>
                            <td class="py-4 px-6 text-right text-[#646a79]">2 jam yang lalu</td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="hover:bg-[#e3e5e7]/30 transition-colors group cursor-pointer">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#c8cbd0]/30 text-[#0f172a] flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">folder</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0f172a] mb-0.5">Laporan Kinerja Triwulan III</p>
                                        <p class="text-[#646a79] text-[11px] tracking-[1px] uppercase">Dokumen</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#e3e5e7] text-[#0f172a]">
                                    Diunggah
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[#4d5464]">Siti Aminah</td>
                            <td class="py-4 px-6 text-right text-[#646a79]">5 jam yang lalu</td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-[#e3e5e7]/30 transition-colors group cursor-pointer">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#ffdad6]/50 text-[#ba1a1a] flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">campaign</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0f172a] mb-0.5">Peringatan: Jadwal Pemeliharaan Server</p>
                                        <p class="text-[#646a79] text-[11px] tracking-[1px] uppercase">Pengumuman</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#ffdad6] text-[#ba1a1a]">
                                    Dihapus
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[#4d5464]">Admin Utama</td>
                            <td class="py-4 px-6 text-right text-[#646a79]">Kemarin, 14:30</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>