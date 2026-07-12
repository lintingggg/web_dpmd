<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();
const currentSection = computed(() => route().params.section || 'sambutan');

const sectionTitles = {
    'sambutan': 'Sambutan Kepala Dinas',
    'visi-misi': 'Visi dan Misi',
    'tupoksi': 'Tugas Pokok & Fungsi',
    'struktur': 'Struktur Organisasi',
    'kode-etik': 'Kode Etik Pelayanan',
    'maklumat': 'Maklumat Pelayanan',
    'motto': 'Motto Pelayanan',
};

const pageTitle = computed(() => sectionTitles[currentSection.value] || 'Profil Dinas');
</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-[13px] font-medium text-[#646a79] mb-2">
                <span>Beranda</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span>Profil Dinas</span>
            </div>
            <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                Profil & Informasi
            </h2>
            <p class="text-[14px] font-medium text-[#646a79]">Kelola konten inti yang mendeskripsikan identitas, struktur, dan sejarah DPMD Kabupaten Bangkalan kepada publik.</p>
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
                        <p class="text-[12px] font-medium text-[#646a79]">Terakhir diubah: 12 Okt 2026, 14:30 oleh Superadmin</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- Row 1: Personal Info (Hanya tampil jika section = sambutan) -->
                <div v-if="currentSection === 'sambutan'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Nama Kepala Dinas</label>
                        <input type="text" value="Drs. H. Ahmad Fathoni, M.Si" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                    </div>
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">NIP / Jabatan Tambahan</label>
                        <input type="text" value="NIP. 197001011998031004" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                    </div>
                </div>

                <!-- Row 2: Photo Upload (Hanya untuk Sambutan dan Struktur) -->
                <div v-if="currentSection === 'sambutan' || currentSection === 'struktur'">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[14px] font-bold text-[#373f50]">
                            {{ currentSection === 'sambutan' ? 'Foto Profil Kepala Dinas' : 'Bagan Struktur Organisasi' }}
                        </label>
                        <span class="text-[12px] font-medium text-[#646a79]">{{ currentSection === 'sambutan' ? 'Rekomendasi rasio 1:1' : 'Format Landscape direkomendasikan' }} (Max 2MB)</span>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Preview -->
                        <div class="w-full md:w-32 h-32 rounded-xl border border-[#e3e5e7] bg-[#f9f9f9] overflow-hidden flex-shrink-0 relative group">
                            <img :src="currentSection === 'sambutan' ? 'https://ui-avatars.com/api/?name=Kadis&background=c8cbd0&color=ffffff&size=128' : 'https://placehold.co/400x300/c8cbd0/ffffff?text=Bagan'" alt="Preview" class="w-full h-full object-cover" />
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

                <!-- Row 3: WYSIWYG Editor -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Teks {{ pageTitle }}</label>
                    <div class="border border-[#e3e5e7] rounded-xl overflow-hidden bg-white">
                        <!-- Toolbar -->
                        <div class="bg-[#f9f9f9] border-b border-[#e3e5e7] p-2 flex flex-wrap gap-1 items-center">
                            <select class="text-[13px] font-medium border-none bg-transparent focus:ring-0 py-1.5 pl-3 pr-8 text-[#0f172a]">
                                <option>Normal</option>
                                <option>Heading 1</option>
                                <option>Heading 2</option>
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
                        </div>
                        
                        <!-- Content Area -->
                        <textarea rows="10" class="w-full p-6 text-[15px] leading-[1.7] text-[#0f172a] border-none focus:ring-0 resize-y" placeholder="Assalamu'alaikum Warahmatullahi Wabarakatuh..."></textarea>
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
