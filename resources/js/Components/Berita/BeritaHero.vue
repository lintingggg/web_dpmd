<script setup>
import { Link } from '@inertiajs/vue3';
import CardNews from '../CardNews.vue';
import { getImageUrl, getDescription, formatDateTime } from '../../Utils/helpers';

defineProps({
  heroBerita: Object,
  sideBerita: Object
});

function onImageError(event) {
    const img = event.target;
    const fallback = 'data:image/svg+xml;utf8,' + encodeURIComponent(`<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'><rect width='600' height='400' fill='#e5e7eb'/><g fill='#9ca3af'><path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/><circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/></g><text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text></svg>`);
    if (img.src.indexOf('data:image/svg+xml') === -1) {
        img.src = fallback;
    }
}
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div v-if="heroBerita" class="lg:col-span-2">
          <Link :href="`/berita/${heroBerita.slug}`" class="block h-full">
              <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-md group aspect-[4/3] md:aspect-[16/9] flex flex-col justify-end p-6 md:p-8 transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-2xl cursor-pointer">
                  <img
                      :src="getImageUrl(heroBerita)"
                      @error="onImageError"
                      class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-300 group-hover:scale-105"
                      alt="Gambar berita utama"
                  >
                  <div class="relative z-10">
                      <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-3">
                          BERITA UTAMA
                      </span>
                      <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-3">
                          {{ heroBerita.judul }}
                      </h2>
                      <p v-if="getDescription(heroBerita)" class="text-sm text-gray-300 max-w-2xl mb-4 line-clamp-3">
                          {{ getDescription(heroBerita) }}
                      </p>

                      <!-- Tagging -->
                      <div v-if="heroBerita.tags?.length" class="flex flex-wrap gap-1.5 mb-3">
                          <span
                              v-for="tag in heroBerita.tags"
                              :key="tag"
                              class="text-[11px] px-2 py-0.5 rounded-full bg-white/10 text-gray-200"
                          >#{{ tag }}</span>
                      </div>

                      <div class="flex items-center space-x-2 text-xs text-gray-400">
                          <span>Oleh: <strong>{{ heroBerita.penulis || 'Humas DPMD' }}</strong></span>
                          <span>•</span>
                          <span>{{ formatDateTime(heroBerita.published_at) }}</span>
                      </div>
                  </div>
              </div>
          </Link>
      </div>

      <div v-if="sideBerita" class="lg:col-span-1">
          <Link :href="`/berita/${sideBerita.slug}`" class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer">
              <CardNews
                  :title="sideBerita.judul"
                  :description="getDescription(sideBerita)"
                  :date="formatDateTime(sideBerita.published_at)"
                  :author="sideBerita.penulis || 'Humas DPMD'"
                  :tags="sideBerita.tags"
                  :image="getImageUrl(sideBerita)"
              />
          </Link>
      </div>
  </div>
</template>
