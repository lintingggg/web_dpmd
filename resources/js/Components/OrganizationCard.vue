<script setup lang="ts">
defineProps<{
    name: string
    position: string
}>()
</script>

<template>
    <div
        class="w-64 rounded-2xl border border-neutral-200 bg-white shadow-sm p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
    >
        <!-- Placeholder Foto -->
        <div
            class="mx-auto h-20 w-20 rounded-full bg-neutral-200 flex items-center justify-center text-xs text-neutral-500"
        >
            FOTO
        </div>

        <div class="mt-4 text-center">
            <h3 class="font-semibold text-neutral-900">
                {{ name }}
            </h3>

            <p class="mt-1 text-sm text-neutral-600">
                {{ position }}
            </p>
        </div>
    </div>
</template>