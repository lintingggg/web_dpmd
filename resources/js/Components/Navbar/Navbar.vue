<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { IconMenu2 } from "@tabler/icons-vue";

import NavbarLogo from "./NavbarLogo.vue";
import NavbarMenu from "./NavbarMenu.vue";
import MobileDrawer from "./MobileDrawer.vue";

const mobileOpen = ref(false);

const page = usePage();
const kontak = computed(() => page.props.kontak as any);

const isScrolled = ref(false);

function handleScroll() {
    isScrolled.value = window.scrollY > 10;
}

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onBeforeUnmount(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<template>

    <!-- Top Bar -->
    <div class="bg-[#0F172A] text-white py-1.5 px-5 text-xs hidden md:block w-full font-['Plus_Jakarta_Sans',sans-serif]">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Jl. Halim Perdana Kusuma No. 4 Mlajah Bangkalan 69111 Bangkalan
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a 
                    v-if="kontak?.whatsapp"
                    :href="`https://wa.me/${kontak.whatsapp.replace(/[^0-9]/g, '')}`" 
                    target="_blank"
                    class="flex items-center gap-1.5 hover:text-gray-300 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <header
        class="sticky top-0 z-40 transition-all duration-300 w-full bg-white font-['Plus_Jakarta_Sans',sans-serif]"
        :class="[
            isScrolled
                ? 'shadow-md py-2'
                : 'shadow-sm py-3'
        ]"
    >

        <div
            class="max-w-7xl mx-auto h-20 px-5 flex items-center justify-between"
        >

            <!-- Logo -->
            <NavbarLogo />

            <!-- Desktop -->
            <div
                class="hidden lg:flex flex-1 justify-center ml-8 lg:ml-16"
            >
                <NavbarMenu />
            </div>

            <!-- Right Space Desktop -->
            <div
                class="hidden lg:block w-32"
            ></div>

            <!-- Mobile -->
            <button
                class="lg:hidden w-11 h-11 rounded-xl bg-[#0F172A] text-white flex items-center justify-center"
                @click="mobileOpen=true"
            >
                <IconMenu2
                    :size="24"
                />
            </button>

        </div>

    </header>

    <MobileDrawer
        :open="mobileOpen"
        @close="mobileOpen=false"
    />

</template>