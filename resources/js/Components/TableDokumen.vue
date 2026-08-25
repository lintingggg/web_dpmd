<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
    dataDokumen: {
        type: Array,
        default: () => []
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});

defineEmits(['lihatDetail']);
</script>

<template>
    <div class="w-full font-sans">

        <!-- Loading state -->
        <div v-if="isLoading" class="p-12 flex flex-col gap-3 items-center justify-center bg-white border border-[#dbe6f7] rounded-2xl">
            <div class="w-8 h-8 border-2 border-slate-200 border-t-slate-900 rounded-full animate-spin"></div>
            <span class="text-[13px] font-medium text-slate-400">Memuat data...</span>
        </div>

        <!-- Empty state -->
        <div v-else-if="!dataDokumen.length" class="p-12 text-center bg-white border border-[#dbe6f7] rounded-2xl">
            <span class="material-symbols-outlined text-[40px] text-slate-300 block mb-3">description</span>
            <p class="text-sm font-semibold text-slate-900 mb-1">Belum ada dokumen</p>
            <p class="text-xs font-medium text-slate-400">Dokumen akan ditampilkan di sini.</p>
        </div>

        <div v-else class="space-y-4">
            <!-- Desktop View: Table -->
            <div class="hidden md:block bg-white border border-[#dbe6f7] rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-[#dbe6f7]">
                            <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Judul Dokumen</th>
                            <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                            <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-24">Tahun</th>
                            <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-56 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(row, idx) in dataDokumen"
                            :key="row.id ?? idx"
                            class="border-b border-slate-100 last:border-0 hover:bg-[#f5f8fd]/50 transition-colors"
                        >
                            <td class="py-4 px-6">
                                <span class="block text-sm font-bold text-slate-900 leading-snug">{{ row.judul }}</span>
                                <span v-if="row.deskripsi" class="block text-xs font-medium text-slate-400 mt-1 line-clamp-2">{{ row.deskripsi }}</span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 bg-[#f5f8fd] border border-[#dbe6f7] rounded-full text-[11px] font-bold text-slate-700">
                                    {{ row.kategori }}
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-semibold text-slate-500">{{ row.tahun }}</td>
                            <td class="py-4 px-6">
                                <div class="flex gap-2 items-center justify-end">
                                    <a
                                        :href="row.link"
                                        :download="row.judul + '.pdf'"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white rounded-full text-xs font-bold transition-all active:scale-95 shadow-sm"
                                    >
                                        <span class="material-symbols-outlined text-[15px]">download</span>
                                        Unduh
                                    </a>
                                    <Link
                                        :href="`/dokumen-dan-peraturan/${row.id}`"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-[#dbe6f7] hover:bg-[#eaf1fb] text-slate-700 rounded-full text-xs font-bold transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-[15px]">visibility</span>
                                        Detail
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Card List -->
            <div class="block md:hidden space-y-4">
                <div 
                    v-for="(row, idx) in dataDokumen"
                    :key="row.id ?? idx"
                    class="bg-white border border-[#dbe6f7] rounded-2xl p-5 shadow-sm space-y-4"
                >
                    <!-- Header: Category & Year -->
                    <div class="flex justify-between items-center gap-2">
                        <span class="inline-flex px-2.5 py-0.5 bg-[#f5f8fd] border border-[#dbe6f7] rounded-full text-[10px] font-bold text-slate-600">
                            {{ row.kategori }}
                        </span>
                        <span class="text-xs font-bold text-slate-400">{{ row.tahun }}</span>
                    </div>

                    <!-- Body: Title & Desc -->
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ row.judul }}</h4>
                        <p v-if="row.deskripsi" class="text-xs font-medium text-slate-400 leading-relaxed">{{ row.deskripsi }}</p>
                    </div>

                    <!-- Footer: Action buttons -->
                    <div class="flex gap-2 pt-2">
                        <a
                            :href="row.link"
                            :download="row.judul + '.pdf'"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 bg-gradient-to-r from-[#1356a0] to-[#528be6] text-white rounded-xl text-xs font-bold active:scale-95 shadow-sm"
                        >
                            <span class="material-symbols-outlined text-[15px]">download</span>
                            Unduh PDF
                        </a>
                        <Link
                            :href="`/dokumen-dan-peraturan/${row.id}`"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 border border-[#dbe6f7] bg-white text-slate-700 rounded-xl text-xs font-bold active:scale-95"
                        >
                            <span class="material-symbols-outlined text-[15px]">visibility</span>
                            Detail
                        </Link>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>