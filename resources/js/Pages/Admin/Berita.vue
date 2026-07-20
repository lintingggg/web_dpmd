<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';
import { useToast } from '@idds/vue';

const props = defineProps({
    berita: Object,
    filters: Object,
    available_tags: Array,
});

const { toast } = useToast();

// Format date helper
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
};

// Search & Filter State
const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const tagFilter = ref(props.filters?.tag || '');

let filterTimeout = null;
watch([search, statusFilter, tagFilter], ([newSearch, newStatus, newTag]) => {
    if (filterTimeout) clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(
            route('admin.berita'),
            { search: newSearch, status: newStatus, tag: newTag },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300);
});

// Modal Form State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const fileInput = ref(null);
const newTagInput = ref('');

const form = useForm({
    judul: '',
    konten: '',
    thumbnail: null,
    tags: [],
    is_published: false,
});

const openModal = (item = null) => {
    isEditing.value = !!item;
    form.clearErrors();
    newTagInput.value = '';
    
    if (item) {
        editingId.value = item.id;
        form.judul = item.judul;
        form.konten = item.konten;
        form.thumbnail = null; // Reset file input
        form.tags = item.tags ? [...item.tags] : [];
        form.is_published = !!item.is_published;
    } else {
        editingId.value = null;
        form.reset();
        form.is_published = true; // Default publish when creating new
    }
    
    if (fileInput.value) fileInput.value.value = '';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.thumbnail = file;
    } else {
        form.thumbnail = null;
    }
};

const addTag = () => {
    const tag = newTagInput.value.trim();
    if (tag && !form.tags.includes(tag)) {
        form.tags.push(tag);
    }
    newTagInput.value = '';
};

const removeTag = (index) => {
    form.tags.splice(index, 1);
};

const submitForm = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.berita.update', editingId.value), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                toast({ state: 'success', title: 'Berhasil', description: 'Berita berhasil diperbarui.' });
            },
            onError: () => {
                toast({ state: 'error', title: 'Gagal', description: 'Silakan periksa form kembali.' });
            }
        });
    } else {
        form.transform((data) => {
            const { _method, ...rest } = data;
            return rest;
        }).post(route('admin.berita.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                toast({ state: 'success', title: 'Berhasil', description: 'Berita berhasil ditambahkan.' });
            },
            onError: () => {
                toast({ state: 'error', title: 'Gagal', description: 'Silakan periksa form kembali.' });
            }
        });
    }
};

// Modal Detail State
const isDetailOpen = ref(false);
const detailItem = ref(null);

const openDetailModal = (item) => {
    detailItem.value = item;
    isDetailOpen.value = true;
};

// Modal Delete State
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const confirmDelete = (item) => {
    itemToDelete.value = item;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(route('admin.berita.destroy', itemToDelete.value.id), {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                itemToDelete.value = null;
                toast({ state: 'success', title: 'Dihapus', description: 'Berita berhasil dihapus.' });
            }
        });
    }
};
</script>

