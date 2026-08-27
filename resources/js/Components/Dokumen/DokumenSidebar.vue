<script setup>
import { IconDownload } from '@tabler/icons-vue';
import { formatDokumenCategory } from '../../Utils/helpers';

const props = defineProps({
  dokumen: Object,
  pdfUrl: String
});

const downloadDoc = () => {
    if (!props.pdfUrl) return;
    const a = document.createElement('a');
    a.href = props.pdfUrl;
    a.download = props.dokumen.judul + '.pdf';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
};
</script>

<template>
  <div class="space-y-6">
      <!-- Download Box -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-sm font-bold text-[#0F172A] mb-4 uppercase tracking-wider">Download</h3>
          <button 
              @click="downloadDoc"
              :disabled="!pdfUrl"
              class="w-full flex items-center justify-center gap-3 bg-[#0F172A] hover:bg-blue-900 text-white py-3.5 px-4 rounded-xl font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed group"
          >
              <IconDownload :size="18" class="group-hover:-translate-y-0.5 transition-transform" />
              Download Dokumen
          </button>
          <p class="text-center text-xs text-gray-400 mt-3 font-medium">Format: PDF</p>
      </div>

      <!-- Info Box -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-sm font-bold text-[#0F172A] mb-5 uppercase tracking-wider">Informasi Dokumen</h3>
          
          <div class="space-y-4">
              <div>
                  <p class="text-xs text-gray-400 mb-1">Kategori</p>
                  <p class="font-semibold text-gray-800">{{ formatDokumenCategory(dokumen?.kategori) }}</p>
              </div>
              <div class="w-full h-px bg-gray-100"></div>
              <div>
                  <p class="text-xs text-gray-400 mb-1">Jenis</p>
                  <p class="font-semibold text-gray-800">{{ dokumen?.kategori === 'peraturan' ? 'Produk Hukum' : 'Laporan Resmi' }}</p>
              </div>
              <div class="w-full h-px bg-gray-100"></div>
              <div>
                  <p class="text-xs text-gray-400 mb-1">Tahun</p>
                  <p class="font-semibold text-gray-800">{{ dokumen?.tahun }}</p>
              </div>
          </div>
      </div>
  </div>
</template>
