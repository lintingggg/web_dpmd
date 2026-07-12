<script setup lang="ts">
import { ref } from "vue";
import { IconMenu2 } from "@tabler/icons-vue";

import NavbarMenu from "./NavbarMenu.vue";
import MobileDrawer from "./MobileDrawer.vue";

const drawerOpen = ref(false);
</script>

<template>
    <header class="w-full">

        <!-- HEADER BIRU -->
        <div class="bg-[#0E3478] text-white">

            <div
                class="max-w-7xl mx-auto px-4 py-5 flex justify-between items-center"
            >

                <!-- Logo + Judul -->
                <div class="flex items-center gap-4">

                    <div
                        class="w-16 h-16 rounded-full bg-white text-[#0E3478] flex items-center justify-center font-bold shrink-0"
                    >
                        LOGO
                    </div>

                    <div>

                        <p class="text-sm opacity-90">
                            Pemerintah Kabupaten
                        </p>

                        <h1
                            class="text-xl lg:text-2xl font-bold leading-tight"
                        >
                            Dinas Pemberdayaan Masyarakat dan Desa
                        </h1>

                    </div>

                </div>

                <!-- HAMBURGER -->
                <button
                    class="lg:hidden p-2 rounded-lg hover:bg-blue-600 transition"
                    @click="drawerOpen = true"
                >
                    <IconMenu2 :size="30" />
                </button>

            </div>

        </div>

        <!-- NAVBAR PUTIH -->
        <div class="bg-white border-b shadow-sm hidden lg:block">

            <div
                class="max-w-7xl mx-auto h-16 px-4 flex justify-center items-center"
            >

                <NavbarMenu />

            </div>

        </div>

        <!-- MOBILE DRAWER -->
        <MobileDrawer
            :open="drawerOpen"
            @close="drawerOpen = false"
        />

    </header>
</template>