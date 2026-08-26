<script setup>
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from '@idds/vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';
import { formatDateTime } from '@/Utils/formatDate';

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
    tupoksi_dokumen: null,

    // Struktur
    struktur_keterangan: props.profil?.struktur_keterangan || '',
    struktur_gambar: null,

    // Kode Etik
    kode_etik_teks: props.profil?.kode_etik_teks || '',
    kode_etik_dokumen: null,

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
const tupoksiDokumenName = ref(props.profil?.tupoksi_dokumen ? 'Dokumen Tersimpan: ' + props.profil.tupoksi_dokumen.split('/').pop() : 'Belum ada dokumen PDF diunggah');
const kodeEtikDokumenName = ref(props.profil?.kode_etik_dokumen ? 'Dokumen Tersimpan: ' + props.profil.kode_etik_dokumen.split('/').pop() : 'Belum ada dokumen PDF diunggah');

const handleFileUpload = (e, field) => {
    const file = e.target.files[0];
    if (!file) return;

    form[field] = file;
    
    if (field === 'maklumat_dokumen') {
        maklumatDokumenName.value = file.name;
        return;
    }
    if (field === 'tupoksi_dokumen') {
        tupoksiDokumenName.value = file.name;
        return;
    }
    if (field === 'kode_etik_dokumen') {
        kodeEtikDokumenName.value = file.name;
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
</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8 pt-4">
            <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1">
                Profil & Informasi
            </h2>
            <p class="text-[14px] font-medium text-slate-500">Kelola konten inti yang mendeskripsikan identitas, struktur, dan sejarah DPMD Kabupaten Bangkalan kepada publik.</p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            
            <!-- Card Header -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] bg-[#f5f8fd]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-4 mb-1">
                            <h3 class="text-[20px] font-bold text-slate-900">Edit {{ pageTitle }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#137333] mr-1.5"></span> Published
                            </span>
                        </div>
                        <p class="text-[12px] font-medium text-slate-500">Terakhir diubah: {{ formatDateTime(profil?.updated_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- SAMBUTAN KADIS -->
                <div v-if="currentSection === 'sambutan'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[14px] font-bold text-slate-700 mb-2">Nama Kepala Dinas</label>
                            <input v-model="form.kadis_nama" type="text" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                            <div v-if="form.errors.kadis_nama" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kadis_nama }}</div>
                        </div>
                        <div>
                            <label class="block text-[14px] font-bold text-slate-700 mb-2">NIP Kepala Dinas</label>
                            <input v-model="form.kadis_nip" type="text" placeholder="Contoh: 19900101 202012 1 001" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                            <div v-if="form.errors.kadis_nip" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kadis_nip }}</div>
                            <p v-else class="text-[12px] text-slate-400 mt-1.5 font-medium">Hanya angka dan spasi (minimal 18 karakter).</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-slate-700">Foto Profil Kepala Dinas</label>
                            <span class="text-[12px] font-medium text-slate-500">Rekomendasi rasio 1:1 (Max 2MB)</span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-32 h-32 rounded-xl border border-[#dbe6f7] bg-[#f5f8fd] overflow-hidden flex-shrink-0 relative group">
                                <img :src="fotoKadisPreview" alt="Preview" class="w-full h-full object-cover" />
                            </div>
                            <div @click="triggerFileInput('kadis_foto_input')" class="flex-1 border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-[#1356a0] transition-colors">cloud_upload</span>
                                <p class="text-[14px] font-medium text-slate-600 mb-1">
                                    <span class="text-[#1356a0] font-bold hover:underline">Klik untuk unggah</span> atau ganti foto
                                </p>
                                <p class="text-[12px] text-slate-400">JPG, PNG format</p>
                                <input type="file" id="kadis_foto_input" class="hidden" accept="image/jpeg,image/png" @change="e => handleFileUpload(e, 'kadis_foto')" />
                            </div>
                        </div>
                        <div v-if="form.errors.kadis_foto" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kadis_foto }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Teks Sambutan</label>
                        <TipTapEditor v-model="form.sambutan_teks" />
                        <div v-if="form.errors.sambutan_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.sambutan_teks }}</div>
                    </div>
                </div>

                <!-- VISI DAN MISI -->
                <div v-if="currentSection === 'visi-misi'" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Teks Visi</label>
                        <TipTapEditor v-model="form.visi_teks" />
                        <div v-if="form.errors.visi_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.visi_teks }}</div>
                    </div>
                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Teks Misi</label>
                        <TipTapEditor v-model="form.misi_teks" />
                        <div v-if="form.errors.misi_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.misi_teks }}</div>
                    </div>
                </div>

                <!-- TUPOKSI -->
                <div v-if="currentSection === 'tupoksi'" class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-slate-700">Dokumen Tugas Pokok & Fungsi</label>
                            <span class="text-[12px] font-medium text-slate-500">Hanya format PDF (Max 10MB)</span>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div @click="triggerFileInput('tupoksi_dokumen_input')" class="w-full border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-[#1356a0] transition-colors">upload_file</span>
                                <p class="text-[14px] font-medium text-slate-600 mb-1">
                                    <span class="text-[#1356a0] font-bold hover:underline">Klik untuk unggah</span> atau ganti dokumen PDF
                                </p>
                                <p class="text-[12px] text-slate-400 font-bold text-[#1356a0] mt-2">{{ tupoksiDokumenName }}</p>
                                <input type="file" id="tupoksi_dokumen_input" class="hidden" accept="application/pdf" @change="e => handleFileUpload(e, 'tupoksi_dokumen')" />
                            </div>
                        </div>
                        <div v-if="form.errors.tupoksi_dokumen" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tupoksi_dokumen }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Tugas Pokok & Fungsi (Teks)</label>
                        <TipTapEditor v-model="form.tupoksi_teks" />
                        <div v-if="form.errors.tupoksi_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tupoksi_teks }}</div>
                    </div>
                </div>

                <!-- STRUKTUR -->
                <div v-if="currentSection === 'struktur'" class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-slate-700">Bagan Struktur Organisasi</label>
                            <span class="text-[12px] font-medium text-slate-500">Format Landscape direkomendasikan (Max 5MB)</span>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="w-full h-auto rounded-xl border border-[#dbe6f7] bg-[#f5f8fd] overflow-hidden flex-shrink-0 relative group">
                                <img :src="strukturPreview" alt="Preview" class="w-full h-auto object-cover" />
                            </div>
                            <div @click="triggerFileInput('struktur_gambar_input')" class="w-full border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-[#1356a0] transition-colors">cloud_upload</span>
                                <p class="text-[14px] font-medium text-slate-600 mb-1">
                                    <span class="text-[#1356a0] font-bold hover:underline">Klik untuk unggah</span> atau ganti bagan
                                </p>
                                <p class="text-[12px] text-slate-400">JPG, PNG format</p>
                                <input type="file" id="struktur_gambar_input" class="hidden" accept="image/jpeg,image/png" @change="e => handleFileUpload(e, 'struktur_gambar')" />
                            </div>
                        </div>
                        <div v-if="form.errors.struktur_gambar" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.struktur_gambar }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Keterangan Struktur (Opsional)</label>
                        <TipTapEditor v-model="form.struktur_keterangan" />
                        <div v-if="form.errors.struktur_keterangan" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.struktur_keterangan }}</div>
                    </div>
                </div>

                <!-- KODE ETIK -->
                <div v-else-if="currentSection === 'kode-etik'" class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-slate-700">Dokumen Kode Etik Pelayanan</label>
                            <span class="text-[12px] font-medium text-slate-500">Hanya format PDF (Max 10MB)</span>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div @click="triggerFileInput('kode_etik_dokumen_input')" class="w-full border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-[#1356a0] transition-colors">upload_file</span>
                                <p class="text-[14px] font-medium text-slate-600 mb-1">
                                    <span class="text-[#1356a0] font-bold hover:underline">Klik untuk unggah</span> atau ganti dokumen PDF
                                </p>
                                <p class="text-[12px] text-slate-400 font-bold text-[#1356a0] mt-2">{{ kodeEtikDokumenName }}</p>
                                <input type="file" id="kode_etik_dokumen_input" class="hidden" accept="application/pdf" @change="e => handleFileUpload(e, 'kode_etik_dokumen')" />
                            </div>
                        </div>
                        <div v-if="form.errors.kode_etik_dokumen" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kode_etik_dokumen }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Kode Etik Pelayanan (Teks)</label>
                        <TipTapEditor v-model="form.kode_etik_teks" />
                        <div v-if="form.errors.kode_etik_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kode_etik_teks }}</div>
                    </div>
                </div>

                <!-- MAKLUMAT -->
                <div v-else-if="currentSection === 'maklumat'" class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[14px] font-bold text-slate-700">Dokumen Maklumat Pelayanan</label>
                            <span class="text-[12px] font-medium text-slate-500">Hanya format PDF (Max 10MB)</span>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div @click="triggerFileInput('maklumat_dokumen_input')" class="w-full border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors cursor-pointer group">
                                <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-[#1356a0] transition-colors">upload_file</span>
                                <p class="text-[14px] font-medium text-slate-600 mb-1">
                                    <span class="text-[#1356a0] font-bold hover:underline">Klik untuk unggah</span> atau ganti dokumen PDF
                                </p>
                                <p class="text-[12px] text-slate-400 font-bold text-[#1356a0] mt-2">{{ maklumatDokumenName }}</p>
                                <input type="file" id="maklumat_dokumen_input" class="hidden" accept="application/pdf" @change="e => handleFileUpload(e, 'maklumat_dokumen')" />
                            </div>
                        </div>
                        <div v-if="form.errors.maklumat_dokumen" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.maklumat_dokumen }}</div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Teks Maklumat Pelayanan</label>
                        <TipTapEditor v-model="form.maklumat_teks" />
                        <div v-if="form.errors.maklumat_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.maklumat_teks }}</div>
                    </div>
                </div>

                <!-- MOTTO -->
                <div v-else-if="currentSection === 'motto'" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Motto Pelayanan</label>
                        <TipTapEditor v-model="form.motto_teks" />
                        <div v-if="form.errors.motto_teks" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.motto_teks }}</div>
                    </div>
                </div>

            </div>

            <!-- Card Footer / Actions -->
            <div class="bg-[#f5f8fd] border-t border-[#dbe6f7] p-6 flex items-center justify-end gap-3">
                <button type="button" @click="form.reset()" class="px-6 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                    Batal
                </button>
                <button 
                    @click="submit"
                    :disabled="form.processing"
                    class="px-6 py-2.5 rounded-full bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold text-[14px] shadow-[0_4px_16px_rgba(19,86,160,0.3)] transition-all active:scale-95 disabled:opacity-70 flex items-center gap-2"
                >
                    <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                    Simpan Perubahan
                </button>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
