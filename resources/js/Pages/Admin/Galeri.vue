<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Dummy data for albums
const albums = ref([
    {
        id: 1,
        title: 'Peringatan Hari Jadi Kabupaten Bangkalan ke-492',
        date: '24 Okt 2026',
        photoCount: 42,
        coverUrl: 'https://placehold.co/600x400/c8cbd0/ffffff?text=Hari+Jadi',
        status: 'published'
    },
    {
        id: 2,
        title: 'Kunjungan Kerja Kementerian Desa RI',
        date: '15 Sep 2026',
        photoCount: 128,
        coverUrl: 'https://placehold.co/600x400/c8cbd0/ffffff?text=Kunker',
        status: 'published'
    },
    {
        id: 3,
        title: 'Rapat Koordinasi Kepala Desa Se-Kabupaten',
        date: '02 Sep 2026',
        photoCount: 15,
        coverUrl: 'https://placehold.co/600x400/c8cbd0/ffffff?text=Rakor',
        status: 'published'
    },
    {
        id: 4,
        title: 'Pelatihan Kewirausahaan BUMDes',
        date: '12 Agu 2026',
        photoCount: 0,
        coverUrl: null,
        status: 'draft'
    }
]);
</script>

<template>
    <Head title="Galeri Dokumentasi" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Galeri Dokumentasi
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola album foto dan dokumentasi kegiatan DPMD Bangkalan.</p>
            </div>
            
            <button class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                Buat Album Baru
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                <input type="text" placeholder="Cari nama album..." class="w-full bg-white border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-full pl-11 pr-4 py-2.5 shadow-[0_2px_8px_rgba(15,23,42,0.04)] focus:ring-[#0f172a] focus:border-[#0f172a]" />
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <select class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-full px-4 py-2.5 shadow-[0_2px_8px_rgba(15,23,42,0.04)] focus:ring-0 focus:border-[#c8cbd0]">
                    <option>Urutkan: Terbaru</option>
                    <option>Urutkan: Terlama</option>
                    <option>Urutkan: Foto Terbanyak</option>
                </select>
            </div>
        </div>

        <!-- Album Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <div v-for="album in albums" :key="album.id" class="bg-white rounded-[20px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all cursor-pointer flex flex-col">
                <!-- Cover Image -->
                <div class="relative aspect-[4/3] bg-[#f0f1f1] overflow-hidden">
                    <img v-if="album.coverUrl" :src="album.coverUrl" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div v-else class="w-full h-full flex flex-col items-center justify-center text-[#9499a3]">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">image_not_supported</span>
                        <span class="text-[12px] font-bold">Belum ada foto</span>
                    </div>
                    
                    <!-- Overlay Actions -->
                    <div class="absolute inset-0 bg-[#0f172a]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                        <button class="w-10 h-10 rounded-full bg-white text-[#0f172a] hover:bg-[#e3e5e7] flex items-center justify-center transition-colors shadow-lg" title="Lihat/Edit Foto">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button class="w-10 h-10 rounded-full bg-[#ba1a1a] text-white hover:bg-[#93000a] flex items-center justify-center transition-colors shadow-lg" title="Hapus Album">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>

                    <!-- Photo Count Badge -->
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-[#0f172a] text-[11px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[14px]">photo_library</span>
                        {{ album.photoCount }}
                    </div>
                </div>

                <!-- Album Info -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-[#646a79] tracking-[0.5px] uppercase">{{ album.date }}</span>
                            <span v-if="album.status === 'published'" class="w-2 h-2 rounded-full bg-[#137333]" title="Published"></span>
                            <span v-else class="w-2 h-2 rounded-full bg-[#9499a3]" title="Draft"></span>
                        </div>
                        <h3 class="text-[15px] font-bold text-[#0f172a] leading-snug line-clamp-2 group-hover:text-[#1976d2] transition-colors">
                            {{ album.title }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Add New Album Card (Ghost Card) -->
            <div class="border-2 border-dashed border-[#c8cbd0] rounded-[20px] bg-[#f9f9f9]/50 hover:bg-[#f9f9f9] transition-colors cursor-pointer flex flex-col items-center justify-center min-h-[250px] group">
                <div class="w-16 h-16 rounded-full bg-white border border-[#e3e5e7] shadow-sm flex items-center justify-center text-[#0f172a] mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">add</span>
                </div>
                <h3 class="text-[15px] font-bold text-[#0f172a] mb-1">Buat Album Baru</h3>
                <p class="text-[13px] text-[#646a79]">Tambah koleksi dokumentasi</p>
            </div>

        </div>

    </AuthenticatedLayout>
</template>
