<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
// import Breadcrumb from "@/Components/Breadcrumb.vue";
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps<{
    profil: any;
}>();
</script>

<template>
    <Head title="Struktur Organisasi" />

    <div class="min-h-screen flex flex-col bg-neutral-50">

        <!-- Navbar -->
        <Navbar />

        <!-- Content -->
        <main class="flex-1">

            <div class="max-w-7xl mx-auto px-4 py-8">

                <!-- Breadcrumb -->
                <!-- <Breadcrumb /> -->

                <!-- Header -->
                <PageHeader
                    title="Struktur Organisasi"
                    description="Struktur organisasi Dinas Pemberdayaan Masyarakat dan Desa."
                />

                <!-- Card -->
                <section
                    class="mt-8 rounded-3xl border border-neutral-200 bg-white shadow-sm p-8"
                >
                    <div v-if="profil.struktur_gambar" class="mb-8">
                        <img :src="profil.struktur_gambar.startsWith('http') ? profil.struktur_gambar : '/storage/' + profil.struktur_gambar" alt="Struktur Organisasi" class="max-w-full h-auto rounded-lg shadow-sm mx-auto">
                    </div>
                    
                    <div class="prose max-w-none text-neutral-700 leading-relaxed text-center" v-html="profil.struktur_keterangan || '<p>Belum ada informasi struktur organisasi.</p>'"></div>
                </section>

            </div>

        </main>

        <!-- Footer -->
        <Footer />

    </div>
</template>