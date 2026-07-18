<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from '@idds/vue';

const props = defineProps({
    kontak: Object
});

const { toast } = useToast();

const form = useForm({
    // Kontak
    alamat: props.kontak?.alamat || '',
    email: props.kontak?.email || '',
    telepon: props.kontak?.telepon || '',
    whatsapp: props.kontak?.whatsapp || '',
    jam_kerja: props.kontak?.jam_kerja || '',
    koordinat_map: props.kontak?.koordinat_map || '',
    
    // Medsos
    facebook_url: props.kontak?.facebook_url || '',
    instagram_url: props.kontak?.instagram_url || '',
    youtube_url: props.kontak?.youtube_url || '',
    twitter_url: props.kontak?.twitter_url || ''
});

// Logic untuk Jam Kerja Interaktif
import { ref, watch } from 'vue';

let initHariBuka = 'Senin';
let initHariTutup = 'Jumat';
let initJamBuka = '08:00';
let initJamTutup = '15:30';

const jamKerjaString = props.kontak?.jam_kerja || '';
const match = jamKerjaString.match(/^([a-zA-Z]+) - ([a-zA-Z]+), (\d{2}:\d{2}) - (\d{2}:\d{2})/);
if (match) {
    initHariBuka = match[1];
    initHariTutup = match[2];
    initJamBuka = match[3];
    initJamTutup = match[4];
}

const hariBuka = ref(initHariBuka);
const hariTutup = ref(initHariTutup);
const jamBuka = ref(initJamBuka);
const jamTutup = ref(initJamTutup);

const hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

watch([hariBuka, hariTutup, jamBuka, jamTutup], () => {
    form.jam_kerja = `${hariBuka.value} - ${hariTutup.value}, ${jamBuka.value} - ${jamTutup.value} WIB`;
}, { immediate: true });

const submit = () => {
    form.post(route('admin.kontak-medsos.update'), {
        preserveScroll: true
    });
};
</script>

<template>
    <Head title="Pengaturan Kontak & Media Sosial" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                        Kontak & Media Sosial
                    </h2>
                    <p class="text-[14px] font-medium text-[#646a79]">Kelola informasi kontak dinas dan tautan akun media sosial resmi.</p>
                </div>
                
                <button 
                    @click="submit"
                    :disabled="form.processing"
                    class="bg-[#0f172a] hover:bg-[#222a3d] disabled:opacity-70 text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]"
                >
                    <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                    <span v-else class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
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
                        <div v-if="form.errors.alamat" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.alamat }}</div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Email Dinas</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">mail</span>
                                <input v-model="form.email" type="email" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="email@domain.go.id" />
                            </div>
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Telepon Kantor</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">call</span>
                                <input v-model="form.telepon" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="(031) xxx" />
                            </div>
                            <div v-if="form.errors.telepon" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.telepon }}</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">WhatsApp Layanan</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">forum</span>
                                <input v-model="form.whatsapp" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="081xxx" />
                            </div>
                            <div v-if="form.errors.whatsapp" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.whatsapp }}</div>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Jam Kerja</label>
                            
                            <div class="bg-[#f9f9f9] border border-[#e3e5e7] rounded-xl p-3 focus-within:border-[#0f172a] focus-within:bg-white transition-colors">
                                <!-- Pilihan Hari -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="material-symbols-outlined text-[#9499a3] text-[18px]">calendar_month</span>
                                    <select v-model="hariBuka" class="flex-1 bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-medium rounded-lg px-2 py-1.5 focus:ring-[#0f172a]">
                                        <option v-for="hari in hariOptions" :key="hari" :value="hari">{{ hari }}</option>
                                    </select>
                                    <span class="text-[#9499a3] font-bold">-</span>
                                    <select v-model="hariTutup" class="flex-1 bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-medium rounded-lg px-2 py-1.5 focus:ring-[#0f172a]">
                                        <option v-for="hari in hariOptions" :key="hari" :value="hari">{{ hari }}</option>
                                    </select>
                                </div>
                                
                                <!-- Pilihan Jam -->
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[#9499a3] text-[18px]">schedule</span>
                                    <input type="time" v-model="jamBuka" class="flex-1 bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-medium rounded-lg px-2 py-1.5 focus:ring-[#0f172a]" />
                                    <span class="text-[#9499a3] font-bold">-</span>
                                    <input type="time" v-model="jamTutup" class="flex-1 bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-medium rounded-lg px-2 py-1.5 focus:ring-[#0f172a]" />
                                </div>
                            </div>
                            
                            <div v-if="form.errors.jam_kerja" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.jam_kerja }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Koordinat Google Maps</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3]">map</span>
                            <input v-model="form.koordinat_map" type="text" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="-7.0270059, 112.7483669" />
                        </div>
                        <div v-if="form.errors.koordinat_map" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.koordinat_map }}</div>
                        <p class="text-[12px] text-[#9499a3] mt-1.5 font-medium">Format: Latitude, Longitude (Contoh: -7.0270059, 112.7483669). Digunakan untuk menampilkan peta di website.</p>
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
                            <input v-model="form.facebook_url" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://facebook.com/..." />
                        </div>
                        <div v-if="form.errors.facebook_url" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.facebook_url }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Instagram URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#e1306c]">photo_camera</span>
                            <input v-model="form.instagram_url" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://instagram.com/..." />
                        </div>
                        <div v-if="form.errors.instagram_url" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.instagram_url }}</div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">YouTube Channel URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#ff0000]">play_circle</span>
                            <input v-model="form.youtube_url" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://youtube.com/..." />
                        </div>
                        <div v-if="form.errors.youtube_url" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.youtube_url }}</div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">X (Twitter) URL <span class="text-[#9499a3] font-normal normal-case">(Opsional)</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#000000]">alternate_email</span>
                            <input v-model="form.twitter_url" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://x.com/..." />
                        </div>
                        <div v-if="form.errors.twitter_url" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.twitter_url }}</div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
