<script setup lang="ts">
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { IconChevronDown } from "@tabler/icons-vue";

import NavbarMegaMenu from "./NavbarMegaMenu.vue";
import { menu } from "./menu";

const page = usePage();

const activeDropdown = ref<string | null>(null);

function isActive(href?: string) {
    if (!href) return false;

    return page.url === href;
}

function isParentActive(children?: { href: string }[]) {
    if (!children) return false;

    return children.some((child) => page.url.startsWith(child.href));
}
</script>

<template>
    <nav class="hidden lg:flex items-center gap-2">

        <template
            v-for="item in menu"
            :key="item.label"
        >

            <!-- MENU BIASA -->

            <Link
                v-if="!item.children"
                :href="item.href!"
                class="px-4 py-2.5 rounded-full text-sm font-semibold transition-all duration-300"
                :class="[
                    isActive(item.href)
                        ? 'bg-[#0F1B3D] text-white shadow-lg shadow-[#0F1B3D]/30'
                        : 'text-[#646A79] hover:bg-[#0F1B3D] hover:text-white hover:shadow-lg hover:shadow-[#0F1B3D]/30'
                ]"
            >

                {{ item.label }}

            </Link>

            <!-- DROPDOWN -->

            <div
                v-else
                class="relative"

                @mouseenter="activeDropdown=item.label"

                @mouseleave="activeDropdown=null"
            >

                <button
                    class="flex items-center gap-1.5 px-4 py-2.5 rounded-full text-sm font-semibold transition-all duration-300"
                    :class="[
                        isParentActive(item.children) || activeDropdown===item.label
                            ? 'bg-[#0F1B3D] text-white shadow-lg shadow-[#0F1B3D]/30'
                            : 'text-[#646A79] hover:bg-[#0F1B3D] hover:text-white hover:shadow-lg hover:shadow-[#0F1B3D]/30'
                    ]"
                >

                    {{ item.label }}

                    <IconChevronDown
                        :size="16"
                        class="transition duration-200"
                        :class="{
                            'rotate-180':activeDropdown===item.label
                        }"
                    />

                </button>

                <transition name="dropdown">

                    <NavbarMegaMenu
                        v-if="activeDropdown===item.label"
                        :items="item.children"
                    />

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

transform:translateY(10px);

}

</style>