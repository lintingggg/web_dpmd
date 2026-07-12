<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2.5 text-start text-sm font-medium leading-5 text-slate-700 transition duration-150 ease-in-out hover:bg-slate-50 hover:text-slate-900 focus:bg-slate-50 focus:text-slate-900 focus:outline-none"
        style="font-family: 'Plus Jakarta Sans', sans-serif;"
    >
        <slot />
    </Link>
</template>
