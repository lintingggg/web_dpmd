<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from '@idds/vue';

// Import Shared Components
import FormImageUpload from '@/Components/Form/FormImageUpload.vue';

// Import Composables
import { useImagePreview } from '@/Composables/useImagePreview';

const props = defineProps({
    pengaturan: Object
});

const { toast } = useToast();

const form = useForm({
    hero_title: props.pengaturan?.hero_title || 'Membangun Desa Bangkalan yang Mandiri dan Sejahtera.',
    hero_description: props.pengaturan?.hero_description || 'Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berkomitmen penuh dalam mendorong kemajuan potensi desa di seluruh wilayah Bangkalan.',
    hero_image_1: null,
    hero_image_2: null,
    hero_image_3: null,
});

// Use Composables for Image Previews
const { previewUrl: previewImg1, updatePreview: updatePreview1 } = useImagePreview(
    props.pengaturan?.hero_image_1 ? '/storage/' + props.pengaturan.hero_image_1 : '/assets/Pengukuhan TP. PKK Kecamatan Kabupaten Bangkalan.jpg.jpeg'
);
const { previewUrl: previewImg2, updatePreview: updatePreview2 } = useImagePreview(
    props.pengaturan?.hero_image_2 ? '/storage/' + props.pengaturan.hero_image_2 : '/assets/PKK Mengikuti Seminar Bagi Perempuan Dalam Menghadapi Era Digital.jpg.jpeg'
);
const { previewUrl: previewImg3, updatePreview: updatePreview3 } = useImagePreview(
    props.pengaturan?.hero_image_3 ? '/storage/' + props.pengaturan.hero_image_3 : '/assets/Rapat Pleno PKK.jpg.jpeg'
);

const handleImageChange = (file, imageIndex) => {
    if (imageIndex === 1) {
        form.hero_image_1 = file;
        updatePreview1(file);
    } else if (imageIndex === 2) {
        form.hero_image_2 = file;
        updatePreview2(file);
    } else if (imageIndex === 3) {
        form.hero_image_3 = file;
        updatePreview3(file);
    }
};

const submit = () => {
    form.post(route('admin.pengaturan-beranda.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                state: 'positive',
                title: 'Berhasil',
                description: 'Pengaturan Banner Utama berhasil diperbarui.'
            });
        },
    });
};
</script>

<template>
    <Head title="Banner Utama" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1 pt-4">
                    Pengaturan Banner Utama
                </h2>
                <p class="text-[14px] font-medium text-slate-500">Ubah teks dan gambar yang pertama kali dilihat pengunjung di halaman beranda website DPMD.</p>
            </div>
        </div>

        <div class="max-w-5xl">
            <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
                <div class="p-8">
                    <form @submit.prevent="submit" class="space-y-10">

                        <!-- Hero Text Section -->
                        <div>
                            <div class="flex items-center gap-2 mb-6 border-b border-[#eaf1fb] pb-4">
                                <span class="material-symbols-outlined text-[#528be6]">text_fields</span>
                                <h3 class="text-lg font-bold text-slate-900">Teks Utama Beranda</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-8">
                                <div class="bg-[#f5f8fd] p-6 rounded-xl border border-[#e2ebfa]">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Judul Besar (Heading)</label>
                                    <input type="text" v-model="form.hero_title" placeholder="Contoh: Membangun Desa Bangkalan..." class="block w-full rounded-lg border-[#d3e0f5] shadow-sm focus:border-[#1356a0] focus:ring-[#1356a0] sm:text-sm py-3 px-4 transition-colors" />
                                    <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-[#528be6]">lightbulb</span>
                                        Teks ini akan tampil dengan ukuran paling besar. Disarankan maksimal 1-2 kalimat singkat.
                                    </p>
                                </div>

                                <div class="bg-[#f5f8fd] p-6 rounded-xl border border-[#e2ebfa]">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Teks Penjelasan (Sub-judul)</label>
                                    <div class="bg-white rounded-lg border border-[#d3e0f5] overflow-hidden focus-within:border-[#1356a0] transition-colors">
                                        <TipTapEditor v-model="form.hero_description" />
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-[#528be6]">lightbulb</span>
                                        Teks penjelasan lebih detail yang berada di bawah judul utama.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Images Section -->
                        <div>
                            <div class="flex items-center gap-2 mb-6 border-b border-[#eaf1fb] pb-4">
                                <span class="material-symbols-outlined text-[#528be6]">imagesmode</span>
                                <h3 class="text-lg font-bold text-slate-900">Kolase Gambar Beranda</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                                <!-- Image 1 -->
                                <FormImageUpload 
                                    :preview-url="previewImg1"
                                    label="Gambar Kiri (Belakang)"
                                    aspect-ratio="aspect-[4/5]"
                                    :error="form.errors.hero_image_1"
                                    max-size-text="Orientasi potret, Maks 2MB"
                                    @change="file => handleImageChange(file, 1)"
                                />

                                <!-- Image 3 (Tengah) -->
                                <FormImageUpload 
                                    :preview-url="previewImg3"
                                    label="Gambar Tengah (Paling Depan)"
                                    aspect-ratio="aspect-[4/5]"
                                    :error="form.errors.hero_image_3"
                                    max-size-text="Orientasi potret, Gambar yang disorot"
                                    @change="file => handleImageChange(file, 3)"
                                >
                                    <template #badge>
                                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#1356a0] text-white text-[10px] font-bold px-3 py-1 rounded-full z-20 whitespace-nowrap shadow-[0_4px_12px_rgba(19,86,160,0.35)]">GAMBAR UTAMA</div>
                                    </template>
                                </FormImageUpload>

                                <!-- Image 2 -->
                                <FormImageUpload 
                                    :preview-url="previewImg2"
                                    label="Gambar Kanan (Belakang)"
                                    aspect-ratio="aspect-[4/5]"
                                    :error="form.errors.hero_image_2"
                                    max-size-text="Orientasi potret, Maks 2MB"
                                    @change="file => handleImageChange(file, 2)"
                                />
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-xs text-slate-500"><span class="font-bold text-slate-700">Tips:</span> Gunakan gambar dengan orientasi potret (berdiri) format JPG/PNG. Maksimal 2MB per gambar.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-6 border-t border-[#eaf1fb]">
                            <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#1356a0] to-[#528be6] border border-transparent rounded-xl font-bold text-sm text-white transition-all duration-200 hover:from-[#103973] hover:to-[#1356a0] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_4px_16px_rgba(19,86,160,0.3)] hover:shadow-[0_6px_20px_rgba(19,86,160,0.4)]">
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>