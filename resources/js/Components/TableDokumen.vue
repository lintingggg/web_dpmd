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

const emit = defineEmits(['lihatDetail']);
</script>

<template>
    <div style="width: 100%; background: #ffffff; border: 1px solid #e3e5e7; border-radius: 16px; overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif;">

        <!-- Loading state -->
        <div v-if="isLoading" style="padding: 48px; display: flex; flex-direction: column; gap: 12px; align-items: center; justify-content: center;">
            <div style="width: 32px; height: 32px; border: 2.5px solid #e3e5e7; border-top-color: #0f172a; border-radius: 9999px; animation: spin 0.8s linear infinite;"></div>
            <span style="font-size: 13px; font-weight: 500; color: #9499a3;">Memuat data...</span>
        </div>

        <!-- Empty state -->
        <div v-else-if="!dataDokumen.length" style="padding: 48px; text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 40px; color: #c8cbd0; display: block; margin-bottom: 12px;">description</span>
            <p style="font-size: 14px; font-weight: 600; color: #0f172a; margin-bottom: 4px;">Belum ada dokumen</p>
            <p style="font-size: 13px; font-weight: 500; color: #9499a3;">Dokumen akan ditampilkan di sini.</p>
        </div>

        <!-- Table -->
        <table v-else style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e3e5e7;">
                    <th style="padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #646a79; letter-spacing: 1px; text-transform: uppercase;">Judul Dokumen</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #646a79; letter-spacing: 1px; text-transform: uppercase;">Kategori</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #646a79; letter-spacing: 1px; text-transform: uppercase;">Tanggal</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #646a79; letter-spacing: 1px; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(row, idx) in dataDokumen"
                    :key="row.id ?? idx"
                    style="border-bottom: 1px solid #f0f1f3; transition: background 0.15s ease;"
                    @mouseenter="$event.currentTarget.style.background = '#f8fafc'"
                    @mouseleave="$event.currentTarget.style.background = 'transparent'"
                >
                    <td style="padding: 14px 16px;">
                        <span style="display: block; font-size: 14px; font-weight: 600; color: #0f172a;">{{ row.judul }}</span>
                        <span v-if="row.deskripsi" style="display: block; font-size: 12px; font-weight: 500; color: #9499a3; margin-top: 2px;">{{ row.deskripsi }}</span>
                    </td>
                    <td style="padding: 14px 16px;">
                        <span style="display: inline-block; padding: 3px 10px; background: #f8fafc; border: 1px solid #e3e5e7; border-radius: 9999px; font-size: 11px; font-weight: 700; color: #373f50; letter-spacing: 0.3px;">
                            {{ row.kategori }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; font-size: 13px; font-weight: 500; color: #646a79;">{{ row.tanggal }}</td>
                    <td style="padding: 14px 16px;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a
                                :href="row.link"
                                :download="row.judul + '.pdf'"
                                style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; border: 1.5px solid #0f172a; border-radius: 9999px; font-size: 12px; font-weight: 700; color: #ffffff; background: #0f172a; cursor: pointer; transition: all 0.15s ease; text-decoration: none;"
                            >
                                <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
                                Download
                            </a>
                            <Link
                                :href="`/dokumen-dan-peraturan/${row.id}`"
                                style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; border: 1.5px solid #e3e5e7; border-radius: 9999px; font-size: 12px; font-weight: 700; color: #0f172a; background: transparent; cursor: pointer; transition: all 0.15s ease; text-decoration: none;"
                            >
                                <span class="material-symbols-outlined" style="font-size: 14px;">visibility</span>
                                Detail
                            </Link>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<style scoped>
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>