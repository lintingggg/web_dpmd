<template>
  <!-- Hamburger Trigger -->
  <button
    type="button"
    class="lg:hidden flex items-center justify-center p-2 rounded-md text-neutral-900 hover:bg-neutral-100"
    aria-label="Buka menu"
    @click="open = true"
  >
    <IconMenu2 :size="24" />
  </button>

  <!-- Overlay + Panel -->
  <transition name="fade">
    <div v-if="open" class="fixed inset-0 z-[60] lg:hidden">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/40" @click="close" />

      <!-- Panel -->
      <transition name="slide" appear>
        <div
          v-if="open"
          class="absolute top-0 right-0 h-full w-full max-w-xs bg-white shadow-xl flex flex-col"
        >
          <!-- Panel Header -->
          <div class="flex items-center justify-between p-4 border-b border-neutral-200">
            <img :src="logoSrc" :alt="logoAlt" class="w-full max-w-20" />
            <button
              type="button"
              class="p-2 rounded-md text-neutral-900 hover:bg-neutral-100"
              aria-label="Tutup menu"
              @click="close"
            >
              <IconX :size="20" />
            </button>
          </div>

          <!-- Panel Search -->
          <div class="p-4 border-b border-neutral-200">
            <TextField v-model="searchValue" placeholder="Cari..." class="w-full h-[32px]!" size="sm">
              <template #prefixIcon>
                <IconSearch :size="16" />
              </template>
            </TextField>
          </div>

          <!-- Panel Nav Items -->
          <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-1">
            <template v-for="item in items" :key="item.label">
              <div v-if="item.children">
                <button
                  type="button"
                  class="flex items-center justify-between w-full text-sm text-neutral-900 py-2.5"
                  @click="toggleSection(item.label)"
                >
                  {{ item.label }}
                  <IconChevronDown
                    :size="16"
                    class="transition-transform duration-150"
                    :class="openSection === item.label ? 'rotate-180' : ''"
                  />
                </button>
                <div v-if="openSection === item.label" class="pl-3 flex flex-col gap-1 pb-2">
                  <a
                    v-for="child in item.children"
                    :key="child.label"
                    :href="child.href"
                    class="text-sm py-2"
                    :class="child.href === currentPath ? 'text-blue-600 font-medium' : 'text-neutral-600'"
                  >
                    {{ child.label }}
                  </a>
                </div>
              </div>
              <a
                v-else
                :href="item.href"
                class="text-sm py-2.5"
                :class="item.href === currentPath ? 'text-blue-600 font-medium' : 'text-neutral-900'"
              >
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
</template>

<script setup lang="ts">
import { ref, watch, onBeforeUnmount } from 'vue';
import { Button, TextField, Avatar } from '@idds/vue';
import { IconSearch, IconBell, IconChevronDown, IconMenu2, IconX } from '@tabler/icons-vue';
import type { NavItem } from './Navbar.vue';

interface Props {
  items: NavItem[];
  currentPath?: string;
  logoSrc?: string;
  logoAlt?: string;
  avatarSrc?: string;
  avatarAlt?: string;
}

withDefaults(defineProps<Props>(), {
  currentPath: '',
  logoSrc: '/images/logo-dpmd.svg',
  logoAlt: 'Logo DPMD',
  avatarSrc: '/images/avatar-default.svg',
  avatarAlt: 'User',
});

const open = ref(false);
const openSection = ref<string | null>(null);
const searchValue = ref('');

function toggleSection(label: string) {
  openSection.value = openSection.value === label ? null : label;
}

function close() {
  open.value = false;
  openSection.value = null;
}

// Kunci scroll body saat mobile menu terbuka
watch(open, (isOpen) => {
  document.body.style.overflow = isOpen ? 'hidden' : '';
});
onBeforeUnmount(() => {
  document.body.style.overflow = '';
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