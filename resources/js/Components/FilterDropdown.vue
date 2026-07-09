<script setup>
import { SelectDropdown } from '@idds/vue';

const model = defineModel({ default: '' });

defineProps({
    label: {
        type: String,
        default: 'Filter'
    },
    placeholder: {
        type: String,
        default: 'Pilih salah satu...'
    },
    options: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <div class="w-full max-w-[250px]">
        
        <SelectDropdown
            v-model="model"
            :options="options"
            :label="label"
            :placeholder="placeholder"
            indicator="check"
        />

    </div>
</template>