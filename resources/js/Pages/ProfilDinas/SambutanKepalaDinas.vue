<script setup>
import Navbar from "@/Components/Navbar/Navbar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Footer from "@/Components/Footer.vue";

const profile = {
    title: "Sambutan Kepala Dinas",
    name: "[Nama Kepala Dinas]",
    positionBadge: "KEPALA DINAS",
    nip: "[nomor NIP]",
    phone: "(0283) xxxxxxx",
    email: "dinaspppa@tegalkab.go.id",
    image: "", // Placeholder
    greeting: `
        <p>Assalamu'alaikum Wr Wb, Salam Sejahtera untuk kita semua,</p>
        <p>Puji syukur kami panjatkan ke hadirat Tuhan Yang Maha Esa atas karunia dan hidayah-Nya, sehingga kita semua dapat membaktikan segala hal yang kita miliki untuk kemajuan pada bidang pemberdayaan perempuan, perlindungan Anak, pengendalian Penduduk serta keluarga berencana. Apapun bentuk dan sumbangsih yang kita berikan, jika dilandasi niat yang tulus tanpa memandang imbalan apapun akan menghasilkan karya yang agung untuk bekal kita dan generasi setelah kita.</p>
        <p>Dinas Pemberdayaan Perempuan Perlindungan Anak Pengendalian Penduduk dan Keluarga Berencana Kabupaten Tegal dibentuk berdasarkan Peraturan Daerah Kabupaten Tegal Nomor 12 Tahun 2016 tentang Pembentukan dan Susunan Perangkat Daerah Kabupaten Tegal. Penjabaran Tugas, Fungsi dan Tata Kerja Dinas Pemberdayaan Perempuan, Perlindungan Anak, Pengendalian Penduduk dan Keluarga Berencana berdasarkan Peraturan Bupati Tegal Nomor 71 Tahun 2016 adalah melaksanakan urusan pemerintahan bidang pemberdayaan perempuan dan perlindungan anak, dan bidang pengendalian penduduk dan keluarga berencana yang menjadi kewenangan daerah dan tugas pembantuan yang ditugaskan kepada daerah.</p>
        <p>Peluncuran Website Resmi Dinas P3A dan P2KB Kabupaten Tegal ini merupakan wujud komitmen keterbukaan kami untuk terus dapat memberikan Informasi dan gambaran kinerja program/kegiatan serta untuk meningkatkan pelayanan di Dinas P3A dan P2KB kepada seluruh masyarakat maupun stakeholder lainnya. Kami menyadari website ini masih jauh dari sempurna, untuk itu kami akan senantiasa melakukan perbaikan dan pengembangan secara bertahap demi peningkatan mutu informasi yang kami sajikan. Semoga keberadaan website ini dapat meningkatkan pelayanan mutu di bidang Pemberdayaan Perempuan, Perlindungan Anak, Pengendalian Penduduk dan Keluarga Berencana. Tidak lupa kami sampaikan terimakasih kepada semua pihak yang telah berpartisipasi turut memberikan saran dan masukan.</p>
        <p>Secara pribadi saya mohon maaf, jika pemenuhan tuntutan dan kinerja yang saya lakukan masih ada kelemahan. Oleh karena itu, bantuan dan kerjasama dari berbagai pihak untuk optimalisasi peningkatan kinerja sangat saya harapkan. Mudah-mudahan dalam tiap langkah dan nafas kita menciptakan nilai jual yang tinggi bagi keilmuan dan nilai hakiki di hadapan Tuhan Yang Maha Esa.</p>
        <p>Demikian sambutan ini saya sampaikan, ditutup dengan pesan moral dan keilmuan bagi kita semua.</p>
    `
};
</script>

<template>
    <Navbar />

    <Breadcrumb />

    <section class="page-header">
        <div class="container">
            <h1>{{ profile.title }}</h1>
        </div>
    </section>

    <main class="container page-content">
        <div class="content-wrapper">
            <!-- Kolom Kiri: Konten Utama -->
            <div class="main-column">
                <div class="card content-card" v-html="profile.greeting"></div>
            </div>

            <!-- Kolom Kanan: Sidebar Profil -->
            <aside class="sidebar-column">
                <div class="card sidebar-card">
                    <div class="profile-image-container">
                        <img v-if="profile.image" :src="profile.image" :alt="profile.name" class="profile-image">
                        <div v-else class="image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-person" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="profile-details">
                        <h3 class="profile-name">{{ profile.name }}</h3>
                        <span class="profile-badge">{{ profile.positionBadge }}</span>
                        
                        <div class="profile-info-list">
                            <div class="info-item">
                                <span class="info-label">NIP</span>
                                <span class="info-value">{{ profile.nip }}</span>
                            </div>
                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <span class="info-value">{{ profile.phone }}</span>
                            </div>
                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span class="info-value">{{ profile.email }}</span>
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

/* Page Header */
.page-header {
    background-color: #FFFFFF;
    padding: 40px 0 20px;
}

.page-header h1 {
    font-size: 36px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
}

/* Main Layout */
.page-content {
    margin-bottom: 60px;
    padding-top: 20px;
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
    width: 320px;
    flex-shrink: 0;
}

/* Card General Styling */
.card {
    background: #FFFFFF;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
    border: 1px solid rgba(226, 232, 240, 0.5);
}

/* Content Card */
.content-card {
    padding: 40px;
}

.content-card :deep(p) {
    font-size: 16px;
    line-height: 1.8;
    color: #334155;
    margin-bottom: 20px;
    text-align: justify;
}

.content-card :deep(p:last-child) {
    margin-bottom: 0;
}

/* Sidebar Card */
.sidebar-card {
    padding: 32px 24px;
}

.profile-image-container {
    display: flex;
    justify-content: center;
    margin-bottom: 24px;
}

.profile-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #F1F5F9;
}

.image-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: #F8FAFC;
    border: 2px dashed #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94A3B8;
}

.icon-person {
    width: 64px;
    height: 64px;
}

.profile-details {
    text-align: center;
}

.profile-name {
    font-size: 20px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 12px 0;
}

.profile-badge {
    display: inline-block;
    background: #0369A1;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    margin-bottom: 24px;
}

.profile-info-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
    text-align: left;
    border-top: 1px solid #E2E8F0;
    padding-top: 24px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    color: #475569;
}

.info-item .icon {
    width: 20px;
    height: 20px;
    color: #0369A1;
    flex-shrink: 0;
}

.info-label {
    font-weight: 600;
    color: #0F172A;
    width: 32px;
}

.info-value {
    word-break: break-word;
}

/* Responsive */
@media (max-width: 992px) {
    .content-wrapper {
        flex-direction: column;
    }
    
    .sidebar-column {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .content-card {
        padding: 24px;
    }
    
    .page-header h1 {
        font-size: 28px;
    }
}
</style>