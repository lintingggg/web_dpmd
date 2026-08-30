<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>

<div class="flex items-center gap-4">

    <Link href="/" class="flex items-center justify-center shrink-0">
        <picture>
            <source srcset="/assets/logo-kabupaten.webp" type="image/webp">
            <img src="/assets/Logo-kabupaten-Bangkalan.jpg"
                 alt="Logo DPMD Bangkalan"
                 class="h-16 w-auto object-contain"
                 width="64" height="64"
                 fetchpriority="high" />
        </picture>
    </Link>

    <div>

        <div
            class="text-[10px] sm:text-[11px] uppercase tracking-wider text-white/80 whitespace-nowrap"
        >
            Pemerintah Kabupaten Bangkalan
        </div>

        <div
            class="font-bold text-base sm:text-xl text-white leading-tight whitespace-nowrap"
        >
            Dinas Pemberdayaan 
        </div>

        <div
            class="font-bold text-base sm:text-xl text-[#2563eb] leading-tight whitespace-nowrap"
        >
            Masyarakat dan Desa
        </div>

    </div>

</div>

</template>