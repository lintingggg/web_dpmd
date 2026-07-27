<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { IconChevronDown } from "@tabler/icons-vue";

interface Child {
    label: string;
    href: string;
}

const props = defineProps<{
    label: string;
    children: Child[];
}>();

const open = ref(false);
</script>

<template>
    <div class="border-b border-neutral-200">

        <button
            @click="open = !open"
            class="w-full flex items-center justify-between py-4 px-3 text-left font-medium rounded-lg transition-all duration-300"
            :class="[
                open
                    ? 'bg-[#0F1B3D] text-white shadow-lg shadow-[#0F1B3D]/30'
                    : 'text-[#646A79] hover:bg-[#0F1B3D] hover:text-white hover:shadow-lg hover:shadow-[#0F1B3D]/30'
            ]"
        >
            {{ label }}

            <IconChevronDown
                :size="18"
                class="transition"
                :class="{ 'rotate-180': open }"
            />
        </button>

        <transition name="accordion">

            <div
                v-if="open"
                class="pb-4 pl-4 flex flex-col"
            >

                <Link
                    v-for="item in children"
                    :key="item.label"
                    :href="item.href"
                    class="py-2 px-3 rounded-lg text-sm text-[#646A79] transition-all duration-200 hover:bg-[#0F1B3D] hover:text-white hover:shadow-lg hover:shadow-[#0F1B3D]/30"
                >

                    {{ item.label }}

                </Link>

            </div>

        </transition>

    </div>
</template>

<style scoped>

.accordion-enter-active,
.accordion-leave-active{

transition:.25s ease;

overflow:hidden;

}

.accordion-enter-from,
.accordion-leave-to{

opacity:0;

max-height:0;

}

.accordion-enter-to,
.accordion-leave-from{

max-height:300px;

}

</style>