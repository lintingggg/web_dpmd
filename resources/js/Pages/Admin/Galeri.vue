<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    galeri: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const detailItem = ref(null);
const fileInput = ref(null);

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const form = useForm({
    judul: '',
    deskripsi: '',
    tanggal_kegiatan: '',
    foto: null,
    is_published: false,
});

let filterTimeout = null;
watch([search, statusFilter], ([newSearch, newStatus]) => {
    if (filterTimeout) clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('admin.galeri'), {
            search: newSearch,
            status: newStatus,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 300);
});

const openModal = (item = null) => {
    isEditing.value = !!item;
    form.clearErrors();
    
    if (item) {
        editingId.value = item.id;
        detailItem.value = item;
        form.judul = item.judul;
        form.deskripsi = item.deskripsi || '';
        form.tanggal_kegiatan = item.tanggal_kegiatan || '';
        form.foto = null; // Reset foto input
        form.is_published = !!item.is_published;
    } else {
        editingId.value = null;
        detailItem.value = null;
        form.reset();
        form.is_published = false;
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
        form.foto = file;
    } else {
        form.foto = null;
    }
};

const submitForm = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put'
        })).post(route('admin.galeri.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.galeri.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (item) => {
    detailItem.value = item;
    isDeleteModalOpen.value = true;
};

const deleteGaleri = () => {
    router.delete(route('admin.galeri.destroy', detailItem.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        }
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Galeri Kegiatan" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-[#0f172a] mb-1">
                    Galeri Kegiatan
                </h2>
                <p class="text-[14px] font-medium text-[#646a79]">Kelola dokumentasi kegiatan DPMD Bangkalan.</p>
            </div>
            
            <button @click="openModal()" class="bg-[#0f172a] hover:bg-[#222a3d] text-white font-bold py-2.5 px-6 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                Buat Entri Baru
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#9499a3] text-[20px]">search</span>
                <input v-model="search" type="text" placeholder="Cari judul kegiatan..." class="w-full bg-white border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-full pl-11 pr-4 py-2.5 shadow-[0_2px_8px_rgba(15,23,42,0.04)] focus:ring-[#0f172a] focus:border-[#0f172a]" />
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <select v-model="statusFilter" class="bg-white border border-[#e3e5e7] text-[#0f172a] text-[13px] font-bold rounded-full px-4 py-2.5 shadow-[0_2px_8px_rgba(15,23,42,0.04)] focus:ring-0 focus:border-[#c8cbd0]">
                    <option value="">Semua Status</option>
                    <option value="publish">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <!-- Galeri Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            
            <div v-for="item in galeri.data" :key="item.id" class="bg-white rounded-[20px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all flex flex-col">
                <!-- Cover Image -->
                <div class="relative aspect-[4/3] bg-[#f0f1f1] overflow-hidden">
                    <img v-if="item.foto_url" :src="item.foto_url" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div v-else class="w-full h-full flex flex-col items-center justify-center text-[#9499a3]">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">image_not_supported</span>
                        <span class="text-[12px] font-bold">Belum ada foto</span>
                    </div>
                    
                    <!-- Overlay Actions -->
                    <div class="absolute inset-0 bg-[#0f172a]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                        <button @click="openModal(item)" class="w-10 h-10 rounded-full bg-white text-[#0f172a] hover:bg-[#e3e5e7] flex items-center justify-center transition-colors shadow-lg" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button @click="confirmDelete(item)" class="w-10 h-10 rounded-full bg-[#ba1a1a] text-white hover:bg-[#93000a] flex items-center justify-center transition-colors shadow-lg" title="Hapus">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-[#646a79] tracking-[0.5px] uppercase">{{ formatDate(item.tanggal_kegiatan) }}</span>
                            <span v-if="item.is_published" class="w-2 h-2 rounded-full bg-[#137333]" title="Published"></span>
                            <span v-else class="w-2 h-2 rounded-full bg-[#9499a3]" title="Draft"></span>
                        </div>
                        <h3 class="text-[15px] font-bold text-[#0f172a] leading-snug line-clamp-2 transition-colors">
                            {{ item.judul }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Add New Card (Ghost Card) -->
            <div @click="openModal()" class="border-2 border-dashed border-[#c8cbd0] rounded-[20px] bg-[#f9f9f9]/50 hover:bg-[#f9f9f9] transition-colors cursor-pointer flex flex-col items-center justify-center min-h-[250px] group">
                <div class="w-16 h-16 rounded-full bg-white border border-[#e3e5e7] shadow-sm flex items-center justify-center text-[#0f172a] mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">add</span>
                </div>
                <h3 class="text-[15px] font-bold text-[#0f172a] mb-1">Buat Entri Baru</h3>
                <p class="text-[13px] text-[#646a79]">Tambah koleksi dokumentasi</p>
            </div>

        </div>

        <!-- Pagination -->
        <div v-if="galeri.data.length > 0" class="bg-white rounded-xl shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden mb-8">
            <div class="p-6 md:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f9f9f9]">
                <p class="text-[13px] font-medium text-[#646a79]">
                    Menampilkan <span class="font-bold text-[#0f172a]">{{ galeri.from || 0 }}-{{ galeri.to || 0 }}</span> dari <span class="font-bold text-[#0f172a]">{{ galeri.total }}</span> entri
                </p>
                
                <div class="flex flex-wrap items-center gap-1" v-if="galeri.links && galeri.links.length > 3">
                    <Link 
                        v-for="(link, index) in galeri.links" 
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
                    <h3 class="text-[24px] font-bold text-[#0f172a] leading-tight">{{ isEditing ? 'Edit Galeri' : 'Tambah Galeri Baru' }}</h3>
                    <p class="text-[14px] text-[#646a79] mt-1">Isi formulir di bawah ini dengan detail kegiatan.</p>
                </div>
                <button @click="closeModal" class="text-[#9499a3] hover:text-[#0f172a] transition-colors">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input v-model="form.judul" type="text" required class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Masukkan judul..." />
                    <div v-if="form.errors.judul" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.judul }}</div>
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Foto Dokumentasi</label>
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <div v-if="isEditing && detailItem.foto_url && !form.foto" class="w-32 h-32 rounded-lg bg-[#f0f1f1] border border-[#e3e5e7] overflow-hidden shrink-0 relative">
                            <img :src="detailItem.foto_url" alt="Current Photo" class="w-full h-full object-cover" />
                            <div class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] font-bold text-center py-1">Tersimpan</div>
                        </div>

                        <div class="relative w-full flex-1 border-2 border-dashed border-[#c8cbd0] bg-[#f9f9f9] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-[#f0f1f1] transition-colors min-h-[128px]">
                            <span class="material-symbols-outlined text-[32px] text-[#646a79] mb-2">image</span>
                            <p class="text-[13px] font-medium text-[#373f50] mb-1">
                                <span v-if="form.foto">{{ form.foto.name }}</span>
                                <span v-else>Pilih file gambar baru (Maks 5MB)</span>
                            </p>
                            <input type="file" ref="fileInput" @change="handleFileUpload" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </div>
                    </div>
                    <div v-if="form.errors.foto" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.foto }}</div>
                    <div v-if="isEditing" class="mt-2 text-[12px] font-medium text-[#646a79]">
                        Biarkan kosong jika tidak ingin mengubah foto saat ini.
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Kegiatan -->
                    <div>
                        <label class="block text-[14px] font-bold text-[#373f50] mb-2">Tanggal Kegiatan</label>
                        <input v-model="form.tanggal_kegiatan" type="date" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" />
                        <div v-if="form.errors.tanggal_kegiatan" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tanggal_kegiatan }}</div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Deskripsi Singkat</label>
                    <textarea v-model="form.deskripsi" rows="3" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Tuliskan deskripsi singkat..."></textarea>
                    <div v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.deskripsi }}</div>
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
                            <span class="text-[14px] font-bold text-[#0f172a] leading-none mb-1">Publikasikan</span>
                            <span class="text-[12px] text-[#646a79] leading-none">Galeri akan tampil di website publik</span>
                        </div>
                    </label>
                    <p class="text-xs text-orange-600 mt-2 font-medium flex items-center gap-1" v-if="form.is_published">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        Foto wajib diisi sebelum dapat dipublikasikan.
                    </p>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-[#e3e5e7] flex justify-end gap-3">
                    <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-full bg-[#0f172a] text-white font-bold text-[14px] hover:bg-[#222a3d] transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_4px_12px_rgba(15,23,42,0.12)]">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Galeri' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>

    <!-- Modal Konfirmasi Hapus -->
    <Modal :show="isDeleteModalOpen" @close="isDeleteModalOpen = false" maxWidth="md">
        <div class="p-6 md:p-8 text-center">
            <div class="w-16 h-16 rounded-full bg-[#fce8e8] text-[#ba1a1a] flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px]">delete_forever</span>
            </div>
            
            <h3 class="text-[24px] font-bold text-[#0f172a] leading-tight mb-3">Hapus Galeri?</h3>
            <p class="text-[15px] text-[#646a79] mb-8">
                Tindakan ini akan menghapus galeri <span class="font-bold text-[#0f172a]">"{{ detailItem?.judul }}"</span> secara permanen beserta file foto fisiknya. Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button @click="isDeleteModalOpen = false" class="px-6 py-3 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                    Batal
                </button>
                <button @click="deleteGaleri" class="px-6 py-3 rounded-full bg-[#ba1a1a] text-white font-bold text-[14px] hover:bg-[#93000a] transition-all active:scale-95 shadow-[0_4px_12px_rgba(186,26,26,0.2)]">
                    Ya, Hapus Permanen
                </button>
            </div>
        </div>
    </Modal>
</template>
