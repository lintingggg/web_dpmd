<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from '@idds/vue';

const props = defineProps({
    pengumuman: Object,
    filters: Object
});

const { toast } = useToast();

// Search & Filter State
const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || 'Semua Status');

// Watch search and status filter
let filterTimeout = null;
watch([search, statusFilter], ([newSearch, newStatus]) => {
    if (filterTimeout) clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(
            route('admin.pengumuman.index'),
            { search: newSearch, status: newStatus === 'Semua Status' ? null : newStatus },
            { preserveState: true, replace: true }
        );
    }, 300);
});

// Modal Form State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const existingLampiran = ref(null);
const fileInput = ref(null);

const form = useForm({
    judul: '',
    konten: '',
    file_lampiran: null,
    is_published: true,
    remove_lampiran: false,
});

// Modal Detail State
const isDetailOpen = ref(false);
const detailItem = ref(null);

const openDetail = (item) => {
    detailItem.value = item;
    isDetailOpen.value = true;
};

const openModal = () => {
    isEditing.value = false;
    existingLampiran.value = null;
    
    // Explicitly reset form fields to fix the bug
    form.judul = '';
    form.konten = '';
    form.file_lampiran = null;
    form.is_published = true;
    form.remove_lampiran = false;
    form.clearErrors();
    
    if (fileInput.value) fileInput.value.value = ''; // Clear file input DOM
    
    isModalOpen.value = true;
};

