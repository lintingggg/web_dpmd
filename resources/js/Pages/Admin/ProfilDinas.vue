<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDateTime } from '@/Utils/formatDate';

// Import Shared Components
import FormEditorGroup from '@/Components/Form/FormEditorGroup.vue';
import FormImageUpload from '@/Components/Form/FormImageUpload.vue';
import FormDocumentUpload from '@/Components/Form/FormDocumentUpload.vue';

// Import Composables
import { useImagePreview } from '@/Composables/useImagePreview';

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

// Image Previews
const { previewUrl: fotoKadisPreview, updatePreview: updateFotoKadis } = useImagePreview(
    props.profil?.kadis_foto ? `/storage/${props.profil.kadis_foto}` : 'https://ui-avatars.com/api/?name=Kadis&background=c8cbd0&color=ffffff&size=128'
);
const { previewUrl: strukturPreview, updatePreview: updateStruktur } = useImagePreview(
    props.profil?.struktur_gambar ? `/storage/${props.profil.struktur_gambar}` : 'https://placehold.co/400x300/c8cbd0/ffffff?text=Bagan+Struktur'
);

const handleFotoKadis = (file) => {
    form.kadis_foto = file;
    updateFotoKadis(file);
};

const handleStrukturGambar = (file) => {
    form.struktur_gambar = file;
    updateStruktur(file);
};

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

        <!-- Navigation Tabs -->
        <div class="flex border-b border-[#dbe6f7] mb-6 overflow-x-auto whitespace-nowrap scrollbar-none -mx-4 px-4 sm:mx-0 sm:px-0">
            <Link 
                v-for="(title, key) in sectionTitles" 
                :key="key"
                :href="route('admin.profil-dinas', key)"
                class="py-3 px-6 text-[14px] font-bold border-b-2 transition-all"
                :class="currentSection === key ? 'border-[#1356a0] text-[#1356a0]' : 'border-transparent text-slate-500 hover:text-slate-900'"
            >
                {{ title }}
            </Link>
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
                <div v-slot="scope" v-if="currentSection === 'sambutan'" class="space-y-6">
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

                    <FormImageUpload 
                        :preview-url="fotoKadisPreview"
                        label="Foto Profil Kepala Dinas"
                        aspect-ratio="aspect-square"
                        max-size-text="Rekomendasi rasio 1:1 (Max 2MB)"
                        :error="form.errors.kadis_foto"
                        @change="handleFotoKadis"
                    />

                    <FormEditorGroup 
                        v-model="form.sambutan_teks"
                        label="Teks Sambutan"
                        :error="form.errors.sambutan_teks"
                    />
                </div>

                <!-- VISI DAN MISI -->
                <div v-if="currentSection === 'visi-misi'" class="space-y-6">
                    <FormEditorGroup 
                        v-model="form.visi_teks"
                        label="Teks Visi"
                        :error="form.errors.visi_teks"
                    />
                    <FormEditorGroup 
                        v-model="form.misi_teks"
                        label="Teks Misi"
                        :error="form.errors.misi_teks"
                    />
                </div>

                <!-- TUPOKSI -->
                <div v-if="currentSection === 'tupoksi'" class="space-y-6">
                    <FormDocumentUpload 
                        v-model="form.tupoksi_dokumen"
                        label="Dokumen Tugas Pokok & Fungsi"
                        :error="form.errors.tupoksi_dokumen"
                        :info-text="profil?.tupoksi_dokumen ? 'Dokumen Tersimpan: ' + profil.tupoksi_dokumen.split('/').pop() : 'Belum ada dokumen PDF diunggah'"
                    />

                    <FormEditorGroup 
                        v-model="form.tupoksi_teks"
                        label="Tugas Pokok & Fungsi (Teks)"
                        :error="form.errors.tupoksi_teks"
                    />
                </div>

                <!-- STRUKTUR -->
                <div v-if="currentSection === 'struktur'" class="space-y-6">
                    <FormImageUpload 
                        :preview-url="strukturPreview"
                        label="Bagan Struktur Organisasi"
                        aspect-ratio="aspect-video"
                        max-size-text="Format Landscape direkomendasikan (Max 5MB)"
                        :error="form.errors.struktur_gambar"
                        @change="handleStrukturGambar"
                    />

                    <FormEditorGroup 
                        v-model="form.struktur_keterangan"
                        label="Keterangan Struktur (Opsional)"
                        :error="form.errors.struktur_keterangan"
                    />
                </div>

                <!-- KODE ETIK -->
                <div v-if="currentSection === 'kode-etik'" class="space-y-6">
                    <FormDocumentUpload 
                        v-model="form.kode_etik_dokumen"
                        label="Dokumen Kode Etik Pelayanan"
                        :error="form.errors.kode_etik_dokumen"
                        :info-text="profil?.kode_etik_dokumen ? 'Dokumen Tersimpan: ' + profil.kode_etik_dokumen.split('/').pop() : 'Belum ada dokumen PDF diunggah'"
                    />

                    <FormEditorGroup 
                        v-model="form.kode_etik_teks"
                        label="Kode Etik Pelayanan (Teks)"
                        :error="form.errors.kode_etik_teks"
                    />
                </div>

                <!-- MAKLUMAT -->
                <div v-if="currentSection === 'maklumat'" class="space-y-6">
                    <FormDocumentUpload 
                        v-model="form.maklumat_dokumen"
                        label="Dokumen Maklumat Pelayanan"
                        :error="form.errors.maklumat_dokumen"
                        :info-text="profil?.maklumat_dokumen ? 'Dokumen Tersimpan: ' + profil.maklumat_dokumen.split('/').pop() : 'Belum ada dokumen PDF diunggah'"
                    />

                    <FormEditorGroup 
                        v-model="form.maklumat_teks"
                        label="Teks Maklumat Pelayanan"
                        :error="form.errors.maklumat_teks"
                    />
                </div>

                <!-- MOTTO -->
                <div v-if="currentSection === 'motto'" class="space-y-6">
                    <FormEditorGroup 
                        v-model="form.motto_teks"
                        label="Motto Pelayanan"
                        :error="form.errors.motto_teks"
                    />
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
