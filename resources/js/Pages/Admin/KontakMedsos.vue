<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    // Kontak
    alamat: 'Jl. Soekarno Hatta No. 35, Bangkalan',
    email: 'dpmd@bangkalankab.go.id',
    telepon: '(031) 3095331',
    whatsapp: '081234567890',
    jamKerja: 'Senin - Jumat, 08:00 - 15:30 WIB',
    koordinatMap: '-7.0270059,112.7483669',
    
    // Medsos
    facebook: 'https://facebook.com/dpmdbangkalan',
    instagram: 'https://instagram.com/dpmdbangkalan',
    youtube: 'https://youtube.com/@dpmdbangkalan',
    twitter: ''
});

const isDirty = ref(false);

const submit = () => {
    // Simulasi save
    isDirty.value = false;
};
</script>

<template>
    <Head title="Pengaturan Kontak & Media Sosial" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Kontak & Media Sosial
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola informasi kontak dinas dan tautan akun media sosial resmi.</p>
            </div>
            
            <button 
                @click="submit"
                class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]"
            >
                <span class="material-symbols-outlined text-[18px]">save</span>
                Simpan Perubahan
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- INFORMASI KONTAK -->
            <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-8">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-[#e3e5e7]">
                    <div class="w-10 h-10 rounded-xl bg-[#e3f2fd] flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#1976d2] text-[22px]">contact_phone</span>
                    </div>
                    <div>
                        <h3 class="text-[18px] font-bold text-[#0f172a]">Informasi Kontak</h3>
                        <p class="text-[13px] font-medium text-[#646a79]">Alamat, nomor telepon, dan jam layanan</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Alamat Kantor</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-3 text-[#9499a3]">location_on</span>
                            <textarea v-model="form.alamat" rows="2" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Masukkan alamat lengkap..."></textarea>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Email Dinas</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">mail</span>
                                <input v-model="form.email" type="email" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="email@domain.go.id" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Telepon Kantor</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">call</span>
                                <input v-model="form.telepon" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="(031) xxx" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">WhatsApp Layanan</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">forum</span>
                                <input v-model="form.whatsapp" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="081xxx" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Jam Kerja</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">schedule</span>
                                <input v-model="form.jamKerja" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Senin - Jumat..." />
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Koordinat Google Maps</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">map</span>
                            <input v-model="form.koordinatMap" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Latitude, Longitude" />
                        </div>
                        <p class="text-[12px] text-[#9499a3] mt-1.5 font-medium">Contoh: -7.0270059, 112.7483669. Digunakan untuk menampilkan peta di website.</p>
                    </div>
                </div>
            </div>

            <!-- MEDIA SOSIAL -->
            <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-8">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-[#e3e5e7]">
                    <div class="w-10 h-10 rounded-xl bg-[#fce4ec] flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#c2185b] text-[22px]">share</span>
                    </div>
                    <div>
                        <h3 class="text-[18px] font-bold text-[#0f172a]">Widget Media Sosial</h3>
                        <p class="text-[13px] font-medium text-[#646a79]">Tautan ke jejaring sosial resmi DPMD</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Facebook URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#1877f2]">public</span>
                            <input v-model="form.facebook" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://facebook.com/..." />
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Instagram URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#e1306c]">photo_camera</span>
                            <input v-model="form.instagram" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://instagram.com/..." />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">YouTube Channel URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#ff0000]">play_circle</span>
                            <input v-model="form.youtube" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://youtube.com/..." />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">X (Twitter) URL <span class="text-[#9499a3] font-normal normal-case">(Opsional)</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#000000]">alternate_email</span>
                            <input v-model="form.twitter" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://x.com/..." />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
