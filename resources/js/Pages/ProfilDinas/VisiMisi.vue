<script setup>
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ProseViewer from "@/Components/ProseViewer.vue";
import { IconHome } from '@tabler/icons-vue';

const props = defineProps({
    profil: Object
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Profil Dinas', href: '/error404'  },
    { label: 'Visi & Misi' }
];
</script>

<template>
    <Head title="Visi & Misi - DPMD Bangkalan" />

    <Navbar />

    <main class="container page-content pt-8">
        <PageHeader 
            :breadcrumbs="breadcrumbItems"
            title="Visi & Misi DPMD"
            description="Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berupaya menciptakan tata kelola pemerintahan desa yang inklusif, ekosistem perdesaan yang memberdayakan, dan ruang ekonomi yang aman dan berdaulat."
        />

        <div class="py-2">
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
                <ProseViewer :html="props.profil.misi_teks" placeholder="(Data Misi Belum Tersedia)" />
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

.page-content {
    margin-bottom: 60px;
}
</style>
