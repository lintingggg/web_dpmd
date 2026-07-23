<script setup>
import { computed, ref } from 'vue';

const model = defineModel({ type: String, default: '' });

const props = defineProps({
    options: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Cari sesuatu...'
    }
});

const emit = defineEmits(['onSearch']);
const showDropdown = ref(false);

const filteredOptions = computed(() => {
    if (!model.value) return [];
    return props.options.filter((opt) =>
        opt.toLowerCase().includes(model.value.toLowerCase())
    );
});

const eksekusiPencarian = () => {
    showDropdown.value = false;
    emit('onSearch', model.value);
};

const onFocus = () => { showDropdown.value = true; };
const onBlur = () => { setTimeout(() => { showDropdown.value = false; }, 200); };
</script>

<template>
  <div class="flex flex-row gap-3 w-full items-start justify-start" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <!-- Search Input with dropdown -->
    <div class="relative w-full">
      <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #9499a3; pointer-events: none;">search</span>
      <input
        v-model="model"
        :placeholder="placeholder"
        type="text"
        style="
          width: 100%;
          height: 44px;
          padding-left: 44px;
          padding-right: 16px;
          background: #ffffff;
          border: 1.5px solid #e3e5e7;
          border-radius: 16px;
          font-size: 14px;
          font-weight: 500;
          color: #0f172a;
          outline: none;
          transition: border-color 0.2s ease;
          box-shadow: 0 1px 3px rgba(15,23,42,0.06);
          font-family: 'Plus Jakarta Sans', sans-serif;
        "
        @keyup.enter="eksekusiPencarian"
        @focus="onFocus"
        @blur="onBlur"
        @focus.native="$event.target.style.borderColor = '#0f172a'"
        @blur.native="$event.target.style.borderColor = '#e3e5e7'"
      />

      <!-- Suggestions Dropdown -->
      <div
        v-if="showDropdown && (filteredOptions.length > 0 || model.length > 0)"
        style="
          position: absolute;
          top: calc(100% + 6px);
          left: 0;
          right: 0;
          background: #ffffff;
          border: 1px solid #e3e5e7;
          border-radius: 16px;
          box-shadow: 0 8px 32px rgba(15,23,42,0.12);
          padding: 8px;
          z-index: 50;
          max-height: 240px;
          overflow-y: auto;
        "
      >
        <p style="font-size: 10px; font-weight: 500; color: #9499a3; letter-spacing: 1.5px; padding: 4px 8px 8px; text-transform: uppercase;">Saran Pencarian</p>

        <template v-if="filteredOptions.length > 0">
          <div
            v-for="opt in filteredOptions"
            :key="opt"
            style="padding: 8px 12px; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #373f50; transition: background 0.15s ease;"
            @mousedown="model = opt; eksekusiPencarian()"
            @mouseenter="$event.target.style.background = '#f8fafc'"
            @mouseleave="$event.target.style.background = 'transparent'"
          >
            {{ opt }}
          </div>
        </template>

        <template v-else-if="model.length > 0">
          <div style="padding: 8px 12px; font-size: 13px; color: #9499a3;">Tidak ada hasil ditemukan</div>
        </template>
      </div>
    </div>

    <!-- Search Button -->
    <button
      style="
        height: 44px;
        padding: 0 20px;
        background: #0f172a;
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
        box-shadow: 0 4px 12px rgba(15,23,42,0.15);
        font-family: 'Plus Jakarta Sans', sans-serif;
      "
      @click="eksekusiPencarian"
      @mouseenter="$event.target.style.background = '#222a3d'"
      @mouseleave="$event.target.style.background = '#0f172a'"
    >
      Cari
    </button>

  </div>
</template>