<script setup>
import { getMediaUrl, getYoutubeThumbnail, formatDate } from '../../Utils/helpers';

const props = defineProps({
  item: Object
});

const emit = defineEmits(['click']);
</script>

<template>
  <div class="break-inside-avoid relative group rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-white cursor-pointer" @click="emit('click')">
      <div v-if="item.tipe === 'foto'">
          <img
              :src="getMediaUrl(item.foto)"
              :alt="item.judul"
              class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105"
          />
          <!-- Expand Icon Overlay -->
          <div class="absolute top-4 right-4 bg-black/50 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <polyline points="9 21 3 21 3 15"></polyline>
                  <line x1="21" y1="3" x2="14" y2="10"></line>
                  <line x1="3" y1="21" x2="10" y2="14"></line>
              </svg>
          </div>
      </div>
      <div v-else-if="item.tipe === 'video'" class="group/video">
          <img
              :src="getYoutubeThumbnail(getMediaUrl(item.foto))"
              :alt="item.judul"
              class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105"
          />
          <!-- Expand Icon Overlay -->
          <div class="absolute top-4 right-4 bg-black/50 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <polyline points="9 21 3 21 3 15"></polyline>
                  <line x1="21" y1="3" x2="14" y2="10"></line>
                  <line x1="3" y1="21" x2="10" y2="14"></line>
              </svg>
          </div>
          <!-- Play Icon Centered -->
          <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
              <div class="bg-black/60 text-white rounded-full p-4 group-hover/video:scale-110 transition-transform duration-300 shadow-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 ml-1" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8 5v14l11-7z" />
                  </svg>
              </div>
          </div>
      </div>

      <!-- Overlay Info -->
      <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 pointer-events-none">
          <span class="text-blue-300 text-xs font-semibold uppercase tracking-wider mb-2 flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
              </svg>
              {{ formatDate(item.tanggal_kegiatan) }}
          </span>
          <h3 class="text-white font-medium text-lg leading-snug">
              {{ item.judul }}
          </h3>
      </div>
  </div>
</template>
