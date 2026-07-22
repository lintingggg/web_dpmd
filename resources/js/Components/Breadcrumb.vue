<template>
  <nav aria-label="Breadcrumb" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <ol class="flex items-center flex-wrap gap-1 p-5">
      <li v-for="(item, index) in (items || [])" :key="index" class="flex items-center gap-1">
        <!-- Separator -->
        <span v-if="index > 0" class="flex items-center text-slate-400 select-none">
          <IconChevronRight :size="16" />
        </span>
        <!-- Link or current -->
        <a
          v-if="item.href && index < (items || []).length - 1"
          :href="item.href"
          class="breadcrumb-link flex items-center gap-1 text-sm font-medium"
        >
          <component v-if="item.icon" :is="item.icon" :size="14" class="breadcrumb-icon" />
          {{ item.label }}
        </a>
        <span
          v-else
          class="flex items-center gap-1 text-sm font-bold"
          style="color: #0f172a;"
        >
          <component v-if="item.icon" :is="item.icon" :size="14" />
          {{ item.label }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import { IconHome, IconChevronRight } from '@tabler/icons-vue';

interface BreadcrumbItem {
  label: string;
  href?: string;
  icon?: any;
}

defineProps<{
  items?: BreadcrumbItem[];
}>();
</script>

<style scoped>
.breadcrumb-link {
  color: #646a79;
  text-decoration: none;
  transition: color 0.15s ease;
}

.breadcrumb-link:hover {
  color: #0f172a;
}

.breadcrumb-icon {
  color: #9499a3;
  flex-shrink: 0;
}
</style>

