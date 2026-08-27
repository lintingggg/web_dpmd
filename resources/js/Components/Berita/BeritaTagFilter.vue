<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  tagList: Array,
  activeTag: String
});

const emit = defineEmits(['change']);

const TAG_VISIBLE_LIMIT = 5;
const visibleTags = computed(() => props.tagList.slice(0, TAG_VISIBLE_LIMIT));
const overflowTags = computed(() => props.tagList.slice(TAG_VISIBLE_LIMIT));

const showTagDropdown = ref(false);
const tagDropdownWrapper = ref(null);

function toggleTagDropdown() {
    showTagDropdown.value = !showTagDropdown.value;
}

function pilihTag(tag) {
    emit('change', tag);
}

function pilihTagDariDropdown(tag) {
    pilihTag(tag);
    showTagDropdown.value = false;
}

function handleClickOutsideTagDropdown(event) {
    if (tagDropdownWrapper.value && !tagDropdownWrapper.value.contains(event.target)) {
        showTagDropdown.value = false;
    }
}

onMounted(() => document.addEventListener('click', handleClickOutsideTagDropdown));
onUnmounted(() => document.removeEventListener('click', handleClickOutsideTagDropdown));
</script>

<template>
  <div
      v-if="tagList && tagList.length > 1"
      ref="tagDropdownWrapper"
      class="relative flex items-center gap-1.5 flex-wrap mb-8"
  >
      <button
          v-for="tag in visibleTags"
          :key="tag"
          @click="pilihTag(tag)"
          :class="[
              activeTag === tag
                  ? 'bg-slate-900 text-white font-semibold'
                  : 'bg-gray-100 text-slate-700 hover:bg-gray-200',
              'whitespace-nowrap px-4 py-2 rounded-full text-sm transition-colors'
          ]"
      >
          {{ tag }}
      </button>

      <!-- Overflow -->
      <div v-if="overflowTags.length" class="relative">
          <button
              @click="toggleTagDropdown"
              :class="[
                  overflowTags.includes(activeTag) ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-500 hover:bg-gray-200',
                  'w-9 h-9 flex items-center justify-center rounded-full transition-colors'
              ]"
              aria-label="Lihat keyword lainnya"
          >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <circle cx="5" cy="12" r="2" />
                  <circle cx="12" cy="12" r="2" />
                  <circle cx="19" cy="12" r="2" />
              </svg>
          </button>

          <div
              v-if="showTagDropdown"
              class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-20"
          >
              <button
                  v-for="tag in overflowTags"
                  :key="tag"
                  @click="pilihTagDariDropdown(tag)"
                  :class="[
                      activeTag === tag ? 'bg-slate-50 font-bold text-slate-900' : 'text-slate-600 hover:bg-slate-50',
                      'w-full text-left px-4 py-2 text-sm transition-colors'
                  ]"
              >
                  {{ tag }}
              </button>
          </div>
      </div>
  </div>
</template>
