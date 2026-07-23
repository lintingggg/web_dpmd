<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full h-11 px-4 bg-white border border-slate-50 rounded-2xl text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-colors duration-200 focus:outline-none focus:border-slate-900 focus:ring-0"
        style="font-family: 'Plus Jakarta Sans', sans-serif;"
        v-model="model"
        ref="input"
    />
</template>
