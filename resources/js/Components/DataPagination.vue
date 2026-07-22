<script setup>
import { computed } from 'vue';

const halamanSekarang = defineModel({ type: Number, default: 1 });
const ukuranHalaman = defineModel('pageSize', { type: Number, default: 10 });

const props = defineProps({
    totalPages: {
        type: Number,
        required: true
    },
    pageSizeOptions: {
        type: Array,
        default: () => [10, 20, 50, 100]
    }
});

const emit = defineEmits(['onRefreshData']);

const gantiHalaman = (halamanBaru) => {
    if (halamanBaru < 1 || halamanBaru > props.totalPages) return;
    halamanSekarang.value = halamanBaru;
    emit('onRefreshData');
};

const gantiUkuranHalaman = (e) => {
    ukuranHalaman.value = Number(e.target.value);
    halamanSekarang.value = 1;
    emit('onRefreshData');
};

const visiblePages = computed(() => {
    const pages = [];
    const total = props.totalPages;
    const current = halamanSekarang.value;
    if (total <= 7) {
        for (let i = 1; i <= total; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current > 3) pages.push('...');
        for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i);
        if (current < total - 2) pages.push('...');
        pages.push(total);
    }
    return pages;
});
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full py-4 mt-2 border-t" style="border-color: #e3e5e7; font-family: 'Plus Jakarta Sans', sans-serif;">

        <!-- Page Size Selector -->
        <div class="flex items-center gap-2">
            <span style="font-size: 13px; font-weight: 500; color: #646a79;">Tampilkan</span>
            <select
                :value="ukuranHalaman"
                @change="gantiUkuranHalaman"
                style="
                    height: 36px;
                    padding: 0 32px 0 12px;
                    background: #ffffff;
                    border: 1.5px solid #e3e5e7;
                    border-radius: 10px;
                    font-size: 13px;
                    font-weight: 500;
                    color: #0f172a;
                    outline: none;
                    cursor: pointer;
                    appearance: none;
                    -webkit-appearance: none;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                "
                @focus="$event.target.style.borderColor = '#0f172a'"
                @blur="$event.target.style.borderColor = '#e3e5e7'"
            >
                <option v-for="size in pageSizeOptions" :key="size" :value="size">{{ size }}</option>
            </select>
            <span style="font-size: 13px; font-weight: 500; color: #646a79;">per halaman</span>
        </div>

        <!-- Pagination buttons -->
        <div class="flex items-center gap-1">
            <!-- Prev -->
            <button
                @click="gantiHalaman(halamanSekarang - 1)"
                :disabled="halamanSekarang <= 1"
                class="flex items-center justify-center w-9 h-9 rounded-full transition-all duration-150"
                :style="halamanSekarang <= 1
                    ? 'background: transparent; border: 1.5px solid #e3e5e7; color: #c8cbd0; cursor: not-allowed;'
                    : 'background: transparent; border: 1.5px solid #e3e5e7; color: #646a79; cursor: pointer;'"
                @mouseenter="halamanSekarang > 1 && ($event.target.style.background = '#f8fafc')"
                @mouseleave="$event.target.style.background = 'transparent'"
            >
                <span class="material-symbols-outlined" style="font-size: 18px;">chevron_left</span>
            </button>

            <!-- Pages -->
            <template v-for="page in visiblePages" :key="page">
                <span
                    v-if="page === '...'"
                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 13px; color: #9499a3;"
                >…</span>
                <button
                    v-else
                    @click="gantiHalaman(page)"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-medium transition-all duration-150"
                    :style="page === halamanSekarang
                        ? 'background: #0f172a; color: #ffffff; border: 1.5px solid #0f172a; font-weight: 700; cursor: default;'
                        : 'background: transparent; border: 1.5px solid #e3e5e7; color: #646a79; cursor: pointer;'"
                    @mouseenter="page !== halamanSekarang && ($event.target.style.background = '#f8fafc')"
                    @mouseleave="page !== halamanSekarang && ($event.target.style.background = 'transparent')"
                >
                    {{ page }}
                </button>
            </template>

            <!-- Next -->
            <button
                @click="gantiHalaman(halamanSekarang + 1)"
                :disabled="halamanSekarang >= totalPages"
                class="flex items-center justify-center w-9 h-9 rounded-full transition-all duration-150"
                :style="halamanSekarang >= totalPages
                    ? 'background: transparent; border: 1.5px solid #e3e5e7; color: #c8cbd0; cursor: not-allowed;'
                    : 'background: transparent; border: 1.5px solid #e3e5e7; color: #646a79; cursor: pointer;'"
                @mouseenter="halamanSekarang < totalPages && ($event.target.style.background = '#f8fafc')"
                @mouseleave="$event.target.style.background = 'transparent'"
            >
                <span class="material-symbols-outlined" style="font-size: 18px;">chevron_right</span>
            </button>
        </div>

    </div>
</template>