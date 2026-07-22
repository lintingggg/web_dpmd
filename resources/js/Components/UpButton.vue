<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { IconArrowUp } from "@tabler/icons-vue";

// Persentase scroll (0 - 100), dipakai untuk mengisi lingkaran progress
const scrollPercent = ref(0);

// Tombol hanya muncul setelah user scroll melewati batas tertentu
const isVisible = ref(false);
const SHOW_AFTER_PX = 300;

// Setup lingkaran SVG untuk progress ring
const RADIUS = 26;
const CIRCUMFERENCE = 2 * Math.PI * RADIUS;

function handleScroll() {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const docHeight =
        document.documentElement.scrollHeight - document.documentElement.clientHeight;

    const percent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    scrollPercent.value = Math.min(100, Math.max(0, percent));

    isVisible.value = scrollTop > SHOW_AFTER_PX;
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
}

onMounted(() => {
    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll();
});

onBeforeUnmount(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<template>
    <Transition name="up-btn">
        <button
            v-if="isVisible"
            @click="scrollToTop"
            class="up-button"
            aria-label="Kembali ke atas"
            type="button"
        >
            <svg class="progress-ring" width="64" height="64" viewBox="0 0 64 64">
                <!-- Track lingkaran (abu-abu tipis, dasar) -->
                <circle
                    class="progress-ring__track"
                    cx="32"
                    cy="32"
                    :r="RADIUS"
                    fill="none"
                    stroke-width="4"
                />
                <!-- Progress lingkaran (biru, mengikuti panjang scroll) -->
                <circle
                    class="progress-ring__fill"
                    cx="32"
                    cy="32"
                    :r="RADIUS"
                    fill="none"
                    stroke-width="4"
                    stroke-linecap="round"
                    :stroke-dasharray="CIRCUMFERENCE"
                    :stroke-dashoffset="CIRCUMFERENCE - (scrollPercent / 100) * CIRCUMFERENCE"
                />
            </svg>

            <span class="up-button__content">
                <IconArrowUp :size="16" stroke-width="2.5" />
                <span class="up-button__label">TOP</span>
            </span>
        </button>
    </Transition>
</template>

<style scoped>
.up-button {
    position: fixed;
    right: 24px;
    bottom: 24px;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: none;
    padding: 0;
    background: #ffffff;
    box-shadow:
        0 8px 20px -8px rgba(15, 23, 42, 0.25),
        0 2px 6px -2px rgba(15, 23, 42, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 50;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.up-button:hover {
    transform: translateY(-3px);
    box-shadow:
        0 12px 24px -8px rgba(15, 23, 42, 0.3),
        0 4px 8px -2px rgba(15, 23, 42, 0.15);
}

.up-button:active {
    transform: translateY(-1px) scale(0.97);
}

/* Ring SVG diputar -90deg supaya progress mulai dari jam 12 */
.progress-ring {
    position: absolute;
    top: 0;
    left: 0;
    transform: rotate(-90deg);
}

.progress-ring__track {
    stroke: #e5e9f0;
}

.progress-ring__fill {
    stroke: #2563eb;
    transition: stroke-dashoffset 0.1s linear;
}

.up-button__content {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1px;
    color: #2563eb;
}

.up-button__label {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
}

/* Transisi muncul/hilang tombol */
.up-btn-enter-active,
.up-btn-leave-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}

.up-btn-enter-from,
.up-btn-leave-to {
    opacity: 0;
    transform: translateY(12px) scale(0.9);
}
</style>