<template>
  <div class="w-full rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">

    <!-- Header -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-neutral-900">
        Berita & Informasi DPMD Bangkalan
      </h2>

      <p class="mt-2 text-sm text-neutral-600">
        Ikuti perkembangan terbaru mengenai program pemberdayaan masyarakat,
        pembangunan desa, serta berbagai informasi resmi dari DPMD Kabupaten
        Bangkalan.
      </p>
    </div>

    <!-- Card -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">

      <Card
        variant="basic"
        mediaPosition="top"
        :mediaSrc="illustrasiImage1"
        mediaAlt="Program Desa"
        :showButton="true"
      >
        <template #title>
          <h3 class="text-base font-semibold line-clamp-2">
            Program Pemberdayaan Masyarakat Desa Tahun 2026
          </h3>
        </template>

        <template #description>
          <p class="text-sm text-neutral-600">
            DPMD Bangkalan terus mendorong peningkatan kualitas SDM desa melalui
            berbagai program pelatihan dan pemberdayaan masyarakat.
          </p>
        </template>
      </Card>

      <Card
        variant="basic"
        mediaPosition="top"
        :mediaSrc="illustrasiImage2"
        mediaAlt="BUMDes"
        :showButton="true"
      >
        <template #title>
          <h3 class="text-base font-semibold line-clamp-2">
            Penguatan BUMDes untuk Meningkatkan Ekonomi Desa
          </h3>
        </template>

        <template #description>
          <p class="text-sm text-neutral-600">
            Pengembangan Badan Usaha Milik Desa dilakukan sebagai langkah
            meningkatkan kesejahteraan masyarakat melalui potensi lokal.
          </p>
        </template>
      </Card>

      <Card
        variant="basic"
        mediaPosition="top"
        :mediaSrc="illustrasiImage3"
        mediaAlt="Pelayanan Desa"
        :showButton="true"
      >
        <template #title>
          <h3 class="text-base font-semibold line-clamp-2">
            Digitalisasi Pelayanan Pemerintahan Desa
          </h3>
        </template>

        <template #description>
          <p class="text-sm text-neutral-600">
            Transformasi digital terus dilakukan untuk memberikan pelayanan desa
            yang lebih cepat, transparan, dan mudah diakses masyarakat.
          </p>
        </template>
      </Card>

    </div>

    <!-- Button -->
    <div class="mt-8">
      <Button hierarchy="primary">
        Lihat Semua Berita
      </Button>
    </div>

  </div>
</template>

<script setup lang="ts">
import { Card, Button } from '@idds/vue';

const illustrasiImage1 =
  'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=800';

const illustrasiImage2 =
  'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800';

const illustrasiImage3 =
  'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800';
</script>