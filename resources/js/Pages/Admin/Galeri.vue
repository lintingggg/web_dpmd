<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { formatDate } from '@/Utils/formatDate';

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
const fileInput = ref(null);

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

let filterTimeout = null;
watch([search, statusFilter], ([newSearch, newStatus]) => {
    if (filterTimeout) clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('admin.galeri', props.album.id), {
            search: newSearch,
            status: newStatus,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['galeri', 'filters']
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

    if (fileInput.value) fileInput.value.value = '';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const youtubeError = ref(false);

const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.foto = file;
    } else {
        form.foto = null;
    }
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

const getYoutubeThumbnail = (url) => {
    if (!url) return '';
    const match = url.match(/\/embed\/([^?]+)/);
    return match ? `https://img.youtube.com/vi/${match[1]}/hqdefault.jpg` : '';
};

const deleteGaleri = () => {
    router.delete(route('admin.galeri.destroy', detailItem.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        }
    });
};

</script>

<template>
    <Head title="Galeri Kegiatan" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div class="pt-4">
                <div class="mb-3">
                    <Link :href="route('admin.album')" class="text-slate-500 hover:text-[#1356a0] font-semibold flex items-center gap-2 text-[13px] transition-colors">
                        <i class="fa-solid fa-arrow-left text-[12px]"></i>
                        Kembali ke Daftar Album
                    </Link>
                </div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1">
                    Galeri: {{ album.nama }}
                </h2>
                <p class="text-[14px] font-medium text-slate-500">Kelola dokumentasi foto dan video dalam album ini.</p>
            </div>

            <button @click="openModal()" class="bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_16px_rgba(19,86,160,0.3)]">
                <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                Buat Entri Baru
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="flex items-center bg-white border border-slate-200 rounded-full px-4 py-2.5 w-full md:w-96 shadow-sm focus-within:ring-2 focus-within:ring-[#528be6]/50 focus-within:border-[#528be6] transition-all">
                <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                <input v-model="search" type="text" placeholder="Cari judul kegiatan..." class="bg-transparent border-none outline-none focus:ring-0 text-sm w-full ml-3 text-slate-700 placeholder-slate-400 font-medium p-0" />
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <select v-model="statusFilter" class="bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold rounded-lg px-4 py-2.5 outline-none focus:border-[#528be6] focus:ring-0 cursor-pointer shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="publish">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <!-- Galeri Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">

            <div v-for="item in galeri.data" :key="item.id" class="bg-white rounded-[1.25rem] border border-slate-200/60 shadow-sm overflow-hidden group hover:shadow-md hover:border-slate-300 transition-all flex flex-col">
                <!-- Cover Image or Video -->
                <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                    <img v-if="item.tipe === 'foto' && item.foto_url" :src="item.foto_url" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div v-else-if="item.tipe === 'video' && item.foto_url" class="relative w-full h-full">
                        <img :src="getYoutubeThumbnail(item.foto_url)" alt="Video Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="bg-[#103973]/60 text-white rounded-full w-11 h-11 flex items-center justify-center">
                                <i class="fa-solid fa-play text-[16px] ml-0.5"></i>
                            </div>
                        </div>
                    </div>
                    <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                        <i class="fa-regular fa-image text-[40px] mb-2 opacity-50"></i>
                        <span class="text-[12px] font-bold">Belum ada media</span>
                    </div>

                    <!-- Overlay Actions -->
                    <div class="absolute inset-0 bg-[#103973]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm pointer-events-none group-hover:pointer-events-auto">
                        <button @click="openModal(item)" class="w-10 h-10 rounded-full bg-white text-[#1e56a0] hover:bg-blue-50 flex items-center justify-center transition-colors shadow-lg" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-[15px]"></i>
                        </button>
                        <button @click="confirmDelete(item)" class="w-10 h-10 rounded-full bg-rose-600 text-white hover:bg-rose-700 flex items-center justify-center transition-colors shadow-lg" title="Hapus">
                            <i class="fa-solid fa-trash text-[15px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-400 tracking-[0.5px] uppercase">{{ formatDate(item.tanggal_kegiatan) }} • {{ item.tipe === 'video' ? 'VIDEO' : 'FOTO' }}</span>
                            <span v-if="item.is_published" class="inline-flex items-center gap-1 text-[10px] font-bold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md border border-emerald-100">
                                <i class="fa-solid fa-check text-[9px]"></i> Live
                            </span>
                            <span v-else class="inline-flex items-center gap-1 text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md border border-slate-200">
                                Draft
                            </span>
                        </div>
                        <h3 class="text-[15px] font-bold text-slate-900 leading-snug line-clamp-2 transition-colors">
                            {{ item.judul }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Add New Card (Ghost Card) -->
            <div @click="openModal()" class="border-2 border-dashed border-slate-300 rounded-[1.25rem] bg-slate-50/60 hover:bg-slate-50 transition-colors cursor-pointer flex flex-col items-center justify-center min-h-[250px] group">
                <div class="w-16 h-16 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-[#1e56a0] mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-plus text-[22px]"></i>
                </div>
                <h3 class="text-[15px] font-bold text-slate-900 mb-1">Buat Entri Baru</h3>
                <p class="text-[13px] text-slate-500">Tambah koleksi dokumentasi</p>
            </div>

        </div>

        <!-- Pagination -->
        <div v-if="galeri.data.length > 0" class="bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden mb-8">
            <div class="p-6 md:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/60">
                <p class="text-[13px] font-medium text-slate-500">
                    Menampilkan <span class="font-bold text-slate-900">{{ galeri.from || 0 }}-{{ galeri.to || 0 }}</span> dari <span class="font-bold text-slate-900">{{ galeri.total }}</span> entri
                </p>

                <div class="flex flex-wrap items-center gap-1" v-if="galeri.links && galeri.links.length > 3">
                    <Link
                        v-for="(link, index) in galeri.links"
                        :key="index"
                        :href="link.url || '#'"
                        class="min-w-[32px] px-3 py-1.5 flex items-center justify-center rounded-lg font-medium text-[13px] transition-colors whitespace-nowrap"
                        :class="[
                            link.active ? 'bg-[#1e56a0] text-white font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-100',
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
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-200">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 leading-tight tracking-tight">{{ isEditing ? 'Edit Galeri' : 'Tambah Galeri Baru' }}</h3>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Isi formulir di bawah ini dengan detail kegiatan.</p>
                </div>
                <button @click="closeModal" class="w-9 h-9 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark text-[18px]"></i>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Judul Kegiatan <span class="text-rose-500">*</span></label>
                    <input v-model="form.judul" type="text" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6]" placeholder="Masukkan judul..." />
                    <div v-if="form.errors.judul" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.judul }}</div>
                </div>

                <!-- Tipe (Foto/Video) -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Media <span class="text-rose-500">*</span></label>
                    <select v-model="form.tipe" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6] cursor-pointer">
                        <option value="foto">Foto</option>
                        <option value="video">Video</option>
                    </select>
                    <div v-if="form.errors.tipe" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.tipe }}</div>
                </div>

                <!-- Foto/Video -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" v-if="form.tipe === 'foto'">File Foto (JPG, PNG, WEBP) <span class="text-rose-500">*</span></label>
                    <label class="block text-sm font-bold text-slate-700 mb-2" v-else>URL YouTube Embed <span class="text-rose-500">*</span></label>

                    <div class="flex flex-col md:flex-row gap-4 items-start" v-if="form.tipe === 'foto'">
                        <div v-if="isEditing && detailItem.foto_url && !form.foto" class="w-32 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 relative">
                            <img :src="detailItem.foto_url" alt="Current Photo" class="w-full h-full object-cover" />
                            <div class="absolute bottom-0 inset-x-0 bg-[#103973]/70 text-white text-[10px] font-bold text-center py-1">Tersimpan</div>
                        </div>

                        <div class="relative w-full flex-1 border-2 border-dashed border-slate-300 bg-slate-50 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-slate-100/70 transition-colors min-h-[128px]">
                            <i class="fa-solid fa-image text-[26px] text-slate-400 mb-2"></i>
                            <p class="text-[13px] font-medium text-slate-600 mb-1">
                                <span v-if="form.foto && form.foto.name">{{ form.foto.name }}</span>
                                <span v-else>Pilih file gambar baru (Maks 1MB)</span>
                            </p>
                            <input type="file" ref="fileInput" @change="handleFileUpload" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </div>
                    </div>

                    <div v-else class="w-full">
                        <textarea v-model="form.foto" rows="3" placeholder='https://www.youtube.com/embed/... atau <iframe src="..."></iframe>' class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#528be6]/40 focus:border-[#528be6]"></textarea>
                        <p class="text-[12px] text-slate-500 mt-2">Masukkan URL Embed atau tempel kode <code>&lt;iframe&gt;</code> dari YouTube di sini.</p>
                    </div>

                    <div v-if="form.errors.foto" class="text-rose-500 text-xs mt-1 font-semibold">{{ form.errors.foto }}</div>
                    <div v-if="youtubeError" class="text-rose-500 text-xs mt-1 font-semibold">
                        Ini bukan kode embed youtube. <a href="https://www.google.com/search?q=cara+mencari+kode+embed+youtube" target="_blank" class="text-[#1e56a0] hover:underline">Cara mencari kode embed youtube</a>
                    </div>
                    <div v-if="isEditing && form.tipe === 'foto'" class="mt-2 text-[12px] font-medium text-slate-500">
                        Biarkan kosong jika tidak ingin mengubah file foto saat ini.
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
                    <label class="flex items-center gap-3 cursor-pointer group w-max">
                        <div class="relative flex items-center">
                            <input type="checkbox" v-model="form.is_published" class="peer sr-only">
                            <div class="w-11 h-6 bg-slate-300 rounded-full peer-checked:bg-[#1e56a0] transition-colors duration-200 ease-in-out"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform duration-200 ease-in-out shadow-sm"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900 leading-none mb-1">Publikasikan</span>
                            <span class="text-[12px] text-slate-500 leading-none">Galeri akan tampil di website publik</span>
                        </div>
                    </label>
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

    <!-- Modal Konfirmasi Hapus -->
    <Modal :show="isDeleteModalOpen" @close="isDeleteModalOpen = false" maxWidth="md">
        <div class="p-6 md:p-8 text-center">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-trash-can text-[26px]"></i>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-900 leading-tight tracking-tight mb-3">Hapus Galeri?</h3>
            <p class="text-[15px] text-slate-500 mb-8">
                Tindakan ini akan menghapus galeri <span class="font-bold text-slate-900">"{{ detailItem?.judul }}"</span> secara permanen beserta file foto fisiknya. Tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button @click="isDeleteModalOpen = false" class="px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm">
                    Batal
                </button>
                <button @click="deleteGaleri" class="px-6 py-3 rounded-xl bg-rose-600 text-white font-semibold text-sm hover:bg-rose-700 transition-all active:scale-95 shadow-lg shadow-rose-600/20">
                    Ya, Hapus Permanen
                </button>
            </div>
        </div>
    </Modal>
</template>