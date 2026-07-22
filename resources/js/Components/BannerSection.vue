<template>
  <section style="background: #ffffff; border: 1px solid #e3e5e7; border-radius: 24px; overflow: hidden; box-shadow: 0 2px 8px rgba(15,23,42,0.06);">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center p-8 lg:p-12">

      <!-- Content -->
      <div style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <span style="font-size: 11px; font-weight: 500; color: #646a79; letter-spacing: 2.5px;" class="uppercase block mb-4">
          Dinas Pemberdayaan Masyarakat dan Desa • Kabupaten Bangkalan
        </span>

        <h1 style="font-size: 40px; font-weight: 700; color: #0f172a; line-height: 1.15; letter-spacing: -0.75px;" class="mb-5">
          Membangun Desa,<br />
          Memberdayakan Masyarakat
        </h1>

        <p style="font-size: 16px; font-weight: 500; color: #646a79; line-height: 1.7; max-width: 480px;" class="mb-8">
          Mendampingi pemerintah desa dan masyarakat Kabupaten Bangkalan
          melalui berbagai program pemberdayaan, peningkatan kapasitas,
          penguatan kelembagaan, serta pembangunan desa yang berkelanjutan.
        </p>

        <a
          href="#program"
          class="btn-cta"
        >
          Lihat Program
        </a>
      </div>

      <!-- Image -->
      <div class="flex justify-center">
        <img
          :src="bannerMedia"
          alt="Banner DPMD Bangkalan"
          style="width: 100%; max-width: 480px; border-radius: 16px; object-fit: cover; box-shadow: 0 8px 32px rgba(15,23,42,0.12);"
        />
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
const bannerMedia =
  'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=1200';
</script>

<style scoped>
.btn-cta {
  display: inline-flex;
  align-items: center;
  background: #0f172a;
  color: #ffffff;
  border-radius: 9999px;
  padding: 12px 24px;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  letter-spacing: -0.2px;
  transition: background 0.2s ease;
  box-shadow: 0 4px 16px rgba(15,23,42,0.18);
}

.btn-cta:hover {
  background: #222a3d;
}
</style>