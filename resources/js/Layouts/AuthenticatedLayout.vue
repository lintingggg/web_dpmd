<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import '@idds/vue/index.css';

const showingNavigationDropdown = ref(false);
const isSidebarCollapsed = ref(false);
const page = usePage();

onMounted(() => {
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        isSidebarCollapsed.value = true;
    }
});

watch(isSidebarCollapsed, (newVal) => {
    localStorage.setItem('sidebarCollapsed', newVal);
});

// Dropdown otomatis terbuka jika salah satu link di dalamnya sedang aktif/dipilih
const profilDinasOpen = ref(page.url.startsWith('/admin/profil-dinas'));
const bidangTugasOpen = ref(page.url.startsWith('/admin/bidang-tugas'));
const kontakMedsosOpen = ref(page.url.startsWith('/admin/kontak-medsos'));

const toggleMobileSidebar = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
};

const toggleDesktopSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
    // Tutup dropdown jika sidebar diperkecil
    if (isSidebarCollapsed.value) {
        profilDinasOpen.value = false;
        bidangTugasOpen.value = false;
        kontakMedsosOpen.value = false;
    }
};

const handleDropdownClick = (dropdownType) => {
    if (dropdownType === 'profil') {
        profilDinasOpen.value = !profilDinasOpen.value;
        bidangTugasOpen.value = false;
        kontakMedsosOpen.value = false;
    } else if (dropdownType === 'bidang') {
        bidangTugasOpen.value = !bidangTugasOpen.value;
        profilDinasOpen.value = false;
        kontakMedsosOpen.value = false;
    } else if (dropdownType === 'kontak') {
        kontakMedsosOpen.value = !kontakMedsosOpen.value;
        profilDinasOpen.value = false;
        bidangTugasOpen.value = false;
    }
};
</script>

