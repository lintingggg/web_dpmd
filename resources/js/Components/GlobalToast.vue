<script setup>
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from '@idds/vue';

const page = usePage();
const { toast } = useToast();

watch(() => page.props.flash, (flash) => {
    if (flash?.message) {
        toast({
            state: 'positive',
            title: 'Berhasil',
            description: flash.message,
            duration: 3000
        });
    }
    
    if (flash?.error) {
        toast({
            state: 'destructive',
            title: 'Gagal',
            description: flash.error,
            duration: 5000
        });
    }
}, { deep: true, immediate: true });

watch(() => page.props.errors, (errors) => {
    if (errors && Object.keys(errors).length > 0) {
        toast({
            state: 'destructive',
            title: 'Validasi Gagal',
            description: 'Terdapat kesalahan pada form. Silakan periksa kembali isian Anda.',
            duration: 5000
        });
    }
}, { deep: true, immediate: true });
</script>

<template>
  <!-- Komponen ini tidak me-render apapun secara visual, hanya menangani logika Toast global -->
</template>
