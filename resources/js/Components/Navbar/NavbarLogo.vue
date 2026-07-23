<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>

<div class="flex items-center gap-4">

    <Link href="/" class="flex items-center justify-center shrink-0">
        <img src="/assets/logo-dpmd-bangkalan.png" alt="Logo DPMD Bangkalan" class="h-12 w-auto object-contain" />
    </Link>

    <div>

        <div
            class="text-[10px] sm:text-[11px] uppercase tracking-wider text-neutral-500"
        >
            Pemerintah Kabupaten Bangkalan
        </div>

        <div
            class="font-bold text-base sm:text-xl text-[#0F172A] leading-tight"
        >
            Dinas Pemberdayaan Masyarakat dan Desa
        </div>

        <div
            class="font-bold text-xl text-[#103973]"
        >
            Masyarakat dan Desa
        </div>

    </div>

</div>

</template>