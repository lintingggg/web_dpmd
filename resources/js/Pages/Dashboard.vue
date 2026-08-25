<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    totalBerita: Number,
    totalAgenda: Number,
    totalDokumen: Number,
    totalGaleri: Number,
    recentActivities: Array,
    agendaTerdekat: Array,
    visitorStats: Array
});

const maxVisitorCount = computed(() => {
    if (!props.visitorStats || props.visitorStats.length === 0) return 1;
    const max = Math.max(...props.visitorStats.map(s => s.count));
    return max > 0 ? max : 1;
});

const getBarHeight = (count) => {
    const percentage = (count / maxVisitorCount.value) * 100;
    if (count === 0) return '8px';
    return `${Math.max(percentage, 12)}%`;
};

// Format date helper
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

// Helper for icons based on type for recent activities
const getTypeIcon = (type) => {
    switch (type) {
        case 'Berita': return 'fa-solid fa-newspaper';
        case 'Agenda': return 'fa-solid fa-calendar-days';
        case 'Dokumen': return 'fa-solid fa-folder-open';
        case 'Album': 
        case 'Galeri': return 'fa-solid fa-images';
        case 'User': return 'fa-solid fa-user';
        default: return 'fa-solid fa-file';
    }
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto space-y-6 lg:space-y-8 mt-8">
            
            <!-- Page Title & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
                    <p class="text-sm text-slate-500 mt-1.5 font-medium">Kelola, prioritaskan, dan pantau konten portal publik dengan mudah.</p>
                </div>
                <!-- Removed buttons based on user request -->
            </div>

            <!-- Top Metric Cards (Minimalist Palette) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                
                <!-- Card 1: Main Metric (Primary Dark) -->
                <Link :href="route('admin.berita')" class="bg-[#1e56a0] rounded-[1.25rem] p-6 text-white shadow-lg shadow-slate-900/10 relative overflow-hidden group outline-none block">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-colors duration-700"></div>
                    
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Berita</p>
                            <h3 class="text-4xl font-extrabold mb-4 tracking-tight">{{ totalBerita || 0 }}</h3>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm cursor-pointer hover:bg-white/20 transition-colors border border-white/10">
                            <i class="fa-solid fa-newspaper text-xs"></i>
                        </div>
                    </div>
                    <div class="relative z-10 inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md px-2.5 py-1 rounded-md text-[11px] font-semibold border border-white/5">
                        <i class="fa-solid fa-arrow-trend-up text-[10px] text-emerald-400"></i>
                        <span class="text-slate-200">Selalu update konten</span>
                    </div>
                </Link>

                <!-- Card 2: Document Metric -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300 transition-all block outline-none group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-slate-500 text-sm font-medium mb-1">Dokumen Publik</p>
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ totalDokumen || 0 }}</h3>
                        </div>
                        <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-[#528be6] group-hover:text-[#528be6] group-hover:bg-blue-50 transition-colors bg-slate-50">
                            <i class="fa-solid fa-folder-open text-xs"></i>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1.5 text-slate-600 text-[11px] font-semibold bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                        <i class="fa-solid fa-check text-[10px] text-emerald-500"></i>
                        <span>Dipublikasikan</span>
                    </div>
                </div>

                <!-- Card 3: Announcement Metric (Reused for Agenda) -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300 transition-all block outline-none group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-slate-500 text-sm font-medium mb-1">Total Agenda</p>
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ totalAgenda || 0 }}</h3>
                        </div>
                        <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-[#528be6] group-hover:text-[#528be6] group-hover:bg-blue-50 transition-colors bg-slate-50">
                            <i class="fa-solid fa-calendar-days text-xs"></i>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1.5 text-slate-600 text-[11px] font-semibold bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                        <i class="fa-regular fa-clock text-[10px] text-[#1e56a0]"></i>
                        <span>Agenda aktif</span>
                    </div>
                </div>

                <!-- Card 4: Draft Metric (Reused for Galeri) -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300 transition-all block outline-none group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-slate-500 text-sm font-medium mb-1">Album Galeri</p>
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ totalGaleri || 0 }}</h3>
                        </div>
                        <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-[#528be6] group-hover:text-[#528be6] group-hover:bg-blue-50 transition-colors bg-slate-50">
                            <i class="fa-solid fa-images text-xs"></i>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1.5 text-slate-600 text-[11px] font-semibold bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                        <i class="fa-solid fa-image text-[10px] text-[#528be6]"></i>
                        <span>Dokumentasi visual</span>
                    </div>
                </div>
            </div>

            <!-- Middle Section Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Analytics Chart Mockup -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm lg:col-span-2 flex flex-col">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="font-bold text-slate-900 text-lg">Statistik Pengunjung Portal</h3>
                        <select class="bg-slate-50 border border-slate-200 text-slate-600 text-xs rounded-lg px-3 py-1.5 outline-none focus:border-slate-400 font-semibold cursor-pointer">
                            <option>7 Hari Terakhir</option>
                            <option>Bulan Ini</option>
                        </select>
                    </div>
                    
                    <!-- Minimalist Bar Chart -->
                    <div class="flex-1 flex items-end justify-between gap-2 sm:gap-6 mt-auto h-48 px-2 pb-6 border-b border-slate-100 relative">
                        <!-- Bars -->
                        <div v-for="stat in visitorStats" :key="stat.date" class="w-full flex flex-col items-center gap-3 group relative">
                            <!-- Tooltip on Hover -->
                            <div class="absolute bottom-full mb-2 bg-slate-950 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-20">
                                {{ stat.count }} Pengunjung
                            </div>
                            <!-- Bar -->
                            <div :style="{ height: getBarHeight(stat.count) }" 
                                 class="w-full rounded-t-lg transition-all duration-500 relative"
                                 :class="[
                                     stat.count > 0 ? 'bg-[#1e56a0] group-hover:bg-[#528be6] shadow-sm' : 'bg-slate-100 border border-slate-200/50'
                                 ]">
                            </div>
                            <!-- Label -->
                            <span class="text-[11px] text-slate-500 font-bold uppercase">{{ stat.label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Agenda / Reminders Panel -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900 text-lg">Agenda Terdekat</h3>
                        <Link :href="route('admin.agenda.index')" class="text-xs text-[#1e56a0] font-semibold hover:text-[#103973] transition-colors">Lihat Semua</Link>
                    </div>
                    
                    <div class="space-y-3 overflow-y-auto custom-scrollbar flex-1 pr-2">
                        <div v-if="!agendaTerdekat || agendaTerdekat.length === 0" class="text-center text-slate-500 py-6 font-medium text-sm">
                            Belum ada agenda terdekat
                        </div>

                        <div v-for="agenda in agendaTerdekat" :key="agenda.id" class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                            <h4 class="font-bold text-slate-900 text-sm mb-2 leading-tight">{{ agenda.judul }}</h4>
                            <div class="space-y-1.5">
                                <p class="text-[12px] text-slate-500 flex items-center gap-2 font-medium">
                                    <i class="fa-regular fa-calendar text-slate-400 text-[11px] w-3 text-center"></i>
                                    {{ formatDate(agenda.tanggal) }}
                                </p>
                                <p class="text-[12px] text-slate-500 flex items-center gap-2 font-medium">
                                    <i class="fa-regular fa-clock text-slate-400 text-[11px] w-3 text-center"></i>
                                    {{ agenda.waktu_mulai }} - {{ agenda.waktu_selesai }}
                                </p>
                                <p v-if="agenda.lokasi" class="text-[12px] text-slate-500 flex items-start gap-2 font-medium">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-[11px] w-3 text-center mt-0.5"></i>
                                    <span class="flex-1">{{ agenda.lokasi }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Recent Activities List -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900 text-lg">Aktivitas Konten Terbaru</h3>
                        <Link :href="route('admin.log-aktivitas')" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                            Lihat Log
                        </Link>
                    </div>
                    
                    <div class="space-y-3">
                        <div v-if="!recentActivities || recentActivities.length === 0" class="text-center text-slate-500 py-6 font-medium text-sm">
                            Belum ada aktivitas terbaru
                        </div>
                        
                        <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center gap-4 p-3 hover:bg-slate-50 rounded-xl transition-colors group cursor-pointer border border-transparent hover:border-slate-100">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                                <i :class="getTypeIcon(activity.subject_type?.split('\\\\').pop() || 'Lainnya')"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate">{{ activity.description }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5 font-medium">{{ activity.subject_type?.split('\\').pop() || 'Sistem' }} • {{ formatDate(activity.created_at) }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-[#1e56a0] text-white shadow-sm uppercase tracking-wider">
                                    {{ activity.event }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target / Progress Panel (Minimalist Donut) -->
                <div class="bg-white rounded-[1.25rem] p-6 border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <h3 class="font-bold text-slate-900 text-lg mb-6">Distribusi Konten</h3>
                    
                    <!-- Donut Chart -->
                    <div class="relative w-40 h-40 mx-auto flex items-center justify-center mb-6">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background track -->
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#f1f5f9" stroke-width="12"></circle>
                            <!-- Primary progress (dpmd-main / slate-900) -->
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#1e56a0" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="100" stroke-linecap="round"></circle>
                            <!-- Secondary progress (slate-400) -->
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#528be6" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="200" stroke-linecap="round" class="origin-center rotate-[216deg]"></circle>
                        </svg>
                        <!-- Center Text -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center mt-1">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ (totalBerita || 0) + (totalAgenda || 0) + (totalDokumen || 0) }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Total</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center justify-center gap-5 mt-auto">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#1e56a0]"></div>
                            <span class="text-xs text-slate-600 font-semibold">Berita</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#528be6]"></div>
                            <span class="text-xs text-slate-600 font-semibold">Dokumen</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-100 border border-slate-200"></div>
                            <span class="text-xs text-slate-600 font-semibold">Lainnya</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>