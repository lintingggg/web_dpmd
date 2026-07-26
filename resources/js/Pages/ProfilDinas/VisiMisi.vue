<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { IconHome } from '@tabler/icons-vue';

const props = defineProps<{
    profil: any;
}>();

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Profil Dinas' },
    { label: 'Visi & Misi' }
];
</script>

<template>
    <Head title="Visi & Misi - DPMD Bangkalan" />

    <Navbar />

    <!-- Header Section like SambutanKepalaDinas -->
    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>Visi & Misi DPMD</h1>
        </div>
    </section>

    <main class="container page-content">
        <!-- Content Area -->
        <div class="py-2">
            
            <p class="text-left text-slate-700 mb-10 text-lg leading-relaxed">
                Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berupaya menciptakan tata kelola pemerintahan desa yang inklusif, ekosistem perdesaan yang memberdayakan, dan ruang ekonomi yang aman dan berdaulat.
            </p>

            <!-- VISI Block -->
            <div class="bg-[#0F172A] rounded-2xl shadow-xl p-8 md:p-12 text-center text-white mb-14 relative overflow-hidden">
                <!-- decorative lines inside visi box -->
                <div class="absolute -top-24 -right-24 w-64 h-64 border-[30px] border-white/5 rounded-full"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 border-[20px] border-white/5 rounded-full"></div>

                <div class="relative z-10">
                    <p class="text-blue-200 font-semibold tracking-widest uppercase mb-4 text-sm md:text-base">
                        VISI Dinas Pemberdayaan Masyarakat dan Desa
                    </p>
                    <div class="text-2xl md:text-3xl font-bold leading-snug text-center" v-if="props.profil.visi_teks" v-html="props.profil.visi_teks"></div>
                    <div class="text-2xl md:text-3xl font-bold leading-snug text-center" v-else>
                        (Data Visi Belum Tersedia)
                    </div>
                </div>
            </div>

            <!-- MISI Block -->
            <div class="mt-8 mb-16">
                <p class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">
                    Visi tersebut akan diwujudkan dengan Misi meliputi:
                </p>
                <div class="prose max-w-none text-slate-700 leading-relaxed text-lg prose-li:my-2 prose-ul:list-disc prose-ul:pl-6 text-left" v-if="props.profil.misi_teks" v-html="props.profil.misi_teks"></div>
                <div class="text-slate-600 text-lg text-left" v-else>
                    (Data Misi Belum Tersedia)
                </div>
            </div>

        </div>
    </main>

    <Footer />
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page Header */
.page-header {
    background-color: #FFFFFF;
    padding: 20px 0 30px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
}

/* Main Layout */
.page-content {
    margin-bottom: 60px;
}

/* Prose styles for Visi/Misi content */
.prose :deep(ol), .prose :deep(ul) {
    padding-left: 1.5rem;
    list-style-position: outside;
}
.prose :deep(ol) {
    list-style-type: decimal;
}
.prose :deep(ul) {
    list-style-type: disc;
}
.prose :deep(li) {
    margin-bottom: 0.75rem;
}
.prose :deep(p) {
    margin-bottom: 1rem;
}
</style>
