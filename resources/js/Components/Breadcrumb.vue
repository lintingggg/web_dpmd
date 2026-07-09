<template>
  <div class="p-5">
    <Breadcrumb :items="items">
      <template #separator>
        <IconChevronRight :size="16" />
      </template>
    </Breadcrumb>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IconHome, IconChevronRight } from '@tabler/icons-vue';
import { Breadcrumb } from '@idds/vue';

const items = ref([
  { label: 'Home', href: '/', icon: IconHome },
  { label: 'Products', href: '/products' },
  { label: 'Electronics', href: '/products/electronics' },
  { label: 'Detail' },
]);
</script>

