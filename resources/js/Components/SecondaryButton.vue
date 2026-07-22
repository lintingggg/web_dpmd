<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-slate-900 bg-white px-6 py-3 text-sm font-bold text-slate-900 transition-all duration-200 ease-in-out hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200 disabled:opacity-40 disabled:cursor-not-allowed"
        style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.2px;"
    >
        <slot />
    </button>
</template>
