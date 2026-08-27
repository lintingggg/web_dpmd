<script setup>
import { Link } from '@inertiajs/vue3';
import { getImageUrl } from '../../Utils/helpers';

const props = defineProps({
  album: Object
});

function onImageError(event) {
    const img = event.target;
    const fallback = 'data:image/svg+xml;utf8,' + encodeURIComponent(`<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'><rect width='600' height='400' fill='#e5e7eb'/><g fill='#9ca3af'><path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/><circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/></g><text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Cover tidak tersedia</text></svg>`);
    if (img.src.indexOf('data:image/svg+xml') === -1) {
        img.src = fallback;
    }
}
</script>

<template>
  <Link
      :href="`/galeri/${album.id}`"
      class="group flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100"
  >
      <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
          <img
              :src="getImageUrl(album.cover)"
              :alt="album.nama"
              @error="onImageError"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
          />
          <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-300"></div>
      </div>
      <div class="p-5 flex-1 flex flex-col justify-between">
          <div>
              <h3 class="text-gray-900 font-bold text-lg leading-snug mb-2 group-hover:text-blue-600 transition-colors">
                  {{ album.nama }}
              </h3>
              <p class="text-gray-500 text-sm line-clamp-2">
                  {{ album.deskripsi || 'Tidak ada deskripsi.' }}
              </p>
          </div>
          <div class="mt-4 flex items-center gap-2 text-blue-600 font-medium text-sm">
              <span>Lihat Album</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                  <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
          </div>
      </div>
  </Link>
</template>
