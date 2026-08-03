<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import '@idds/vue/index.css';

const showingNavigationDropdown = ref(false);
const page = usePage();
// Dropdown otomatis terbuka jika salah satu link di dalamnya sedang aktif/dipilih
const profilDinasOpen = ref(route().current('admin.profil-dinas'));
const bidangTugasOpen = ref(route().current('admin.bidang-tugas'));

</script>

<template>
    <div class="bg-[#f9f9f9] font-['Plus_Jakarta_Sans'] text-[#1a1c1c] antialiased flex h-screen overflow-hidden">
            
            <!-- SideNavBar -->
            <nav class="fixed left-0 top-0 h-screen w-72 flex flex-col p-6 bg-[#0f172a] shadow-xl z-50 text-white">
                <div class="mb-12">
                    <h1 class="text-[18px] font-bold text-white mb-1">DPMD Bangkalan</h1>
                    <p class="text-[11px] font-medium text-[#9499a3] tracking-widest uppercase">Admin CMS</p>
                </div>
                
                <div class="flex-1 overflow-y-auto space-y-1">
                    <!-- Dashboard Link -->
                    <Link :href="route('dashboard')" 
                        class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold transition-all duration-200 active:scale-95 mb-2"
                        :class="route().current('dashboard') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white group'"
                    >
                        <span class="material-symbols-outlined" :style="route().current('dashboard') ? 'font-variation-settings: \'FILL\' 1;' : ''">dashboard</span>
                        Dashboard
                    </Link>

                    <template v-if="$page.props.auth.user.role === 'superadmin'">
                        <Link :href="route('admin.pengaturan-beranda')" 
                            class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold text-left transition-all duration-200 active:scale-95 mb-2 group"
                            :class="route().current('admin.pengaturan-beranda') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'"
                        >
                            <span class="material-symbols-outlined transition-colors" :style="route().current('admin.pengaturan-beranda') ? 'font-variation-settings: \'FILL\' 1;' : ''">view_carousel</span>
                            Banner Utama
                        </Link>
                    </template>

                    <Link :href="route('admin.berita')" 
                        class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold transition-all duration-200 active:scale-95 mb-2 group"
                        :class="route().current('admin.berita') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'"
                    >
                        <span class="material-symbols-outlined transition-colors" :style="route().current('admin.berita') ? 'font-variation-settings: \'FILL\' 1;' : ''">newspaper</span>
                        Berita
                    </Link>

                    <template v-if="$page.props.auth.user.role === 'superadmin'">
                        <Link :href="route('admin.agenda.index')" 
                            class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold text-left transition-all duration-200 active:scale-95 mb-2 group"
                            :class="route().current('admin.agenda.*') || route().current('admin.agenda.index') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'"
                        >
                            <span class="material-symbols-outlined transition-colors" :style="route().current('admin.agenda.*') || route().current('admin.agenda.index') ? 'font-variation-settings: \'FILL\' 1;' : ''">event</span>
                            Agenda Acara
                        </Link>
                        
                        <Link :href="route('admin.album')" 
                            class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold transition-all duration-200 active:scale-95 mb-2 group"
                            :class="route().current('admin.album') || route().current('admin.galeri') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'"
                        >
                            <span class="material-symbols-outlined transition-colors" :style="route().current('admin.album') || route().current('admin.galeri') ? 'font-variation-settings: \'FILL\' 1;' : ''">collections</span>
                            Galeri Dokumentasi
                        </Link>

                        <Link :href="route('admin.publikasi-dokumen')" 
                            class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold transition-all duration-200 active:scale-95 mb-2 group"
                            :class="route().current('admin.publikasi-dokumen') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'"
                        >
                            <span class="material-symbols-outlined transition-colors" :style="route().current('admin.publikasi-dokumen') ? 'font-variation-settings: \'FILL\' 1;' : ''">folder_open</span>
                            Publikasi Dokumen
                        </Link>

                        <div>
                            <button @click="profilDinasOpen = !profilDinasOpen; bidangTugasOpen = false" class="w-full flex items-center gap-4 font-bold text-left text-[#9499a3] hover:bg-[#222a3d] hover:text-white rounded-lg px-4 py-2 transition-all duration-200 active:scale-95 mb-2 group">
                                <span class="material-symbols-outlined group-hover:text-white transition-colors">account_balance</span>
                                Profil Dinas
                                <span class="material-symbols-outlined ml-auto text-[18px] transition-transform duration-200" :class="profilDinasOpen ? 'rotate-90' : ''">chevron_right</span>
                            </button>
                            <!-- Dropdown Content -->
                            <div v-show="profilDinasOpen" class="pl-8 pr-4 space-y-1 mb-2">
                                <Link :href="route('admin.profil-dinas', { section: 'sambutan' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'sambutan' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Sambutan Kadis</Link>
                                <Link :href="route('admin.profil-dinas', { section: 'visi-misi' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'visi-misi' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Visi & Misi</Link>
                                <Link :href="route('admin.profil-dinas', { section: 'tupoksi' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'tupoksi' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Tugas Pokok & Fungsi</Link>
                                <Link :href="route('admin.profil-dinas', { section: 'struktur' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'struktur' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Struktur Organisasi</Link>
                                <Link :href="route('admin.profil-dinas', { section: 'kode-etik' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'kode-etik' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Kode Etik Pelayanan</Link>
                                <Link :href="route('admin.profil-dinas', { section: 'maklumat' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'maklumat' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Maklumat Pelayanan</Link>
                                <Link :href="route('admin.profil-dinas', { section: 'motto' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.profil-dinas', { section: 'motto' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Motto Pelayanan</Link>
                            </div>
                        </div>
                        
                        <div>
                            <button @click="bidangTugasOpen = !bidangTugasOpen; profilDinasOpen = false" class="w-full flex items-center gap-4 font-bold text-left text-[#9499a3] hover:bg-[#222a3d] hover:text-white rounded-lg px-4 py-2 transition-all duration-200 active:scale-95 mb-2 group">
                                <span class="material-symbols-outlined group-hover:text-white transition-colors">assignment</span>
                                Bidang Tugas
                                <span class="material-symbols-outlined ml-auto text-[18px] transition-transform duration-200" :class="bidangTugasOpen ? 'rotate-90' : ''">chevron_right</span>
                            </button>
                            <!-- Dropdown Content -->
                            <div v-show="bidangTugasOpen" class="pl-8 pr-4 space-y-1 mb-2">
                                <Link :href="route('admin.bidang-tugas', { section: 'pemdes' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.bidang-tugas', { section: 'pemdes' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Pemerintahan Desa</Link>
                                <Link :href="route('admin.bidang-tugas', { section: 'pemberdayaan' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.bidang-tugas', { section: 'pemberdayaan' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Pemberdayaan Desa</Link>
                                <Link :href="route('admin.bidang-tugas', { section: 'lembaga' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.bidang-tugas', { section: 'lembaga' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Lembaga Kemasyarakatan</Link>
                                <Link :href="route('admin.bidang-tugas', { section: 'sekretariat' })" class="block text-[14.5px] font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95" :class="route().current('admin.bidang-tugas', { section: 'sekretariat' }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'">Sekretariat</Link>
                            </div>
                        </div>

                        <Link :href="route('admin.kontak-medsos')" 
                            class="flex items-center gap-4 rounded-lg px-4 py-2 font-bold text-left transition-all duration-200 active:scale-95 mb-2 group"
                            :class="route().current('admin.kontak-medsos') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'text-[#9499a3] hover:bg-[#222a3d] hover:text-white'"
                        >
                            <span class="material-symbols-outlined transition-colors" :style="route().current('admin.kontak-medsos') ? 'font-variation-settings: \'FILL\' 1;' : ''">contact_phone</span>
                            Kontak & Medsos
                        </Link>
                    </template>
                </div>

                <div class="mt-auto pt-6 border-t border-[#373f50]">
                    <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-4 font-bold text-[#ba1a1a] hover:bg-[#222a3d] rounded-lg px-4 py-2 transition-all duration-200 active:scale-95 group text-left">
                        <span class="material-symbols-outlined">logout</span>
                        Logout
                    </Link>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="flex-1 ml-72 flex flex-col h-screen relative bg-[#f9f9f9]">
                
                <!-- TopAppBar -->
                <header class="fixed top-0 right-0 left-72 h-16 flex justify-end items-center px-8 z-40 bg-white border-b border-[#e3e5e7] shadow-sm transition-shadow duration-200">
                    
                    <div class="flex items-center gap-6">
                        <button class="text-[#646a79] hover:text-[#0f172a] hover:bg-[#e3e5e7] p-2 rounded-full transition-colors relative">
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-[#ba1a1a] rounded-full"></span>
                        </button>
                        <div class="flex items-center gap-2">
                            <img alt="Admin Avatar" class="w-8 h-8 rounded-full object-cover border border-[#c8cbd0]" src="https://ui-avatars.com/api/?name=Admin+DPMD&background=0f172a&color=ffffff" />
                            <span class="font-medium text-[14px] text-[#0f172a] font-bold hidden md:block">{{ $page.props.auth.user.name }}</span>
                        </div>
                    </div>
                </header>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto pt-24 px-8 pb-16">
                    <div class="max-w-7xl mx-auto space-y-8">
                        <slot />
                    </div>
                </div>

            </main>
        </div>
</template>
