<script setup>
defineProps({
  title: String,
  description: String
});
</script>

<template>
  <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
      <div>
          <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1 pt-4">
              {{ title }}
          </h2>
          <p class="text-[14px] font-medium text-slate-500">{{ description }}</p>
      </div>
      
      <div class="flex-shrink-0">
          <slot />
      </div>
  </div>
</template>
