<script setup lang="ts">
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { 
    IconChevronDown,
    IconHome2,
    IconUser,
    IconLayoutGrid,
    IconFileText,
    IconPhoto,
    IconNews
} from "@tabler/icons-vue";

import NavbarMegaMenu from "./NavbarMegaMenu.vue";
import { menu } from "./menu";

const page = usePage();

const activeDropdown = ref<string | null>(null);

const iconMap: Record<string, any> = {
    "Beranda": IconHome2,
    "Profil": IconUser,
    "Bidang": IconLayoutGrid,
    "Dokumen": IconFileText,
    "Galeri": IconPhoto,
    "Berita": IconNews,
};

function getIcon(label: string) {
    return iconMap[label] || IconHome2;
}

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

            <div class="relative">
                <Link
                    v-if="!item.children"
                    :href="item.href!"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-full text-sm font-semibold transition-all duration-300"
                    :class="[
                        isActive(item.href)
                            ? 'bg-white text-[#0F172A] shadow-lg shadow-black/30 ring-1 ring-black/5'
                            : 'text-white hover:bg-white hover:text-[#0F172A] hover:shadow-lg hover:shadow-black/30'
                    ]"
                >
                    <component
                        :is="getIcon(item.label)"
                        :size="16"
                        class="shrink-0"
                    />

                    {{ item.label }}

                </Link>

                <span
                    v-if="!item.children && isActive(item.href)"
                    class="menu-underline"
                ></span>
            </div>

            <!-- DROPDOWN -->

            <div
                v-if="item.children"
                class="relative"

                @mouseenter="activeDropdown=item.label"

                @mouseleave="activeDropdown=null"
            >

                <button
                    class="flex items-center gap-1 px-4 py-2.5 rounded-full text-sm font-semibold transition-all duration-300"
                    :class="[
                        isParentActive(item.children) || activeDropdown===item.label
                            ? 'bg-white text-[#0F172A] shadow-lg shadow-black/30 ring-1 ring-black/5'
                            : 'text-white hover:bg-white hover:text-[#0F172A] hover:shadow-lg hover:shadow-black/30'
                    ]"
                >
                    <component
                        :is="getIcon(item.label)"
                        :size="16"
                        class="shrink-0"
                    />

                    {{ item.label }}

                    <IconChevronDown
                        :size="14"
                        class="transition-transform duration-200 opacity-80"
                        :class="{
                            'rotate-180':activeDropdown===item.label
                        }"
                    />

                </button>

                <span
                    v-if="isParentActive(item.children)"
                    class="menu-underline"
                ></span>

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

.menu-underline{
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -8px;
    width: 24px;
    height: 3px;
    border-radius: 2px;
    background-color: #ffffff;
}

</style>