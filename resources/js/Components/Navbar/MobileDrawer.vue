<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { IconX } from "@tabler/icons-vue";

import { menu } from "./menu";
import MobileAccordion from "./MobileAccordion.vue";

defineProps<{
    open: boolean;
}>();

const emit = defineEmits([
    "close",
]);
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