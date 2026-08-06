<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from '@idds/vue';

const props = defineProps({
    agenda: Object,
    filters: Object
});

const { toast } = useToast();

const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || 'Semua Status');

let filterTimeout = null;
watch([search, statusFilter], ([newSearch, newStatus]) => {
    if (filterTimeout) clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(
            route('admin.agenda.index'),
            { search: newSearch, status: newStatus === 'Semua Status' ? null : newStatus },
            { preserveState: true, replace: true }
        );
    }, 300);
});

const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    judul: '',
    deskripsi: '',
    tanggal: '',
    waktu_mulai: '',
    waktu_selesai: '',
    lokasi: '',
    is_published: true,
});

const openModal = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    editingId.value = item.id;
    form.judul = item.judul;
    form.deskripsi = item.deskripsi || '';
    form.tanggal = item.tanggal;
    form.waktu_mulai = item.waktu_mulai ? item.waktu_mulai.substring(0, 5) : '';
    form.waktu_selesai = item.waktu_selesai ? item.waktu_selesai.substring(0, 5) : '';
    form.lokasi = item.lokasi || '';
    form.is_published = item.is_published;
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const validateTime = () => {
    if (form.waktu_mulai && form.waktu_selesai) {
        if (form.waktu_selesai < form.waktu_mulai) {
            form.setError('waktu_selesai', 'Waktu selesai tidak boleh lebih awal dari waktu mulai');
            return false;
        }
    }
    return true;
};

const submit = () => {
    if (!validateTime()) return;

    if (isEditing.value) {
        form.post(route('admin.agenda.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                toast.success('Agenda berhasil diperbarui!');
            },
        });
    } else {
        form.post(route('admin.agenda.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                toast.success('Agenda berhasil ditambahkan!');
            },
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus agenda ini?')) {
        router.delete(route('admin.agenda.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Agenda berhasil dihapus!');
            },
        });
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(date);
};
</script>

