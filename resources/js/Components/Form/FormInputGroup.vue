<script setup>
defineProps({
    modelValue: [String, Number],
    label: String,
    icon: String,
    error: String,
    type: {
        type: String,
        default: 'text'
    },
    placeholder: String,
    pattern: String,
    title: String,
    required: Boolean
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div>
      <label class="block text-[13px] font-bold text-slate-700 uppercase tracking-[0.5px] mb-2">{{ label }} <span v-if="required" class="text-red-500">*</span></label>
      <div class="relative">
          <span v-if="icon" class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">{{ icon }}</span>
          <input 
              :value="modelValue" 
              @input="emit('update:modelValue', $event.target.value)"
              :type="type" 
              :placeholder="placeholder"
              :pattern="pattern"
              :title="title"
              :required="required"
              class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] font-medium rounded-xl pr-4 py-3 focus:ring-[#1356a0] focus:border-[#1356a0] focus:bg-white transition-colors"
              :class="icon ? 'pl-11' : 'pl-4'"
          />
      </div>
      <div v-if="error" class="text-red-500 text-xs mt-1 font-semibold">{{ error }}</div>
  </div>
</template>
