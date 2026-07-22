<script setup lang="ts">
import {
  IconBrandFacebook,
  IconBrandInstagram,
  IconBrandYoutube,
  IconBrandX,
  IconMapPin,
  IconPhone,
  IconMail
} from "@tabler/icons-vue";
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const kontak = computed(() => (page.props.kontak as any) || {});
</script>

<template>
  <footer class="bg-[#0F1B3D] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-12">
        
        <!-- Column 1: Info -->
        <div class="md:col-span-5">
          <h3 class="font-bold text-xl mb-4">DPMD Bangkalan</h3>
          <p class="text-blue-100 text-sm leading-relaxed mb-6 max-w-sm">
            Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Bangkalan. Membangun desa mandiri untuk masa depan yang lebih baik.
          </p>
          <div class="space-y-3 mt-4">
            <div class="flex items-start gap-3 text-blue-100 text-sm" v-if="kontak.alamat">
              <IconMapPin class="w-5 h-5 shrink-0 text-white" />
              <span>{{ kontak.alamat }}</span>
            </div>
            <div class="flex items-center gap-3 text-blue-100 text-sm" v-if="kontak.telepon">
              <IconPhone class="w-5 h-5 shrink-0 text-white" />
              <span>{{ kontak.telepon }}</span>
            </div>
            <div class="flex items-center gap-3 text-blue-100 text-sm" v-if="kontak.email">
              <IconMail class="w-5 h-5 shrink-0 text-white" />
              <span>{{ kontak.email }}</span>
            </div>
          </div>
        </div>
        
        <!-- Column 2: Quick Links -->
        <div class="md:col-span-3">
          <h4 class="font-bold mb-4">Tautan Cepat</h4>
          <ul class="space-y-3">
            <li><a href="/" class="text-blue-100 text-sm hover:text-white transition-colors">Beranda</a></li>
            <li><a href="#" class="text-blue-100 text-sm hover:text-white transition-colors">Profil Instansi</a></li>
            <li><a href="/berita" class="text-blue-100 text-sm hover:text-white transition-colors">Berita & Pengumuman</a></li>
            <li><a href="#kontak" class="text-blue-100 text-sm hover:text-white transition-colors">Kontak Kami</a></li>
          </ul>
        </div>
        
        <!-- Column 3: Social Media -->
        <div class="md:col-span-4">
          <h4 class="font-bold mb-4">Ikuti Kami</h4>
          <div class="flex gap-4">
            <a v-if="kontak.facebook_url" :href="kontak.facebook_url" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1877F2] transition-colors" aria-label="Facebook">
              <IconBrandFacebook class="w-5 h-5 text-white" />
            </a>
            <a v-if="kontak.instagram_url" :href="kontak.instagram_url" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#E4405F] transition-colors" aria-label="Instagram">
              <IconBrandInstagram class="w-5 h-5 text-white" />
            </a>
            <a v-if="kontak.youtube_url" :href="kontak.youtube_url" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF0000] transition-colors" aria-label="YouTube">
              <IconBrandYoutube class="w-5 h-5 text-white" />
            </a>
            <a v-if="kontak.twitter_url" :href="kontak.twitter_url" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-black transition-colors" aria-label="Twitter">
              <IconBrandX class="w-5 h-5 text-white" />
            </a>
          </div>
        </div>
      </div>
      
      <div class="pt-8 border-t border-white/10 text-center flex flex-col md:flex-row justify-center items-center">
        <p class="text-xs text-blue-200">
          &copy; 2024 DPMD Kabupaten Bangkalan. All Rights Reserved.
        </p>
      </div>
    </div>
  </footer>
</template>