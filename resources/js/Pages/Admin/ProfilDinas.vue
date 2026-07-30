<script setup>
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from '@idds/vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';

const props = defineProps({
    profil: Object,
    section: String
});

const currentSection = computed(() => props.section || 'sambutan');

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

const form = useForm({
    // Sambutan
    kadis_nama: props.profil?.kadis_nama || '',
    kadis_nip: props.profil?.kadis_nip || '',
    sambutan_teks: props.profil?.sambutan_teks || '',
    kadis_foto: null,

    // Visi & Misi
    visi_teks: props.profil?.visi_teks || '',
    misi_teks: props.profil?.misi_teks || '',

    // Tupoksi
    tupoksi_teks: props.profil?.tupoksi_teks || '',

    // Struktur
    struktur_keterangan: props.profil?.struktur_keterangan || '',
    struktur_gambar: null,

    // Kode Etik
    kode_etik_teks: props.profil?.kode_etik_teks || '',

    // Maklumat
    maklumat_teks: props.profil?.maklumat_teks || '',
    maklumat_dokumen: null,

    // Motto
    motto_teks: props.profil?.motto_teks || '',
});

// Photo Previews
const fotoKadisPreview = ref(props.profil?.kadis_foto ? `/storage/${props.profil.kadis_foto}` : 'https://ui-avatars.com/api/?name=Kadis&background=c8cbd0&color=ffffff&size=128');
const strukturPreview = ref(props.profil?.struktur_gambar ? `/storage/${props.profil.struktur_gambar}` : 'https://placehold.co/400x300/c8cbd0/ffffff?text=Bagan+Struktur');
const maklumatDokumenName = ref(props.profil?.maklumat_dokumen ? 'Dokumen Tersimpan: ' + props.profil.maklumat_dokumen.split('/').pop() : 'Belum ada dokumen PDF diunggah');

