<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';

import { formatDateTime } from '@/Utils/formatDate';

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
            { preserveState: true, replace: true, only: ['logs', 'filters'] }
        );
    }, 300)
);

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
        <!-- Page Header Top -->
        <div class="mb-8 pt-4">
            <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1">
                Log Aktivitas
            </h2>
            <p class="text-[14px] font-medium text-slate-500">Pantau seluruh riwayat riwayat perubahan data pada sistem.</p>
        </div>

        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            <!-- Filter / Search Area -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-[#f5f8fd]">
                <!-- Search -->
                <div class="w-full md:w-96 relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[20px]">search</span>
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Cari aksi, deskripsi, atau nama..." 
                        class="w-full bg-white border border-[#dbe6f7] text-slate-900 text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]"
                    >
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#dbe6f7] bg-[#f5f8fd]">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[1px] whitespace-nowrap">Waktu</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[1px] whitespace-nowrap">Aksi</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[1px] whitespace-nowrap">Pengguna</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="log in logs.data" :key="log.id" class="border-b border-[#dbe6f7] hover:bg-[#f5f8fd]/50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="text-[13px] font-semibold text-slate-700 whitespace-nowrap flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">schedule</span>
                                    {{ formatDateTime(log.created_at) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span :class="getActionColor(log.action)" class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold border uppercase tracking-wider">
                                    {{ log.action }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-[#dbe6f7] flex items-center justify-center text-slate-500 flex-shrink-0">
                                        <span class="material-symbols-outlined text-[16px]">person</span>
                                    </div>
                                    <div>
                                        <span class="text-[13px] font-bold text-slate-900 block">
                                            {{ log.user ? log.user.name : 'Sistem / Anonim' }}
                                        </span>
                                        <div class="text-[11px] text-slate-500 mt-0.5 font-medium" v-if="log.ip_address">
                                            IP: {{ log.ip_address }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-[13px] font-bold text-slate-900 block mb-1">
                                    {{ log.description }}
                                </span>
                                <div class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5" v-if="log.subject_type">
                                    <span class="material-symbols-outlined text-[14px] text-slate-400 font-bold">database</span>
                                    {{ log.subject_type.split('\\').pop() }} #{{ log.subject_id }}
                                </div>
                            </td>
                        </tr>
                        <tr v-if="logs.data.length === 0">
                            <td colspan="4" class="py-16 text-center">
                                <div class="w-16 h-16 bg-[#f5f8fd] border border-[#dbe6f7] rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                                    <span class="material-symbols-outlined text-[28px]">history</span>
                                </div>
                                <p class="text-slate-500 font-medium">Tidak ada data log yang ditemukan.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 md:px-8 border-t border-[#dbe6f7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f5f8fd]" v-if="logs.last_page > 1">
                <p class="text-[13px] font-medium text-slate-500">
                    Menampilkan <span class="font-bold text-slate-900">{{ logs.from }}</span> hingga <span class="font-bold text-slate-900">{{ logs.to }}</span> dari <span class="font-bold text-slate-900">{{ logs.total }}</span> log
                </p>
                <div class="flex flex-wrap items-center gap-1">
                    <Link
                        v-for="(link, index) in logs.links"
                        :key="index"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="min-w-[32px] px-3 py-1.5 flex items-center justify-center rounded-lg font-medium text-[13px] transition-colors whitespace-nowrap"
                        :class="[
                            link.active 
                                ? 'bg-[#1356a0] text-white font-bold' 
                                : 'text-slate-500 hover:bg-[#dbe6f7]',
                            !link.url && 'opacity-50 cursor-not-allowed'
                        ]"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
