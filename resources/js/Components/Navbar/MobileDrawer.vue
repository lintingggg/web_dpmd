<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { IconX } from "@tabler/icons-vue";
import { computed } from "vue";

import { menu } from "./menu";
import MobileAccordion from "./MobileAccordion.vue";

defineProps<{
    open: boolean;
}>();

const emit = defineEmits([
    "close",
]);

const page = usePage();
const kontak = computed(() => page.props.kontak as any);
</script>

<template>

<transition name="drawer">

<div
    v-if="open"
    class="fixed inset-0 z-50"
>

    <!-- Overlay -->

    <div
        class="absolute inset-0 bg-black/40"
        @click="emit('close')"
    ></div>

    <!-- Drawer -->

    <aside
        class="absolute right-0 top-0 h-full w-80 bg-white shadow-xl p-6 overflow-y-auto"
    >

        <div
            class="flex items-center justify-between mb-8"
        >

            <h2
                class="font-bold text-xl text-[#0F172A]"
            >

                Menu

            </h2>

            <button
                @click="emit('close')"
            >

                <IconX />

            </button>

        </div>

        <template
            v-for="item in menu"
            :key="item.label"
        >

            <MobileAccordion
                v-if="item.children"
                :label="item.label"
                :children="item.children"
            />

            <Link
                v-else
                :href="item.href!"
                class="block py-4 border-b border-neutral-200 font-medium text-[#646A79] hover:text-[#0F172A]"
                @click="emit('close')"
            >

                {{ item.label }}

            </Link>

        </template>

        <!-- Contact Section in Mobile -->
        <div class="mt-8 pt-6 border-t border-neutral-200">
            <a
                v-if="kontak?.whatsapp"
                :href="`https://wa.me/${kontak.whatsapp.replace(/[^0-9]/g, '')}`"
                target="_blank"
                class="flex items-center justify-center gap-3 bg-[#0F172A] text-white px-5 py-3.5 rounded-xl font-bold text-sm shadow-md hover:bg-[#1e293b] transition-all"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"></path>
                    <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"></path>
                </svg>
                Hubungi Kami
            </a>
        </div>

    </aside>

</div>

</transition>

</template>

<style scoped>

.drawer-enter-active,
.drawer-leave-active{

transition:.3s ease;

}

.drawer-enter-from,
.drawer-leave-to{

opacity:0;

}

.drawer-enter-from aside,
.drawer-leave-to aside{

transform:translateX(100%);

}

.drawer-enter-active aside,
.drawer-leave-active aside{

transition:.3s ease;

}

</style>