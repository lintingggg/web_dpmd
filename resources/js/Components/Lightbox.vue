<template>
  <transition name="fade">
    <div
      v-if="show"
      class="lightbox-overlay"
      @click.self="close"
    >
      <button class="close-btn" @click="close">
        &times;
      </button>

      <button
        v-if="images.length > 1"
        class="nav-btn prev"
        @click="prevImage"
      >
        &#10094;
      </button>

      <img
        :src="images[currentIndex]"
        class="lightbox-image"
        alt="Preview"
      />

      <button
        v-if="images.length > 1"
        class="nav-btn next"
        @click="nextImage"
      >
        &#10095;
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

    position:fixed;

    inset:0;

    background:rgba(0,0,0,.85);

    display:flex;

    justify-content:center;

    align-items:center;

    z-index:9999;

}

.lightbox-image{

    max-width:90%;

    max-height:85vh;

    border-radius:8px;

    object-fit:contain;

}

.close-btn{

    position:absolute;

    top:20px;

    right:25px;

    font-size:40px;

    color:white;

    background:none;

    border:none;

    cursor:pointer;

}

.nav-btn{

    position:absolute;

    top:50%;

    transform:translateY(-50%);

    font-size:45px;

    color:white;

    background:none;

    border:none;

    cursor:pointer;

    padding:15px;

}

.prev{

    left:20px;

}

.next{

    right:20px;

}

.fade-enter-active,
.fade-leave-active{

    transition:opacity .25s;

}

.fade-enter-from,
.fade-leave-to{

    opacity:0;

}

</style>