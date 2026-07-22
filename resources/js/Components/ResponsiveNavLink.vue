<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-4 pe-4 py-3 border-l-4 border-slate-900 text-start text-sm font-bold text-slate-900 bg-slate-50 focus:outline-none transition duration-150 ease-in-out'
        : 'block w-full ps-4 pe-4 py-3 border-l-4 border-transparent text-start text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:text-slate-900 focus:bg-slate-50 focus:border-slate-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <slot />
    </Link>
</template>