<template>
    <Head title="Berita & Kegiatan" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Berita & Kegiatan
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola publikasi berita, artikel, dan dokumentasi kegiatan dinas.</p>
            </div>
            
            <button @click="openModal()" class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Berita
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden">
            
            <!-- Filter & Search Bar -->
            <div class="p-6 md:p-8 border-b border-[#e3e5e7] flex flex-col xl:flex-row gap-6 justify-between items-start xl:items-center bg-[#f9f9f9]">
                <!-- Search Box -->
                <div class="relative w-full xl:w-80">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari judul berita..." class="w-full bg-white border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                </div>
                
                <!-- Filter Pills -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full xl:w-auto overflow-x-auto pb-2 sm:pb-0">
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] font-bold text-[#9499a3] uppercase tracking-[1px] whitespace-nowrap">Tag:</span>
                        <select v-model="tagFilter" class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c8cbd0]">
                            <option value="">Semua Tag</option>
                            <option v-for="tag in available_tags" :key="tag" :value="tag">{{ tag }}</option>
                        </select>
                    </div>
                    
                    <div class="hidden sm:block w-[1px] h-8 bg-[#c8cbd0]"></div>
                    
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] font-bold text-[#9499a3] uppercase tracking-[1px]">Status:</span>
                        <select v-model="statusFilter" class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c8cbd0]">
                            <option value="">Semua Status</option>
                            <option value="publish">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7]">Judul Berita</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-48">Tags</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-48">Penulis / Tgl</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] w-32">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-[#646a79] uppercase tracking-[2.75px] border-b border-[#e3e5e7] text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#e3e5e7]">
                        <tr v-for="item in berita.data" :key="item.id" class="hover:bg-[#f9f9f9]/50 transition-colors group">
                            
                            <!-- Judul & Thumbnail -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-14 rounded-lg bg-[#f0f1f1] flex items-center justify-center flex-shrink-0 border border-[#e3e5e7] overflow-hidden">
                                        <img v-if="item.thumbnail_url" :src="item.thumbnail_url" alt="Thumbnail" class="w-full h-full object-cover" />
                                        <span v-else class="material-symbols-outlined text-[#9499a3] text-[24px]">image_not_supported</span>
                                    </div>
                                    <p @click="openDetailModal(item)" class="font-bold text-[#0f172a] leading-tight group-hover:text-[#1976d2] transition-colors cursor-pointer line-clamp-2">
                                        {{ item.judul }}
                                    </p>
                                </div>
                            </td>
                            
                            <!-- Tags -->
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5 items-start">
                                    <span v-for="tag in item.tags" :key="tag" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-[0.5px] uppercase bg-[#f0f1f1] text-[#373f50]">
                                        {{ tag }}
                                    </span>
                                </div>
                            </td>

                            <!-- Penulis & Tgl -->
                            <td class="py-4 px-6">
                                <p class="font-bold text-[#0f172a] mb-0.5">{{ item.penulis }}</p>
                                <p class="text-[12px] text-[#646a79]">{{ item.is_published ? formatDate(item.published_at) : 'Draft' }}</p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span v-if="item.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Published
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f0f1f1] text-[#646a79] border border-[#e3e5e7]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#9499a3]"></span> Draft
                                </span>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 md:px-8 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button @click="openModal(item)" class="w-8 h-8 rounded-full hover:bg-[#e3e5e7] text-[#646a79] flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button @click="confirmDelete(item)" class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <tr v-if="berita.data.length === 0">
                            <td colspan="5" class="py-12 text-center text-[#646a79]">
                                <span class="material-symbols-outlined text-[48px] text-[#c8cbd0] mb-2 block">article</span>
                                Belum ada berita yang sesuai dengan filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#e3e5e7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f9f9f9]">
                <p class="text-[13px] font-medium text-[#646a79]">
                    Menampilkan <span class="font-bold text-[#0f172a]">{{ berita.from || 0 }}-{{ berita.to || 0 }}</span> dari <span class="font-bold text-[#0f172a]">{{ berita.total }}</span> berita
                </p>
                
                <div class="flex flex-wrap items-center gap-1" v-if="berita.links && berita.links.length > 3">
                    <Link 
                        v-for="(link, index) in berita.links" 
                        :key="index"
                        :href="link.url || '#'"
                        class="min-w-[32px] px-3 py-1.5 flex items-center justify-center rounded-lg font-medium text-[13px] transition-colors whitespace-nowrap"
                        :class="[
                            link.active ? 'bg-[#0f172a] text-white font-bold' : 'text-[#646a79] hover:bg-[#e3e5e7]',
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
    <Modal :show="isModalOpen" @close="closeModal" maxWidth="2xl">
        <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-[#e3e5e7]">
                <div>
                    <h3 class="text-[24px] font-bold text-[#0f172a] leading-tight">{{ isEditing ? 'Edit Berita' : 'Tambah Berita Baru' }}</h3>
                    <p class="text-[14px] text-[#646a79] mt-1">Isi formulir di bawah ini dengan detail berita.</p>
                </div>
                <button @click="closeModal" class="text-[#9499a3] hover:text-[#0f172a] transition-colors">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Judul Berita <span class="text-red-500">*</span></label>
                    <input v-model="form.judul" type="text" required class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Masukkan judul..." />
                    <div v-if="form.errors.judul" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.judul }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thumbnail -->
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Thumbnail (Opsional)</label>
                        <div class="relative w-full border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-4 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors min-h-[120px]">
                            <span class="material-symbols-outlined text-[24px] text-[#646a79] mb-1">image</span>
                            <p class="text-[12px] font-medium text-[#373f50] mb-1">
                                <span v-if="form.thumbnail">{{ form.thumbnail.name }}</span>
                                <span v-else>Pilih gambar (Maks 5MB)</span>
                            </p>
                            <input type="file" ref="fileInput" @change="handleFileUpload" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </div>
                        <div v-if="form.errors.thumbnail" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.thumbnail }}</div>
                        <div v-if="isEditing" class="mt-2 text-[12px] font-semibold text-[#1976d2] flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">info</span>
                            <span>Biarkan kosong jika tidak ingin mengubah thumbnail.</span>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Tags</label>
                        <div class="w-full bg-[#f9f9f9] border border-[#e3e5e7] rounded-lg px-3 py-2 flex flex-wrap gap-2 focus-within:ring-1 focus-within:ring-[#0f172a] focus-within:border-[#0f172a]">
                            <span v-for="(tag, index) in form.tags" :key="index" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-[#e3e5e7] text-[#0f172a] text-[12px] font-semibold">
                                {{ tag }}
                                <button type="button" @click="removeTag(index)" class="hover:text-red-500 flex items-center">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </span>
                            <input 
                                v-model="newTagInput" 
                                @keydown.enter.prevent="addTag"
                                type="text" 
                                placeholder="Ketik tag & Enter" 
                                class="flex-1 bg-transparent border-none p-1 text-[13px] focus:ring-0 min-w-[100px]"
                            />
                        </div>
                        <p class="text-xs text-[#9499a3] mt-1">Tekan Enter untuk menambahkan tag.</p>
                        <div v-if="form.errors.tags" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tags }}</div>
                    </div>
                </div>

                <!-- Konten Rich Text -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Konten Berita <span class="text-red-500">*</span></label>
                    <TipTapEditor v-model="form.konten" />
                    <div v-if="form.errors.konten" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.konten }}</div>
                </div>

                <!-- Publish Toggle -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group w-max">
                        <div class="relative flex items-center">
                            <input type="checkbox" v-model="form.is_published" class="peer sr-only">
                            <div class="w-11 h-6 bg-[#c8cbd0] rounded-full peer-checked:bg-[#0f172a] transition-colors duration-200 ease-in-out"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform duration-200 ease-in-out shadow-sm"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[14px] font-bold text-[#0f172a] leading-none mb-1">Publikasikan Berita</span>
                            <span class="text-[12px] text-[#646a79] leading-none">Berita akan tampil di website publik</span>
                        </div>
                    </label>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-[#e3e5e7] flex justify-end gap-3">
                    <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-full bg-[#0f172a] text-white font-bold text-[14px] hover:bg-[#222a3d] transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Berita' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>

    <!-- Modal Detail Berita -->
    <Modal :show="isDetailOpen" @close="isDetailOpen = false" maxWidth="2xl">
        <div class="relative bg-white overflow-hidden" v-if="detailItem">
            <!-- Header Image -->
            <div class="w-full h-64 bg-[#f0f1f1] relative">
                <img v-if="detailItem.thumbnail_url" :src="detailItem.thumbnail_url" alt="Cover" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-[#9499a3]">
                    <span class="material-symbols-outlined text-[48px] mb-2">image_not_supported</span>
                    <span class="text-[14px] font-medium">Tidak ada thumbnail</span>
                </div>
                
                <!-- Close button floating -->
                <button @click="isDetailOpen = false" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition-colors backdrop-blur-sm">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <div class="p-6 md:p-8">
                <!-- Meta tags -->
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span v-if="detailItem.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Published
                    </span>
                    <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#f0f1f1] text-[#646a79] border border-[#e3e5e7]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9499a3]"></span> Draft
                    </span>
                    
                    <div class="w-1 h-1 rounded-full bg-[#c8cbd0]"></div>
                    
                    <span v-for="tag in detailItem.tags" :key="tag" class="text-[12px] font-bold uppercase tracking-[0.5px] text-[#1976d2]">
                        #{{ tag }}
                    </span>
                </div>
                
                <!-- Title -->
                <h2 class="text-[28px] md:text-[32px] font-bold text-[#0f172a] leading-tight mb-4">
                    {{ detailItem.judul }}
                </h2>
                
                <!-- Author & Date -->
                <div class="flex items-center gap-4 text-[13px] text-[#646a79] mb-8 pb-6 border-b border-[#e3e5e7]">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">edit_document</span>
                        <span class="font-bold text-[#373f50]">{{ detailItem.penulis }}</span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-[#c8cbd0]"></div>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        <span>{{ detailItem.is_published ? formatDate(detailItem.published_at) : 'Belum dipublikasikan' }}</span>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="prose prose-sm md:prose-base prose-slate max-w-none prose-headings:font-bold prose-a:text-[#1976d2] prose-img:rounded-xl" v-html="detailItem.konten"></div>
                
                <div class="mt-10 pt-6 border-t border-[#e3e5e7] flex justify-end">
                    <button @click="isDetailOpen = false" class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </Modal>

    <!-- Delete Confirmation Modal -->
    <Modal :show="isDeleteModalOpen" @close="isDeleteModalOpen = false" maxWidth="sm">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-[#ffebee] flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px] text-[#d32f2f]">warning</span>
            </div>
            <h3 class="text-[20px] font-bold text-[#0f172a] mb-2">Hapus Berita?</h3>
            <p class="text-[14px] text-[#646a79] mb-6">
                Apakah Anda yakin ingin menghapus berita <span class="font-bold text-[#0f172a]">"{{ itemToDelete?.judul }}"</span>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center gap-3">
                <button @click="isDeleteModalOpen = false" class="px-5 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                    Batal
                </button>
                <button @click="executeDelete" class="px-5 py-2.5 rounded-full bg-[#d32f2f] text-white font-bold text-[14px] hover:bg-[#b71c1c] shadow-[0_4px_12px_rgba(211,47,47,0.2)] transition-all active:scale-95">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </Modal>

</template>
