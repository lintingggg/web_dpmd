<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: [Object, File],
    label: {
        type: String,
        default: 'Pilih File'
    },
    accept: {
        type: String,
        default: 'image/jpeg,image/png,image/webp'
    },
    maxSizeText: {
        type: String,
        default: 'Maks 1MB'
    },
    icon: {
        type: String,
        default: 'image'
    },
    error: String,
    infoText: String
});

const emit = defineEmits(['update:modelValue']);
const nativeInput = ref(null);

const handleFile = (e) => {
    const file = e.target.files[0] || null;
    emit('update:modelValue', file);
};

const clearFile = () => {
    if (nativeInput.value) nativeInput.value.value = '';
    emit('update:modelValue', null);
};

defineExpose({
    clearFile
});
</script>

<template>
  <div>
      <div class="relative w-full border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-4 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors min-h-[120px]">
          <span class="material-symbols-outlined text-[24px] text-slate-500 mb-1">{{ icon }}</span>
          <p class="text-[12px] font-medium text-slate-700 mb-1 px-4 truncate max-w-full">
              <span v-if="modelValue">{{ modelValue.name || 'File terpilih' }}</span>
              <span v-else>{{ label }} ({{ maxSizeText }})</span>
          </p>
          <input 
              type="file" 
              ref="nativeInput" 
              @change="handleFile" 
              :accept="accept" 
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
          />
      </div>
      <div v-if="error" class="text-red-500 text-xs mt-1 font-semibold">{{ error }}</div>
      <div v-if="infoText" class="mt-2 text-[12px] font-semibold text-slate-700 flex items-center gap-1">
          <span class="material-symbols-outlined text-[14px]">info</span>
          <span>{{ infoText }}</span>
      </div>
  </div>
</template>
