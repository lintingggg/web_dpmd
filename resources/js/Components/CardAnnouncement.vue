<template>
  <div class="w-full">
    <Card
      variant="horizontal"
      media-position="right"
      :avatar="avatar"
      avatar-alt="DPMD Bangkalan"
      :title="title"
      :description="description"
      :media-src="image"
      :media-alt="title"
      :show-button="showButton"
      :button-text="buttonText"
      button-hierarchy="primary"
    />
  </div>
</template>

<script setup lang="ts">
import { Card } from '@idds/vue';

withDefaults(
  defineProps<{
    title: string;
    description?: string;
    image?: string;
    avatar?: string;
    showButton?: boolean;
    buttonText?: string;
  }>(),
  {
    description: '',
    image: '',
    avatar: 'https://ui-avatars.com/api/?name=DPMD&background=2563eb&color=ffffff',
    showButton: true,
    buttonText: 'Selengkapnya',
  }
);
</script>