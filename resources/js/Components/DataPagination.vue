<script setup>
import { Pagination as IddsPagination } from '@idds/vue';

const halamanSekarang = defineModel({ type: Number, default: 1 });

const ukuranHalaman = defineModel('pageSize', { type: Number, default: 10 });

defineProps({
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
    halamanSekarang.value = halamanBaru;
    emit('onRefreshData');
};

const gantiUkuranHalaman = (ukuranBaru) => {
    ukuranHalaman.value = ukuranBaru;
    halamanSekarang.value = 1;
    emit('onRefreshData'); 
};
</script>

<template>
    <div class="flex justify-end w-full py-4 mt-2 border-t border-neutral-100">
        
        <IddsPagination
            v-model="halamanSekarang"
            :total-pages="totalPages"
            :page-size="ukuranHalaman"
            :page-size-options="pageSizeOptions"
            @update:model-value="gantiHalaman"
            @page-size-change="gantiUkuranHalaman"
        />

    </div>
</template>