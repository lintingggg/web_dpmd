<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
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
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Agenda Acara</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Controls -->
                <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex gap-4 w-full sm:w-auto">
                        <input 
                            v-model="search" 
                            type="text" 
                            placeholder="Cari judul agenda..." 
                            class="w-full sm:w-64 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                        <select 
                            v-model="statusFilter"
                            class="w-full sm:w-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Non-Aktif</option>
                        </select>
                    </div>
                    
                    <button 
                        @click="openModal" 
                        class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Agenda
                    </button>
                </div>

                <!-- Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul & Lokasi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in agenda.data" :key="item.id">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ item.judul }}</div>
                                        <div class="text-sm text-gray-500 flex items-center gap-1 mt-1" v-if="item.lokasi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ item.lokasi }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ formatDate(item.tanggal) }}</div>
                                        <div class="text-sm text-gray-500 mt-1" v-if="item.waktu_mulai">
                                            {{ item.waktu_mulai.substring(0, 5) }} <span v-if="item.waktu_selesai">- {{ item.waktu_selesai.substring(0, 5) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            item.is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        ]">
                                            {{ item.is_published ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openEditModal(item)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                        <button @click="deleteItem(item.id)" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="agenda.data.length === 0">
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data agenda.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200" v-if="agenda.links && agenda.links.length > 3">
                        <div class="flex flex-wrap gap-1">
                            <template v-for="(link, p) in agenda.links" :key="p">
                                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                                <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-gray-100 focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-50 text-blue-700': link.active }" :href="link.url" v-html="link.label" />
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Form Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    {{ isEditing ? 'Edit Agenda Acara' : 'Tambah Agenda Acara Baru' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Acara *</label>
                        <input v-model="form.judul" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <div v-if="form.errors.judul" class="text-red-500 text-xs mt-1">{{ form.errors.judul }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pelaksanaan *</label>
                        <input v-model="form.tanggal" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <div v-if="form.errors.tanggal" class="text-red-500 text-xs mt-1">{{ form.errors.tanggal }}</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                            <input v-model="form.waktu_mulai" type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <div v-if="form.errors.waktu_mulai" class="text-red-500 text-xs mt-1">{{ form.errors.waktu_mulai }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
                            <input v-model="form.waktu_selesai" type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <div v-if="form.errors.waktu_selesai" class="text-red-500 text-xs mt-1">{{ form.errors.waktu_selesai }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lokasi Acara</label>
                        <input v-model="form.lokasi" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Misal: Balai Desa...">
                        <div v-if="form.errors.lokasi" class="text-red-500 text-xs mt-1">{{ form.errors.lokasi }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi / Keterangan</label>
                        <textarea v-model="form.deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <div v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1">{{ form.errors.deskripsi }}</div>
                    </div>

                    <div class="flex items-center">
                        <input v-model="form.is_published" type="checkbox" id="is_published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">Aktif (Tampilkan di website)</label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
