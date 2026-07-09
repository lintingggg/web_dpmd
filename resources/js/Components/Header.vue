<template>
  <header class="w-full border border-neutral-200 rounded-lg relative">
    <nav class="flex flex-row items-center gap-2 justify-between p-4 w-full">
      <!-- Logo -->
      <a href="/" class="shrink-0">
        <img :src="logoSrc" :alt="logoAlt" class="w-full max-w-24" />
      </a>

      <!-- Desktop Navigation -->
      <div class="hidden lg:flex gap-6 items-center">
        <template v-for="item in navItems" :key="item.label">
          <!-- Item dengan dropdown -->
          <div
            v-if="item.children"
            class="relative"
            @mouseenter="openDropdown = item.label"
            @mouseleave="openDropdown = null"
          >
            <button
              type="button"
              class="flex items-center gap-1 text-neutral-900 text-sm py-2"
              :aria-expanded="openDropdown === item.label"
            >
              {{ item.label }}
              <IconChevronDown
                :size="14"
                class="transition-transform duration-150"
                :class="openDropdown === item.label ? 'rotate-180' : ''"
              />
            </button>
            <transition name="fade">
              <div
                v-if="openDropdown === item.label"
                class="absolute top-full left-0 pt-1 min-w-64 z-50"
              >
                <div class="bg-white border border-neutral-200 rounded-lg shadow-lg p-1.5">
                  <a
                    v-for="child in item.children"
                    :key="child.label"
                    :href="child.href"
                    class="block px-3 py-2 rounded-md text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900"
                  >
                    {{ child.label }}
                  </a>
                </div>
              </div>
            </transition>
          </div>

          <!-- Item biasa (tanpa dropdown) -->
          <a v-else :href="item.href" class="text-neutral-900 text-sm py-2">
            {{ item.label }}
          </a>
        </template>
      </div>

      <!-- Actions (Desktop) -->
      <div class="hidden lg:flex items-center gap-1 justify-center h-fit">
        <div class="flex-1 flex items-center">
          <BasicDropdown class="w-full max-w-30">
            <template #trigger>
              <TextField
                v-model="value"
                placeholder="Cari..."
                class="w-full max-w-30 h-[32px]!"
                size="sm"
              >
                <template #prefixIcon>
                  <IconSearch :size="16" />
                </template>
              </TextField>
            </template>
            <template #content>
              <div class="p-1 flex flex-col max-h-60 overflow-y-auto w-full space-y-4 cursor-pointer">
                <template v-if="filteredOptions.length > 0">
                  <div v-for="opt in filteredOptions" :key="opt" @mousedown="value = opt">
                    {{ opt }}
                  </div>
                </template>
                <template v-else>
                  <div class="p-2 text-sm text-gray-500">Tidak ada hasil</div>
                </template>
              </div>
            </template>
          </BasicDropdown>
        </div>
        <!-- Buttons & Avatar -->
        <Button hierarchy="primary" size="md" class="bg-blue-400">Layanan</Button>
        <Button hierarchy="tertiary" size="md">
          <IconBell :size="16" />
        </Button>
        <Button hierarchy="tertiary" size="md" class="p-0!">
          <Avatar :src="avatarSrc" :alt="avatarAlt" />
        </Button>
      </div>

      <!-- Hamburger (Mobile) -->
      <button
        type="button"
        class="lg:hidden flex items-center justify-center p-2 rounded-md text-neutral-900 hover:bg-neutral-100"
        aria-label="Buka menu"
        @click="mobileOpen = true"
      >
        <IconMenu2 :size="24" />
      </button>
    </nav>

    <!-- Mobile Menu Overlay -->
    <transition name="fade">
      <div v-if="mobileOpen" class="fixed inset-0 z-[60] lg:hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40" @click="closeMobileMenu" />

        <!-- Panel -->
        <transition name="slide" appear>
          <div
            v-if="mobileOpen"
            class="absolute top-0 right-0 h-full w-full max-w-xs bg-white shadow-xl flex flex-col"
          >
            <!-- Panel Header -->
            <div class="flex items-center justify-between p-4 border-b border-neutral-200">
              <img :src="logoSrc" :alt="logoAlt" class="w-full max-w-20" />
              <button
                type="button"
                class="p-2 rounded-md text-neutral-900 hover:bg-neutral-100"
                aria-label="Tutup menu"
                @click="closeMobileMenu"
              >
                <IconX :size="20" />
              </button>
            </div>

            <!-- Panel Search -->
            <div class="p-4 border-b border-neutral-200">
              <TextField
                v-model="value"
                placeholder="Cari..."
                class="w-full h-[32px]!"
                size="sm"
              >
                <template #prefixIcon>
                  <IconSearch :size="16" />
                </template>
              </TextField>
            </div>

            <!-- Panel Nav Items -->
            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-1">
              <template v-for="item in navItems" :key="item.label">
                <div v-if="item.children">
                  <button
                    type="button"
                    class="flex items-center justify-between w-full text-sm text-neutral-900 py-2.5"
                    @click="toggleMobileSection(item.label)"
                  >
                    {{ item.label }}
                    <IconChevronDown
                      :size="16"
                      class="transition-transform duration-150"
                      :class="mobileOpenSection === item.label ? 'rotate-180' : ''"
                    />
                  </button>
                  <div v-if="mobileOpenSection === item.label" class="pl-3 flex flex-col gap-1 pb-2">
                    <a
                      v-for="child in item.children"
                      :key="child.label"
                      :href="child.href"
                      class="text-sm text-neutral-600 py-2"
                    >
                      {{ child.label }}
                    </a>
                  </div>
                </div>
                <a v-else :href="item.href" class="text-sm text-neutral-900 py-2.5">
                  {{ item.label }}
                </a>
              </template>
            </div>

            <!-- Panel Footer Actions -->
            <div class="p-4 border-t border-neutral-200 flex flex-col gap-3">
              <Button hierarchy="primary" size="md" class="bg-blue-400 w-full">Layanan</Button>
              <div class="flex items-center justify-between">
                <Button hierarchy="tertiary" size="md">
                  <IconBell :size="16" />
                </Button>
                <Button hierarchy="tertiary" size="md" class="p-0!">
                  <Avatar :src="avatarSrc" :alt="avatarAlt" />
                </Button>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, watch, onBeforeUnmount } from 'vue';
