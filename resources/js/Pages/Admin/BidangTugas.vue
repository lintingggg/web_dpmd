<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();
// Menerima parameter bidang dari URL
const currentBidang = computed(() => route().params.bidang || 'pemdes');

// Definisi Judul Halaman berdasarkan bidang
const bidangTitles = {
    'pemdes': 'Bidang Pemerintahan Desa',
    'pemberdayaan': 'Bidang Pemberdayaan Desa',
    'lembaga': 'Bidang Pemberdayaan Lembaga Kemasyarakatan',
    'sekretariat': 'Sekretariat',
};

const pageTitle = computed(() => bidangTitles[currentBidang.value] || 'Bidang Tugas');
</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-[13px] font-medium text-[#646a79] mb-2">
                <span>Beranda</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span>Bidang Tugas</span>
            </div>
            <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                Kelola Bidang Tugas
            </h2>
            <p class="text-[14px] font-medium text-[#646a79]">Kelola uraian tugas, fungsi, dan layanan untuk masing-masing bidang/sekretariat DPMD.</p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Card Header -->
            <div class="p-6 md:p-8 border-b border-[#e3e5e7]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-4 mb-1">
                            <h3 class="text-[20px] font-bold text-[#0f172a]">Edit {{ pageTitle }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#137333] mr-1.5"></span> Published
                            </span>
                        </div>
                        <p class="text-[12px] font-medium text-[#646a79]">Terakhir diubah: 14 Okt 2026, 09:15 oleh Superadmin</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- Row 1: Photo Upload (Untuk Ilustrasi Bidang) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[14px] font-bold text-[#373f50]">
                            Gambar Ilustrasi / Struktur Bidang
                        </label>
                        <span class="text-[12px] font-medium text-[#646a79]">Format Landscape direkomendasikan (Max 2MB)</span>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Preview -->
                        <div class="w-full md:w-48 h-32 rounded-xl border border-[#e3e5e7] bg-[#f9f9f9] overflow-hidden flex-shrink-0 relative group">
                            <img src="https://placehold.co/600x400/c8cbd0/ffffff?text=Ilustrasi+Bidang" alt="Preview" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-[#0f172a]/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="w-8 h-8 rounded-full bg-white text-[#ba1a1a] flex items-center justify-center hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Upload Dropzone -->
                        <div class="flex-1 border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors cursor-pointer group">
                            <span class="material-symbols-outlined text-[32px] text-[#646a79] mb-2 group-hover:text-[#0f172a] transition-colors">cloud_upload</span>
                            <p class="text-[14px] font-medium text-[#373f50] mb-1">
                                <span class="text-[#0f172a] font-bold hover:underline">Klik untuk unggah</span> atau seret foto kesini
                            </p>
                            <p class="text-[12px] text-[#9499a3]">JPG, PNG, WebP format</p>
                        </div>
                    </div>
                </div>

                <!-- Row 2: WYSIWYG Editor -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Uraian Tugas Pokok, Fungsi, dan Layanan</label>
                    <div class="border border-[#e3e5e7] rounded-xl overflow-hidden bg-white">
                        <!-- Toolbar -->
                        <div class="bg-[#f9f9f9] border-b border-[#e3e5e7] p-2 flex flex-wrap gap-1 items-center">
                            <select class="text-[13px] font-medium border-none bg-transparent focus:ring-0 py-1.5 pl-3 pr-8 text-[#0f172a]">
                                <option>Normal</option>
                                <option>Heading 1</option>
                                <option>Heading 2</option>
                                <option>Heading 3</option>
                            </select>
                            <div class="w-[1px] h-5 bg-[#c8cbd0] mx-2"></div>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center font-bold">B</button>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center italic font-serif">I</button>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center underline">U</button>
                            <div class="w-[1px] h-5 bg-[#c8cbd0] mx-2"></div>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">format_list_bulleted</span></button>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">format_list_numbered</span></button>
                            <div class="w-[1px] h-5 bg-[#c8cbd0] mx-2"></div>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">link</span></button>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">image</span></button>
                            <button class="w-8 h-8 rounded hover:bg-[#e3e5e7] text-[#373f50] flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">table_chart</span></button>
                        </div>
                        
                        <!-- Content Area -->
                        <textarea rows="12" class="w-full p-6 text-[15px] leading-[1.7] text-[#0f172a] border-none focus:ring-0 resize-y" placeholder="Tuliskan uraian tugas, fungsi, dan jenis layanan di bidang ini secara rinci..."></textarea>
                    </div>
                </div>

            </div>

            <!-- Card Footer / Actions -->
            <div class="bg-[#f9f9f9] border-t border-[#e3e5e7] p-6 flex items-center justify-end gap-3">
                <button class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                    Batal
                </button>
                <button class="px-6 py-2.5 rounded-full bg-[#0f172a] text-white font-bold text-[14px] hover:bg-[#222a3d] shadow-[0_4px_12px_rgba(15,23,42,0.12)] transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
