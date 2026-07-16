<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
// import Breadcrumb from "@/Components/Breadcrumb.vue";
import PageHeader from "@/Components/PageHeader.vue";

import OrganizationTree from "@/Components/OrganizationTree.vue";


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
                    <OrganizationTree />
                </section>

            </div>

        </main>

        <!-- Footer -->
        <Footer />

    </div>
</template>