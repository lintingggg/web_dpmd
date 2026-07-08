<script setup>
import { ref, computed } from 'vue';
import { Button, TextField, Avatar, BasicDropdown } from '@idds/vue';
import { IconSearch, IconBell } from '@tabler/icons-vue';

// Data untuk contoh search bar
const searchOptions = ['Dashboard', 'Data Desa', 'Proposal', 'Laporan', 'Pengaturan'];
const value = ref('');

const filteredOptions = computed(() => {
  return searchOptions.filter((opt) => opt.toLowerCase().includes(value.value.toLowerCase()));
});
</script>

<template>
  <nav class="flex flex-row items-center gap-2 justify-between rounded-lg border border-neutral-200 p-4 w-full bg-white shadow-sm">
    
    <!-- Logo (Sementara pakai teks biar ngga error) -->
    <div class="font-bold text-xl text-blue-600 tracking-tight">
      DPMD<span class="text-neutral-900">.</span>
    </div>

    <!-- Navigation -->
    <div class="hidden md:flex gap-6 items-center">
      <a href="#" class="text-neutral-900 text-sm font-medium hover:text-blue-600 transition"> Beranda </a>
      <a href="#" class="text-neutral-900 text-sm font-medium hover:text-blue-600 transition"> Profil </a>
      <a href="#" class="text-neutral-900 text-sm font-medium hover:text-blue-600 transition"> Layanan Desa </a>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2 justify-center h-fit">
      
      <!-- Search Dropdown -->
      <div class="flex-1 flex items-center hidden md:flex">
        <BasicDropdown class="w-full max-w-30">
          <template #trigger>
            <TextField
              v-model="value"
              placeholder="Cari..."
              class="w-full max-w-30 h-[32px]!"
              size="sm"
            >
              <template #prefixIcon>
                <IconSearch :size="16" />
              </template>
            </TextField>
          </template>
          <template #content>
            <div class="p-1 flex flex-col max-h-60 overflow-y-auto w-full space-y-2 cursor-pointer">
              <template v-if="filteredOptions.length > 0">
                <div v-for="opt in filteredOptions" :key="opt" @mousedown="value = opt" class="p-2 hover:bg-gray-50 rounded text-sm">
                  {{ opt }}
                </div>
              </template>
              <template v-else>
                <div class="p-2 text-sm text-gray-500">Tidak ada hasil</div>
              </template>
            </div>
          </template>
        </BasicDropdown>
      </div>

      <!-- Buttons & Avatar -->
      <Button hierarchy="primary" size="md" class="bg-blue-600 text-white">Buat Laporan</Button>
      
      <Button hierarchy="tertiary" size="md" class="text-gray-600">
        <IconBell :size="20" />
      </Button>
      
      <Button hierarchy="tertiary" size="md" class="p-0!">
        <!-- Avatar tanpa import src statis biar aman -->
        <Avatar alt="Admin DPMD" />
      </Button>
    </div>
  </nav>
</template>