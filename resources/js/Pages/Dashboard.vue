<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    totalBerita: Number,
    totalAgenda: Number,
    totalDokumen: Number,
    totalGaleri: Number,
    recentActivities: Array
});

// Format date helper
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

// Helper for icons based on type
const getTypeIcon = (type) => {
    switch (type) {
        case 'Berita': return 'newspaper';
        case 'Agenda': return 'event';
        case 'Dokumen': return 'folder';
        case 'Galeri': return 'collections';
        default: return 'article';
    }
};

const getTypeColorClass = (type) => {
    switch (type) {
        case 'Berita': return 'bg-[#dae2fd]/50 text-[#1976d2]';
        case 'Agenda': return 'bg-[#e6f4ea] text-[#137333]';
        case 'Dokumen': return 'bg-[#fce8e6] text-[#c5221f]';
        case 'Galeri': return 'bg-[#fef7e0] text-[#f29900]';
        default: return 'bg-[#f0f1f1] text-[#646a79]';
    }
};
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
        </div>

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Total Berita</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">{{ totalBerita }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">newspaper</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">newspaper</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Total Agenda</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">{{ totalAgenda }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">event</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform -rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">event</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Total Dokumen</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">{{ totalDokumen }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">folder</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">folder</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] relative overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <p class="text-[14px] font-medium text-[#646a79] mb-1">Total Galeri</p>
                        <h3 class="text-[48px] leading-[60px] font-bold text-[#0f172a]">{{ totalGaleri }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#e3e5e7] flex items-center justify-center text-[#0f172a] group-hover:bg-[#0f172a] group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">collections</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 text-[#e3e5e7] opacity-[0.3] transform -rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">collections</span>
                </div>
            </div>
        </div>

        <!-- Main Content Card (Same pattern as Berita) -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Table Header -->
            <div class="p-6 md:p-8 border-b border-[#e3e5e7] flex justify-between items-center bg-[#f9f9f9]">
                <h3 class="text-[18px] font-bold text-[#0f172a]">Aktivitas Terbaru</h3>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Konten</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-32">Status</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-48">Admin</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-right w-48">Waktu Pembaruan</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        
                        <tr v-for="(activity, index) in recentActivities" :key="index" class="hover:bg-[#f9f9f9]/50 transition-colors group cursor-pointer">
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 border border-[#e3e5e7]" :class="getTypeColorClass(activity.type)">
                                        <span class="material-symbols-outlined text-[20px]">{{ getTypeIcon(activity.type) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0f172a] leading-tight mb-0.5 group-hover:text-[#1976d2] transition-colors line-clamp-1">
                                            {{ activity.title }}
                                        </p>
                                        <p class="text-[#646a79] text-[11px] tracking-[1px] uppercase">{{ activity.type }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-[0.5px] uppercase bg-[#f0f1f1] text-[#373f50]">
                                    {{ activity.status }}
                                </span>
                            </td>
                            
                            <td class="py-4 px-6 text-[#0f172a] font-bold">
                                {{ activity.author }}
                            </td>
                            
                            <td class="py-4 px-6 md:px-8 text-right">
                                <p class="text-[#0f172a] font-bold mb-0.5">{{ activity.time }}</p>
                                <p class="text-[11px] text-[#646a79]">{{ formatDate(activity.updated_at) }}</p>
                            </td>
                        </tr>

                        <tr v-if="!recentActivities || recentActivities.length === 0">
                            <td colspan="4" class="py-12 text-center text-[#646a79]">
                                <span class="material-symbols-outlined text-[48px] text-[#c8cbd0] mb-2 block">history</span>
                                Belum ada aktivitas tercatat.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Footer empty space -->
            <div class="p-4 border-t border-[#e3e5e7] bg-[#f9f9f9]"></div>
        </div>
    </AuthenticatedLayout>
</template>