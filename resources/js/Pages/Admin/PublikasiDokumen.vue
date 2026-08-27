<script setup>
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from '@idds/vue';

// Import Shared Components
import AdminPageHeader from '@/Components/Admin/AdminPageHeader.vue';
import AdminPagination from '@/Components/Admin/AdminPagination.vue';
import AdminConfirmDelete from '@/Components/Admin/AdminConfirmDelete.vue';
import FormToggle from '@/Components/Form/FormToggle.vue';
import FormFileInput from '@/Components/Form/FormFileInput.vue';

// Import Composables
import { useDebouncedFilter } from '@/Composables/useDebouncedFilter';

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

// Use Composable for Debounced Routing
useDebouncedFilter(
    route('admin.publikasi-dokumen'),
    { 
        kategori: activeTab, 
        search,
        tahun: tahunFilter,
        status: statusFilter
    },
    { only: ['dokumen', 'filters'] }
);

// Modal State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const fileInputRef = ref(null);
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
        form.is_published = !!item.is_published;
        form.file_dokumen = null; // Tidak perlu assign file object lama
    } else {
        isEditing.value = false;
        editingId.value = null;
        form.reset();
        form.kategori = activeTab.value;
        form.tahun = currentYear;
    }
    
    if (fileInputRef.value) {
        fileInputRef.value.clearFile();
    }
    fileSizeError.value = false;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const handleFileUpload = (file) => {
    fileSizeError.value = false;
    if (file) {
        if (file.size > 5242880) { // 5MB limit
            fileSizeError.value = true;
            form.file_dokumen = null;
        } else {
            form.file_dokumen = file;
        }
    } else {
        form.file_dokumen = null;
    }
};

const submitForm = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.publikasi-dokumen.update', editingId.value), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                toast.success('Dokumen berhasil diperbarui!');
            }
        });
    } else {
        form.transform((data) => {
            const { _method, ...rest } = data;
            return rest;
        }).post(route('admin.publikasi-dokumen.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                toast.success('Dokumen berhasil ditambahkan!');
            }
        });
    }
};

// Modal Detail State
const isDetailModalOpen = ref(false);
const selectedDocument = ref(null);

const viewDetails = (item) => {
    selectedDocument.value = item;
    isDetailModalOpen.value = true;
};

const openDocument = (path) => {
    if (path) {
        window.open('/storage/' + path, '_blank');
    }
};

// Modal Konfirmasi Hapus
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const confirmDelete = (item) => {
    itemToDelete.value = item;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(route('admin.publikasi-dokumen.destroy', itemToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                itemToDelete.value = null;
                toast.success('Dokumen berhasil dihapus!');
            }
        });
    }
};
</script>

