<script setup>
import FormFileInput from './FormFileInput.vue';

defineProps({
    modelValue: [Object, File],
    label: {
        type: String,
        default: 'Klik untuk unggah atau ganti dokumen PDF'
    },
    error: String,
    infoText: String,
    accept: {
        type: String,
        default: 'application/pdf'
    },
    maxSizeText: {
        type: String,
        default: 'Hanya format PDF (Max 10MB)'
    }
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <FormFileInput 
      :modelValue="modelValue"
      @update:modelValue="val => emit('update:modelValue', val)"
      :label="label"
      :accept="accept"
      :max-size-text="maxSizeText"
      icon="upload_file"
      :error="error"
      :info-text="infoText"
  />
</template>
