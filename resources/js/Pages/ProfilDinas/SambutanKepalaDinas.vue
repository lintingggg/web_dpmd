<script setup>
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ProseViewer from "@/Components/ProseViewer.vue";
import { Head } from '@inertiajs/vue3';
import { IconHome } from '@tabler/icons-vue';

const props = defineProps({
    profil: Object
});

const breadcrumbItems = [
    { label: 'Beranda', href: '/', icon: IconHome },
    { label: 'Profil Dinas', href: '/error404'  },
    { label: 'Sambutan Kepala Dinas' }
];
</script>

<template>
    <Head title="Sambutan Kepala Dinas - DPMD Bangkalan" />

    <Navbar />

    <main class="container page-content pt-8">
        <PageHeader 
            :breadcrumbs="breadcrumbItems"
            title="Sambutan Kepala Dinas"
        />

        <div class="content-wrapper mt-4">
            <!-- Kolom Kiri: Konten Utama -->
            <div class="main-column">
                <div class="card content-card">
                    <ProseViewer :html="props.profil.sambutan_teks" placeholder="Belum ada data sambutan kepala dinas." />
                </div>
            </div>

            <!-- Kolom Kanan: Sidebar Profil -->
            <aside class="sidebar-column">
                <div class="card sidebar-card">
                    <div class="profile-image-container">
                        <img v-if="props.profil.kadis_foto" :src="'/storage/' + props.profil.kadis_foto" :alt="props.profil.kadis_nama" class="profile-image">
                        <div v-else class="image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-person" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="profile-details">
                        <h3 class="profile-name">{{ props.profil.kadis_nama || 'Nama Kepala Dinas' }}</h3>
                        <span class="profile-badge">Kepala Dinas</span>
                        
                        <div class="profile-info-list" v-if="props.profil.kadis_nip">
                            <div class="info-item">
                                <span class="info-label">NIP</span>
                                <span class="info-value font-medium">{{ props.profil.kadis_nip }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
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

.content-wrapper {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}

.main-column {
    flex: 1;
    min-width: 0;
}

.sidebar-column {
    width: 350px;
    flex-shrink: 0;
    position: sticky;
    top: 100px;
}

/* Card General Styling */
.card {
    background: #FFFFFF;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    border: 1px solid rgba(226, 232, 240, 0.6);
    overflow: hidden;
}

/* Content Card */
.content-card {
    padding: 40px;
}

/* Sidebar Card */
.sidebar-card {
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: linear-gradient(to bottom, #f8fafc, #ffffff);
}

.profile-image-container {
    display: flex;
    justify-content: center;
    margin-bottom: 28px;
    position: relative;
}

.profile-image {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid #ffffff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    position: relative;
    z-index: 1;
}

.image-placeholder {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: #e2e8f0;
    border: 6px solid #ffffff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    position: relative;
    z-index: 1;
}

.icon-person {
    width: 80px;
    height: 80px;
}

.profile-details {
    text-align: center;
    width: 100%;
}

.profile-name {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
}

.profile-badge {
    display: inline-block;
    background: #0056b3;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    margin-bottom: 28px;
    box-shadow: 0 4px 10px rgba(0, 86, 179, 0.3);
}

.profile-info-list {
    display: flex;
    flex-direction: column;
    width: 100%;
    border-top: 1px dashed #cbd5e1;
    padding-top: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    font-size: 15px;
    color: #475569;
}

.info-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-value {
    color: #0f172a;
    word-break: break-word;
}

/* Responsive */
@media (max-width: 992px) {
    .content-wrapper {
        flex-direction: column;
    }
    
    .sidebar-column {
        width: 100%;
        position: relative;
        top: 0;
    }
}

@media (max-width: 768px) {
    .content-card {
        padding: 24px;
    }
}
</style>
