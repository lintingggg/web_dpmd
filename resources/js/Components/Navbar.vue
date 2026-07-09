<template>
  <nav class="hidden lg:flex gap-6 items-center">
    <template v-for="item in items" :key="item.label">
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
          @click="toggleDropdown(item.label)"
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
                :class="{ 'bg-neutral-100 text-neutral-900 font-medium': child.href === currentPath }"
              >
                {{ child.label }}
              </a>
            </div>
          </div>
        </transition>
      </div>

      <!-- Item biasa (tanpa dropdown) -->
      <a
        v-else
        :href="item.href"
        class="text-sm py-2"
        :class="item.href === currentPath ? 'text-blue-600 font-medium' : 'text-neutral-900'"
      >
        {{ item.label }}
      </a>
    </template>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { IconChevronDown } from '@tabler/icons-vue';

export interface NavChild {
  label: string;
  href: string;
}
export interface NavItem {
  label: string;
  href?: string;
  children?: NavChild[];
}

interface Props {
  items: NavItem[];
  currentPath?: string;
}

withDefaults(defineProps<Props>(), {
  currentPath: '',
});

const openDropdown = ref<string | null>(null);

function toggleDropdown(label: string) {
  openDropdown.value = openDropdown.value === label ? null : label;
}

function closeDropdown() {
  openDropdown.value = null;
}

// Tutup dropdown saat klik di luar area nav (fallback selain hover, berguna untuk sentuh/tap)
function handleClickOutside(event: MouseEvent) {
  const target = event.target as HTMLElement;
  if (!target.closest('nav')) {
    closeDropdown();
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
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
</style>