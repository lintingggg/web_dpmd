<script setup>
const props = defineProps({
    modelValue: Boolean,
    title: {
        type: String,
        default: 'Publikasikan'
    },
    description: String
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div class="pt-2">
      <label class="flex items-center gap-3 cursor-pointer group w-max select-none">
          <div class="relative flex items-center">
              <input 
                  type="checkbox" 
                  :checked="modelValue" 
                  @change="emit('update:modelValue', $event.target.checked)"
                  class="peer sr-only"
              >
              <div class="w-11 h-6 bg-[#c7dafa] rounded-full peer-checked:bg-[#1356a0] transition-colors duration-200 ease-in-out"></div>
              <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform duration-200 ease-in-out shadow-sm"></div>
          </div>
          <div class="flex flex-col">
              <span class="text-[14px] font-bold text-slate-900 leading-none mb-1">{{ title }}</span>
              <span v-if="description" class="text-[12px] text-slate-500 leading-none">{{ description }}</span>
          </div>
      </label>
  </div>
</template>