import { Button, TextField, Avatar, BasicDropdown } from '@idds/vue';
import { IconSearch, IconBell, IconChevronDown, IconMenu2, IconX } from '@tabler/icons-vue';

// Logo & avatar dikirim lewat props supaya komponen ini reusable di halaman lain
// tanpa bergantung pada file asset demo tertentu.
interface Props {
  logoSrc?: string;
  logoAlt?: string;
  avatarSrc?: string;
  avatarAlt?: string;
}

withDefaults(defineProps<Props>(), {
  logoSrc: '/images/logo-dpmd.svg',
  logoAlt: 'Logo DPMD',
  avatarSrc: '/images/avatar-default.svg',
  avatarAlt: 'User',
});

interface NavChild {
  label: string;
  href: string;
}
interface NavItem {
  label: string;
  href?: string;
  children?: NavChild[];
}

// Struktur menu sesuai permintaan DPMD (Beranda, Profil Dinas, Bidang Tugas, Publikasi Dokumen, Berita)
const navItems: NavItem[] = [
  { label: 'Beranda', href: '/' },
  {
    label: 'Profil Dinas',
    children: [
      { label: 'Sambutan Kepala Dinas', href: '/profil-dinas/sambutan-kepala-dinas' },
      { label: 'Visi dan Misi', href: '/profil-dinas/visi-misi' },
      { label: 'Tugas Pokok dan Fungsi', href: '/profil-dinas/tugas-pokok-fungsi' },
      { label: 'Struktur Organisasi', href: '/profil-dinas/struktur-organisasi' },
      { label: 'Kode Etik Pelayanan', href: '/profil-dinas/kode-etik-pelayanan' },
      { label: 'Maklumat Pelayanan', href: '/profil-dinas/maklumat-pelayanan' },
      { label: 'Motto Pelayanan', href: '/profil-dinas/motto-pelayanan' },
    ],
  },
  {
    label: 'Bidang Tugas',
    children: [
      { label: 'Bidang Pemerintahan Desa', href: '/bidang-tugas/pemerintahan-desa' },
      { label: 'Bidang Pemberdayaan Desa', href: '/bidang-tugas/pemberdayaan-desa' },
      {
        label: 'Bidang Pemberdayaan Lembaga Kemasyarakatan',
        href: '/bidang-tugas/pemberdayaan-lembaga-kemasyarakatan',
      },
      { label: 'Sekretariat', href: '/bidang-tugas/sekretariat' },
    ],
  },
  {
    label: 'Publikasi Dokumen',
    children: [
      { label: 'Dokumen Perencanaan', href: '/publikasi-dokumen/dokumen-perencanaan' },
      { label: 'Produk Peraturan', href: '/publikasi-dokumen/produk-peraturan' },
      { label: 'Dokumen Lainnya', href: '/publikasi-dokumen/dokumen-lainnya' },
    ],
  },
  { label: 'Berita / Kegiatan', href: '/berita' },
];

// Dropdown desktop (hover)
const openDropdown = ref<string | null>(null);

// Mobile menu state
const mobileOpen = ref(false);
const mobileOpenSection = ref<string | null>(null);

function toggleMobileSection(label: string) {
  mobileOpenSection.value = mobileOpenSection.value === label ? null : label;
}

function closeMobileMenu() {
  mobileOpen.value = false;
  mobileOpenSection.value = null;
}

// Kunci scroll body saat mobile menu terbuka
watch(mobileOpen, (isOpen) => {
  document.body.style.overflow = isOpen ? 'hidden' : '';
});
onBeforeUnmount(() => {
  document.body.style.overflow = '';
});

// Search
const searchOptions = ['Accordion', 'Alert', 'Table', 'Tooltip', 'Card', 'Chart'];
const value = ref('');
const filteredOptions = computed(() => {
  return searchOptions.filter((opt) => opt.toLowerCase().includes(value.value.toLowerCase()));
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.2s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
}
</style>