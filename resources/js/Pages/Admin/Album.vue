<script setup>
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

// Import Shared Components
import AdminPageHeader from '@/Components/Admin/AdminPageHeader.vue';
import AdminPagination from '@/Components/Admin/AdminPagination.vue';
import AdminConfirmDelete from '@/Components/Admin/AdminConfirmDelete.vue';
import FormToggle from '@/Components/Form/FormToggle.vue';
import FormFileInput from '@/Components/Form/FormFileInput.vue';

// Import Composables
import { useDebouncedFilter } from '@/Composables/useDebouncedFilter';

const props = defineProps({
    albums: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const detailItem = ref(null);
const fileInputRef = ref(null);

const search = ref(props.filters.search || '');

const form = useForm({
    nama: '',
    deskripsi: '',
    cover: null,
    is_published: false,
});

// Use Composable for Debounced Routing
useDebouncedFilter(
    route('admin.album'),
    { search },
    { only: ['albums', 'filters'] }
);

const openModal = (item = null) => {
    isEditing.value = !!item;
    form.clearErrors();
    
    if (item) {
        editingId.value = item.id;
        detailItem.value = item;
        form.nama = item.nama;
        form.deskripsi = item.deskripsi || '';
        form.cover = null;
        form.is_published = !!item.is_published;
    } else {
        editingId.value = null;
        detailItem.value = null;
        form.reset();
        form.is_published = false;
    }
    
    if (fileInputRef.value) fileInputRef.value.clearFile();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const handleFileUpload = (file) => {
    form.cover = file;
};

const submitForm = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put'
        })).post(route('admin.album.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.album.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (item) => {
    detailItem.value = item;
    isDeleteModalOpen.value = true;
};

const deleteAlbum = () => {
    router.delete(route('admin.album.destroy', detailItem.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        }
    });
};

const goToGaleri = (albumId) => {
    router.visit(route('admin.galeri', albumId));
};
</script>

