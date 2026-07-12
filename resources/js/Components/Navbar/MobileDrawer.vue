<script setup lang="ts">
import { ref } from "vue";
import {
    IconChevronDown,
    IconChevronUp,
    IconX
} from "@tabler/icons-vue";

import { menu } from "./menu";

const props = defineProps<{
    open: boolean;
}>();

const emit = defineEmits<{
    (e: "close"): void;
}>();

const activeMenu = ref<string | null>(null);

function toggleMenu(label: string) {
    activeMenu.value =
        activeMenu.value === label
            ? null
            : label;
}
</script>

<template>

<!-- Overlay -->

<transition name="fade">

<div
    v-if="open"
    class="fixed inset-0 bg-black/40 z-40 lg:hidden"
    @click="emit('close')"
/>

</transition>

<!-- Drawer -->

<transition name="slide">

<aside
    v-if="open"
    class="fixed left-0 top-0 h-screen w-80 bg-white z-50 shadow-xl overflow-y-auto lg:hidden"
>

    <!-- Header -->

    <div
        class="bg-blue-700 text-white p-5 flex items-center justify-between"
    >

        <div class="flex items-center gap-3">

            <div
                class="w-12 h-12 rounded-full bg-white text-blue-700 flex items-center justify-center font-bold"
            >
                LOGO
            </div>

            <div>

                <p class="text-xs opacity-80">

                    Pemerintah Kabupaten

                </p>

                <h2 class="font-semibold">

                    DPMD

                </h2>

            </div>

        </div>

        <button
            @click="emit('close')"
        >

            <IconX :size="22"/>

        </button>

    </div>

    <!-- Menu -->

    <nav class="py-2">

        <template
            v-for="item in menu"
            :key="item.label"
        >

            <!-- Menu biasa -->

            <a
                v-if="!item.children"
                :href="item.href"
                class="flex px-6 py-4 text-neutral-700 hover:bg-neutral-100"
                @click="emit('close')"
            >

                {{ item.label }}

            </a>

            <!-- Accordion -->

            <div
                v-else
            >

                <button
                    class="w-full px-6 py-4 flex justify-between items-center hover:bg-neutral-100"
                    @click="toggleMenu(item.label)"
                >

                    <span>

                        {{ item.label }}

                    </span>

                    <IconChevronDown
                        v-if="activeMenu !== item.label"
                        :size="18"
                    />

                    <IconChevronUp
                        v-else
                        :size="18"
                    />

                </button>

                <transition
                    name="accordion"
                >

                <div
                    v-show="activeMenu===item.label"
                    class="bg-neutral-50"
                >

                    <a
                        v-for="child in item.children"
                        :key="child.label"
                        :href="child.href"
                        class="block pl-12 pr-6 py-3 text-sm hover:bg-neutral-100"
                        @click="emit('close')"
                    >

                        {{ child.label }}

                    </a>

                </div>

                </transition>

            </div>

        </template>

    </nav>

</aside>

</transition>

</template>

<style scoped>

.fade-enter-active,
.fade-leave-active{
transition:.25s;
}

.fade-enter-from,
.fade-leave-to{
opacity:0;
}

.slide-enter-active,
.slide-leave-active{
transition:.25s ease;
}

.slide-enter-from,
.slide-leave-to{
transform:translateX(-100%);
}

.accordion-enter-active,
.accordion-leave-active{
transition:all .25s;
overflow:hidden;
}

.accordion-enter-from,
.accordion-leave-to{
max-height:0;
opacity:0;
}

.accordion-enter-to,
.accordion-leave-from{
max-height:500px;
opacity:1;
}

</style>