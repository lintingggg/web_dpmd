<!-- <script setup lang="ts">

import { menu } from "@/Components/Navbar/menu";

</script>

<template>

<nav
class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-neutral-200"
>

<div
class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between"
>

<div class="lg:hidden">

☰

</div>

<div
class="hidden lg:flex gap-8"
>

<a
v-for="item in menu"
:key="item.label"
:href="item.href"
class="text-sm font-medium hover:text-blue-600"
>

{{ item.label }}

</a>

</div>

</div>

</nav>

</template> -->

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { IconChevronDown } from "@tabler/icons-vue";

import { menu } from "./menu";

const page = usePage();

const openDropdown = ref<string | null>(null);

function toggleDropdown(label: string) {
    openDropdown.value =
        openDropdown.value === label
            ? null
            : label;
}

function closeDropdown() {
    openDropdown.value = null;
}

function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement;

    if (!target.closest(".navbar-menu")) {
        closeDropdown();
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

function isActive(href?: string) {
    if (!href) return false;

    return page.url === href;
}
</script>

<template>

<nav
class="navbar-menu hidden lg:flex items-center gap-8"
>

    <template
        v-for="item in menu"
        :key="item.label"
    >

        <!-- Menu tanpa dropdown -->

        <Link
            v-if="!item.children"
            :href="item.href!"
            class="text-sm font-medium transition"
            :class="[
                isActive(item.href)
                    ? 'text-blue-600'
                    : 'text-neutral-700 hover:text-blue-600'
            ]"
        >

            {{ item.label }}

        </Link>

        <!-- Dropdown -->

        <div
            v-else
            class="relative"

            @mouseenter="openDropdown=item.label"

            @mouseleave="closeDropdown"
        >

            <button
                class="flex items-center gap-1 text-sm font-medium text-neutral-700 hover:text-blue-600 transition"
                @click="toggleDropdown(item.label)"
            >

                {{ item.label }}

                <IconChevronDown
                    :size="16"
                    class="transition-transform"
                    :class="{
                        'rotate-180':
                        openDropdown===item.label
                    }"
                />

            </button>

            <transition name="dropdown">

                <div
                    v-if="openDropdown===item.label"
                    class="absolute left-0 top-full pt-3 w-64 z-50"
                >

                    <div
                        class="rounded-xl border border-neutral-200 bg-white shadow-xl overflow-hidden"
                    >

                        <Link
                            v-for="child in item.children"
                            :key="child.label"
                            :href="child.href"
                            class="block px-5 py-3 text-sm transition"
                            :class="[
                                isActive(child.href)
                                    ? 'bg-blue-50 text-blue-600 font-medium'
                                    : 'hover:bg-neutral-50 text-neutral-700'
                            ]"
                        >

                            {{ child.label }}

                        </Link>

                    </div>

                </div>

            </transition>

        </div>

    </template>

</nav>

</template>

<style scoped>

.dropdown-enter-active,
.dropdown-leave-active{

transition:.18s ease;

}

.dropdown-enter-from,
.dropdown-leave-to{

opacity:0;

transform:translateY(8px);

}

</style>