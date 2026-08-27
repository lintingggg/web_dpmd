<script setup>
import { Link } from '@inertiajs/vue3';
import { IconArrowRight } from '@tabler/icons-vue';
import CardNews from '../CardNews.vue';
import { getDescription, getImageUrl, formatDate } from '../../Utils/helpers';

defineProps({
  beritaTerkini: Array
});
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 section-berita">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 border-b border-gray-200 pb-4 section-header">
      <div>
        <h2 class="text-3xl font-bold text-[#0F172A] mb-2">Berita Terkini</h2>
        <p class="text-[#646A79]">Informasi terbaru seputar kegiatan dan program DPMD Bangkalan.</p>
      </div>
      <a href="/berita" class="mt-4 sm:mt-0 flex items-center text-sm font-semibold text-[#0F172A] hover:underline">
        Lihat Semua
        <IconArrowRight class="ml-1 w-4 h-4" />
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 card-container" v-if="beritaTerkini && beritaTerkini.length > 0">
      <Link
        v-for="berita in beritaTerkini"
        :key="berita.id"
        :href="`/berita/${berita.slug}`"
        class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer card-item"
      >
        <CardNews
          :title="berita.judul"
          :description="getDescription(berita)"
          :date="formatDate(berita.published_at)"
          :author="berita.penulis || 'Humas DPMD'"
          :tags="berita.tags"
          :image="getImageUrl(berita)"
        />
      </Link>
    </div>
    <div v-else class="text-center py-12 text-[#646A79] border border-dashed border-gray-200 rounded-xl">
      Belum ada berita terbaru.
    </div>
  </section>
</template>
