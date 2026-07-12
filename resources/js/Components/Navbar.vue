<template>
  <nav class="hidden lg:flex gap-6 items-center" style="font-family: 'Plus Jakarta Sans', sans-serif;">
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
          class="flex items-center gap-1.5 py-2 text-sm font-medium transition-colors duration-150"
          :class="openDropdown === item.label ? 'text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900'"
          :aria-expanded="openDropdown === item.label"
          @click="toggleDropdown(item.label)"
        >
          {{ item.label }}
          <IconChevronDown
            :size="14"
            class="transition-transform duration-200"
            :class="openDropdown === item.label ? 'rotate-180' : ''"
          />
        </button>
        <transition name="fade">
          <div
            v-if="openDropdown === item.label"
            class="absolute top-full left-0 pt-2 min-w-64 z-50"
          >
            <div style="background: #ffffff; border: 1px solid #e3e5e7; border-radius: 16px; box-shadow: 0 8px 32px rgba(15,23,42,0.12); padding: 6px;">
              <a
                v-for="child in item.children"
                :key="child.label"
                :href="child.href"
                class="nav-child-link block px-3 py-2.5 text-sm font-medium transition-colors duration-150"
                :class="child.href === currentPath
                  ? 'bg-slate-50 text-slate-900 font-bold nav-child-active'
                  : 'text-slate-600'"
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
        class="text-sm py-2 font-medium transition-colors duration-150"
        :class="item.href === currentPath ? 'text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900'"
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
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

.nav-child-link {
  border-radius: 10px;
  display: block;
}

.nav-child-link:not(.nav-child-active):hover {
  background: #f8fafc;
  color: #0f172a;
}
</style>