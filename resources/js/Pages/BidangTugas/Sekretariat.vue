<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { IconHome } from '@tabler/icons-vue';
import Navbar from '../../Components/Navbar/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import Breadcrumb from '../../Components/Breadcrumb.vue';

const breadcrumbItems = [
  { label: 'Beranda', href: '/', icon: IconHome },
  { label: 'Bidang Tugas', href: '/error404' },
  { label: 'Sekretariat' },
];

const dasarFungsi = {
  deskripsi: "Sekretariat mempunyai tugas melaksanakan pengelolaan surat menyurat, kearsipan, administrasi kepegawaian, keuangan, perlengkapan dan rumah tangga kantor serta pengkoordinasian penyusunan rencana program, evaluasi dan pelaporan.",
  kalimatPengantar: "Dalam melaksanakan tugas tersebut, Sekretariat menyelenggarakan fungsi:",
  listFungsi: [
    "Pelaksanaan pengkoordinasian penyusunan rencana program dan kegiatan;",
    "Pelayanan administrasi umum, ketatausahaan, kearsipan dan dokumentasi dalam rangka menunjang kelancaran pelaksanaan tugas;",
    "Pengelolaan administrasi keuangan dan urusan kepegawaian;",
    "Pengelolaan urusan rumah tangga, perlengkapan dan inventaris kantor;",
    "Pelayanan administrasi perjalanan dinas;",
    "Pelaksanaan pengkoordinasian bidang-bidang di lingkup Dinas Pemberdayaan Masyarakat dan Desa;",
    "Pengkoordinasian dan penyusunan laporan hasil pelaksanaan program dan kegiatan; dan",
    "Pelaksanaan tugas kedinasan lain yang diberikan Kepala Dinas sesuai dengan bidang tugasnya."
  ]
};
</script>

<template>
    <Head title="Sekretariat - DPMD Bangkalan" />

    <Navbar />

    <section class="page-header">
        <div class="container">
            <Breadcrumb :items="breadcrumbItems" class="mb-4 -ml-5" />
            <h1>Sekretariat</h1>
        </div>
    </section>

    <main class="container page-content">
        <div class="py-2">
            
            <p class="text-left text-slate-700 mb-10 text-lg leading-relaxed">
                {{ dasarFungsi.deskripsi }}
            </p>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mb-16">
                <!-- Garis kecil -->
                <hr class="w-16 h-1 bg-blue-500 border-0 rounded mb-6">
                
                <!-- Gambar setelah garis kecil -->
                <img src="https://placehold.co/800x400?text=Sekretariat" alt="Sekretariat" class="w-full h-auto rounded-xl mb-8 object-cover bg-slate-100 min-h-[200px]" />

                <div class="text-slate-700 leading-relaxed text-lg text-left">
                    <p class="mb-3 font-medium">{{ dasarFungsi.kalimatPengantar }}</p>
                    <ol class="list-decimal pl-5 space-y-2">
                      <li v-for="(fungsi, index) in dasarFungsi.listFungsi" :key="index">
                        {{ fungsi }}
                      </li>
                    </ol>
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
</style>