<template>
    <Head title="Kelola Dokumen Publikasi - Admin DPMD" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <AdminPageHeader 
            title="Publikasi Dokumen" 
            description="Kelola berkas publikasi, dokumen perencanaan, dan regulasi DPMD."
        >
            <button @click="openModal()" class="bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_16px_rgba(19,86,160,0.3)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Dokumen
            </button>
        </AdminPageHeader>

        <!-- Tabs Kategori -->
        <div class="flex border-b border-[#dbe6f7] mb-6 overflow-x-auto whitespace-nowrap scrollbar-none -mx-4 px-4 sm:mx-0 sm:px-0">
            <button 
                v-for="(label, key) in categories" 
                :key="key"
                @click="activeTab = key"
                class="py-3 px-6 text-[14px] font-bold border-b-2 transition-all"
                :class="activeTab === key ? 'border-[#1356a0] text-[#1356a0]' : 'border-transparent text-slate-500 hover:text-slate-900'"
            >
                {{ label }}
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            <!-- Filter & Search Bar -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] flex flex-col xl:flex-row gap-6 justify-between items-start xl:items-center bg-[#f5f8fd]">
                <!-- Search Box -->
                <div class="relative w-full xl:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari judul dokumen..." class="w-full bg-white border border-[#dbe6f7] text-slate-900 text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                </div>
                
                <!-- Filter Section -->
                <div class="flex flex-wrap items-center gap-4 w-full xl:w-auto">
                    <!-- Filter Tahun -->
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Tahun:</span>
                        <select v-model="tahunFilter" class="bg-white border border-[#dbe6f7] text-slate-900 text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c7dafa]">
                            <option value="">Semua Tahun</option>
                            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>

                    <div class="hidden sm:block w-[1px] h-8 bg-[#c7dafa]"></div>

                    <!-- Filter Status -->
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Status:</span>
                        <select v-model="statusFilter" class="bg-white border border-[#dbe6f7] text-slate-900 text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c7dafa]">
                            <option value="">Semua Status</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7]">Judul Dokumen</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] w-32">Tahun</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] w-36">Lampiran</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] w-32">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#dbe6f7]">
                        <tr v-for="item in dokumen.data" :key="item.id" class="hover:bg-[#f5f8fd]/50 transition-colors group">
                            <!-- Judul & Deskripsi -->
                            <td class="py-4 px-6 md:px-8">
                                <p @click="viewDetails(item)" class="font-bold text-slate-900 leading-tight hover:text-slate-700 transition-colors cursor-pointer mb-1">{{ item.judul }}</p>
                                <p class="text-[12px] text-slate-500 line-clamp-1" v-if="item.deskripsi">{{ item.deskripsi }}</p>
                            </td>

                            <!-- Tahun -->
                            <td class="py-4 px-6 text-slate-700">
                                {{ item.tahun }}
                            </td>

                            <!-- Lampiran File -->
                            <td class="py-4 px-6">
                                <button v-if="item.file_dokumen" @click="openDocument(item.file_dokumen)" class="inline-flex items-center gap-1 text-[#1356a0] hover:text-blue-900 font-bold transition-all text-xs">
                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                    Lihat File
                                </button>
                                <span v-else class="text-slate-400 italic text-xs">Tidak ada file</span>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span v-if="item.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Publish
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f1f3f4] text-slate-500 border border-[#dbe6f7]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#7488a8]"></span> Draft
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button @click="openModal(item)" class="w-8 h-8 rounded-full hover:bg-[#dbe6f7] text-slate-500 flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button @click="confirmDelete(item)" class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="dokumen.data.length === 0">
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-[48px] text-[#c7dafa] mb-2 block">folder_open</span>
                                Belum ada dokumen publikasi yang ditambahkan pada kategori ini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <AdminPagination 
                :links="dokumen.links" 
                :from="dokumen.from" 
                :to="dokumen.to" 
                :total="dokumen.total" 
                label="dokumen" 
            />
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

                <!-- File Dokumen Upload -->
                <div>
                    <label class="block text-[14px] font-bold text-slate-700 mb-2">File Dokumen (PDF/Word/Excel)</label>
                    <FormFileInput 
                        ref="fileInputRef"
                        :modelValue="form.file_dokumen"
                        @update:modelValue="handleFileUpload"
                        label="Pilih file untuk diunggah"
                        accept=".pdf,.doc,.docx,.xls,.xlsx"
                        max-size-text="Maksimal 5MB"
                        icon="upload_file"
                        :error="form.errors.file_dokumen"
                        :info-text="isEditing ? 'Biarkan kosong jika tidak ingin mengubah dokumen saat ini.' : ''"
                    />
                    <div v-if="fileSizeError" class="text-red-500 text-xs mt-1 font-semibold">Ukuran file maksimal 5MB. File yang Anda pilih terlalu besar.</div>
                </div>

                <div>
                    <label class="block text-[14px] font-bold text-slate-700 mb-2">Deskripsi Singkat</label>
                    <textarea v-model="form.deskripsi" rows="3" placeholder="Opsional..." class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0] resize-none"></textarea>
                    <div v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.deskripsi }}</div>
                </div>

                <!-- Publish Toggle -->
                <FormToggle 
                    v-model="form.is_published"
                    title="Tampilkan ke Publik"
                />
                
                <div class="pt-4 flex justify-end gap-3 border-t border-[#dbe6f7]">
                    <button type="button" @click="closeModal" class="px-5 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing || fileSizeError" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold text-[14px] shadow-[0_4px_16px_rgba(19,86,160,0.3)] transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        {{ isEditing ? 'Simpan Perubahan' : 'Tambah Dokumen' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>

    <!-- Delete Confirmation Modal -->
    <AdminConfirmDelete 
        :show="isDeleteModalOpen" 
        title="Hapus Dokumen?"
        :message="`Anda yakin ingin menghapus <span class='font-bold text-slate-900'>\&quot;${itemToDelete?.judul}\&quot;</span>? Tindakan ini akan menghapus file fisik dokumen tersebut.`"
        @close="isDeleteModalOpen = false"
        @confirm="executeDelete"
    />

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