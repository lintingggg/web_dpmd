<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import {
    IconSpeakerphone,
    IconTarget,
    IconChecklist,
    IconSitemap,
    IconHeartHandshake,
    IconShieldCheck,
    IconGavel,
    IconBriefcase,
    IconBuildingCommunity,
    IconUsersGroup,
    IconHomeHeart,
    IconCircleDot
} from "@tabler/icons-vue";

interface Child {
    label: string;
    href: string;
}

defineProps<{
    items: Child[];
}>();

const iconMap: Record<string, any> = {
    // Dropdown Profil
    "Sambutan Kepala Dinas": IconSpeakerphone,
    "Visi & Misi": IconTarget,
    "Tugas Pokok & Fungsi": IconChecklist,
    "Struktur Organisasi": IconSitemap,
    "Motto Pelayanan": IconHeartHandshake,
    "Maklumat Pelayanan": IconShieldCheck,
    "Kode Etik Pelayanan": IconGavel,

    // Dropdown Bidang
    "Sekretariat": IconBriefcase,
    "Pemerintahan Desa": IconBuildingCommunity,
    "Pemberdayaan Desa": IconUsersGroup,
    "Pemberdayaan Lembaga Kemasyarakatan": IconHomeHeart,
};

function getIcon(label: string) {
    return iconMap[label] || IconCircleDot;
}
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
            class="flex items-center gap-3 px-6 py-4 transition-all duration-200 hover:bg-[#0F1B3D] hover:shadow-lg group outline-none focus:outline-none ring-0 focus:ring-0"
        >

            <component
                :is="getIcon(item.label)"
                :size="18"
                class="shrink-0 text-[#646A79] group-hover:text-white transition-colors duration-200"
            />

            <div
                class="font-medium text-[#646A79] group-hover:text-white transition-colors duration-200"
            >

                {{ item.label }}

            </div>

        </Link>

    </div>

</div>

</template>