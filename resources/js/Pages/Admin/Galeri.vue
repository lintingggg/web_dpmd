<script setup>
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { formatDate } from '@/Utils/formatDate';

// Import Shared Components
import AdminPageHeader from '@/Components/Admin/AdminPageHeader.vue';
import AdminPagination from '@/Components/Admin/AdminPagination.vue';
import AdminConfirmDelete from '@/Components/Admin/AdminConfirmDelete.vue';
import FormToggle from '@/Components/Form/FormToggle.vue';
import FormFileInput from '@/Components/Form/FormFileInput.vue';

// Import Composables
import { useDebouncedFilter } from '@/Composables/useDebouncedFilter';

const props = defineProps({
    album: Object,
    galeri: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const detailItem = ref(null);
const fileInputRef = ref(null);

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const form = useForm({
    judul: '',
    deskripsi: '',
    tanggal_kegiatan: '',
    tipe: 'foto',
    foto: null,
    is_published: false,
});

// Use Composable for Debounced Routing
useDebouncedFilter(
    route('admin.galeri', props.album.id),
    { search, status: statusFilter },
    { only: ['galeri', 'filters'] }
);

const openModal = (item = null) => {
    isEditing.value = !!item;
    form.clearErrors();

    if (item) {
        editingId.value = item.id;
        detailItem.value = item;
        form.judul = item.judul;
        form.deskripsi = item.deskripsi || '';
        form.tanggal_kegiatan = item.tanggal_kegiatan || '';
        form.tipe = item.tipe || 'foto';
        form.foto = item.tipe === 'video' ? item.foto : null; // Set foto to URL if video, else reset
        form.is_published = !!item.is_published;
    } else {
        editingId.value = null;
        detailItem.value = null;
        form.reset();
        form.tipe = 'foto';
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

const youtubeError = ref(false);

const handleFileUpload = (file) => {
    form.foto = file;
};

const submitForm = () => {
    youtubeError.value = false;
    if (form.tipe === 'video' && form.foto) {
        const isUnchangedUrl = isEditing.value && form.foto === detailItem.value?.foto;
        if (!form.foto.includes('<iframe') && !isUnchangedUrl) {
            youtubeError.value = true;
            return;
        }
    }

    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put'
        })).post(route('admin.galeri.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.transform((data) => data).post(route('admin.galeri.store', props.album.id), {
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
    if (detailItem.value) {
        router.delete(route('admin.galeri.destroy', detailItem.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                detailItem.value = null;
            }
        });
    }
};
</script>

<template>
    <Head :title="`Galeri Album ${album.nama} - Admin DPMD`" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <AdminPageHeader 
            :title="`Galeri: ${album.nama}`" 
            :description="`Kelola dokumentasi foto dan video untuk album ${album.nama}.`"
        >
            <div class="flex items-center gap-2">
                <Link :href="route('admin.album')" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold text-[14px] hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
                    Kembali
                </Link>
                <button @click="openModal()" class="bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_16px_rgba(19,86,160,0.3)]">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Media
                </button>
            </div>
        </AdminPageHeader>

        <!-- Main Content -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            <!-- Toolbar -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-[#f5f8fd]">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari judul media..." class="w-full bg-white border border-[#dbe6f7] text-slate-900 text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                </div>
                
                <!-- Filter Section -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 whitespace-nowrap">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Status:</span>
                        <select v-model="statusFilter" class="bg-white border border-[#dbe6f7] text-slate-900 text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c7dafa]">
                            <option value="">Semua Status</option>
                            <option value="publish">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#f5f8fd]">
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] w-64">Media</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7]">Judul & Deskripsi</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] w-40">Tanggal</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] w-32">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#dbe6f7]">
                        <tr v-for="item in galeri.data" :key="item.id" class="hover:bg-[#f5f8fd]/50 transition-colors group">
                            <!-- Preview Media -->
                            <td class="py-4 px-6 md:px-8">
                                <div class="w-48 aspect-video rounded-xl bg-slate-100 border border-slate-200 overflow-hidden relative" v-if="item.tipe === 'foto'">
                                    <img :src="item.foto_url" :alt="item.judul" class="w-full h-full object-cover" />
                                </div>
                                <div class="w-48 aspect-video rounded-xl bg-slate-900 border border-slate-800 overflow-hidden relative flex items-center justify-center" v-else>
                                    <iframe :src="item.foto" class="w-full h-full border-0 pointer-events-none" allowfullscreen></iframe>
                                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                        <i class="fa-solid fa-play text-white text-2xl drop-shadow-md"></i>
                                    </div>
                                </div>
                            </td>

                            <!-- Judul & Deskripsi -->
                            <td class="py-4 px-6">
                                <p class="font-bold text-slate-900 leading-snug mb-1">{{ item.judul }}</p>
                                <p class="text-[12px] text-slate-500 line-clamp-2" v-if="item.deskripsi">{{ item.deskripsi }}</p>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-4 px-6 text-slate-700 whitespace-nowrap">
                                {{ item.tanggal_kegiatan ? formatDate(item.tanggal_kegiatan) : '-' }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span v-if="item.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Published
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#eaf1fb] text-slate-500 border border-[#dbe6f7]">
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

                        <tr v-if="galeri.data.length === 0">
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-[48px] text-[#c7dafa] mb-2 block">image</span>
                                Album ini belum memiliki media.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <AdminPagination 
                :links="galeri.links" 
                :from="galeri.from" 
                :to="galeri.to" 
                :total="galeri.total" 
                label="media" 
            />
        </div>

        <!-- Form Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-[#dbe6f7]">
                    <div>
                        <h3 class="text-[24px] font-bold text-slate-900 leading-tight">
                            {{ isEditing ? 'Edit Media Galeri' : 'Tambah Media Baru' }}
                        </h3>
                        <p class="text-[14px] text-slate-500 mt-1">Lengkapi informasi di bawah untuk mengunggah media baru.</p>
                    </div>
                    <button @click="closeModal" class="text-slate-500 hover:text-slate-900 transition-colors">
                        <span class="material-symbols-outlined text-[24px]">close</span>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Media <span class="text-rose-500">*</span></label>
                        <input v-model="form.judul" type="text" required placeholder="Misal: Penerimaan Kunjungan DPMD Provinsi" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6]" />
                        <div v-if="form.errors.judul" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.judul }}</div>
                    </div>

                    <!-- Tipe Media -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Media</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700 text-sm">
                                <input type="radio" v-model="form.tipe" value="foto" class="text-[#1e56a0] focus:ring-0" />
                                Foto
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700 text-sm">
                                <input type="radio" v-model="form.tipe" value="video" class="text-[#1e56a0] focus:ring-0" />
                                Video (YouTube)
                            </label>
                        </div>
                    </div>

                    <!-- Input Media (Foto / Video) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" v-if="form.tipe === 'foto'">File Foto <span class="text-rose-500">*</span></label>
                        <label class="block text-sm font-bold text-slate-700 mb-2" v-else>URL YouTube Embed <span class="text-rose-500">*</span></label>

                        <div class="flex flex-col md:flex-row gap-4 items-start" v-if="form.tipe === 'foto'">
                            <div v-if="isEditing && detailItem.foto_url && !form.foto" class="w-32 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 relative">
                                <img :src="detailItem.foto_url" alt="Current Photo" class="w-full h-full object-cover" />
                                <div class="absolute bottom-0 inset-x-0 bg-[#103973]/70 text-white text-[10px] font-bold text-center py-1">Tersimpan</div>
                            </div>

                            <FormFileInput 
                                ref="fileInputRef"
                                :modelValue="form.foto"
                                @update:modelValue="handleFileUpload"
                                label="Pilih gambar baru"
                                maxSizeText="Maks 1MB"
                                :error="form.errors.foto"
                                :infoText="isEditing ? 'Biarkan kosong jika tidak ingin mengubah file foto saat ini.' : ''"
                                class="flex-grow w-full"
                            />
                        </div>

                        <div v-else class="w-full">
                            <textarea v-model="form.foto" rows="3" placeholder='https://www.youtube.com/embed/... atau <iframe src="..."></iframe>' class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6]"></textarea>
                            <p class="text-[12px] text-slate-500 mt-2">Masukkan URL Embed atau tempel kode <code>&lt;iframe&gt;</code> dari YouTube di sini.</p>
                        </div>

                        <div v-if="form.errors.foto" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.foto }}</div>
                        <div v-if="youtubeError" class="text-rose-500 text-xs mt-1 font-semibold">
                            Ini bukan kode embed youtube. <a href="https://www.google.com/search?q=cara+mencari+kode+embed+youtube" target="_blank" class="text-[#1e56a0] hover:underline">Cara mencari kode embed youtube</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal Kegiatan -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Kegiatan</label>
                            <input v-model="form.tanggal_kegiatan" type="date" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6]" />
                            <div v-if="form.errors.tanggal_kegiatan" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.tanggal_kegiatan }}</div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Singkat</label>
                        <textarea v-model="form.deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6]" placeholder="Tuliskan deskripsi singkat..."></textarea>
                        <div v-if="form.errors.deskripsi" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.deskripsi }}</div>
                    </div>

                    <!-- Publish Toggle -->
                    <div class="pt-2">
                        <FormToggle 
                            v-model="form.is_published"
                            title="Publikasikan"
                            description="Galeri akan tampil di website publik"
                        />
                        <p class="text-xs text-amber-600 mt-2 font-semibold flex items-center gap-1.5" v-if="form.is_published">
                            <i class="fa-solid fa-triangle-exclamation text-[12px]"></i>
                            Foto wajib diisi sebelum dapat dipublikasikan.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-xl bg-[#1e56a0] text-white font-semibold text-sm hover:bg-[#103973] transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-[#1e56a0]/20">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Galeri' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <AdminConfirmDelete 
            :show="isDeleteModalOpen" 
            title="Hapus Galeri?"
            :message="`Tindakan ini akan menghapus galeri <span class='font-bold text-slate-900'>\&quot;${detailItem?.judul}\&quot;</span> secara permanen beserta file foto fisiknya. Tindakan ini tidak dapat dibatalkan.`"
            @close="isDeleteModalOpen = false"
            @confirm="deleteGaleri"
        />
    </AuthenticatedLayout>
</template>