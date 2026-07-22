<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="flex items-center gap-1.5 mt-1.5">
        <span class="material-symbols-outlined" style="font-size: 14px; color: #ba1a1a;">error</span>
        <p class="text-xs font-medium" style="color: #ba1a1a; font-family: 'Plus Jakarta Sans', sans-serif;">
            {{ message }}
        </p>
    </div>
</template>
