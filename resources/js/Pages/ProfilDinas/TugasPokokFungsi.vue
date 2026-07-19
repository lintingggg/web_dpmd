<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";

const tugasPokok =
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";

const fungsi = [
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
];
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

                <!-- Tugas Pokok -->
                <section
                    class="mt-8 rounded-3xl bg-white border border-neutral-200 shadow-sm p-8"
                >

                    <h2 class="text-2xl font-bold text-blue-700 mb-6">
                        Tugas Pokok
                    </h2>

                    <p class="text-neutral-700 leading-8 text-justify">
                        {{ tugasPokok }}
                    </p>

                </section>

                <!-- Fungsi -->
                <section
                    class="mt-8 rounded-3xl bg-white border border-neutral-200 shadow-sm p-8"
                >

                    <h2 class="text-2xl font-bold text-blue-700 mb-8">
                        Fungsi
                    </h2>

                    <div class="grid gap-5">

                        <div
                            v-for="(item, index) in fungsi"
                            :key="index"
                            class="flex gap-5 p-5 rounded-2xl border border-neutral-200 hover:border-blue-300 hover:shadow-md transition"
                        >

                            <div
                                class="w-12 h-12 rounded-xl bg-blue-700 text-white flex items-center justify-center font-bold shrink-0"
                            >
                                {{ index + 1 }}
                            </div>

                            <div>

                                <h3 class="font-semibold text-neutral-900">
                                    Fungsi {{ index + 1 }}
                                </h3>

                                <p class="mt-2 text-neutral-600 leading-7">
                                    {{ item }}
                                </p>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

        </main>

        <Footer />

    </div>
</template>