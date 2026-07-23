<template>
  <div class="w-full">
    <Card
      variant="basic"
      mediaPosition="top"
      :mediaSrc="image"
      :mediaAlt="title"
      :showButton="showButton"
      :buttonText="buttonText"
      buttonHierarchy="secondary"
    >
      <template #title>
        <div class="flex items-center gap-2 mb-1" v-if="category || date">
          <span
            v-if="category"
            class="text-xs font-semibold uppercase tracking-wide text-blue-600"
          >
            {{ category }}
          </span>
          <span v-if="category && date" class="text-neutral-300">•</span>
          <span v-if="date" class="text-xs text-neutral-500">{{ date }}</span>
        </div>

        <h3 class="text-base font-semibold line-clamp-2 text-neutral-900">
          {{ title }}
        </h3>
      </template>

      <template #description>
        <p class="text-sm text-neutral-600 line-clamp-2">
          {{ description }}
        </p>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { Card } from '@idds/vue';

defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
  image: { type: String, default: '' },
  category: { type: String, default: '' },
  date: { type: String, default: '' },
  showButton: { type: Boolean, default: true },
  buttonText: { type: String, default: 'Selengkapnya' },
});
</script>