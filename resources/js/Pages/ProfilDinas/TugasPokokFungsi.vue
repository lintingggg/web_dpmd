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
    <Head title="Tugas Pokok & Fungsi" />

    <div class="min-h-screen flex flex-col bg-neutral-50">

        <Navbar />

        <main class="flex-1">

            <div class="max-w-6xl mx-auto px-4 py-8">

                <PageHeader
                    title="Tugas Pokok & Fungsi"
                    description="Informasi mengenai tugas pokok dan fungsi Dinas Pemberdayaan Masyarakat dan Desa."
                />

                <!-- Tugas Pokok & Fungsi -->
                <section
                    class="mt-8 rounded-3xl bg-white border border-neutral-200 shadow-sm p-8"
                >

                    <h2 class="text-2xl font-bold text-blue-700 mb-6">
                        Tugas Pokok & Fungsi
                    </h2>

                    <div class="prose max-w-none text-neutral-700 leading-relaxed" v-html="profil.tupoksi_teks"></div>

                </section>

            </div>

        </main>

        <Footer />

    </div>
</template>