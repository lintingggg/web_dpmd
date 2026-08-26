<script setup>
import { Head, usePage, useForm, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';
import { formatDateTime } from '@/Utils/formatDate';

const props = defineProps({
    bidangTugas: Object,
    section: String
});

const currentSection = computed(() => props.section || 'pemdes');

const sectionTitles = {
    'pemdes': 'Bidang Pemerintahan Desa',
    'pemberdayaan': 'Bidang Pemberdayaan Desa',
    'lembaga': 'Bidang Pemberdayaan Lembaga Kemasyarakatan',
    'sekretariat': 'Sekretariat',
};

const pageTitle = computed(() => sectionTitles[currentSection.value] || 'Bidang Tugas');

const form = useForm({
    pemdes_gambar: null,
    pemdes_konten: props.bidangTugas?.pemdes_konten || '',
    pemberdayaan_gambar: null,
    pemberdayaan_konten: props.bidangTugas?.pemberdayaan_konten || '',
    lembaga_gambar: null,
    lembaga_konten: props.bidangTugas?.lembaga_konten || '',
    sekretariat_gambar: null,
    sekretariat_konten: props.bidangTugas?.sekretariat_konten || '',
});

const imagePreview = ref(
    props.bidangTugas?.[currentSection.value + '_gambar'] 
    ? `/storage/${props.bidangTugas[currentSection.value + '_gambar']}` 
    : 'https://placehold.co/600x400/c8cbd0/ffffff?text=Ilustrasi+Bidang'
);

const handleFileUpload = (e, field) => {
    const file = e.target.files[0];
    if (!file) return;

    form[field] = file;
    
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const triggerFileInput = (id) => {
    document.getElementById(id).click();
};

const submit = () => {
    form.post(route('admin.bidang-tugas.update', currentSection.value), {
        forceFormData: true,
        preserveScroll: true
    });
};

</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="mb-8 pt-4">
            <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1">
                Kelola Bidang Tugas
            </h2>
            <p class="text-[14px] font-medium text-slate-500">Kelola uraian tugas, fungsi, dan layanan untuk masing-masing bidang/sekretariat DPMD.</p>
        </div>

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
                        <p class="text-[12px] font-medium text-slate-500">Terakhir diubah: {{ formatDateTime(bidangTugas?.updated_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- Photo Upload -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[14px] font-bold text-slate-700">
                            Gambar Ilustrasi / Struktur Bidang
                        </label>
                        <span class="text-[12px] font-medium text-slate-500">Format Landscape direkomendasikan (Max 2MB)</span>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-48 h-32 rounded-xl border border-[#dbe6f7] bg-[#f5f8fd] overflow-hidden flex-shrink-0 relative group">
                            <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                        </div>
                        
                        <div @click="triggerFileInput(currentSection + '_gambar_input')" class="flex-1 border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors cursor-pointer group">
                            <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-[#1356a0] transition-colors">cloud_upload</span>
                            <p class="text-[14px] font-medium text-slate-600 mb-1">
                                <span class="text-[#1356a0] font-bold hover:underline">Klik untuk unggah</span> atau seret foto kesini
                            </p>
                            <p class="text-[12px] text-slate-400">JPG, PNG format</p>
                            <input type="file" :id="currentSection + '_gambar_input'" class="hidden" accept="image/jpeg,image/png" @change="e => handleFileUpload(e, currentSection + '_gambar')" />
                        </div>
                    </div>
                    <div v-if="form.errors[`${currentSection}_gambar`]" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors[`${currentSection}_gambar`] }}</div>
                </div>

                <!-- WYSIWYG Editor -->
                <div>
                    <label class="block text-[14px] font-bold text-slate-700 mb-2">Uraian Tugas Pokok, Fungsi, dan Layanan</label>
                    <TipTapEditor v-model="form[currentSection + '_konten']" />
                    <div v-if="form.errors[`${currentSection}_konten`]" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors[`${currentSection}_konten`] }}</div>
                </div>

            </div>

            <!-- Card Footer / Actions -->
            <div class="bg-[#f5f8fd] border-t border-[#dbe6f7] p-6 flex items-center justify-end gap-3">
                <button @click="form.reset()" type="button" class="px-6 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                    Batal
                </button>
                <button 
                    @click="submit" 
                    :disabled="form.processing"
                    class="px-6 py-2.5 rounded-full bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold text-[14px] shadow-[0_4px_16px_rgba(19,86,160,0.3)] transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70"
                >
                    <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                    Simpan Perubahan
                </button>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
