<script setup>
defineProps({
  pdfUrl: String
});
</script>

<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col h-full">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
          <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
              </div>
              <span class="font-semibold text-gray-700 text-sm">PDF Document</span>
          </div>
          <a v-if="pdfUrl" :href="pdfUrl" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">Buka di Tab Baru</a>
      </div>
      <div class="flex-grow bg-gray-100 relative min-h-[500px]">
          <iframe 
              v-if="pdfUrl" 
              :src="pdfUrl" 
              class="absolute inset-0 w-full h-full border-0" 
              title="PDF Viewer"
          ></iframe>
          <div v-else class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
              <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
              <p>File dokumen belum tersedia.</p>
          </div>
      </div>
  </div>
</template>
