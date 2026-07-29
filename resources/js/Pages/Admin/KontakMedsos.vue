<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from '@idds/vue';
import { ref, watch } from 'vue';

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
    twitter_url: props.kontak?.twitter_url || '',
    tiktok_url: props.kontak?.tiktok_url || '',
    
    // Embeds
    instagram_embed_1: props.kontak?.instagram_embed_1 || '',
    instagram_embed_2: props.kontak?.instagram_embed_2 || '',
    tiktok_embed_1: props.kontak?.tiktok_embed_1 || '',
    tiktok_embed_2: props.kontak?.tiktok_embed_2 || '',
    youtube_embed_1: props.kontak?.youtube_embed_1 || '',
    youtube_embed_2: props.kontak?.youtube_embed_2 || '',
    facebook_embed_1: props.kontak?.facebook_embed_1 || '',
    facebook_embed_2: props.kontak?.facebook_embed_2 || '',
    twitter_embed_1: props.kontak?.twitter_embed_1 || '',
    twitter_embed_2: props.kontak?.twitter_embed_2 || '',

    // Toggles
    show_instagram: Boolean(props.kontak?.show_instagram ?? true),
    show_tiktok: Boolean(props.kontak?.show_tiktok ?? true),
    show_youtube: Boolean(props.kontak?.show_youtube ?? true),
    show_facebook: Boolean(props.kontak?.show_facebook ?? true),
    show_twitter: Boolean(props.kontak?.show_twitter ?? true),
});

// State Tab
const activeTab = ref('kontak');

