<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    pengaturan: any;
}>();

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
        onSuccess: () => alert('Pengaturan Beranda berhasil diperbarui!'),
    });
};
</script>

<template>
    <Head title="Pengaturan Beranda" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Beranda (Hero Section)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Hero Text Section -->
                            <div class="border-b border-gray-200 pb-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Teks Hero Utama</h3>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Judul Hero</label>
                                        <input type="text" v-model="form.hero_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <p class="mt-1 text-sm text-gray-500">Contoh: Membangun Desa Bangkalan yang Mandiri dan Sejahtera.</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Deskripsi Hero</label>
                                        <textarea v-model="form.hero_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Hero Images Section -->
                            <div class="pb-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Hero Section (Tiga Gambar Tumpuk)</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                    <!-- Image 1 -->
                                    <div class="border border-gray-200 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar 1 (Kiri/Belakang)</label>
                                        <div class="mb-4 relative w-full aspect-[4/5] rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <img :src="previewImg1" alt="Preview 1" class="w-full h-full object-cover" />
                                        </div>
                                        <input type="file" accept="image/*" @change="e => handleImageChange(e, 1)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>

                                    <!-- Image 2 -->
                                    <div class="border border-gray-200 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar 2 (Kanan/Belakang)</label>
                                        <div class="mb-4 relative w-full aspect-[4/5] rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <img :src="previewImg2" alt="Preview 2" class="w-full h-full object-cover" />
                                        </div>
                                        <input type="file" accept="image/*" @change="e => handleImageChange(e, 2)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>

                                    <!-- Image 3 -->
                                    <div class="border border-gray-200 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar 3 (Tengah/Depan)</label>
                                        <div class="mb-4 relative w-full aspect-[4/5] rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <img :src="previewImg3" alt="Preview 3" class="w-full h-full object-cover" />
                                        </div>
                                        <input type="file" accept="image/*" @change="e => handleImageChange(e, 3)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-[#0F172A] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
