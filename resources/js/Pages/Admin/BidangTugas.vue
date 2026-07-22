<script setup>
import { Head, usePage, useForm, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';

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
        <div class="mb-8">
            <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                Kelola Bidang Tugas
            </h2>
            <p class="text-[14px] font-medium text-[#646a79]">Kelola uraian tugas, fungsi, dan layanan untuk masing-masing bidang/sekretariat DPMD.</p>
        </div>

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
                        <p class="text-[12px] font-medium text-[#646a79]">Terakhir diubah: {{ formatDate(bidangTugas?.updated_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- Photo Upload -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[14px] font-bold text-[#373f50]">
                            Gambar Ilustrasi / Struktur Bidang
                        </label>
                        <span class="text-[12px] font-medium text-[#646a79]">Format Landscape direkomendasikan (Max 2MB)</span>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-48 h-32 rounded-xl border border-[#e3e5e7] bg-[#f9f9f9] overflow-hidden flex-shrink-0 relative group">
                            <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                        </div>
                        
                        <div @click="triggerFileInput(currentSection + '_gambar_input')" class="flex-1 border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors cursor-pointer group">
                            <span class="material-symbols-outlined text-[32px] text-[#646a79] mb-2 group-hover:text-[#0f172a] transition-colors">cloud_upload</span>
                            <p class="text-[14px] font-medium text-[#373f50] mb-1">
                                <span class="text-[#0f172a] font-bold hover:underline">Klik untuk unggah</span> atau seret foto kesini
                            </p>
                            <p class="text-[12px] text-[#9499a3]">JPG, PNG format</p>
                            <input type="file" :id="currentSection + '_gambar_input'" class="hidden" accept="image/jpeg,image/png" @change="e => handleFileUpload(e, currentSection + '_gambar')" />
                        </div>
                    </div>
                    <div v-if="form.errors[`${currentSection}_gambar`]" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors[`${currentSection}_gambar`] }}</div>
                </div>

                <!-- WYSIWYG Editor -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Uraian Tugas Pokok, Fungsi, dan Layanan</label>
                    <TipTapEditor v-model="form[currentSection + '_konten']" />
                    <div v-if="form.errors[`${currentSection}_konten`]" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors[`${currentSection}_konten`] }}</div>
                </div>

            </div>

            <!-- Card Footer / Actions -->
            <div class="bg-[#f9f9f9] border-t border-[#e3e5e7] p-6 flex items-center justify-end gap-3">
                <button @click="form.reset()" type="button" class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                    Batal
                </button>
                <button 
                    @click="submit" 
                    :disabled="form.processing"
                    class="px-6 py-2.5 rounded-full bg-[#0f172a] text-white font-bold text-[14px] hover:bg-[#222a3d] shadow-[0_4px_12px_rgba(15,23,42,0.12)] transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70"
                >
                    <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                    Simpan Perubahan
                </button>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
