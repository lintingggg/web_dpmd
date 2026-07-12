<script setup lang="ts">
import {
  IconBrandInstagram,
  IconBrandLinkedin,
  IconWorld,
} from "@tabler/icons-vue";
</script>

<template>
  <footer style="background: #ffffff; border-top: 1px solid #e3e5e7; margin-top: 40px; font-family: 'Plus Jakarta Sans', sans-serif;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 48px 24px; display: flex; flex-direction: column; gap: 40px;">

      <!-- TOP -->
      <div style="display: flex; flex-direction: column; gap: 32px;" class="md:flex-row md:justify-between">

        <!-- Logo & Description -->
        <div style="max-width: 380px;">
          <div style="width: 48px; height: 48px; background: #0f172a; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
            <span style="color: #ffffff; font-size: 11px; font-weight: 700; letter-spacing: 1px;">DPMD</span>
          </div>
          <p style="font-size: 14px; font-weight: 500; color: #646a79; line-height: 1.7;">
            Portal Digital Dinas Pemberdayaan Masyarakat dan Desa sebagai media
            informasi, layanan, dan publikasi kepada masyarakat.
          </p>
        </div>

        <!-- Social Media Links (desktop) -->
        <div class="hidden md:flex" style="gap: 8px; align-items: flex-start;">
          <a href="#" class="social-link">
            <IconBrandInstagram :size="15" />
            Instagram
          </a>
          <a href="#" class="social-link">
            <IconBrandLinkedin :size="15" />
            LinkedIn
          </a>
          <a href="#" class="social-link">
            <IconWorld :size="15" />
            Website
          </a>
        </div>

      </div>

      <!-- MENU -->
      <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 32px;" class="md:grid-cols-3">

        <div>
          <h5 style="font-size: 12px; font-weight: 700; color: #0f172a; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;">Portal</h5>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            <a v-for="link in [['Beranda', '/'], ['Profil', '/profil'], ['Berita', '/berita'], ['Kontak', '/kontak']]"
              :key="link[0]" :href="link[1]" class="footer-nav-link"
            >{{ link[0] }}</a>
          </div>
        </div>

        <div>
          <h5 style="font-size: 12px; font-weight: 700; color: #0f172a; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;">Layanan</h5>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            <a v-for="link in [['Data Desa', '#'], ['Pengaduan', '#'], ['Dokumen', '#'], ['FAQ', '#']]"
              :key="link[0]" :href="link[1]" class="footer-nav-link"
            >{{ link[0] }}</a>
          </div>
        </div>

        <div>
          <h5 style="font-size: 12px; font-weight: 700; color: #0f172a; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;">Kontak</h5>
          <div style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 500; color: #646a79;">
            <p>📍 Dinas Pemberdayaan Masyarakat dan Desa</p>
            <p>📞 (031) xxxx-xxxx</p>
            <p>✉ info@dpmd.go.id</p>
          </div>
        </div>

      </div>

      <!-- BOTTOM -->
      <div style="border-top: 1px solid #e3e5e7; padding-top: 24px; display: flex; flex-direction: column; gap: 16px;" class="md:flex-row md:justify-between md:items-center">

        <div style="font-size: 12px; font-weight: 500; color: #9499a3;">
          © 2026 Portal DPMD Kabupaten Bangkalan. Seluruh hak cipta dilindungi.
        </div>

        <!-- Social Media (mobile) -->
        <div class="flex md:hidden" style="gap: 8px;">
          <a href="#" class="social-link social-link--sm">
            <IconBrandInstagram :size="14" />
            Instagram
          </a>
          <a href="#" class="social-link social-link--sm">
            <IconBrandLinkedin :size="14" />
            LinkedIn
          </a>
        </div>

      </div>

    </div>
  </footer>
</template>

<style scoped>
.social-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border: 1.5px solid #e3e5e7;
  border-radius: 9999px;
  font-size: 13px;
  font-weight: 600;
  color: #373f50;
  text-decoration: none;
  transition: border-color 0.15s ease, color 0.15s ease;
}

.social-link:hover {
  border-color: #0f172a;
  color: #0f172a;
}

.social-link--sm {
  padding: 7px 12px;
  font-size: 12px;
  gap: 5px;
}

.footer-nav-link {
  font-size: 14px;
  font-weight: 500;
  color: #646a79;
  text-decoration: none;
  transition: color 0.15s ease;
}

.footer-nav-link:hover {
  color: #0f172a;
}
</style>