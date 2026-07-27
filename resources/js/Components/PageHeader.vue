<script setup>
import Breadcrumb from '@/Components/Breadcrumb.vue';
defineProps({
  title: String,
  description: String,
  breadcrumbs: {
    type: Array,
    default: () => []
  }
})
</script>

<template>
  <section class="pt-0 pb-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <Breadcrumb v-if="breadcrumbs && breadcrumbs.length" :items="breadcrumbs" class="mb-4 -ml-5" />
    <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; letter-spacing: -0.45px; line-height: 1.25;">
      {{ title }}
    </h1>

    <p v-if="description" class="mt-2" style="font-size: 16px; font-weight: 500; color: #646a79; line-height: 1.6;">
      {{ description }}
    </p>
  </section>
</template>