<script setup>
defineProps({
    previewUrl: String,
    label: String,
    aspectRatio: {
        type: String,
        default: 'aspect-[16/9]'
    },
    error: String,
    maxSizeText: {
        type: String,
        default: 'Rekomendasi 16:9 (Maks 1MB)'
    }
});

const emit = defineEmits(['change']);

const onFileSelect = (e) => {
    const file = e.target.files[0] || null;
    if (file) {
        emit('change', file);
    }
};
</script>

<template>
  <div class="relative bg-[#f5f8fd] p-6 rounded-xl border border-[#e2ebfa]">
      <slot name="badge" />
      <label class="block text-sm font-bold text-slate-700 mb-4 text-center">{{ label }}</label>
      
      <div :class="['relative group w-full max-w-2xl mx-auto rounded-xl overflow-hidden border-2 border-dashed border-[#c7dafa] hover:border-[#1356a0] transition-colors bg-slate-900/5 flex items-center justify-center', aspectRatio]">
          <img v-if="previewUrl" :src="previewUrl" alt="Preview Image" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          <div v-else class="text-slate-400 flex flex-col items-center">
              <span class="material-symbols-outlined text-[36px] mb-2">image</span>
              <span class="text-xs font-bold">Belum Ada Gambar</span>
          </div>
          
          <!-- Hover Overlay for Upload -->
          <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white cursor-pointer backdrop-blur-[2px] z-20">
              <span class="material-symbols-outlined text-[36px] mb-2 animate-bounce">upload_file</span>
              <span class="text-sm font-bold">Pilih Gambar Baru</span>
              <span class="text-xs text-white/70 mt-1">{{ maxSizeText }}</span>
          </div>
          <input type="file" @change="onFileSelect" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" />
      </div>
      <div v-if="error" class="text-red-500 text-xs mt-2 font-semibold text-center">{{ error }}</div>
  </div>
</template>
