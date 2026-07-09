<template>
  <section class="bg-white rounded-xl border border-neutral-200 shadow-sm overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center p-8 lg:p-12">

      <!-- Content -->
      <div>
        <span class="text-sm font-semibold uppercase tracking-wider text-amber-600">
          Dinas Pemberdayaan Masyarakat dan Desa • Kabupaten Bangkalan
        </span>

        <h1 class="mt-4 text-4xl font-bold text-neutral-900 leading-tight">
          Membangun Desa,
          <br />
          Memberdayakan Masyarakat
        </h1>

        <p class="mt-5 text-neutral-600 leading-7 max-w-xl">
          Mendampingi pemerintah desa dan masyarakat Kabupaten Bangkalan
          melalui berbagai program pemberdayaan, peningkatan kapasitas,
          penguatan kelembagaan, serta pembangunan desa yang berkelanjutan.
        </p>

        <div class="mt-8">
          <a
            href="#program"
            class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-3 text-white font-medium hover:bg-blue-700 transition"
          >
            Lihat Program
          </a>
        </div>
      </div>

      <!-- Image -->
      <div class="flex justify-center">
        <img
          :src="bannerMedia"
          alt="Banner DPMD Bangkalan"
          class="w-full max-w-lg rounded-xl shadow-lg object-cover"
        />
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
const bannerMedia =
  'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=1200';
</script>