// Logic untuk Jam Kerja Interaktif
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

        <!-- Tabs Navigation -->
        <div class="flex flex-wrap gap-2 mb-6 bg-white p-2 rounded-xl border border-[#e3e5e7] shadow-sm">
            <button @click="activeTab = 'kontak'" :class="['px-5 py-2.5 rounded-lg text-[14px] font-bold transition-all', activeTab === 'kontak' ? 'bg-[#0f172a] text-white shadow-md' : 'text-[#646a79] hover:bg-[#f3f4f6] hover:text-[#0f172a]']">Informasi Kontak</button>
            <button @click="activeTab = 'sosmed'" :class="['px-5 py-2.5 rounded-lg text-[14px] font-bold transition-all', activeTab === 'sosmed' ? 'bg-[#0f172a] text-white shadow-md' : 'text-[#646a79] hover:bg-[#f3f4f6] hover:text-[#0f172a]']">Tautan Profil Media Sosial</button>
            <button @click="activeTab = 'embed'" :class="['px-5 py-2.5 rounded-lg text-[14px] font-bold transition-all', activeTab === 'embed' ? 'bg-[#0f172a] text-white shadow-md' : 'text-[#646a79] hover:bg-[#f3f4f6] hover:text-[#0f172a]']">Widget Beranda (Embed)</button>
        </div>

        <div class="w-full">
            
            <!-- TAB: INFORMASI KONTAK -->
            <div v-show="activeTab === 'kontak'" class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-8">
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

            <!-- TAB: MEDIA SOSIAL -->
            <div v-show="activeTab === 'sosmed'" class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-8">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-[#e3e5e7]">
                    <div class="w-10 h-10 rounded-xl bg-[#fce4ec] flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#c2185b] text-[22px]">share</span>
                    </div>
                    <div>
                        <h3 class="text-[18px] font-bold text-[#0f172a]">Tautan Profil Media Sosial</h3>
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

                    <div>
                        <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">TikTok URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#000000]">music_note</span>
                            <input v-model="form.tiktok_url" type="url" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-medium rounded-xl pl-11 pr-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="https://tiktok.com/@..." />
                        </div>
                        <div v-if="form.errors.tiktok_url" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tiktok_url }}</div>
                    </div>
                </div>
            </div>

            <!-- TAB: WIDGET EMBED BERANDA -->
            <div v-show="activeTab === 'embed'" class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-8">
                <div class="flex items-center gap-3 mb-8 pb-6 border-b border-[#e3e5e7]">
                    <div class="w-10 h-10 rounded-xl bg-[#e8f5e9] flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#2e7d32] text-[22px]">code</span>
                    </div>
                    <div>
                        <h3 class="text-[18px] font-bold text-[#0f172a]">Pengaturan Widget Beranda (Embed)</h3>
                        <p class="text-[13px] font-medium text-[#646a79]">Kode Embed Iframe untuk menampilkan postingan media sosial di Halaman Utama.</p>
                    </div>
                </div>

                <div class="space-y-12">
                    <!-- Instagram Embeds -->
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#0f172a] text-[16px]">Instagram</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.show_instagram" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0f172a]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Tampilkan di Beranda</span>
                            </label>
                        </div>
                        
                        <div v-show="form.show_instagram" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link Instagram 1</label>
                                <textarea v-model="form.instagram_embed_1" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed atau link Instagram (https://instagram.com/p/...)"></textarea>
                                <div v-if="form.errors.instagram_embed_1" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.instagram_embed_1 }}</div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link Instagram 2</label>
                                <textarea v-model="form.instagram_embed_2" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed atau link Instagram (https://instagram.com/p/...)"></textarea>
                                <div v-if="form.errors.instagram_embed_2" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.instagram_embed_2 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- TikTok Embeds -->
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#0f172a] text-[16px]">TikTok</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.show_tiktok" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0f172a]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Tampilkan di Beranda</span>
                            </label>
                        </div>
                        
                        <div v-show="form.show_tiktok" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link TikTok 1</label>
                                <textarea v-model="form.tiktok_embed_1" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed atau link video/photo TikTok (https://tiktok.com/@.../video/...)"></textarea>
                                <div v-if="form.errors.tiktok_embed_1" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tiktok_embed_1 }}</div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link TikTok 2</label>
                                <textarea v-model="form.tiktok_embed_2" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed atau link video/photo TikTok (https://tiktok.com/@.../video/...)"></textarea>
                                <div v-if="form.errors.tiktok_embed_2" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tiktok_embed_2 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- YouTube Embeds -->
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#0f172a] text-[16px]">YouTube</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.show_youtube" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0f172a]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Tampilkan di Beranda</span>
                            </label>
                        </div>
                        
                        <div v-show="form.show_youtube" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link YouTube 1</label>
                                <textarea v-model="form.youtube_embed_1" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed HTML atau link (https://youtube.com/...)"></textarea>
                                <div v-if="form.errors.youtube_embed_1" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.youtube_embed_1 }}</div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link YouTube 2</label>
                                <textarea v-model="form.youtube_embed_2" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed HTML atau link (https://youtube.com/...)"></textarea>
                                <div v-if="form.errors.youtube_embed_2" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.youtube_embed_2 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Facebook Embeds -->
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#0f172a] text-[16px]">Facebook</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.show_facebook" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0f172a]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Tampilkan di Beranda</span>
                            </label>
                        </div>
                        
                        <div v-show="form.show_facebook" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link Facebook 1</label>
                                <textarea v-model="form.facebook_embed_1" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed HTML atau link posting Facebook (https://facebook.com/...)"></textarea>
                                <div v-if="form.errors.facebook_embed_1" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.facebook_embed_1 }}</div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link Facebook 2</label>
                                <textarea v-model="form.facebook_embed_2" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed HTML atau link posting Facebook (https://facebook.com/...)"></textarea>
                                <div v-if="form.errors.facebook_embed_2" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.facebook_embed_2 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Twitter Embeds -->
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#0f172a] text-[16px]">X (Twitter)</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.show_twitter" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0f172a]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Tampilkan di Beranda</span>
                            </label>
                        </div>
                        
                        <div v-show="form.show_twitter" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link X (Twitter) 1</label>
                                <textarea v-model="form.twitter_embed_1" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed HTML atau link tweet (https://x.com/...)"></textarea>
                                <div v-if="form.errors.twitter_embed_1" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.twitter_embed_1 }}</div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-[#373f50] uppercase tracking-[0.5px] mb-2">Kode Embed / Link X (Twitter) 2</label>
                                <textarea v-model="form.twitter_embed_2" rows="4" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] font-mono rounded-xl px-4 py-3 focus:ring-[#0f172a] focus:border-[#0f172a] focus:bg-white transition-colors" placeholder="Paste kode embed HTML atau link tweet (https://x.com/...)"></textarea>
                                <div v-if="form.errors.twitter_embed_2" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.twitter_embed_2 }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