<template>
    <Head title="Album Kegiatan" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <AdminPageHeader 
            title="Album Kegiatan" 
            description="Kelola album untuk dokumentasi kegiatan DPMD Bangkalan."
        >
            <button @click="openModal()" class="bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_16px_rgba(19,86,160,0.3)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Buat Album Baru
            </button>
        </AdminPageHeader>

        <!-- Main Content Wrapper -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            <!-- Toolbar -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-[#f5f8fd]">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari nama album..." class="w-full bg-white border border-[#dbe6f7] text-slate-900 text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                </div>
            </div>

            <!-- Album Grid Content -->
            <div class="p-6 md:p-8 bg-white">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
                    <div v-for="item in albums.data" :key="item.id" class="bg-white rounded-[20px] shadow-[0_4px_20px_rgba(15,23,42,0.04)] border border-[#e3e5e7] overflow-hidden group hover:shadow-[0_8px_30px_rgba(15,23,42,0.08)] transition-all flex flex-col">
                        <!-- Cover Image -->
                        <div class="relative aspect-[4/3] bg-[#f0f1f1] overflow-hidden cursor-pointer" @click="goToGaleri(item.id)">
                            <img v-if="item.cover" :src="`/storage/${item.cover}`" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div v-else class="w-full h-full flex flex-col items-center justify-center text-[#9499a3]">
                                <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">folder</span>
                                <span class="text-[12px] font-bold">Belum ada cover</span>
                            </div>
                            
                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-[#0f172a]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                                <button @click.stop="goToGaleri(item.id)" class="w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 flex items-center justify-center transition-colors shadow-lg" title="Buka Album">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                <button @click.stop="openModal(item)" class="w-10 h-10 rounded-full bg-white text-[#0f172a] hover:bg-[#e3e5e7] flex items-center justify-center transition-colors shadow-lg" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button @click.stop="confirmDelete(item)" class="w-10 h-10 rounded-full bg-[#ba1a1a] text-white hover:bg-[#93000a] flex items-center justify-center transition-colors shadow-lg" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span v-if="item.is_published" class="w-2 h-2 rounded-full bg-[#137333]" title="Published"></span>
                                    <span v-else class="w-2 h-2 rounded-full bg-[#9499a3]" title="Draft"></span>
                                </div>
                                <h3 class="text-[15px] font-bold text-[#0f172a] leading-snug line-clamp-2 transition-colors cursor-pointer hover:text-blue-600" @click="goToGaleri(item.id)">
                                    {{ item.nama }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Card (Ghost Card) -->
                    <div @click="openModal()" class="border-2 border-dashed border-[#dbe6f7] rounded-[20px] bg-[#f5f8fd]/50 hover:bg-[#f5f8fd] transition-colors cursor-pointer flex flex-col items-center justify-center min-h-[250px] group">
                        <div class="w-16 h-16 rounded-full bg-white border border-[#dbe6f7] shadow-sm flex items-center justify-center text-[#1356a0] mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[28px]">add</span>
                        </div>
                        <h3 class="text-[15px] font-bold text-slate-900 mb-1">Buat Album Baru</h3>
                        <p class="text-[13px] text-slate-500">Kelompokkan foto & video</p>
                    </div>

                </div>
            </div>

            <!-- Footer / Pagination -->
            <AdminPagination 
                :links="albums.links" 
                :from="albums.from" 
                :to="albums.to" 
                :total="albums.total" 
                label="album" 
            />
        </div>
    </AuthenticatedLayout>

    <!-- Modal Form Tambah/Edit -->
    <Modal :show="isModalOpen" @close="closeModal" maxWidth="2xl">
        <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-[#e3e5e7]">
                <div>
                    <h3 class="text-[24px] font-bold text-[#0f172a] leading-tight">{{ isEditing ? 'Edit Album' : 'Tambah Album Baru' }}</h3>
                </div>
                <button @click="closeModal" class="text-[#9499a3] hover:text-[#0f172a] transition-colors">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Nama Album <span class="text-red-500">*</span></label>
                    <input v-model="form.nama" type="text" required class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Masukkan nama album..." />
                    <div v-if="form.errors.nama" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.nama }}</div>
                </div>

                <!-- Foto / Cover -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Cover Album</label>
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <div v-if="isEditing && detailItem.cover && !form.cover" class="w-32 h-32 rounded-lg bg-[#f0f1f1] border border-[#e3e5e7] overflow-hidden shrink-0 relative">
                            <img :src="`/storage/${detailItem.cover}`" alt="Current Photo" class="w-full h-full object-cover" />
                            <div class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] font-bold text-center py-1">Tersimpan</div>
                        </div>

                        <FormFileInput 
                            ref="fileInputRef"
                            v-model="form.cover"
                            @update:modelValue="handleFileUpload"
                            label="Pilih file cover"
                            maxSizeText="Maks 5MB"
                            :error="form.errors.cover"
                            class="flex-grow w-full"
                        />
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-[14px] font-bold text-[#373f50] mb-2">Deskripsi Singkat</label>
                    <textarea v-model="form.deskripsi" rows="3" class="w-full bg-[#f9f9f9] border border-[#e3e5e7] text-[#0f172a] text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#0f172a] focus:border-[#0f172a]" placeholder="Tuliskan deskripsi singkat..."></textarea>
                    <div v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.deskripsi }}</div>
                </div>

                <!-- Publish Toggle -->
                <FormToggle 
                    v-model="form.is_published"
                    title="Publikasikan"
                    description="Album akan tampil di website publik"
                />

                <!-- Actions -->
                <div class="pt-6 border-t border-[#e3e5e7] flex justify-end gap-3">
                    <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-full border border-[#c8cbd0] bg-white text-[#373f50] font-bold text-[14px] hover:bg-[#f0f1f1] transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold text-[14px] shadow-[0_4px_16px_rgba(19,86,160,0.3)] transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Album' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>

    <!-- Delete Confirmation Modal -->
    <AdminConfirmDelete 
        :show="isDeleteModalOpen" 
        title="Hapus Album?"
        :message="`Tindakan ini akan menghapus album <span class='font-bold text-slate-900'>\&quot;${detailItem?.nama}\&quot;</span> beserta semua foto dan video di dalamnya secara permanen. Tindakan ini tidak dapat dibatalkan.`"
        @close="isDeleteModalOpen = false"
        @confirm="deleteAlbum"
    />
</template>
