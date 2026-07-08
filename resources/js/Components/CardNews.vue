<template>
  <div class="w-full">
    <Card
      variant="horizontal"
      media-position="right"
      :avatar="avatarImage"
      avatar-alt="DPMD Bangkalan"
      title="Program Prioritas DPMD Kabupaten Bangkalan"
      description="DPMD Kabupaten Bangkalan berkomitmen meningkatkan kualitas pembangunan desa melalui pemberdayaan masyarakat, penguatan kelembagaan desa, serta pemanfaatan teknologi dalam pelayanan publik."
      :media-src="bannerImage"
      media-alt="Program DPMD"
      :show-button="true"
      button-text="Selengkapnya"
      button-hierarchy="primary"
    />
  </div>
</template>

<script setup lang="ts">
import { Card } from '@idds/vue';

// Avatar sementara menggunakan layanan avatar online
const avatarImage =
  'https://ui-avatars.com/api/?name=DPMD&background=2563eb&color=ffffff';

// Gambar sementara
const bannerImage =
  'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=1200';
</script>