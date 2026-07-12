<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button, TextField, Badge } from '@idds/vue';

// Dummy data untuk pengumuman
const announcements = ref([
    { id: 1, title: 'Pemeliharaan Server SIPKD', status: 'Aktif', date: '12/07/2026' },
    { id: 2, title: 'Libur Nasional Hari Raya', status: 'Tidak Aktif', date: '01/06/2026' },
]);
</script>

<template>
    <Head title="Manajemen Beranda" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">Manajemen Beranda</h2>
                <p class="text-[14px] font-medium text-[#646a79]">Atur konten halaman utama, pengumuman, dan informasi kontak DPMD.</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <button class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Pengumuman -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Section Pengumuman -->
                <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
                    <div class="p-6 border-b border-[#e3e5e7] flex justify-between items-center bg-[#f9f9f9]">
                        <div>
                            <h3 class="text-[18px] font-bold text-[#0f172a]">Daftar Pengumuman</h3>
                            <p class="text-[12px] text-[#646a79] mt-1">Pengumuman berjalan yang tampil di beranda website.</p>
                        </div>
                        <button class="bg-[#0f172a] text-white text-[12px] font-bold px-4 py-2 rounded-full hover:bg-[#222a3d] transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">add</span> Tambah
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Judul Pengumuman</th>
                                    <th class="py-3 px-4 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Status</th>
                                    <th class="py-3 px-4 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Tanggal</th>
                                    <th class="py-3 px-4 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] text-right border-b border-[#e3e5e7]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                                <tr v-for="item in announcements" :key="item.id" class="hover:bg-[#f9f9f9] transition-colors">
                                    <td class="py-3 px-4 text-[#0f172a]">{{ item.title }}</td>
                                    <td class="py-3 px-4">
                                        <span v-if="item.status === 'Aktif'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#dae2fd] text-[#0f172a]">Aktif</span>
                                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#e3e5e7] text-[#646a79]">Tidak Aktif</span>
                                    </td>
                                    <td class="py-3 px-4 text-[#646a79]">{{ item.date }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <button class="text-[#0f172a] hover:text-[#222a3d] font-bold text-[12px] uppercase tracking-wider">Edit</button>
                                        <button class="text-[#ba1a1a] hover:text-[#93000a] font-bold text-[12px] uppercase tracking-wider ml-3">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section Highlight Berita -->
                <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden p-6">
                    <h3 class="text-[18px] font-bold text-[#0f172a] mb-1">Berita Terkini</h3>
                    <p class="text-[12px] text-[#646a79] mb-4">Pengaturan jumlah berita terbaru yang ditampilkan di halaman Beranda.</p>
                    
                    <div class="flex items-center gap-4">
                        <label class="text-[14px] font-bold text-[#0f172a]">Jumlah Berita Tampil:</label>
                        <select class="bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2 focus:ring-[#0f172a] focus:border-[#0f172a]">
                            <option>3 Berita</option>
                            <option selected>6 Berita</option>
                            <option>9 Berita</option>
                        </select>
                    </div>
                    <div class="mt-4 p-4 bg-[#f9f9f9] rounded-lg border border-[#e3e5e7] flex gap-3 items-start">
                        <span class="material-symbols-outlined text-[#646a79]">info</span>
                        <p class="text-[13px] text-[#646a79] leading-relaxed">
                            Konten berita sepenuhnya dikelola melalui menu <strong>Berita</strong> di sidebar. Pengaturan di sini hanya membatasi jumlah berita yang ditarik ke halaman utama.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Kontak & Medsos -->
            <div class="space-y-8">
                <!-- Kontak Form -->
                <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-6">
                    <h3 class="text-[18px] font-bold text-[#0f172a] mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">contact_support</span> Info Kontak
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">Call Center / Telepon</label>
                            <input type="text" value="(031) xxxx-xxxx" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">WhatsApp</label>
                            <input type="text" value="08123456789" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">Email Dinas</label>
                            <input type="email" value="info@dpmd.bangkalan.go.id" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">Alamat Lengkap</label>
                            <textarea rows="3" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]">Jl. Raya Bangkalan No. 123, Kabupaten Bangkalan, Jawa Timur.</textarea>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">Google Map URL (Embed)</label>
                            <input type="text" placeholder="https://www.google.com/maps/embed?pb=..." class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                    </div>
                </div>

                <!-- Widget Medsos Form -->
                <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] p-6">
                    <h3 class="text-[18px] font-bold text-[#0f172a] mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">share</span> Widget Medsos
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">URL Instagram</label>
                            <input type="url" placeholder="https://instagram.com/dpmdbangkalan" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">URL Facebook Page</label>
                            <input type="url" placeholder="https://facebook.com/..." class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#373f50] mb-1">URL YouTube</label>
                            <input type="url" placeholder="https://youtube.com/..." class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
