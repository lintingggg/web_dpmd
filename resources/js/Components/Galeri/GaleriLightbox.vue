<script setup>
import { onMounted, onUnmounted } from 'vue';
import { IconX } from '@tabler/icons-vue';
import { getMediaUrl, formatDate } from '../../Utils/helpers';

const props = defineProps({
  selectedImage: Object
});

const emit = defineEmits(['close']);

onMounted(() => {
    document.body.style.overflow = 'hidden';
});

onUnmounted(() => {
    document.body.style.overflow = '';
});
</script>

<template>
  <div 
      class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 sm:p-8 backdrop-blur-sm"
      @click="emit('close')"
  >
      <button 
          @click="emit('close')"
          class="absolute top-6 right-6 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 rounded-full p-2 transition-all z-10"
      >
          <IconX class="w-8 h-8" />
      </button>
      
      <div 
          class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center"
          @click.stop
      >
          <img 
              v-if="selectedImage.tipe === 'foto'"
              :src="getMediaUrl(selectedImage.foto)" 
              :alt="selectedImage.judul"
              class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl"
          />
          <iframe v-else-if="selectedImage.tipe === 'video'" :src="getMediaUrl(selectedImage.foto)" class="w-full max-w-5xl aspect-video rounded-lg shadow-2xl bg-black" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen @click.stop></iframe>
          
          <div class="mt-6 text-center">
              <h3 class="text-white text-xl font-medium mb-2">{{ selectedImage.judul }}</h3>
              <span class="text-blue-300 text-sm font-semibold uppercase tracking-wider flex items-center justify-center gap-1.5">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="4" width="18" height="18" rx="2" />
                      <line x1="16" y1="2" x2="16" y2="6" />
                      <line x1="8" y1="2" x2="8" y2="6" />
                      <line x1="3" y1="10" x2="21" y2="10" />
                  </svg>
                  {{ formatDate(selectedImage.tanggal_kegiatan) }}
              </span>
          </div>
      </div>
  </div>
</template>
