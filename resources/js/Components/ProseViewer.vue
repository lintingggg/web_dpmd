<script setup>
defineProps({
  html: String,
  placeholder: {
    type: String,
    default: '(Data Belum Tersedia)'
  }
});
</script>

<template>
  <div>
    <div v-if="html" class="prose max-w-none text-slate-700 leading-relaxed text-lg prose-li:my-2 prose-ul:list-disc prose-ol:list-decimal prose-ul:pl-6 prose-ol:pl-6 text-left" v-html="html"></div>
    <div v-else class="text-slate-600 text-lg text-left">
        <p>{{ placeholder }}</p>
    </div>
  </div>
</template>

<style scoped>
.prose :deep(ol), .prose :deep(ul) {
    padding-left: 1.5rem;
    list-style-position: outside;
}
.prose :deep(ol) {
    list-style-type: decimal;
}
.prose :deep(ul) {
    list-style-type: disc;
}
.prose :deep(li) {
    margin-bottom: 0.75rem;
}
.prose :deep(p) {
    margin-bottom: 1rem;
}
.prose :deep(h2), .prose :deep(h3), .prose :deep(h4) {
    color: #0F172A;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
</style>
