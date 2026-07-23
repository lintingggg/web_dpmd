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
                        ? 'bg-[#F8FAFC] text-[#0F172A] shadow-sm'
                        : 'text-[#646A79] hover:bg-[#F8FAFC] hover:text-[#0F172A]'
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
                        isParentActive(item.children)
                            ? 'bg-[#F8FAFC] text-[#0F172A] shadow-sm'
                            : 'text-[#646A79] hover:bg-[#F8FAFC] hover:text-[#0F172A]'
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