<template>
    <Head title="Kelola Agenda Acara - Admin DPMD" />

    <AuthenticatedLayout>
        <!-- Page Header Top -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-[40px] tracking-[-0.45px] font-bold text-slate-900 mb-1 pt-4">
                    Agenda Acara
                </h2>
                <p class="text-[14px] font-medium text-slate-500">Kelola jadwal kegiatan dan acara yang tampil di portal publik.</p>
            </div>
            
            <button @click="openModal" class="bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_16px_rgba(19,86,160,0.3)]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Agenda
            </button>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-[24px] shadow-[0_4px_20px_rgba(16,57,115,0.06)] border border-[#dbe6f7] overflow-hidden">
            <!-- Toolbar -->
            <div class="p-6 md:p-8 border-b border-[#dbe6f7] flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-[#f5f8fd]">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[20px]">search</span>
                    <input v-model="search" type="text" placeholder="Cari judul agenda..." class="w-full bg-white border border-[#dbe6f7] text-slate-900 text-[14px] rounded-xl pl-11 pr-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                </div>
                
                <!-- Filter Section -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 whitespace-nowrap">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[1px]">Status:</span>
                        <select v-model="statusFilter" class="bg-white border border-[#dbe6f7] text-slate-900 text-[13px] font-bold rounded-lg px-3 py-1.5 focus:ring-0 focus:border-[#c7dafa]">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Non-Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#f5f8fd]">
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7]">Judul & Lokasi</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7]">Waktu</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7]">Status</th>
                            <th class="py-4 px-6 md:px-8 text-[11px] font-bold text-slate-500 uppercase tracking-[2.75px] border-b border-[#dbe6f7] text-right w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] font-medium divide-y divide-[#dbe6f7]">
                        <tr v-for="item in agenda.data" :key="item.id" class="hover:bg-[#f5f8fd] transition-colors group">
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-[#eaf1fb] flex items-center justify-center flex-shrink-0 border border-[#dbe6f7]">
                                        <span class="material-symbols-outlined text-[#1356a0] text-[24px]">event</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-tight">
                                            {{ item.judul }}
                                        </p>
                                        <div class="text-[12px] text-slate-500 flex items-center gap-1.5 mt-1" v-if="item.lokasi">
                                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                                            {{ item.lokasi }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-bold text-slate-900 mb-0.5">{{ formatDate(item.tanggal) }}</p>
                                <div class="text-[12px] text-slate-500 flex items-center gap-1.5" v-if="item.waktu_mulai">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ item.waktu_mulai.substring(0, 5) }} <span v-if="item.waktu_selesai">- {{ item.waktu_selesai.substring(0, 5) }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span v-if="item.is_published" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e6f4ea] text-[#137333] border border-[#ceead6]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Aktif
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#eaf1fb] text-slate-500 border border-[#dbe6f7]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#7488a8]"></span> Non-Aktif
                                </span>
                            </td>
                            <td class="py-4 px-6 md:px-8">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button @click="openEditModal(item)" class="w-8 h-8 rounded-full hover:bg-[#dbe6f7] text-slate-500 flex items-center justify-center transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button @click="deleteItem(item.id)" class="w-8 h-8 rounded-full hover:bg-[#ffebee] text-[#d32f2f] flex items-center justify-center transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <tr v-if="agenda.data.length === 0">
                            <td colspan="4" class="py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-[48px] text-[#c7dafa] mb-2 block">event_busy</span>
                                Belum ada agenda acara.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="p-6 md:px-8 border-t border-[#dbe6f7] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f5f8fd]">
                <p class="text-[13px] font-medium text-slate-500">
                    Menampilkan <span class="font-bold text-slate-900">{{ agenda.from || 0 }}-{{ agenda.to || 0 }}</span> dari <span class="font-bold text-slate-900">{{ agenda.total || agenda.data.length }}</span> agenda
                </p>
                
                <div class="flex flex-wrap items-center gap-1" v-if="agenda.links && agenda.links.length > 3">
                    <Link
                        v-for="(link, p) in agenda.links"
                        :key="p"
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


        <!-- Form Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-[#dbe6f7]">
                    <div>
                        <h3 class="text-[24px] font-bold text-slate-900 leading-tight">{{ isEditing ? 'Edit Agenda Acara' : 'Tambah Agenda Acara Baru' }}</h3>
                        <p class="text-[14px] text-slate-500 mt-1">Isi formulir di bawah ini dengan detail agenda.</p>
                    </div>
                    <button @click="closeModal" class="text-slate-500 hover:text-slate-900 transition-colors">
                        <span class="material-symbols-outlined text-[24px]">close</span>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Judul Acara <span class="text-red-500">*</span></label>
                        <input v-model="form.judul" type="text" required class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" placeholder="Misal: Rapat Koordinasi Tahunan" />
                        <div v-if="form.errors.judul" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.judul }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[14px] font-bold text-slate-700 mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input v-model="form.tanggal" type="date" required class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                            <div v-if="form.errors.tanggal" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.tanggal }}</div>
                        </div>
                        <div>
                            <label class="block text-[14px] font-bold text-slate-700 mb-2">Lokasi Acara</label>
                            <input v-model="form.lokasi" type="text" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" placeholder="Misal: Balai Desa..." />
                            <div v-if="form.errors.lokasi" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.lokasi }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[14px] font-bold text-slate-700 mb-2">Waktu Mulai</label>
                            <input v-model="form.waktu_mulai" type="time" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                            <div v-if="form.errors.waktu_mulai" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.waktu_mulai }}</div>
                        </div>
                        <div>
                            <label class="block text-[14px] font-bold text-slate-700 mb-2">Waktu Selesai</label>
                            <input v-model="form.waktu_selesai" type="time" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0]" />
                            <div v-if="form.errors.waktu_selesai" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.waktu_selesai }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-slate-700 mb-2">Deskripsi / Keterangan</label>
                        <textarea v-model="form.deskripsi" rows="3" class="w-full bg-[#f5f8fd] border border-[#dbe6f7] text-slate-900 text-[14px] rounded-lg px-4 py-2.5 focus:ring-[#1356a0] focus:border-[#1356a0] resize-none" placeholder="Tuliskan keterangan detail tentang agenda..."></textarea>
                        <div v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.deskripsi }}</div>
                    </div>

                    <div class="bg-[#f5f8fd] border border-[#dbe6f7] rounded-xl p-4 mt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" v-model="form.is_published" class="peer sr-only">
                                <div class="w-11 h-6 bg-[#c7dafa] rounded-full peer-checked:bg-[#1356a0] transition-colors duration-200 ease-in-out"></div>
                                <div class="absolute left-[2px] top-[2px] bg-white w-5 h-5 rounded-full shadow transition-transform duration-200 ease-in-out peer-checked:translate-x-5"></div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[14px] font-bold text-slate-900 leading-none mb-1 group-hover:text-slate-700 transition-colors">Tampilkan ke Publik</span>
                                <span class="text-[12px] text-slate-500 leading-none">Aktifkan agar agenda ini tampil di halaman website</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-6 border-t border-[#dbe6f7] flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-[#1356a0] to-[#528be6] hover:from-[#103973] hover:to-[#1356a0] text-white font-bold text-[14px] shadow-[0_4px_16px_rgba(19,86,160,0.3)] transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                            <i v-if="form.processing" class="fa-solid fa-circle-notch fa-spin text-[18px]"></i>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Agenda' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>