<script setup>
defineProps({
  pdfUrl: String,
  title: {
    type: String,
    default: 'PDF Document'
  }
});
</script>

<template>
  <div v-if="pdfUrl" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
          <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
              </div>
              <span class="font-semibold text-gray-700 text-sm">{{ title }}</span>
          </div>
          <a :href="pdfUrl" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">Buka di Tab Baru</a>
      </div>
      <div class="flex-grow bg-gray-100 relative" style="min-height: 600px;">
          <iframe 
              :src="pdfUrl" 
              class="absolute inset-0 w-full h-full border-0" 
              title="PDF Viewer"
          ></iframe>
      </div>
  </div>
</template>
