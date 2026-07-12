<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-sm font-medium" style="color: #373f50; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 6px;">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
