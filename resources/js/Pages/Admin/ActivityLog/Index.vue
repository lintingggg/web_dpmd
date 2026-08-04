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
    if (act.includes('membuat')) return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    if (act.includes('menghapus')) return 'bg-red-100 text-red-700 border-red-200';
    if (act.includes('mengubah')) return 'bg-amber-100 text-amber-700 border-amber-200';
    return 'bg-blue-100 text-blue-700 border-blue-200';
};
</script>

<template>
    <Head title="Log Aktivitas - Admin DPMD" />

    <AuthenticatedLayout>
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Log Aktivitas
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Pantau seluruh riwayat perubahan data pada sistem.</p>
            </div>
        </div>

        <!-- Filter Area -->
        <div class="bg-white rounded-t-[24px] border border-b-0 border-[#e3e5e7] p-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <!-- Search -->
            <div class="w-full sm:max-w-md relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                <input 
                    v-model="search"
                    type="text" 
                    placeholder="Cari aksi, deskripsi, atau nama user..." 
                    class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-full pl-11 pr-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all"
                >
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-b-[24px] border border-[#e3e5e7] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#e3e5e7] bg-[#f9f9f9]">
                            <th class="py-4 px-6 text-[13px] font-bold text-[#373f50] whitespace-nowrap">Waktu</th>
                            <th class="py-4 px-6 text-[13px] font-bold text-[#373f50] whitespace-nowrap">Aksi</th>
                            <th class="py-4 px-6 text-[13px] font-bold text-[#373f50] whitespace-nowrap">Pengguna</th>
                            <th class="py-4 px-6 text-[13px] font-bold text-[#373f50]">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="log in logs.data" :key="log.id" class="border-b border-[#e3e5e7] hover:bg-[#f9f9f9] transition-colors">
                            <td class="py-4 px-6">
                                <span class="text-[14px] font-medium text-[#373f50] whitespace-nowrap">
                                    {{ formatDate(log.created_at) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span :class="getActionColor(log.action)" class="inline-flex items-center px-2.5 py-1 rounded-md text-[12px] font-bold border">
                                    {{ log.action }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-[14px] font-bold text-[#0f172a]">
                                    {{ log.user ? log.user.name : 'Sistem / Anonim' }}
                                </span>
                                <div class="text-[12px] text-[#646a79] mt-0.5" v-if="log.ip_address">
                                    IP: {{ log.ip_address }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-[14px] text-[#373f50]">
                                    {{ log.description }}
                                </span>
                                <div class="text-[11px] text-[#9499a3] mt-1 font-mono" v-if="log.subject_type">
                                    {{ log.subject_type.split('\\').pop() }} #{{ log.subject_id }}
                                </div>
                            </td>
                        </tr>
                        <tr v-if="logs.data.length === 0">
                            <td colspan="4" class="py-12 text-center text-[#646a79] font-medium">
                                Tidak ada data log yang ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#e3e5e7] flex items-center justify-between" v-if="logs.last_page > 1">
                <p class="text-[13px] font-medium text-[#646a79]">
                    Menampilkan <span class="font-bold text-[#0f172a]">{{ logs.from }}</span> hingga <span class="font-bold text-[#0f172a]">{{ logs.to }}</span> dari <span class="font-bold text-[#0f172a]">{{ logs.total }}</span> log
                </p>
                <div class="flex gap-2">
                    <Link
                        v-for="(link, index) in logs.links"
                        :key="index"
                        :href="link.url"
                        v-html="link.label"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[13px] font-bold transition-all"
                        :class="[
                            link.active 
                                ? 'bg-[#0f172a] text-white shadow-sm' 
                                : 'bg-white border border-[#e3e5e7] text-[#373f50] hover:bg-[#f9f9f9]',
                            !link.url && 'opacity-50 cursor-not-allowed'
                        ]"
                    />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
