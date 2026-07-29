<template>
  <div class="w-full h-full flex flex-col rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

    <!-- Gambar: flex-1 supaya ikut membesar saat kartu di-stretch grid
         (mis. side card disamakan tinggi dengan hero) -->
    <div class="relative flex-1 min-h-[180px]">
      <img
        :src="resolvedImage"
        :alt="title"
        @error="onImageError"
        class="absolute inset-0 w-full h-full object-cover"
      >
    </div>

    <!-- Konten: shrink-0 supaya tinggi teks tetap natural, ruang ekstra
         dari stretching diserap gambar di atas -->
    <div class="flex flex-col shrink-0 p-5">
      <h3 class="text-base font-bold line-clamp-2 min-h-[3rem] text-neutral-900 mb-1.5">
        {{ title }}
      </h3>

      <p v-if="description" class="text-sm text-neutral-500 line-clamp-1 mb-3">
        {{ description }}
      </p>

      <!-- Tagging (poin 3): sebelumnya cuma ada di hero, sekarang CardNews
           juga support -> muncul di side card, grid bawah, & sidebar detail -->
      <div v-if="tags?.length" class="flex flex-wrap gap-1.5 mb-3">
        <span
          v-for="tag in tags"
          :key="tag"
          class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500"
        >#{{ tag }}</span>
      </div>

      <!-- Footer: penulis + tanggal (poin 4) + tombol panah bulat -->
      <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
        <span class="text-xs tracking-wide text-slate-500 truncate">
          <template v-if="author">{{ author }} • </template>{{ date }}
        </span>

        <button
          v-if="showButton"
          type="button"
          :aria-label="buttonText"
          class="w-8 h-8 shrink-0 flex items-center justify-center rounded-full border border-slate-300 text-slate-700 transition-colors hover:bg-slate-900 hover:text-white hover:border-slate-900"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
            <path d="M7 17L17 7" />
            <path d="M7 7h10v10" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, PropType } from 'vue';

const props = defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
  image: { type: String, default: '' },
  date: { type: String, default: '' },
  author: { type: String, default: '' },
  tags: { type: Array as PropType<string[]>, default: () => [] },
  showButton: { type: Boolean, default: true },
  buttonText: { type: String, default: 'Selengkapnya' },
});

// Placeholder inline SVG (data URI) -> tidak pernah 404 karena tidak minta
// apa pun ke server.
const FALLBACK_SVG = `<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'>
  <rect width='600' height='400' fill='#e5e7eb'/>
  <g fill='#9ca3af'>
    <path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/>
    <circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/>
  </g>
  <text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text>
</svg>`;
const FALLBACK_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(FALLBACK_SVG)}`;

const resolvedImage = ref(props.image || FALLBACK_IMAGE);
watch(() => props.image, (newSrc) => { resolvedImage.value = newSrc || FALLBACK_IMAGE; });

function onImageError(event: Event) {
  const img = event.target as HTMLImageElement;
  if (img.src !== FALLBACK_IMAGE) {
    img.src = FALLBACK_IMAGE;
  }
}
</script>