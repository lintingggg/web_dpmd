<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps<{
    profil: any;
}>();
</script>

<template>

<Head title="Visi & Misi" />

<div class="min-h-screen flex flex-col bg-neutral-50">

    <Navbar />

    <main class="flex-1">

        <div class="max-w-6xl mx-auto px-4 py-8">

            <PageHeader
                title="Visi & Misi"
                description="Visi dan Misi Dinas Pemberdayaan Masyarakat dan Desa."
            />

            <!-- VISI -->

            <section
                class="mt-8 rounded-3xl bg-white border border-neutral-200 shadow-sm p-8"
            >

                <h2
                    class="text-2xl font-bold text-blue-700 mb-6"
                >

                    Visi

                </h2>

                <div
                    class="border-l-4 border-blue-600 pl-6 italic text-lg text-neutral-700 leading-8"
                    v-html="profil.visi_teks"
                ></div>

            </section>

            <!-- MISI -->

            <section
                class="mt-8 rounded-3xl bg-white border border-neutral-200 shadow-sm p-8"
            >

                <h2
                    class="text-2xl font-bold text-blue-700 mb-8"
                >

                    Misi

                </h2>

                <div
                    class="space-y-5"
                >
                    <div class="prose max-w-none text-neutral-700 leading-relaxed" v-html="profil.misi_teks"></div>
                </div>

            </section>

        </div>

    </main>

    <Footer />

</div>

</template>