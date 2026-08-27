<script setup>
import { ref, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';

const props = defineProps({
  modelValue: String
});

const emit = defineEmits(['update:modelValue', 'search']);

const searchHistory = ref([]);
const isDropdownOpen = ref(false);
const tagDropdownWrapper = ref(null);

onMounted(() => {
    try {
        const savedHistory = localStorage.getItem('search_history_dpmd');
        if (savedHistory) {
            searchHistory.value = JSON.parse(savedHistory);
        }
    } catch (e) {
        console.error('Failed to read search history from localStorage', e);
    }
});

const saveToHistory = () => {
    const query = props.modelValue?.trim();
    if (!query) return;

    let history = searchHistory.value.filter(item => item !== query);
    history.unshift(query);

    if (history.length > 3) {
        history = history.slice(0, 3);
    }

    searchHistory.value = history;
    try {
        localStorage.setItem('search_history_dpmd', JSON.stringify(history));
    } catch (e) {
        console.error('Failed to save search history to localStorage', e);
    }
    isDropdownOpen.value = false;
    emit('search', query);
};

const selectHistory = (item) => {
    emit('update:modelValue', item);
    isDropdownOpen.value = false;
    emit('search', item);
};

const deleteHistoryItem = (index) => {
    searchHistory.value.splice(index, 1);
    try {
        localStorage.setItem('search_history_dpmd', JSON.stringify(searchHistory.value));
    } catch (e) {
        console.error('Failed to update search history in localStorage', e);
    }
};

const handleBlur = () => {
    setTimeout(() => {
        isDropdownOpen.value = false;
    }, 200);
};
</script>

<template>
  <div class="relative w-full mb-5" ref="tagDropdownWrapper">
      <SearchBar
          :model-value="modelValue"
          @update:model-value="$emit('update:modelValue', $event)"
          placeholder="Cari dokumen..."
          @focus="isDropdownOpen = true"
          @blur="handleBlur"
          @keyup.enter="saveToHistory"
      />

      <div v-if="isDropdownOpen && searchHistory.length > 0" class="absolute z-50 mt-1 w-full rounded-md bg-white shadow-lg border border-gray-200 py-1 text-sm">
          <div class="px-3 py-1 text-xs font-semibold text-gray-400 select-none">Pencarian Terakhir</div>
          <ul>
              <li
                  v-for="(item, index) in searchHistory"
                  :key="index"
                  class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 transition-colors"
                  @mousedown="selectHistory(item)"
              >
                  <div class="flex items-center space-x-2 truncate">
                      <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                      <span class="truncate">{{ item }}</span>
                  </div>
                  <button @mousedown.stop="deleteHistoryItem(index)" class="text-gray-400 hover:text-red-500 p-1 rounded">
                      <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                  </button>
              </li>
          </ul>
      </div>
  </div>
</template>
