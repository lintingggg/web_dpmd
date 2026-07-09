<script setup lang="ts">
import { Button } from "@idds/vue";
import {
  IconBrandInstagram,
  IconBrandLinkedin,
  IconWorld,
} from "@tabler/icons-vue";

// import logo from "@/assets/logo-dpmd.png"; // ganti dengan logo DPMD
</script>

<template>
  <footer class="bg-white border-t border-neutral-200 mt-10">
    <div class="max-w-7xl mx-auto p-6 space-y-8">

      <!-- TOP -->
      <div class="flex flex-col md:flex-row justify-between gap-8">

        <!-- Logo -->
        <div class="space-y-3">
          <img :src="logo" class="h-14" alt="Logo DPMD" />

          <p class="text-sm text-neutral-600 max-w-md">
            Portal Digital Dinas Pemberdayaan Masyarakat dan Desa sebagai media
            informasi, layanan, dan publikasi kepada masyarakat.
          </p>
        </div>

        <!-- Sosial Media -->
        <div class="hidden md:flex gap-4">

          <Button hierarchy="link" size="sm">
            <template #prefixIcon>
              <IconBrandInstagram />
            </template>
            Instagram
          </Button>

          <Button hierarchy="link" size="sm">
            <template #prefixIcon>
              <IconBrandLinkedin />
            </template>
            LinkedIn
          </Button>

          <Button hierarchy="link" size="sm">
            <template #prefixIcon>
              <IconWorld />
            </template>
            Website
          </Button>

        </div>

      </div>

      <!-- MENU -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div>
          <h5 class="text-sm font-semibold mb-3">Portal</h5>

          <div class="flex flex-col gap-2 text-sm">

            <a href="/">Beranda</a>
            <a href="/profil">Profil</a>
            <a href="/berita">Berita</a>
            <a href="/kontak">Kontak</a>

          </div>
        </div>

        <div>
          <h5 class="text-sm font-semibold mb-3">Layanan</h5>

          <div class="flex flex-col gap-2 text-sm">

            <a href="#">Data Desa</a>
            <a href="#">Pengaduan</a>
            <a href="#">Dokumen</a>
            <a href="#">FAQ</a>
          </div>
        </div>

        <div>
          <h5 class="text-sm font-semibold mb-3">Kontak</h5>

          <div class="text-sm space-y-2 text-neutral-700">

            <p>📍 Dinas Pemberdayaan Masyarakat dan Desa</p>
            <p>📞 (031) xxxx-xxxx</p>
            <p>✉ info@dpmd.go.id</p>

          </div>
        </div>

      </div>

      <!-- BOTTOM -->
      <hr>

      <div class="flex flex-col md:flex-row justify-between items-center gap-4">

        <div class="text-sm text-neutral-500">
          © 2026 Portal DPMD. Seluruh hak cipta dilindungi.
        </div>

        <div class="flex md:hidden gap-3">

          <Button hierarchy="link" size="sm">
            <template #prefixIcon>
              <IconBrandInstagram />
            </template>
            Instagram
          </Button>

          <Button hierarchy="link" size="sm">
            <template #prefixIcon>
              <IconBrandLinkedin />
            </template>
            LinkedIn
          </Button>

        </div>

      </div>

    </div>
  </footer>
</template>