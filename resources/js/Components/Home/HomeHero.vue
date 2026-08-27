<script setup>
const props = defineProps({
  pengaturanBeranda: Object
});
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
    <div class="flex flex-col lg:flex-row items-center gap-12">
      
      <!-- TEXT CONTENT -->
      <div class="lg:w-5/12 flex flex-col justify-center z-10">
        <h1 class="text-4xl lg:text-[2.75rem] font-extrabold text-[#0F172A] leading-[1.15] mb-6 overflow-hidden">
          <span class="inline-block hero-title-inner">{{ props.pengaturanBeranda?.hero_title || 'Membangun Desa Bangkalan yang Mandiri dan Sejahtera.' }}</span>
        </h1>
        <div 
          class="text-[#646A79] text-lg mb-10 leading-relaxed hero-fade-up font-medium"
          v-html="props.pengaturanBeranda?.hero_description || 'Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berkomitmen penuh dalam mendorong kemajuan potensi desa di seluruh wilayah Bangkalan.'"
        ></div>
      </div>

      <!-- IMAGE COLLAGE -->
      <div class="lg:w-7/12 relative min-h-[400px] sm:min-h-[500px] lg:min-h-[600px] w-full flex items-center justify-center p-8 bg-transparent">
        
        <div class="relative w-full max-w-[600px] h-full flex items-center justify-center">
          
          <!-- Image 1 (Left/Back) -->
          <div class="absolute left-[0%] top-[15%] w-2/5 aspect-[4/5] rounded-3xl overflow-hidden shadow-xl border-4 border-[#FFFFFF] hero-img-1 z-0 -rotate-6">
            <img :src="props.pengaturanBeranda?.hero_image_1 ? '/storage/' + props.pengaturanBeranda.hero_image_1 : '/assets/Pengukuhan TP. PKK Kecamatan Kabupaten Bangkalan.jpg.jpeg'" alt="Kegiatan 1" class="w-full h-full object-cover" />
          </div>

          <!-- Image 2 (Right/Back) -->
          <div class="absolute right-[0%] top-[30%] w-2/5 aspect-[4/5] rounded-3xl overflow-hidden shadow-xl border-4 border-[#FFFFFF] hero-img-2 z-0 rotate-6">
            <img :src="props.pengaturanBeranda?.hero_image_2 ? '/storage/' + props.pengaturanBeranda.hero_image_2 : '/assets/PKK Mengikuti Seminar Bagi Perempuan Dalam Menghadapi Era Digital.jpg.jpeg'" alt="Kegiatan 2" class="w-full h-full object-cover" />
          </div>

          <!-- Image 3 (Center/Front) -->
          <div class="relative w-1/2 aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl border-4 border-[#FFFFFF] hero-img-3 z-10 translate-y-[-10%]">
            <img :src="props.pengaturanBeranda?.hero_image_3 ? '/storage/' + props.pengaturanBeranda.hero_image_3 : '/assets/Rapat Pleno PKK.jpg.jpeg'" alt="Kegiatan 3" class="w-full h-full object-cover" />
          </div>

        </div>
      </div>

    </div>
  </section>
</template>
