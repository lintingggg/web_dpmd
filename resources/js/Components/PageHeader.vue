<script setup>
defineProps({
  title: String,
  description: String,
})
</script>

<template>
  <section class="py-8">
    <h1 class="text-3xl font-bold text-neutral-900">
      {{ title }}
    </h1>

    <p v-if="description" class="mt-2 text-neutral-600">
      {{ description }}
    </p>
  </section>
</template>