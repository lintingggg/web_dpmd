<script setup>
import { h } from 'vue';
import { Table, Button } from '@idds/vue';

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

const kolomTabel = [
    {
        header: 'Judul Dokumen',
        accessor: 'judul',
        sortable: true,
        render: (row) => h('div', { class: 'flex flex-col gap-1' }, [
            h('span', { class: 'font-semibold text-neutral-900' }, row.judul),
            h('span', { class: 'text-xs text-neutral-500' }, row.deskripsi || '-')
        ])
    },
    {
        header: 'Kategori',
        accessor: 'kategori',
        sortable: true,
        render: (row) => h('span', { 
            class: 'px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-xs font-medium' 
        }, row.kategori)
    },
    {
        header: 'Tanggal',
        accessor: 'tanggal',
        sortable: true,
    },
    {
        header: 'Aksi',
        accessor: 'id',
        render: (row) => h(Button, {
            hierarchy: 'tertiary',
            size: 'sm',
            class: 'text-blue-600 hover:bg-blue-50',
            onClick: () => alert('Membuka detail dokumen: ' + row.judul)
        }, () => 'Lihat Detail')
    }
];
</script>

<template>
    <div class="w-full bg-white border border-neutral-200 rounded-lg overflow-hidden">
        
        <Table
            :columns="kolomTabel"
            :data="dataDokumen"
            :loading="isLoading"
            :striped="true"
            :showSearch="false"
            rowKey="id"
        />
        
    </div>
</template>