const editModal = (item) => {
    isEditing.value = true;
    editingId.value = item.id;
    existingLampiran.value = item.file_lampiran;
    
    // Explicit assignment
    form.judul = item.judul;
    form.konten = item.konten;
    form.is_published = Boolean(item.is_published);
    form.file_lampiran = null;
    form.remove_lampiran = false;
    form.clearErrors();

    if (fileInput.value) fileInput.value.value = ''; // Clear file input DOM

    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => {
        form.reset();
    }, 300);
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(route('admin.pengumuman.update', editingId.value), {
            onSuccess: () => {
                closeModal();
            },
        });
    } else {
        form.post(route('admin.pengumuman.store'), {
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

// Delete Modal State
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const confirmDelete = (item) => {
    itemToDelete.value = item;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(route('admin.pengumuman.destroy', itemToDelete.value.id), {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                itemToDelete.value = null;
            }
        });
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};
</script>

<template>
    <Head title="Manajemen Pengumuman" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                        Pengumuman
                    </h2>
                    <p class="text-[14px] font-medium text-[#646a79]">Kelola daftar pengumuman penting yang tampil di halaman depan website.</p>
                </div>
            
                <button @click="openModal" class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Pengumuman
                </button>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Filter & Search Bar -->
            <div class="p-6 md:p-8 border-b border-[#e3e5e7] flex flex-col md:flex-row gap-6 justify-between items-start md:items-center bg-[#f9f9f9]">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari judul pengumuman..." class="w-full bg-white border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                </div>
                
                <!-- Status Filter -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <span class="text-[11px] font-bold text-[#9499a3] uppercase tracking-[1px]">Status:</span>
                    <select v-model="statusFilter" class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c8cbd0]">
                        <option value="Semua Status">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif (Draft)</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Judul Pengumuman</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-40">Tanggal Posting</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-36">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        <tr v-if="props.pengumuman.data.length === 0">
                            <td colspan="4" class="py-8 text-center text-[#646a79]">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-[48px] text-[#e3e5e7] mb-2">find_in_page</span>
                                    <p>Tidak ada pengumuman yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="item in props.pengumuman.data" :key="item.id" class="hover:bg-[#f9f9f9]/50 transition-colors group">
                            
                            <!-- Judul -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#fff8e1] flex items-center justify-center flex-shrink-0 border border-[#ffecb3]">
                                        <span class="material-symbols-outlined text-[#f57f17] text-[20px]">campaign</span>
                                    </div>
                                    <div>
                                        <p @click="openDetail(item)" class="font-bold text-[#0f172a] leading-tight hover:text-[#1976d2] hover:underline transition-colors cursor-pointer line-clamp-2">
                                            {{ item.judul }}
                                        </p>
                                        <div v-if="item.file_lampiran" class="text-xs flex items-center gap-1 text-blue-500 mt-1">
                                            <span class="material-symbols-outlined text-[14px]">attachment</span>
                                            Ada lampiran
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-4 px-6">
                                <p v-if="item.is_published" class="text-[13px] font-bold text-[#646a79]">{{ formatDate(item.created_at) }}</p>
                                <p v-else class="text-[13px] italic text-[#9499a3]">Belum dipublish</p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span v-if="item.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Aktif
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f0f1f1] text-[#646a79] border border-[#e3e5e7]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#9499a3]"></span> Non-Aktif
                                </span>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button @click="editModal(item)" class="w-8 h-8 rounded-full hover:bg-[#e3e5e7] text-[#646a79] flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button @click="confirmDelete(item)" class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#e3e5e7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f9f9f9]" v-if="props.pengumuman.total > 0">
                <p class="text-[13px] font-medium text-[#646a79]">Menampilkan <span class="font-bold text-[#0f172a]">{{ props.pengumuman.from }}</span> - <span class="font-bold text-[#0f172a]">{{ props.pengumuman.to }}</span> dari <span class="font-bold text-[#0f172a]">{{ props.pengumuman.total }}</span> pengumuman</p>
                
                <div class="flex items-center gap-1">
                    <component 
                        v-for="link in props.pengumuman.links" 
                        :is="link.url ? 'a' : 'span'" 
                        :href="link.url" 
                        :key="link.label"
                        v-html="link.label"
                        :class="[ 
                            'px-3 py-1.5 flex items-center justify-center rounded-lg font-medium text-[13px] transition-colors',
                            link.active ? 'bg-[#0f172a] text-white font-bold' : 'text-[#646a79] hover:bg-[#e3e5e7]',
                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                    ></component>
                </div>
            </div>
            
        </div>

        <!-- Modal Detail Pengumuman -->
        <Modal :show="isDetailOpen" @close="isDetailOpen = false" maxWidth="2xl">
            <div class="p-6 md:p-8" v-if="detailItem">
                <div class="flex items-center justify-between mb-6">
                    <span v-if="detailItem.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Status: Aktif
                    </span>
                    <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f0f1f1] text-[#646a79] border border-[#e3e5e7]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9499a3]"></span> Status: Non-Aktif (Draft)
                    </span>
                    
                    <button @click="isDetailOpen = false" class="text-[#9499a3] hover:text-[#0f172a] p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <h2 class="text-[24px] font-bold text-[#0f172a] leading-tight mb-2">
                    {{ detailItem.judul }}
                </h2>
                
                <div class="flex items-center gap-2 text-[13px] text-[#646a79] mb-8">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    {{ detailItem.is_published ? formatDate(detailItem.created_at) : 'Belum dipublikasikan' }}
                </div>

                <div class="prose max-w-none text-[#373f50] text-[15px] leading-relaxed mb-8 whitespace-pre-wrap">
                    {{ detailItem.konten }}
                </div>

                <div v-if="detailItem.file_lampiran" class="bg-[#f8f9fa] border border-[#e3e5e7] rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg border border-[#e3e5e7] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1976d2]">description</span>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-[#0f172a]">Terdapat File Lampiran</p>
                            <p class="text-[12px] text-[#646a79]">Tersimpan di sistem</p>
                        </div>
                    </div>
                    <a :href="'/storage/' + detailItem.file_lampiran" target="_blank" class="text-[13px] font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                        Buka File <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                    </a>
                </div>

                <div class="mt-8 pt-6 border-t border-[#e3e5e7] flex justify-end">
                    <button @click="isDetailOpen = false" class="px-5 py-2.5 rounded-xl font-bold bg-[#0f172a] text-white hover:bg-[#222a3d] transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Modal Form Pengumuman -->
        <Modal :show="isModalOpen" @close="closeModal" maxWidth="2xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-bold text-[#0f172a]">
                        {{ isEditing ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
                    </h2>
                    <button @click="closeModal" class="text-[#9499a3] hover:text-[#0f172a]">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-bold text-[#0f172a] mb-1.5">Judul Pengumuman</label>
                        <input v-model="form.judul" type="text" class="w-full bg-white border border-[#e3e5e7] rounded-xl px-4 py-2.5 text-[14px] focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Masukkan judul..." required />
                        <div v-if="form.errors.judul" class="text-red-500 text-xs mt-1">{{ form.errors.judul }}</div>
                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-bold text-[#0f172a] mb-1.5">Isi Pengumuman</label>
                        <textarea v-model="form.konten" rows="5" class="w-full bg-white border border-[#e3e5e7] rounded-xl px-4 py-2.5 text-[14px] focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Tulis konten pengumuman..." required></textarea>
                        <div v-if="form.errors.konten" class="text-red-500 text-xs mt-1">{{ form.errors.konten }}</div>
                    </div>

                    <!-- File Lampiran -->
                    <div>
                        <label class="block text-sm font-bold text-[#0f172a] mb-1.5">File Lampiran (Opsional)</label>
                        <input ref="fileInput" type="file" @input="form.file_lampiran = $event.target.files[0]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                        
                        <!-- Existing File Notice and Delete Action -->
                        <div v-if="isEditing && existingLampiran && !form.remove_lampiran" class="mt-3 flex items-center justify-between p-3 border border-blue-100 rounded-lg bg-blue-50/50">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">attachment</span>
                                <span class="text-[13px] font-semibold text-blue-900">File sudah dilampirkan sebelumnya.</span>
                            </div>
                            <button type="button" @click="form.remove_lampiran = true" class="text-[13px] font-bold text-red-600 hover:text-red-800 transition-colors bg-red-50 px-3 py-1.5 rounded-md">
                                Hapus File
                            </button>
                        </div>
                        <div v-else-if="isEditing && existingLampiran && form.remove_lampiran" class="mt-3 flex items-center justify-between p-3 border border-red-100 rounded-lg bg-red-50/50">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-red-600">delete</span>
                                <span class="text-[13px] font-semibold text-red-900 italic">File lama akan dihapus saat disimpan.</span>
                            </div>
                            <button type="button" @click="form.remove_lampiran = false" class="text-[13px] font-bold text-blue-600 hover:text-blue-800 transition-colors bg-blue-50 px-3 py-1.5 rounded-md">
                                Batal Hapus
                            </button>
                        </div>

                        <div v-if="form.errors.file_lampiran" class="text-red-500 text-xs mt-1">{{ form.errors.file_lampiran }}</div>
                        <p class="text-xs text-gray-500 mt-2">* Format didukung: PDF, Word, JPG, PNG. Maksimal 10MB.</p>
                    </div>

                    <!-- Status Publish/Draft -->
                    <div>
                        <label class="block text-sm font-bold text-[#0f172a] mb-1.5">Status Publikasi</label>
                        <select v-model="form.is_published" class="w-full bg-white border border-[#e3e5e7] rounded-xl px-4 py-2.5 text-[14px] focus:ring-[#0f172a] focus:border-[#0f172a]">
                            <option :value="true">Aktif (Tampil di Web)</option>
                            <option :value="false">Non-Aktif (Sembunyikan / Draft)</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-[#e3e5e7]">
                        <button type="button" @click="closeModal" class="px-5 py-2.5 rounded-xl font-bold text-[#646a79] hover:bg-[#f0f1f1] transition-colors">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-xl transition-all disabled:opacity-70 flex items-center gap-2">
                            <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal Konfirmasi Hapus -->
        <Modal :show="isDeleteModalOpen" @close="isDeleteModalOpen = false" maxWidth="md">
            <div class="p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-[#ffebee] flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-[#d32f2f] text-[32px]">warning</span>
                    </div>
                    <h2 class="text-xl font-bold text-[#0f172a] mb-2">Hapus Pengumuman?</h2>
                    <p class="text-[#646a79] text-[14px] mb-6">
                        Tindakan ini tidak dapat dibatalkan. Pengumuman <strong class="text-[#0f172a]">"{{ itemToDelete?.judul }}"</strong> akan dihapus secara permanen.
                    </p>
                    <div class="flex items-center justify-center gap-3 w-full">
                        <button @click="isDeleteModalOpen = false" class="flex-1 py-2.5 rounded-xl font-bold text-[#646a79] bg-[#f0f1f1] hover:bg-[#e3e5e7] transition-colors">
                            Batal
                        </button>
                        <button @click="executeDelete" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-[#d32f2f] hover:bg-[#b71c1c] transition-colors shadow-[0_4px_12px_rgba(211,47,47,0.2)]">
                            Ya, Hapus!
                        </button>
                    </div>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
