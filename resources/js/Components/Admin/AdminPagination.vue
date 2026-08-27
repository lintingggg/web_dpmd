<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
    from: Number,
    to: Number,
    total: Number,
    label: {
        type: String,
        default: 'data'
    }
});
</script>

<template>
  <div class="p-6 md:px-8 border-t border-[#dbe6f7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f5f8fd]">
      <p class="text-[13px] font-medium text-slate-500">
          Menampilkan <span class="font-bold text-slate-900">{{ from || 0 }}-{{ to || 0 }}</span> dari <span class="font-bold text-slate-900">{{ total }}</span> {{ label }}
      </p>
      
      <div class="flex flex-wrap items-center gap-1" v-if="links && links.length > 3">
          <Link 
              v-for="(link, index) in links" 
              :key="index"
              :href="link.url || '#'"
              class="min-w-[32px] px-3 py-1.5 flex items-center justify-center rounded-lg font-medium text-[13px] transition-colors whitespace-nowrap"
              :class="[
                  link.active ? 'bg-[#1356a0] text-white font-bold' : 'text-slate-500 hover:bg-[#dbe6f7]',
                  !link.url ? 'opacity-50 cursor-not-allowed' : ''
              ]"
              v-html="link.label"
              preserve-scroll
          />
      </div>
  </div>
</template>
