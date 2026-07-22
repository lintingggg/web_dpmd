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
            class="w-full flex items-center justify-between py-4 text-left font-medium text-neutral-700"
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
                    class="py-2 text-sm text-neutral-500 hover:text-[#103973]"
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