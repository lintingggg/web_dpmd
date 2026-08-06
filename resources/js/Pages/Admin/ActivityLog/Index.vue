<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';

function debounce(fn, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), wait);
    };
}

const props = defineProps({
    logs: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

watch(
    search,
    debounce((value) => {
        router.get(
            route('admin.log-aktivitas'),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getActionColor = (action) => {
    const act = action.toLowerCase();
    if (act.includes('membuat')) return 'bg-emerald-50 text-emerald-600 border-emerald-200';
    if (act.includes('menghapus')) return 'bg-rose-50 text-rose-600 border-rose-200';
    if (act.includes('mengubah')) return 'bg-amber-50 text-amber-600 border-amber-200';
    return 'bg-blue-50 text-[#1e56a0] border-blue-200';
};
</script>

<template>
    <Head title="Log Aktivitas - Admin DPMD" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto space-y-6 lg:space-y-8 mt-6">
            
            <!-- Page Title -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Log Aktivitas
                    </h1>
                    <p class="text-sm text-slate-500 mt-1.5 font-medium">Pantau seluruh riwayat perubahan data pada sistem.</p>
                </div>
            </div>

            <div class="bg-white rounded-[1.25rem] border border-slate-200/60 shadow-sm flex flex-col overflow-hidden">
                <!-- Filter Area -->
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white">
                    <!-- Search -->
                    <div class="w-full sm:max-w-md relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Cari aksi, deskripsi, atau nama user..." 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-full pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-[#528be6]/50 focus:border-[#528be6] transition-all outline-none"
                        >
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pengguna</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="log in logs.data" :key="log.id" class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors group cursor-default">
                                <td class="py-4 px-6">
                                    <span class="text-sm font-medium text-slate-700 whitespace-nowrap flex items-center gap-2">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        {{ formatDate(log.created_at) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span :class="getActionColor(log.action)" class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold border uppercase tracking-wider">
                                        {{ log.action }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 flex-shrink-0">
                                            <i class="fa-solid fa-user text-xs"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-slate-900 block">
                                                {{ log.user ? log.user.name : 'Sistem / Anonim' }}
                                            </span>
                                            <div class="text-[11px] text-slate-500 mt-0.5 font-medium" v-if="log.ip_address">
                                                IP: {{ log.ip_address }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm font-bold text-slate-900 block mb-1">
                                        {{ log.description }}
                                    </span>
                                    <div class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5" v-if="log.subject_type">
                                        <i class="fa-solid fa-cube text-slate-300"></i>
                                        {{ log.subject_type.split('\\').pop() }} #{{ log.subject_id }}
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="logs.data.length === 0">
                                <td colspan="4" class="py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                                        <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">Tidak ada data log yang ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white" v-if="logs.last_page > 1">
                    <p class="text-xs font-medium text-slate-500">
                        Menampilkan <span class="font-bold text-slate-900">{{ logs.from }}</span> hingga <span class="font-bold text-slate-900">{{ logs.to }}</span> dari <span class="font-bold text-slate-900">{{ logs.total }}</span> log
                    </p>
                    <div class="flex gap-2">
                        <Link
                            v-for="(link, index) in logs.links"
                            :key="index"
                            :href="link.url"
                            v-html="link.label"
                            class="min-w-[32px] h-8 px-2 flex items-center justify-center rounded-lg text-xs font-bold transition-all outline-none"
                            :class="[
                                link.active 
                                    ? 'bg-[#1e56a0] text-white shadow-sm shadow-[#1e56a0]/20' 
                                    : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900',
                                !link.url && 'opacity-50 cursor-not-allowed'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
