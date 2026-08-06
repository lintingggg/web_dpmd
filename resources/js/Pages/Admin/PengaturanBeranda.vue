<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from '@idds/vue';

const props = defineProps<{
    pengaturan: any;
}>();

const { toast } = useToast();

const form = useForm({
    hero_title: props.pengaturan?.hero_title || 'Membangun Desa Bangkalan yang Mandiri dan Sejahtera.',
    hero_description: props.pengaturan?.hero_description || 'Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berkomitmen penuh dalam mendorong kemajuan potensi desa di seluruh wilayah Bangkalan.',
    hero_image_1: null as File | null,
    hero_image_2: null as File | null,
    hero_image_3: null as File | null,
});

const previewImg1 = ref(props.pengaturan?.hero_image_1 ? '/storage/' + props.pengaturan.hero_image_1 : '/assets/Pengukuhan TP. PKK Kecamatan Kabupaten Bangkalan.jpg.jpeg');
const previewImg2 = ref(props.pengaturan?.hero_image_2 ? '/storage/' + props.pengaturan.hero_image_2 : '/assets/PKK Mengikuti Seminar Bagi Perempuan Dalam Menghadapi Era Digital.jpg.jpeg');
const previewImg3 = ref(props.pengaturan?.hero_image_3 ? '/storage/' + props.pengaturan.hero_image_3 : '/assets/Rapat Pleno PKK.jpg.jpeg');

const handleImageChange = (e: Event, imageIndex: number) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        if (imageIndex === 1) {
            form.hero_image_1 = file;
            previewImg1.value = URL.createObjectURL(file);
        } else if (imageIndex === 2) {
            form.hero_image_2 = file;
            previewImg2.value = URL.createObjectURL(file);
        } else if (imageIndex === 3) {
            form.hero_image_3 = file;
            previewImg3.value = URL.createObjectURL(file);
        }
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
                                    <!-- Tiptap Editor for rich text description -->
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

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Image 1 -->
                                <div class="border-2 border-dashed border-[#dbe6f7] p-5 rounded-2xl bg-[#f5f8fd] hover:bg-[#eaf1fb] transition-colors relative group">
                                    <label class="block text-sm font-bold text-slate-700 mb-3 text-center">Gambar Kiri (Belakang)</label>
                                    <div class="mb-4 relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-white shadow-sm flex items-center justify-center border border-[#dbe6f7] group-hover:border-[#1356a0] transition-colors">
                                        <img :src="previewImg1" alt="Preview Kiri" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-[#103973]/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                            <span class="material-symbols-outlined text-white text-3xl">upload</span>
                                        </div>
                                    </div>
                                    <input type="file" accept="image/*" @change="e => handleImageChange(e, 1)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                    <p class="text-center text-xs text-slate-500">Klik area ini untuk mengganti gambar.</p>
                                </div>

                                <!-- Image 3 (Tengah) -->
                                <div class="border-2 border-dashed border-[#528be6]/50 p-5 rounded-2xl bg-[#eaf1fb] hover:bg-[#dfeafc] transition-colors relative group shadow-sm md:-mt-4">
                                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#1356a0] text-white text-[10px] font-bold px-3 py-1 rounded-full z-20 whitespace-nowrap shadow-[0_4px_12px_rgba(19,86,160,0.35)]">GAMBAR UTAMA</div>
                                    <label class="block text-sm font-bold text-slate-900 mb-3 text-center">Gambar Tengah (Paling Depan)</label>
                                    <div class="mb-4 relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-white shadow-md flex items-center justify-center border border-[#c7dafa] group-hover:border-[#1356a0] transition-colors">
                                        <img :src="previewImg3" alt="Preview Tengah" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-[#103973]/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                            <span class="material-symbols-outlined text-white text-3xl">upload</span>
                                        </div>
                                    </div>
                                    <input type="file" accept="image/*" @change="e => handleImageChange(e, 3)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                    <p class="text-center text-xs text-slate-500">Gambar yang paling disorot.</p>
                                </div>

                                <!-- Image 2 -->
                                <div class="border-2 border-dashed border-[#dbe6f7] p-5 rounded-2xl bg-[#f5f8fd] hover:bg-[#eaf1fb] transition-colors relative group">
                                    <label class="block text-sm font-bold text-slate-700 mb-3 text-center">Gambar Kanan (Belakang)</label>
                                    <div class="mb-4 relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-white shadow-sm flex items-center justify-center border border-[#dbe6f7] group-hover:border-[#1356a0] transition-colors">
                                        <img :src="previewImg2" alt="Preview Kanan" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-[#103973]/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                            <span class="material-symbols-outlined text-white text-3xl">upload</span>
                                        </div>
                                    </div>
                                    <input type="file" accept="image/*" @change="e => handleImageChange(e, 2)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                    <p class="text-center text-xs text-slate-500">Klik area ini untuk mengganti gambar.</p>
                                </div>
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