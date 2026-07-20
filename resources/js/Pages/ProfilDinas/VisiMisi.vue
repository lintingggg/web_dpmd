<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";

const visi =
    "Terwujudnya masyarakat desa yang mandiri, maju, sejahtera, dan berdaya saing melalui tata kelola pemerintahan desa yang profesional.";

const misi = [
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
];
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

                <blockquote
                    class="border-l-4 border-blue-600 pl-6 italic text-lg text-neutral-700 leading-8"
                >

                    "{{ visi }}"

                </blockquote>

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

                    <div
                        v-for="(item,index) in misi"
                        :key="index"
                        class="flex gap-5 items-start"
                    >

                        <div
                            class="w-14 h-14 rounded-2xl bg-blue-700 text-white flex items-center justify-center text-lg font-bold shrink-0"
                        >

                            {{ String(index+1).padStart(2,'0') }}

                        </div>

                        <div>

                            <h3
                                class="font-semibold text-neutral-900"
                            >

                                Misi {{ index+1 }}

                            </h3>

                            <p
                                class="mt-1 text-neutral-600 leading-7"
                            >

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