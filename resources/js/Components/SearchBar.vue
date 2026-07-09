<script setup>
import { computed } from 'vue';
import { Button, BasicDropdown, TextField } from '@idds/vue';
import { IconSearch } from '@tabler/icons-vue';

const model = defineModel({ type: String, default: '' });

const props = defineProps({
    options: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Cari sesuatu...'
    }
});

const emit = defineEmits(['onSearch']);

const filteredOptions = computed(() => {
    if (!model.value) return [];
    
    return props.options.filter((opt) =>
        opt.toLowerCase().includes(model.value.toLowerCase())
    );
});

const eksekusiPencarian = () => {
    emit('onSearch', model.value);
};
</script>

<template>
  <div class="flex flex-row gap-4 lg:gap-6 w-full items-start justify-start">
    
    <BasicDropdown class="w-full">
      
      <template #trigger>
        <TextField 
            v-model="model" 
            :placeholder="placeholder" 
            class="w-full"
            @keyup.enter="eksekusiPencarian"
        >
          <template #prefixIcon>
            <IconSearch :size="16" />
          </template>
        </TextField>
      </template>

      <template #content>
        <div class="p-4 flex flex-col max-h-64 overflow-y-auto w-full space-y-2">
          <p class="text-[10px] sm:text-xs text-gray-500 mb-2">Saran Pencarian</p>
          
          <template v-if="filteredOptions.length > 0">
            <div
              v-for="opt in filteredOptions"
              :key="opt"
              class="cursor-pointer hover:bg-gray-50 p-2 rounded transition"
              @mousedown="model = opt; eksekusiPencarian()"
            >
              <h3 class="text-sm md:text-md text-neutral-800">{{ opt }}</h3>
            </div>
          </template>
          
          <template v-else-if="model.length > 0">
            <div class="p-2 text-xs md:text-sm text-gray-400">Tidak ada hasil</div>
          </template>
          
        </div>
      </template>

    </BasicDropdown>

    <Button hierarchy="primary" class="bg-blue-600 text-white" @click="eksekusiPencarian"> 
        Cari 
    </Button>

  </div>
</template>