const handleFileUpload = (e, field) => {
    const file = e.target.files[0];
    if (!file) return;

    form[field] = file;
    
    if (field === 'maklumat_dokumen') {
        maklumatDokumenName.value = file.name;
        return;
    }

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
        if (field === 'kadis_foto') fotoKadisPreview.value = e.target.result;
        if (field === 'struktur_gambar') strukturPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const triggerFileInput = (id) => {
    document.getElementById(id).click();
};

const { toast } = useToast();

const submit = () => {
    form.post(route('admin.profil-dinas.update', currentSection.value), {
        forceFormData: true,
        preserveScroll: true
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', { 
        year: 'numeric', month: 'short', day: 'numeric', 
        hour: '2-digit', minute: '2-digit' 
    });
};
</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8">
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
                        <p class="text-[12px] font-medium text-[#646a79]">Terakhir diubah: {{ formatDate(profil?.updated_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- SAMBUTAN KADIS -->
                <div v-if="currentSection === 'sambutan'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[14px] font-bold text-[#373f50] mb-2">Nama Kepala Dinas</label>
                            <input v-model="form.kadis_nama" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                            <div v-if="form.errors.kadis_nama" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kadis_nama }}</div>
                        </div>
                        <div>
                            <label class="block text-[14px] font-bold text-[#373f50] mb-2">NIP Kepala Dinas</label>
                            <input v-model="form.kadis_nip" type="text" placeholder="Contoh: 19900101 202012 1 001" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                            <div v-if="form.errors.kadis_nip" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kadis_nip }}</div>
                            <p v-else class="text-[12px] text-[#9499a3] mt-1.5 font-medium">Hanya angka dan spasi (minimal 18 karakter).</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-[#373f50]">Foto Profil Kepala Dinas</label>
                            <span class="text-[12px] font-medium text-[#646a79]">Rekomendasi rasio 1:1 (Max 2MB)</span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-32 h-32 rounded-xl border border-[#e3e5e7] bg-[#f9f9f9] overflow-hidden flex-shrink-0 relative group">
                                <img :src="fotoKadisPreview" alt="Preview" class="w-full h-full object-cover" />
                            </div>
                            <div @click="triggerFileInput('kadis_foto_input')" class="flex-1 border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-[#646a79] mb-2 group-hover:text-[#0f172a] transition-colors">cloud_upload</span>
                                <p class="text-[14px] font-medium text-[#373f50] mb-1">
                                    <span class="text-[#0f172a] font-bold hover:underline">Klik untuk unggah</span> atau ganti foto
                                </p>
                                <p class="text-[12px] text-[#9499a3]">JPG, PNG format</p>
                                <input type="file" id="kadis_foto_input" class="hidden" accept="image/jpeg,image/png" @change="e => handleFileUpload(e, 'kadis_foto')" />
                            </div>
                        </div>
                        <div v-if="form.errors.kadis_foto" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kadis_foto }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Teks Sambutan</label>
                        <TipTapEditor v-model="form.sambutan_teks" />
                        <div v-if="form.errors.sambutan_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.sambutan_teks }}</div>
                    </div>
                </div>

                <!-- VISI DAN MISI -->
                <div v-else-if="currentSection === 'visi-misi'" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Teks Visi</label>
                        <TipTapEditor v-model="form.visi_teks" />
                        <div v-if="form.errors.visi_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.visi_teks }}</div>
                    </div>
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Teks Misi</label>
                        <TipTapEditor v-model="form.misi_teks" />
                        <div v-if="form.errors.misi_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.misi_teks }}</div>
                    </div>
                </div>

                <!-- TUPOKSI -->
                <div v-else-if="currentSection === 'tupoksi'" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Tugas Pokok & Fungsi</label>
                        <TipTapEditor v-model="form.tupoksi_teks" />
                        <div v-if="form.errors.tupoksi_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tupoksi_teks }}</div>
                    </div>
                </div>

                <!-- STRUKTUR -->
                <div v-else-if="currentSection === 'struktur'" class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-[#373f50]">Bagan Struktur Organisasi</label>
                            <span class="text-[12px] font-medium text-[#646a79]">Format Landscape direkomendasikan (Max 5MB)</span>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="w-full h-auto rounded-xl border border-[#e3e5e7] bg-[#f9f9f9] overflow-hidden flex-shrink-0 relative group">
                                <img :src="strukturPreview" alt="Preview" class="w-full h-auto object-cover" />
                            </div>
                            <div @click="triggerFileInput('struktur_gambar_input')" class="w-full border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-[#646a79] mb-2 group-hover:text-[#0f172a] transition-colors">cloud_upload</span>
                                <p class="text-[14px] font-medium text-[#373f50] mb-1">
                                    <span class="text-[#0f172a] font-bold hover:underline">Klik untuk unggah</span> atau ganti bagan
                                </p>
                                <p class="text-[12px] text-[#9499a3]">JPG, PNG format</p>
                                <input type="file" id="struktur_gambar_input" class="hidden" accept="image/jpeg,image/png" @change="e => handleFileUpload(e, 'struktur_gambar')" />
                            </div>
                        </div>
                        <div v-if="form.errors.struktur_gambar" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.struktur_gambar }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Keterangan Struktur (Opsional)</label>
                        <TipTapEditor v-model="form.struktur_keterangan" />
                        <div v-if="form.errors.struktur_keterangan" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.struktur_keterangan }}</div>
                    </div>
                </div>

                <!-- KODE ETIK -->
                <div v-else-if="currentSection === 'kode-etik'" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Kode Etik Pelayanan</label>
                        <TipTapEditor v-model="form.kode_etik_teks" />
                        <div v-if="form.errors.kode_etik_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kode_etik_teks }}</div>
                    </div>
                </div>

                <!-- MAKLUMAT -->
                <div v-else-if="currentSection === 'maklumat'" class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-[#373f50]">Dokumen Maklumat Pelayanan</label>
                            <span class="text-[12px] font-medium text-[#646a79]">Hanya format PDF (Max 10MB)</span>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div @click="triggerFileInput('maklumat_dokumen_input')" class="w-full border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-[#646a79] mb-2 group-hover:text-[#0f172a] transition-colors">upload_file</span>
                                <p class="text-[14px] font-medium text-[#373f50] mb-1">
                                    <span class="text-[#0f172a] font-bold hover:underline">Klik untuk unggah</span> atau ganti dokumen PDF
                                </p>
                                <p class="text-[12px] text-[#9499a3] font-bold text-blue-600 mt-2">{{ maklumatDokumenName }}</p>
                                <input type="file" id="maklumat_dokumen_input" class="hidden" accept="application/pdf" @change="e => handleFileUpload(e, 'maklumat_dokumen')" />
                            </div>
                        </div>
                        <div v-if="form.errors.maklumat_dokumen" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.maklumat_dokumen }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Teks Maklumat Pelayanan</label>
                        <TipTapEditor v-model="form.maklumat_teks" />
                        <div v-if="form.errors.maklumat_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.maklumat_teks }}</div>
                    </div>
                </div>

                <!-- MOTTO -->
                <div v-else-if="currentSection === 'motto'" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Motto Pelayanan</label>
                        <TipTapEditor v-model="form.motto_teks" />
                        <div v-if="form.errors.motto_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.motto_teks }}</div>
                    </div>
                </div>

            </div>

            <!-- Card Footer / Actions -->
            <div class="bg-[#f9f9f9] border-t border-[#e3e5e7] p-6 flex items-center justify-end gap-3">
                <button type="button" @click="form.reset()" class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                    Batal
                </button>
                <button 
                    @click="submit"
                    :disabled="form.processing"
                    class="px-6 py-2.5 rounded-full bg-[#0f172a] text-white font-bold text-[14px] hover:bg-[#222a3d] shadow-[0_4px_12px_rgba(15,23,42,0.12)] transition-all active:scale-95 disabled:opacity-70 flex items-center gap-2"
                >
                    <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                    Simpan Perubahan
                </button>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
