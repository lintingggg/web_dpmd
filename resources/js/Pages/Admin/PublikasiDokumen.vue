<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from '@idds/vue';

const props = defineProps({
    dokumen: Object,
    filters: Object,
    categories: Object
});

const { toast } = useToast();

const activeTab = ref(props.filters?.kategori || 'perencanaan');
const search = ref(props.filters?.search || '');
const tahunFilter = ref(props.filters?.tahun || '');
const statusFilter = ref(props.filters?.status || '');

// Buat list unik tahun dari tahun saat ini sampai 2020
const currentYear = new Date().getFullYear();
const availableYears = Array.from({ length: currentYear - 2020 + 1 }, (_, i) => currentYear - i);

let filterTimeout = null;
watch([activeTab, search, tahunFilter, statusFilter], () => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(
            route('admin.publikasi-dokumen'),
            { 
                kategori: activeTab.value, 
                search: search.value,
                tahun: tahunFilter.value,
                status: statusFilter.value
            },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300);
});

// Modal State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const fileInput = ref(null);
const fileSizeError = ref(false);

const form = useForm({
    judul: '',
    kategori: 'perencanaan',
    tahun: currentYear,
    deskripsi: '',
    is_published: true,
    file_dokumen: null,
});

const openModal = (item = null) => {
    if (item) {
        isEditing.value = true;
        editingId.value = item.id;
        form.judul = item.judul;
        form.kategori = item.kategori;
        form.tahun = item.tahun;
        form.deskripsi = item.deskripsi || '';
        form.is_published = item.is_published;
        form.file_dokumen = null; // Tidak perlu assign file object lama
    } else {
        isEditing.value = false;
        editingId.value = null;
        form.reset();
        form.kategori = activeTab.value;
        form.tahun = currentYear;
    }
    
    if (fileInput.value) {
        fileInput.value.value = '';
    }
    
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const handleFileUpload = (e) => {
    fileSizeError.value = false;
    const file = e.target.files[0];
    if (file) {
        if (file.size > 5242880) { // 5MB limit
            fileSizeError.value = true;
            form.file_dokumen = null;
            if (fileInput.value) fileInput.value.value = '';
        } else {
            form.file_dokumen = file;
        }
    } else {
        form.file_dokumen = null;
    }
};

const submitForm = () => {
    if (fileSizeError.value) {
        toast({ state: 'warning', title: 'File Terlalu Besar', description: 'Ukuran file dokumen melebihi batas 5MB.' });
        return;
    }

    if (isEditing.value) {
        // Tambahkan spoofing PUT saat edit
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.publikasi-dokumen.update', editingId.value), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    } else {
        // Kembalikan ke normal (tanpa PUT) saat tambah baru
        form.transform((data) => data).post(route('admin.publikasi-dokumen.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

// Delete
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const confirmDelete = (item) => {
    itemToDelete.value = item;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(route('admin.publikasi-dokumen.destroy', itemToDelete.value.id), {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                itemToDelete.value = null;
            }
        });
    }
};

// Detail Modal
const isDetailModalOpen = ref(false);
const selectedDocument = ref(null);

const openDetailModal = (item) => {
    selectedDocument.value = item;
    isDetailModalOpen.value = true;
};

const openDocument = (path) => {
    if (path) {
        window.open('/storage/' + path, '_blank');
    } else {
        toast({ state: 'warning', title: 'File Kosong', description: 'Dokumen ini belum memiliki file lampiran.', duration: 3000 });
    }
};
</script>

<template>
    <Head title="Publikasi Dokumen" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="pt-4 text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1">
                        Publikasi Dokumen
                    </h2>
                    <p class="text-[14px] font-medium text-slate-500">Kelola file dokumen PDF publik seperti Renstra, Peraturan, dan SOP.</p>
                </div>
                
                <button @click="openModal()" class="bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_16px_rgba(19,86,160,0.3)]">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Dokumen
                </button>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            
            <!-- Tabs Navigation -->
            <div class="px-6 md:px-8 border-b border-[#dbe6f7] flex gap-8 overflow-x-auto">
                <button 
                    v-for="(label, key) in categories" 
                    :key="key"
                    @click="activeTab = key"
                    class="py-4 text-[14px] font-bold border-b-2 transition-colors whitespace-nowrap"
                    :class="activeTab === key ? 'border-[#1356a0] text-slate-700' : 'border-transparent text-slate-500 hover:text-slate-900'"
                >
                    {{ label }}
                </button>
            </div>

            <!-- Filters Area -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-[#f5f8fd]">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari judul dokumen..." class="w-full bg-white border border-[#dbe6f7] text-slate-900 text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                </div>
                
                <!-- Filter Section -->
                <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                    <div class="flex items-center gap-2 whitespace-nowrap">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Status:</span>
                        <select v-model="statusFilter" class="bg-white border border-[#dbe6f7] text-slate-900 text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c7dafa]">
                            <option value="">Semua</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-2 whitespace-nowrap">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Tahun:</span>
                        <select v-model="tahunFilter" class="bg-white border border-[#dbe6f7] text-slate-900 text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c7dafa]">
                            <option value="">Semua</option>
                            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#f5f8fd]">
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7]">Judul Dokumen</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] text-center w-32">Tahun</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] text-right w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#dbe6f7]">
                        <tr v-for="doc in dokumen.data" :key="doc.id" class="hover:bg-[#f5f8fd]/50 transition-colors group">
                            <!-- Judul -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#ffebee] flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[#d32f2f] text-[22px]">picture_as_pdf</span>
                                    </div>
                                    <div>
                                        <p @click="openDetailModal(doc)" class="font-bold text-slate-900 leading-tight hover:text-slate-700 transition-colors cursor-pointer">
                                            {{ doc.judul }}
                                        </p>
                                        <span v-if="!doc.is_published" class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-bold bg-[#f1f3f4] text-slate-500 uppercase">
                                            Draft
                                        </span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Tahun -->
                            <td class="py-4 px-6 text-center text-slate-500 font-semibold">
                                {{ doc.tahun }}
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button @click="openModal(doc)" class="w-8 h-8 rounded-full hover:bg-[#dbe6f7] text-slate-500 flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button @click="confirmDelete(doc)" class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Empty State -->
                        <tr v-if="dokumen.data.length === 0">
                            <td colspan="3" class="py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-[48px] text-[#c7dafa] mb-2 block">folder_off</span>
                                Belum ada dokumen di kategori ini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#dbe6f7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f5f8fd]">
                <p class="text-[13px] font-medium text-slate-500">
                    Menampilkan <span class="font-bold text-slate-900">{{ dokumen.from || 0 }}-{{ dokumen.to || 0 }}</span> dari <span class="font-bold text-slate-900">{{ dokumen.total }}</span> dokumen
                </p>
                
                <div class="flex flex-wrap items-center gap-1" v-if="dokumen.links && dokumen.links.length > 3">
                    <Link 
                        v-for="(link, index) in dokumen.links" 
                        :key="index"
                        :href="link.url || '#'"
                        class="min-w-[32px] px-3 py-1.5 flex items-center justify-center rounded-lg font-medium text-[13px] transition-colors whitespace-nowrap"
                        :class="[
                            link.active ? 'bg-[#1356a0] text-white font-bold' : 'text-slate-500 hover:bg-[#dbe6f7]',
                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                        v-html="link.label"
                        preserve-scroll
                    />
                </div>
            </div>
            
        </div>
    </AuthenticatedLayout>

    <!-- Modal Form Tambah/Edit -->
    <Modal :show="isModalOpen" @close="closeModal">
        <div class="p-6 md:p-8">
            <h3 class="text-[20px] font-bold text-slate-900 mb-6">
                {{ isEditing ? 'Edit Dokumen' : 'Tambah Dokumen Publikasi' }}
            </h3>
            
            <form @submit.prevent="submitForm" class="space-y-5">
                <div>
                    <label class="block text-[14px] font-bold text-slate-700 mb-2">Judul Dokumen <span class="text-red-500">*</span></label>
                    <input v-model="form.judul" type="text" required placeholder="Misal: Rencana Strategis DPMD 2024-2029" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                    <div v-if="form.errors.judul" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.judul }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select v-model="form.kategori" required class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]">
                            <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
                        </select>
                        <div v-if="form.errors.kategori" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.kategori }}</div>
                    </div>
                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                        <select v-model="form.tahun" required class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]">
                            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                        <div v-if="form.errors.tahun" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tahun }}</div>
                    </div>
                </div>

                <div>
                    <label class="block text-[14px] font-bold text-slate-700 mb-2">File Dokumen (PDF/Word/Excel)</label>
                    <div class="relative w-full border-2 border-dashed border-[#c7dafa] bg-[#f5f8fd] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#eaf1fb] transition-colors group">
                        <span class="material-symbols-outlined text-[32px] text-slate-500 mb-2 group-hover:text-slate-900 transition-colors">upload_file</span>
                        <p class="text-[14px] font-medium text-slate-700 mb-1">
                            <span v-if="form.file_dokumen">{{ form.file_dokumen.name }}</span>
                            <span v-else>Pilih file untuk diunggah</span>
                        </p>
                        <p class="text-[12px] text-slate-500">Format .pdf, .doc, .docx, .xls, .xlsx (Maksimal 5MB)</p>
                        <input type="file" ref="fileInput" @change="handleFileUpload" accept=".pdf,.doc,.docx,.xls,.xlsx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    </div>
                    <div v-if="form.errors.file_dokumen" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.file_dokumen }}</div>
                    <div v-if="fileSizeError" class="text-red-500 text-xs mt-1 font-semibold">Ukuran file maksimal 5MB. File yang Anda pilih terlalu besar.</div>
                    
                    <div v-if="isEditing" class="mt-2 text-[12px] font-semibold text-slate-700 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        <span>Biarkan kosong jika tidak ingin mengubah dokumen saat ini.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[14px] font-bold text-slate-700 mb-2">Deskripsi Singkat</label>
                    <textarea v-model="form.deskripsi" rows="3" placeholder="Opsional..." class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0] resize-none"></textarea>
                    <div v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.deskripsi }}</div>
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" v-model="form.is_published" class="peer sr-only">
                            <div class="w-11 h-6 bg-[#c7dafa] rounded-full peer-checked:bg-[#1356a0] transition-colors duration-200 ease-in-out"></div>
                            <div class="absolute left-[2px] top-[2px] bg-white w-5 h-5 rounded-full shadow transition-transform duration-200 ease-in-out peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-[14px] font-bold text-slate-700 group-hover:text-slate-900 transition-colors">Tampilkan ke Publik</span>
                    </label>
                </div>
                
                <div class="pt-4 flex justify-end gap-3 border-t border-[#dbe6f7]">
                    <button type="button" @click="closeModal" class="px-5 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing || fileSizeError" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold text-[14px] shadow-[0_4px_16px_rgba(19,86,160,0.3)] transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                        {{ isEditing ? 'Simpan Perubahan' : 'Tambah Dokumen' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>

    <!-- Modal Konfirmasi Hapus -->
    <Modal :show="isDeleteModalOpen" @close="isDeleteModalOpen = false" max-width="md">
        <div class="p-6 md:p-8 text-center">
            <div class="w-16 h-16 rounded-full bg-[#ffebee] flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px] text-[#d32f2f]">warning</span>
            </div>
            <h3 class="text-[20px] font-bold text-slate-900 mb-2">Hapus Dokumen?</h3>
            <p class="text-[14px] font-medium text-slate-500 mb-8">
                Anda yakin ingin menghapus <span class="font-bold text-slate-900">"{{ itemToDelete?.judul }}"</span>? Tindakan ini akan menghapus file fisik dokumen tersebut.
            </p>
            <div class="flex justify-center gap-3">
                <button @click="isDeleteModalOpen = false" class="px-6 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                    Batal
                </button>
                <button @click="executeDelete" class="px-6 py-2.5 rounded-full bg-[#d32f2f] text-white font-bold text-[14px] hover:bg-[#b71c1c] shadow-[0_4px_12px_rgba(211,47,47,0.2)] transition-all active:scale-95">
                    Ya, Hapus Dokumen
                </button>
            </div>
        </div>
    </Modal>
    <!-- Modal Detail Dokumen -->
    <Modal :show="isDetailModalOpen" @close="isDetailModalOpen = false" max-width="2xl">
        <div v-if="selectedDocument" class="p-6 md:p-8">
            <div class="flex items-start justify-between mb-6 pb-6 border-b border-[#dbe6f7]">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#ffebee] flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#d32f2f] text-[28px]">picture_as_pdf</span>
                    </div>
                    <div>
                        <h3 class="text-[20px] font-bold text-slate-900 leading-tight mb-2">{{ selectedDocument.judul }}</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <span v-if="selectedDocument.is_published" class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-[#e6f4ea] text-[#137333] uppercase">
                                Publish
                            </span>
                            <span v-else class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-[#f1f3f4] text-slate-500 uppercase">
                                Draft
                            </span>
                            <span class="inline-flex items-center gap-1 text-[12px] font-semibold text-slate-500">
                                <span class="material-symbols-outlined text-[14px]">folder</span>
                                {{ categories[selectedDocument.kategori] }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[12px] font-semibold text-slate-500">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                Tahun {{ selectedDocument.tahun }}
                            </span>
                        </div>
                    </div>
                </div>
                <button @click="isDetailModalOpen = false" class="text-slate-500 hover:text-slate-900 transition-colors">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>

            <div class="space-y-6">
                <div>
                    <h4 class="text-[13px] font-bold text-slate-500 uppercase tracking-[1px] mb-2">Status Lampiran</h4>
                    <div v-if="selectedDocument.file_dokumen" class="inline-flex items-center gap-2 p-2.5 bg-[#eaf1fb] rounded-lg border border-[#c7dafa]">
                        <span class="material-symbols-outlined text-[20px] text-slate-700">insert_drive_file</span>
                        <span class="text-slate-900 text-[14px]">Telah terlampir: <span class="font-bold">File Tersimpan</span></span>
                    </div>
                    <div v-else class="inline-flex items-center gap-2 p-2.5 bg-[#eaf1fb] rounded-lg border border-[#dbe6f7]">
                        <span class="material-symbols-outlined text-[20px] text-slate-500">description</span>
                        <span class="text-slate-500 text-[14px] italic">Belum ada file yang dilampirkan</span>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-[13px] font-bold text-slate-500 uppercase tracking-[1px] mb-2">Deskripsi Dokumen</h4>
                    <p class="text-[14px] text-slate-700 leading-relaxed whitespace-pre-wrap">{{ selectedDocument.deskripsi || 'Tidak ada deskripsi yang ditambahkan untuk dokumen ini.' }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-[#dbe6f7] flex justify-end gap-3">
                <button @click="isDetailModalOpen = false" class="px-5 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                    Tutup
                </button>
                <button @click="openDocument(selectedDocument.file_dokumen)" 
                        :disabled="!selectedDocument.file_dokumen"
                        class="px-5 py-2.5 rounded-full font-bold text-[14px] transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="selectedDocument.file_dokumen ? 'bg-[#1356a0] text-white hover:bg-[#103973] shadow-[0_4px_12px_rgba(19,86,160,0.25)] active:scale-95' : 'bg-[#dbe6f7] text-slate-500'">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    Buka File
                </button>
            </div>
        </div>
    </Modal>
</template>