<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Filter State
const activeCategory = ref('semua');
const categories = ['semua', 'pemerintahan', 'infrastruktur', 'sosial'];

// Dummy Articles
const articles = ref([
    { 
        id: 1, 
        title: 'Kunjungan Kerja Bupati ke Desa Sukamaju', 
        thumbnail: 'https://placehold.co/100x64/c8cbd0/ffffff?text=Img',
        tags: ['Pemerintahan', 'Kunjungan'],
        author: 'Admin Utama',
        date: '24 Okt 2026',
        status: 'published'
    },
    { 
        id: 2, 
        title: 'Pembangunan Jalan Poros Desa Selesai Tepat Waktu', 
        thumbnail: 'https://placehold.co/100x64/c8cbd0/ffffff?text=Img',
        tags: ['Infrastruktur', 'Desa'],
        author: 'Sekretaris Dinas',
        date: '22 Okt 2026',
        status: 'published'
    },
    { 
        id: 3, 
        title: 'Draft Laporan Akhir Tahun Anggaran 2026', 
        thumbnail: null,
        tags: ['Pemerintahan'],
        author: 'Admin Utama',
        date: '20 Okt 2026',
        status: 'draft'
    }
]);

</script>

<template>
    <Head title="Berita & Kegiatan" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Berita & Kegiatan
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola publikasi berita, artikel, dan dokumentasi kegiatan dinas.</p>
            </div>
            
            <button class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Berita
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Filter & Search Bar -->
            <div class="p-6 md:p-8 border-b border-[#e3e5e7] flex flex-col xl:flex-row gap-6 justify-between items-start xl:items-center bg-[#f9f9f9]">
                <!-- Search Box -->
                <div class="relative w-full xl:w-80">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                    <input type="text" placeholder="Cari judul berita..." class="w-full bg-white border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                </div>
                
                <!-- Filter Pills -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full xl:w-auto overflow-x-auto pb-2 sm:pb-0">
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] font-bold text-[#9499a3] uppercase tracking-[1px] whitespace-nowrap">Filter Kategori:</span>
                        <div class="flex gap-2">
                            <button 
                                v-for="cat in categories" :key="cat"
                                @click="activeCategory = cat"
                                class="px-4 py-1.5 rounded-full text-[13px] font-bold capitalize transition-colors whitespace-nowrap"
                                :class="activeCategory === cat ? 'bg-[#0f172a] text-white' : 'bg-[#e3e5e7] text-[#646a79] hover:bg-[#c8cbd0] hover:text-[#0f172a]'"
                            >
                                {{ cat }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="hidden sm:block w-[1px] h-8 bg-[#c8cbd0]"></div>
                    
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] font-bold text-[#9499a3] uppercase tracking-[1px]">Status:</span>
                        <select class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c8cbd0]">
                            <option>Semua Status</option>
                            <option>Published</option>
                            <option>Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Judul Berita</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-48">Kategori & Tag</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-48">Penulis / Tgl</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-32">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        <tr v-for="article in articles" :key="article.id" class="hover:bg-[#f9f9f9]/50 transition-colors group">
                            
                            <!-- Judul & Thumbnail -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-14 rounded-lg bg-[#f0f1f1] flex items-center justify-center flex-shrink-0 border border-[#e3e5e7] overflow-hidden">
                                        <img v-if="article.thumbnail" :src="article.thumbnail" alt="Thumbnail" class="w-full h-full object-cover" />
                                        <span v-else class="material-symbols-outlined text-[#9499a3] text-[24px]">image_not_supported</span>
                                    </div>
                                    <p class="font-bold text-[#0f172a] leading-tight group-hover:text-[#1976d2] transition-colors cursor-pointer line-clamp-2">
                                        {{ article.title }}
                                    </p>
                                </div>
                            </td>
                            
                            <!-- Tags -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-1.5 items-start">
                                    <span v-for="tag in article.tags" :key="tag" class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold tracking-[0.5px] uppercase bg-[#f0f1f1] text-[#373f50]">
                                        {{ tag }}
                                    </span>
                                </div>
                            </td>

                            <!-- Penulis & Tgl -->
                            <td class="py-4 px-6">
                                <p class="font-bold text-[#0f172a] mb-0.5">{{ article.author }}</p>
                                <p class="text-[12px] text-[#646a79]">{{ article.date }}</p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span v-if="article.status === 'published'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Published
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f0f1f1] text-[#646a79] border border-[#e3e5e7]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#9499a3]"></span> Draft
                                </span>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button class="w-8 h-8 rounded-full hover:bg-[#e3e5e7] text-[#646a79] flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#e3e5e7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f9f9f9]">
                <p class="text-[13px] font-medium text-[#646a79]">Menampilkan <span class="font-bold text-[#0f172a]">1-3</span> dari <span class="font-bold text-[#0f172a]">45</span> berita</p>
                
                <div class="flex items-center gap-1">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9499a3] hover:text-[#0f172a] hover:bg-[#e3e5e7] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#0f172a] text-white font-bold text-[13px]">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#646a79] font-medium text-[13px] hover:bg-[#e3e5e7] transition-colors">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#646a79] font-medium text-[13px] hover:bg-[#e3e5e7] transition-colors">3</button>
                    <span class="w-8 h-8 flex items-center justify-center text-[#9499a3]">...</span>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9499a3] hover:text-[#0f172a] hover:bg-[#e3e5e7] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
