<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from "vue";
import { IconMenu2 } from "@tabler/icons-vue";

import NavbarLogo from "./NavbarLogo.vue";
import NavbarMenu from "./NavbarMenu.vue";
import MobileDrawer from "./MobileDrawer.vue";

const mobileOpen = ref(false);

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

<header
    class="sticky top-0 z-50 transition-all duration-300"
    :class="[
        isScrolled
            ? 'bg-white shadow-md'
            : 'bg-white'
    ]"
>

    <div
        class="max-w-7xl mx-auto h-20 px-5 flex items-center justify-between"
    >

        <!-- Logo -->

        <NavbarLogo />

        <!-- Desktop -->

        <div
            class="hidden lg:flex flex-1 justify-center"
        >

            <NavbarMenu />

        </div>

        <!-- Right Space Desktop -->

        <div
            class="hidden lg:block w-32"
        ></div>

        <!-- Mobile -->

        <button
            class="lg:hidden w-11 h-11 rounded-xl bg-[#103973] text-white flex items-center justify-center"
            @click="mobileOpen=true"
        >

            <IconMenu2
                :size="24"
            />

        </button>

    </div>

    <MobileDrawer
        :open="mobileOpen"
        @close="mobileOpen=false"
    />

</header>

</template>