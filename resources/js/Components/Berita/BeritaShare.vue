<script setup>
import { ref } from 'vue';

const props = defineProps({
  judul: String
});

const showShareMenu = ref(false);

const getShareUrl = () => {
    return typeof window !== 'undefined' ? window.location.href : '';
};

const fallbackCopyText = (text) => {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        alert('Tautan berita berhasil disalin ke clipboard!');
    } catch (err) {
        alert('Gagal menyalin tautan.');
    }
    document.body.removeChild(textArea);
};

const copyToClipboard = () => {
    const url = getShareUrl();
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url)
            .then(() => alert('Tautan berita berhasil disalin ke clipboard!'))
            .catch(() => fallbackCopyText(url));
    } else {
        fallbackCopyText(url);
    }
    showShareMenu.value = false;
};
</script>

<template>
  <div class="relative">
      <button
          type="button"
          @click="showShareMenu = !showShareMenu"
          class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-blue-700 transition-colors cursor-pointer"
      >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="18" cy="5" r="3" />
              <circle cx="6" cy="12" r="3" />
              <circle cx="18" cy="19" r="3" />
              <line x1="8.6" y1="13.5" x2="15.4" y2="17.5" />
              <line x1="15.4" y1="6.5" x2="8.6" y2="10.5" />
          </svg>
          Bagikan
      </button>

      <!-- Dropdown Share Menu -->
      <div v-if="showShareMenu" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg border border-gray-200 py-1.5 text-sm z-50">
          <a
              :href="`https://api.whatsapp.com/send?text=${encodeURIComponent(judul + ' ' + getShareUrl())}`"
              target="_blank"
              class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors"
          >
              <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
              </svg>
              WhatsApp
          </a>
          <a
              :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareUrl())}`"
              target="_blank"
              class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors"
          >
              <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
              </svg>
              Facebook
          </a>
          <button
              @click="copyToClipboard"
              class="w-full flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors text-left"
          >
              <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                  <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
              </svg>
              Salin Tautan
          </button>
      </div>
  </div>
</template>
