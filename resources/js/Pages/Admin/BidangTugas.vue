<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDateTime } from '@/Utils/formatDate';

// Import Shared Components
import FormEditorGroup from '@/Components/Form/FormEditorGroup.vue';
import FormImageUpload from '@/Components/Form/FormImageUpload.vue';

// Import Composables
import { useImagePreview } from '@/Composables/useImagePreview';

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

// Image Preview
const { previewUrl: imagePreview, updatePreview } = useImagePreview(
    props.bidangTugas?.[currentSection.value + '_gambar'] 
    ? `/storage/${props.bidangTugas[currentSection.value + '_gambar']}` 
    : 'https://placehold.co/600x400/c8cbd0/ffffff?text=Ilustrasi+Bidang'
);

watch(currentSection, (newSection) => {
    const originalImage = props.bidangTugas?.[newSection + '_gambar'];
    if (form[newSection + '_gambar']) {
        updatePreview(form[newSection + '_gambar']);
    } else if (originalImage) {
        imagePreview.value = `/storage/${originalImage}`;
    } else {
        imagePreview.value = 'https://placehold.co/600x400/c8cbd0/ffffff?text=Ilustrasi+Bidang';
    }
});

const handleFileUpload = (file) => {
    form[currentSection.value + '_gambar'] = file;
    updatePreview(file);
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

        <!-- Navigation Tabs -->
        <div class="flex border-b border-[#dbe6f7] mb-6 overflow-x-auto whitespace-nowrap scrollbar-none -mx-4 px-4 sm:mx-0 sm:px-0">
            <Link 
                v-for="(title, key) in sectionTitles" 
                :key="key"
                :href="route('admin.bidang-tugas', key)"
                class="py-3 px-6 text-[14px] font-bold border-b-2 transition-all"
                :class="currentSection === key ? 'border-[#1356a0] text-[#1356a0]' : 'border-transparent text-slate-500 hover:text-slate-900'"
            >
                {{ title }}
            </Link>
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
                <FormImageUpload 
                    :preview-url="imagePreview"
                    label="Gambar Ilustrasi / Struktur Bidang"
                    aspect-ratio="aspect-video"
                    max-size-text="Format Landscape direkomendasikan (Max 2MB)"
                    :error="form.errors[`${currentSection}_gambar`]"
                    @change="handleFileUpload"
                />

                <!-- WYSIWYG Editor -->
                <FormEditorGroup 
                    v-model="form[currentSection + '_konten']"
                    label="Uraian Tugas Pokok, Fungsi, dan Layanan"
                    :error="form.errors[`${currentSection}_konten`]"
                />

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
