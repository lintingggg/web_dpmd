<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

interface Child {

    label:string;

    href:string;

}

defineProps<{

    items:Child[];

}>();
</script>

<template>

<div
    class="absolute left-0 top-full pt-4 z-50"
>

    <div
        class="w-72 rounded-2xl bg-white border border-neutral-200 shadow-xl overflow-hidden"
    >

        <Link
            v-for="item in items"
            :key="item.label"
            :href="item.href"
            class="block px-6 py-4 hover:bg-[#F8FAFC] transition group"
        >

            <div
                class="font-medium text-[#646A79] group-hover:text-[#0F172A]"
            >

                {{ item.label }}

            </div>

        </Link>

    </div>

</div>

</template>