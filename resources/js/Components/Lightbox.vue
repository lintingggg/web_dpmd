<template>
  <transition name="fade">
    <div
      v-if="show"
      class="lightbox-overlay"
      @click.self="close"
    >
      <!-- Close Button -->
      <button class="close-btn" @click="close" aria-label="Tutup">
        <span class="material-symbols-outlined" style="font-size: 22px;">close</span>
      </button>

      <!-- Prev Button -->
      <button
        v-if="images.length > 1"
        class="nav-btn prev"
        @click="prevImage"
        aria-label="Sebelumnya"
      >
        <span class="material-symbols-outlined" style="font-size: 24px;">chevron_left</span>
      </button>

      <!-- Image -->
      <img
        :src="images[currentIndex]"
        class="lightbox-image"
        alt="Preview"
      />

      <!-- Image counter -->
      <div v-if="images.length > 1" class="img-counter">
        {{ currentIndex + 1 }} / {{ images.length }}
      </div>

      <!-- Next Button -->
      <button
        v-if="images.length > 1"
        class="nav-btn next"
        @click="nextImage"
        aria-label="Berikutnya"
      >
        <span class="material-symbols-outlined" style="font-size: 24px;">chevron_right</span>
      </button>
    </div>
  </transition>
</template>

<script setup>
import { onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },

  images: {
    type: Array,
    default: () => [],
  },

  currentIndex: {
    type: Number,
    default: 0,
  },
});

const emit = defineEmits([
  "close",
  "update:currentIndex",
]);

function close() {
  emit("close");
}

function nextImage() {
  const next =
    (props.currentIndex + 1) % props.images.length;

  emit("update:currentIndex", next);
}

function prevImage() {
  const prev =
    (props.currentIndex - 1 + props.images.length) %
    props.images.length;

  emit("update:currentIndex", prev);
}

function handleKey(e) {
  if (!props.show) return;

  if (e.key === "Escape") close();

  if (e.key === "ArrowRight") nextImage();

  if (e.key === "ArrowLeft") prevImage();
}

onMounted(() => {
  window.addEventListener("keydown", handleKey);
});

onBeforeUnmount(() => {
  window.removeEventListener("keydown", handleKey);
});
</script>

<style scoped>

.lightbox-overlay{
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.92);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(8px);
}

.lightbox-image{
    max-width: 88%;
    max-height: 82vh;
    border-radius: 16px;
    object-fit: contain;
    box-shadow: 0 24px 64px rgba(0,0,0,0.4);
}

.close-btn{
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1.5px solid rgba(255, 255, 255, 0.15);
    border-radius: 9999px;
    color: white;
    cursor: pointer;
    transition: background 0.2s ease;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.close-btn:hover{
    background: rgba(255, 255, 255, 0.2);
}

.nav-btn{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1.5px solid rgba(255, 255, 255, 0.15);
    border-radius: 9999px;
    color: white;
    cursor: pointer;
    transition: background 0.2s ease;
}

.nav-btn:hover{
    background: rgba(255, 255, 255, 0.2);
}

.prev{
    left: 20px;
}

.next{
    right: 20px;
}

.img-counter{
    position: absolute;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 9999px;
    padding: 6px 16px;
    font-size: 13px;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.fade-enter-active,
.fade-leave-active{
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to{
    opacity: 0;
}

</style>