<script setup>
import TipTapEditor from '@/Components/TipTapEditor.vue';

defineProps({
    modelValue: String,
    label: String,
    error: String,
    infoText: String
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div>
      <label class="block text-[14px] font-bold text-slate-700 mb-2">{{ label }}</label>
      <div class="bg-white rounded-lg border border-[#dbe6f7] overflow-hidden focus-within:border-[#1356a0] transition-colors">
          <TipTapEditor 
              :modelValue="modelValue" 
              @update:modelValue="val => emit('update:modelValue', val)" 
          />
      </div>
      <div v-if="error" class="text-red-500 text-xs mt-1 font-semibold">{{ error }}</div>
      <p v-if="infoText" class="mt-2 text-xs text-slate-500 flex items-center gap-1">
          <span class="material-symbols-outlined text-[14px] text-[#528be6]">lightbulb</span>
          {{ infoText }}
      </p>
  </div>
</template>