<template>
    <div class="bg-[#f8fafc] font-sans text-slate-900 antialiased flex h-screen overflow-hidden">
        
        <!-- SideNavBar -->
        <nav :class="[
                showingNavigationDropdown ? 'translate-x-0' : '-translate-x-full', 
                'md:translate-x-0 transition-all duration-300 ease-in-out fixed left-0 top-0 h-screen flex flex-col bg-white shadow-xl md:shadow-none md:border-r border-slate-200 z-50',
                isSidebarCollapsed ? 'w-24' : 'w-72'
            ]">
            
            <!-- Header Sidebar (Logo + Title) with bottom border -->
            <div class="relative flex items-center border-b border-slate-100 p-6 flex-shrink-0 min-h-[5.5rem]"
                 :class="isSidebarCollapsed ? 'justify-center' : 'justify-start'">
                <!-- Close Button (Mobile) -->
                <button @click="toggleMobileSidebar" class="md:hidden absolute -top-2 -right-2 text-slate-400 hover:text-slate-900 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center z-10 shadow-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                
                <div class="flex items-center gap-3">
                    <img src="/assets/Logo-kabupaten-Bangkalan.png" alt="Logo Bangkalan" class="w-10 h-10 object-contain drop-shadow-sm flex-shrink-0 transition-transform hover:scale-105">
                    <div v-show="!isSidebarCollapsed" class="overflow-hidden whitespace-nowrap transition-all opacity-100 duration-300">
                        <h1 class="text-[17px] leading-tight font-extrabold text-[#103973] tracking-tight">DPMD Bangkalan</h1>
                        <p class="text-[10px] font-bold text-[#528be6] tracking-widest uppercase mt-0.5">Admin Panel</p>
                    </div>
                </div>
            </div>
            
            <!-- Menu Area -->
            <!-- custom-scrollbar class is assumed to exist in your global CSS to hide/style scrollbar -->
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-4 pb-12 custom-scrollbar">
                
                <!-- SECTION 1: DASHBOARD & MONITORING -->
                <p v-show="!isSidebarCollapsed" class="px-4 text-[10px] font-bold text-slate-400 mb-3 whitespace-nowrap uppercase tracking-wider">Dashboard & Monitoring</p>
                <div class="space-y-1.5 mb-6">
                    <Link :href="route('dashboard')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/dashboard') || $page.url === '/dashboard' ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Dashboard' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/dashboard') || $page.url === '/dashboard' ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-solid fa-chart-pie text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Dashboard</span>
                    </Link>

                    <Link :href="route('admin.log-aktivitas')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/admin/log-aktivitas') ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Log Aktivitas' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/admin/log-aktivitas') ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Log Aktivitas</span>
                    </Link>
                </div>

                <!-- SECTION 2: KONTEN PUBLIK -->
                <p v-show="!isSidebarCollapsed" class="px-4 text-[10px] font-bold text-slate-400 mb-3 whitespace-nowrap uppercase tracking-wider">Konten Publik</p>
                <div class="space-y-1.5 mb-6">
                    <Link :href="route('admin.pengaturan-beranda')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/admin/pengaturan-beranda') ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Banner Utama' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/admin/pengaturan-beranda') ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-solid fa-pager text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Banner Utama</span>
                    </Link>

                    <Link :href="route('admin.berita')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/admin/berita') ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Berita' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/admin/berita') ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-regular fa-newspaper text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Berita</span>
                    </Link>

                    <Link :href="route('admin.agenda.index')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/admin/agenda') ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Agenda Acara' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/admin/agenda') ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-regular fa-calendar-days text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Agenda Acara</span>
                    </Link>
                    
                    <Link :href="route('admin.album')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/admin/album') || $page.url.startsWith('/admin/galeri') ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Galeri Dokumentasi' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/admin/album') || $page.url.startsWith('/admin/galeri') ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-regular fa-images text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Galeri Dokumentasi</span>
                    </Link>

                    <Link :href="route('admin.publikasi-dokumen')" 
                        class="flex items-center gap-3 rounded-xl font-bold transition-colors group relative outline-none"
                        :class="[
                            $page.url.startsWith('/admin/publikasi-dokumen') ? 'bg-[#f0f4f8] text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8] font-medium',
                            isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                        ]"
                        :title="isSidebarCollapsed ? 'Publikasi Dokumen' : ''"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                             :class="$page.url.startsWith('/admin/publikasi-dokumen') ? 'bg-[#1e56a0] text-white shadow-sm' : 'text-slate-400 group-hover:text-slate-600'">
                            <i class="fa-regular fa-folder-open text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Publikasi Dokumen</span>
                    </Link>
                </div>

                <!-- SECTION 3: PROFIL & STRUKTUR -->
                <p v-show="!isSidebarCollapsed" class="px-4 text-[10px] font-bold text-slate-400 mb-3 whitespace-nowrap uppercase tracking-wider">Profil & Struktur</p>
                <div class="space-y-1.5 mb-6">
                    <!-- Dropdown: Profil Dinas -->
                    <div>
                        <button @click="handleDropdownClick('profil')" 
                                class="w-full flex items-center gap-3 rounded-xl font-medium transition-colors group outline-none relative"
                                :class="[
                                    $page.url.startsWith('/admin/profil-dinas') ? 'text-[#103973] font-bold bg-[#f0f4f8]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8]',
                                    isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                                ]"
                                :title="isSidebarCollapsed ? 'Profil Dinas' : ''">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 transition-colors"
                                 :class="$page.url.startsWith('/admin/profil-dinas') ? 'bg-[#1e56a0] text-white shadow-sm rounded-full' : 'text-slate-400 group-hover:text-slate-600 rounded-full'">
                                <i class="fa-solid fa-building-flag text-sm"></i>
                            </div>
                            <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Profil Dinas</span>
                            <i v-show="!isSidebarCollapsed" class="fa-solid fa-chevron-down ml-auto text-[10px] text-slate-300 group-hover:text-slate-400 transition-transform duration-200" :class="profilDinasOpen ? '-rotate-180 text-[#1e56a0]' : ''"></i>
                        </button>
                        
                        <div v-show="!isSidebarCollapsed && profilDinasOpen" class="pl-14 pr-3 space-y-1 mt-1 mb-2">
                            <Link :href="route('admin.profil-dinas', { section: 'sambutan' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('sambutan') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Sambutan Kadis</Link>
                            <Link :href="route('admin.profil-dinas', { section: 'visi-misi' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('visi-misi') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Visi & Misi</Link>
                            <Link :href="route('admin.profil-dinas', { section: 'tupoksi' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('tupoksi') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Tugas Pokok & Fungsi</Link>
                            <Link :href="route('admin.profil-dinas', { section: 'struktur' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('struktur') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Struktur Organisasi</Link>
                            <Link :href="route('admin.profil-dinas', { section: 'kode-etik' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('kode-etik') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Kode Etik Pelayanan</Link>
                            <Link :href="route('admin.profil-dinas', { section: 'maklumat' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('maklumat') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Maklumat Pelayanan</Link>
                            <Link :href="route('admin.profil-dinas', { section: 'motto' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('motto') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Motto Pelayanan</Link>
                        </div>
                    </div>
                    
                    <!-- Dropdown: Bidang Tugas -->
                    <div>
                        <button @click="handleDropdownClick('bidang')" 
                                class="w-full flex items-center gap-3 rounded-xl font-medium transition-colors group outline-none relative"
                                :class="[
                                    $page.url.startsWith('/admin/bidang-tugas') ? 'text-[#103973] font-bold bg-[#f0f4f8]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8]',
                                    isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                                ]"
                                :title="isSidebarCollapsed ? 'Bidang Tugas' : ''">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 transition-colors"
                                 :class="$page.url.startsWith('/admin/bidang-tugas') ? 'bg-[#1e56a0] text-white shadow-sm rounded-full' : 'text-slate-400 group-hover:text-slate-600 rounded-full'">
                                <i class="fa-solid fa-sitemap text-sm"></i>
                            </div>
                            <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Bidang Tugas</span>
                            <i v-show="!isSidebarCollapsed" class="fa-solid fa-chevron-down ml-auto text-[10px] text-slate-300 group-hover:text-slate-400 transition-transform duration-200" :class="bidangTugasOpen ? '-rotate-180 text-[#1e56a0]' : ''"></i>
                        </button>
                        
                        <div v-show="!isSidebarCollapsed && bidangTugasOpen" class="pl-14 pr-3 space-y-1 mt-1 mb-2">
                            <Link :href="route('admin.bidang-tugas', { section: 'pemdes' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('pemdes') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Pemerintahan Desa</Link>
                            <Link :href="route('admin.bidang-tugas', { section: 'pemberdayaan' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('pemberdayaan') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Pemberdayaan Desa</Link>
                            <Link :href="route('admin.bidang-tugas', { section: 'lembaga' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('lembaga') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Lembaga Kemasyarakatan</Link>
                            <Link :href="route('admin.bidang-tugas', { section: 'sekretariat' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('sekretariat') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Sekretariat</Link>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: PENGATURAN -->
                <p v-show="!isSidebarCollapsed" class="px-4 text-[10px] font-bold text-slate-400 mb-3 whitespace-nowrap uppercase tracking-wider">Pengaturan</p>
                <div class="space-y-1.5">
                    <!-- Dropdown: Kontak & Medsos -->
                    <div>
                        <button @click="handleDropdownClick('kontak')" 
                                class="w-full flex items-center gap-3 rounded-xl font-medium transition-colors group outline-none relative"
                                :class="[
                                    $page.url.startsWith('/admin/kontak-medsos') ? 'text-[#103973] font-bold bg-[#f0f4f8]' : 'text-slate-500 hover:text-[#103973] hover:bg-[#f0f4f8]',
                                    isSidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3'
                                ]"
                                :title="isSidebarCollapsed ? 'Kontak & Medsos' : ''">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 transition-colors"
                                 :class="$page.url.startsWith('/admin/kontak-medsos') ? 'bg-[#1e56a0] text-white shadow-sm rounded-full' : 'text-slate-400 group-hover:text-slate-600 rounded-full'">
                                <i class="fa-solid fa-share-nodes text-sm"></i>
                            </div>
                            <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Kontak & Medsos</span>
                            <i v-show="!isSidebarCollapsed" class="fa-solid fa-chevron-down ml-auto text-[10px] text-slate-300 group-hover:text-slate-400 transition-transform duration-200" :class="kontakMedsosOpen ? '-rotate-180 text-[#1e56a0]' : ''"></i>
                        </button>
                        
                        <div v-show="!isSidebarCollapsed && kontakMedsosOpen" class="pl-14 pr-3 space-y-1 mt-1 mb-2">
                            <Link :href="route('admin.kontak-medsos', { section: 'kontak' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('kontak-medsos/kontak') || $page.url === '/admin/kontak-medsos' ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Informasi Kontak</Link>
                            <Link :href="route('admin.kontak-medsos', { section: 'sosmed' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('kontak-medsos/sosmed') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Tautan Sosmed</Link>
                            <Link :href="route('admin.kontak-medsos', { section: 'embedding' })" class="block text-sm font-semibold py-2 px-3 rounded-xl transition-all" :class="$page.url.includes('kontak-medsos/embedding') ? 'bg-slate-100 text-[#103973]' : 'text-slate-500 hover:text-[#103973] hover:bg-slate-50'">Pengaturan Embedding</Link>
                        </div>
                    </div>
                    
                    <Link :href="route('logout')" method="post" as="button" 
                        class="w-full flex items-center gap-3 rounded-xl font-medium transition-colors group mt-2 relative outline-none"
                        :class="[
                            isSidebarCollapsed ? 'p-3 justify-center text-slate-400 hover:text-rose-600' : 'px-4 py-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50'
                        ]"
                        :title="isSidebarCollapsed ? 'Logout' : ''"
                    >
                        <div class="w-8 h-8 flex items-center justify-center text-slate-300 group-hover:text-rose-500 flex-shrink-0 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                        </div>
                        <span v-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity">Logout</span>
                    </Link>
                </div>
                
                <!-- App Promo Banner (Minimalist Style) -->
                <div v-show="!isSidebarCollapsed" class="mt-8 bg-[#1e56a0] rounded-2xl p-5 text-white relative overflow-hidden group shadow-lg shadow-[#1e56a0]/20">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-[#103973] rounded-full opacity-50 group-hover:scale-110 transition-transform duration-700"></div>
                    <div class="absolute -left-2 -top-2 w-16 h-16 bg-[#528be6] rounded-full opacity-20"></div>
                    
                    <div class="relative z-10">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mb-4 backdrop-blur-sm border border-white/10">
                            <i class="fa-solid fa-earth-asia text-sm text-white"></i>
                        </div>
                        <h4 class="font-semibold text-sm mb-1">Portal Publik</h4>
                        <p class="text-[11px] text-blue-100 mb-5 leading-relaxed font-light">Cek tampilan website dari sisi masyarakat.</p>
                        <a href="/" target="_blank" class="block w-full bg-white text-[#1e56a0] hover:bg-slate-100 text-xs text-center font-bold py-2.5 rounded-xl transition-colors shadow-sm">
                            Buka Website
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Overlay for mobile when sidebar is open -->
        <div v-if="showingNavigationDropdown" @click="toggleMobileSidebar" class="fixed inset-0 bg-slate-900/50 z-40 md:hidden backdrop-blur-sm transition-opacity"></div>

        <!-- Main Content Area -->
        <main :class="isSidebarCollapsed ? 'md:ml-24' : 'md:ml-72'" class="flex-1 flex flex-col h-screen relative bg-[#f8fafc] w-full transition-all duration-300 ease-in-out">
            
            <!-- TopAppBar -->
            <header class="h-20 flex items-center justify-between px-6 lg:px-10 flex-shrink-0 bg-white/80 backdrop-blur-md border-b border-slate-200/70 shadow-sm z-30 sticky top-0">
                
                <div class="flex items-center gap-4">
                    <!-- Toggle Mobile -->
                    <button class="md:hidden text-slate-500 hover:text-slate-900 focus:outline-none bg-white p-2 rounded-lg shadow-sm border border-slate-200" @click="toggleMobileSidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <!-- Toggle Desktop -->
                    <button class="hidden md:flex text-slate-500 hover:text-[#1e56a0] hover:bg-blue-50 focus:outline-none bg-white w-10 h-10 items-center justify-center rounded-full shadow-sm border border-slate-200 transition-colors" @click="toggleDesktopSidebar">
                        <i class="fa-solid" :class="isSidebarCollapsed ? 'fa-indent' : 'fa-outdent'"></i>
                    </button>
                    
                    <!-- Search Bar -->
                    <div class="hidden sm:flex items-center bg-white border border-slate-200 rounded-full px-4 py-2 w-64 lg:w-80 shadow-sm focus-within:ring-2 focus-within:ring-[#528be6]/50 focus-within:border-[#528be6] transition-all">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Cari data, berita..." class="bg-transparent border-none outline-none text-sm w-full ml-3 text-slate-700 placeholder-slate-400 font-medium focus:ring-0">
                        <div class="flex items-center justify-center bg-slate-100 rounded md px-1.5 py-0.5 ml-2 text-[10px] text-slate-400 font-bold tracking-widest border border-slate-200">
                            /
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:gap-5">
                    <!-- Notification Bell -->
                    <button class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-[#1e56a0] hover:bg-blue-50 transition-all relative shadow-sm outline-none">
                        <i class="fa-regular fa-bell"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    
                    <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                    
                    <!-- User Profile (Text on left, Image on right) -->
                    <Link :href="route('profile.edit')" class="flex items-center gap-3 cursor-pointer group outline-none">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-slate-900 leading-tight group-hover:text-[#1e56a0] transition-colors">{{ $page.props.auth.user.name }}</p>
                            <p class="text-[11px] text-slate-500 font-medium capitalize mt-0.5">{{ $page.props.auth.user.role }}</p>
                        </div>
                        <img src="https://placehold.co/100x100/1e56a0/ffffff?text=AD" alt="Admin Profile" class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm object-cover group-hover:ring-[#528be6] transition-all flex-shrink-0">
                    </Link>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-6 lg:px-10 pb-16 custom-scrollbar">
                <slot />
            </div>

        </main>
    </div>
</template>
