<script setup>
defineProps({
  title: String,
  description: String,
})
</script>

<template>
  <section class="py-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; letter-spacing: -0.45px; line-height: 1.25;">
      {{ title }}
    </h1>

    <p v-if="description" class="mt-2" style="font-size: 16px; font-weight: 500; color: #646a79; line-height: 1.6;">
      {{ description }}
    </p>
  </section>
</template>