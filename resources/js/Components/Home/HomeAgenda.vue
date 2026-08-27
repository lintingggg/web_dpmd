<script setup>
import { ref, computed } from 'vue';
import { 
  IconClock, 
  IconMapPin, 
  IconChevronRight, 
  IconCalendarEvent,
  IconX
} from '@tabler/icons-vue';
import CalendarAgenda from '../CalendarAgenda.vue';
import Modal from '../Modal.vue';
import { formatFullDate, getDayNumber, getShortMonth } from '../../Utils/helpers';

const props = defineProps({
  agendaList: Array,
  tanggalAdaAcara: Array,
  bulan: Number,
  tahun: Number,
  timezoneLabel: String
});

const selectedDate = ref(null);
const isDetailOpen = ref(false);
const selectedAgenda = ref(null);

const openDetail = (item) => {
    selectedAgenda.value = item;
    isDetailOpen.value = true;
};

const closeDetail = () => {
    isDetailOpen.value = false;
    setTimeout(() => { selectedAgenda.value = null; }, 300);
};

const filteredAgendaList = computed(() => {
    if (!selectedDate.value) return props.agendaList;
    return props.agendaList.filter(agenda => {
        const d = new Date(agenda.tanggal);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const agendaDateStr = `${year}-${month}-${day}`;
        return agendaDateStr === selectedDate.value;
    });
});
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 section-agenda">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 border-b border-gray-200 pb-4 section-header">
      <div>
        <h2 class="text-3xl font-bold text-[#0F172A] mb-2">Agenda Mendatang</h2>
        <p class="text-[#646A79]">Rencana kegiatan dan acara yang akan datang</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
      
      <!-- Left Column: Calendar Component -->
      <div class="lg:col-span-4 sticky top-24">
          <CalendarAgenda 
              v-model="selectedDate"
              :tanggal-ada-acara="tanggalAdaAcara" 
              :bulan="bulan" 
              :tahun="tahun" 
          />
      </div>

      <!-- Right Column: Agenda List -->
      <div class="lg:col-span-8">
          <!-- Filter Info -->
          <div v-if="selectedDate" class="mb-4 bg-sky-50 border border-sky-100 rounded-2xl p-4 flex items-center justify-between text-sky-800 text-sm">
              <div class="flex items-center gap-2 font-semibold">
                  <IconCalendarEvent :size="18" />
                  <span>Menampilkan agenda untuk tanggal: {{ formatFullDate(selectedDate) }}</span>
              </div>
              <button @click="selectedDate = null" class="text-xs bg-white hover:bg-sky-100 border border-sky-200 text-sky-700 font-bold px-3 py-1.5 rounded-xl transition-colors shadow-sm">
                  Tampilkan Semua
              </button>
          </div>

          <div v-if="filteredAgendaList && filteredAgendaList.length > 0" class="space-y-4">
              <div v-for="agenda in filteredAgendaList" :key="agenda.id" 
                  @click="openDetail(agenda)"
                  class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col sm:flex-row gap-5 hover:border-[#646A79]/30 hover:shadow-md transition-all cursor-pointer group"
              >
                  <!-- Date Badge -->
                  <div class="shrink-0 flex sm:flex-col items-center justify-center gap-2 sm:gap-0 w-full sm:w-20 bg-[#F8FAFC] rounded-xl p-3 border border-gray-100 group-hover:bg-[#0F172A] group-hover:border-[#0F172A] transition-colors">
                      <span class="text-2xl font-bold text-[#0F172A] group-hover:text-[#FFFFFF] leading-none">{{ getDayNumber(agenda.tanggal) }}</span>
                      <span class="text-xs font-bold text-[#646A79] group-hover:text-[#F8FAFC] uppercase tracking-wider mt-1">{{ getShortMonth(agenda.tanggal) }}</span>
                  </div>
                  
                  <!-- Content -->
                  <div class="flex-grow">
                      <div class="flex items-center gap-2 mb-2">
                          <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-[#F8FAFC] text-[#646A79] border border-gray-200">Agenda Utama</span>
                      </div>
                      <h3 class="text-lg font-bold text-[#0F172A] leading-snug mb-3 group-hover:text-[#646A79] transition-colors line-clamp-2">
                          {{ agenda.judul }}
                      </h3>
                      
                      <div class="flex flex-wrap gap-4 text-sm text-[#646A79] font-medium">
                          <div class="flex items-center gap-1.5" v-if="agenda.waktu_mulai">
                              <IconClock :size="16" class="text-[#646A79]" />
                              {{ agenda.waktu_mulai.substring(0, 5) }} {{ timezoneLabel }}
                          </div>
                          <div class="flex items-center gap-1.5" v-if="agenda.lokasi">
                              <IconMapPin :size="16" class="text-[#646A79]" />
                              <span class="line-clamp-1 max-w-[200px]">{{ agenda.lokasi }}</span>
                          </div>
                      </div>
                  </div>
                  
                  <div class="hidden sm:flex shrink-0 items-center justify-center">
                      <div class="w-10 h-10 rounded-full bg-[#F8FAFC] flex items-center justify-center text-[#646A79] group-hover:bg-[#0F172A] group-hover:text-[#FFFFFF] transition-colors">
                          <IconChevronRight :size="20" />
                      </div>
                  </div>
              </div>
          </div>
          
          <div v-else class="bg-white rounded-2xl border border-dashed border-[#646A79]/30 p-12 text-center">
              <div class="w-16 h-16 bg-[#F8FAFC] rounded-full flex items-center justify-center mx-auto mb-4 text-[#646A79]">
                  <IconCalendarEvent :size="32" />
              </div>
              <h3 class="text-lg font-bold text-[#0F172A] mb-1">Belum ada agenda</h3>
              <p class="text-[#646A79]">Tidak ada agenda acara pada tanggal ini. <button v-if="selectedDate" @click="selectedDate = null" class="text-sky-600 hover:underline font-bold">Tampilkan semua agenda bulan ini</button></p>
          </div>
      </div>

    </div>
  </section>

  <!-- Modal Detail Agenda -->
  <Modal :show="isDetailOpen" @close="closeDetail" maxWidth="2xl">
      <div v-if="selectedAgenda" class="bg-white rounded-2xl overflow-hidden relative">
          
          <!-- Blue Header Area -->
          <div class="bg-[#0F172A] p-8 pt-10 text-white relative">
              <button @click="closeDetail" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition-colors">
                  <IconX :size="18" />
              </button>
              
              <div class="flex items-center gap-5">
                  <div class="w-20 h-20 bg-white rounded-2xl flex flex-col items-center justify-center shadow-lg shrink-0">
                      <span class="text-2xl font-bold text-[#0F172A] leading-none">{{ getDayNumber(selectedAgenda.tanggal) }}</span>
                      <span class="text-xs font-bold text-[#646A79] uppercase tracking-wider mt-1">{{ getShortMonth(selectedAgenda.tanggal) }}</span>
                  </div>
                  <div>
                      <p class="text-[#F8FAFC]/70 text-xs font-bold tracking-widest uppercase mb-1">Detail Agenda</p>
                      <p class="text-lg font-medium text-[#F8FAFC]">{{ formatFullDate(selectedAgenda.tanggal) }}</p>
                  </div>
              </div>
          </div>
          
          <!-- Content Area -->
          <div class="p-8">
              <div class="mb-8">
                  <p class="text-xs font-bold text-[#646A79] tracking-widest uppercase mb-2">Nama Kegiatan</p>
                  <h2 class="text-xl md:text-2xl font-bold text-[#0F172A] leading-snug">
                      {{ selectedAgenda.judul }}
                  </h2>
              </div>
              
              <div class="space-y-6">
                  <div v-if="selectedAgenda.waktu_mulai" class="flex gap-4 border-t border-gray-100 pt-6">
                      <div class="w-12 h-12 rounded-xl bg-[#F8FAFC] flex items-center justify-center shrink-0 text-[#0F172A]">
                          <IconClock :size="24" />
                      </div>
                      <div>
                          <p class="text-xs font-bold text-[#646A79] tracking-widest uppercase mb-1">Waktu</p>
                          <p class="font-bold text-[#0F172A] text-lg">
                              {{ selectedAgenda.waktu_mulai.substring(0, 5) }} <span v-if="selectedAgenda.waktu_selesai"> - {{ selectedAgenda.waktu_selesai.substring(0, 5) }}</span> {{ timezoneLabel }}
                          </p>
                      </div>
                  </div>
                  
                  <div v-if="selectedAgenda.lokasi" class="flex gap-4 border-t border-gray-100 pt-6">
                      <div class="w-12 h-12 rounded-xl bg-[#F8FAFC] flex items-center justify-center shrink-0 text-[#0F172A]">
                          <IconMapPin :size="24" />
                      </div>
                      <div>
                          <p class="text-xs font-bold text-[#646A79] tracking-widest uppercase mb-1">Lokasi</p>
                          <p class="font-bold text-[#0F172A] text-lg">
                              {{ selectedAgenda.lokasi }}
                          </p>
                      </div>
                  </div>
                  
                  <div v-if="selectedAgenda.deskripsi" class="flex gap-4 border-t border-gray-100 pt-6">
                      <div class="w-12 h-12 rounded-xl bg-[#F8FAFC] flex items-center justify-center shrink-0 text-[#0F172A]">
                          <IconCalendarEvent :size="24" />
                      </div>
                      <div>
                          <p class="text-xs font-bold text-[#646A79] tracking-widest uppercase mb-1">Keterangan</p>
                          <div class="text-[#646A79] whitespace-pre-line leading-relaxed">
                              {{ selectedAgenda.deskripsi }}
                          </div>
                      </div>
                  </div>
              </div>
              
              <div class="mt-10 flex justify-end">
                  <button @click="closeDetail" class="px-6 py-2.5 bg-[#F8FAFC] hover:bg-gray-200 text-[#0F172A] font-semibold rounded-xl transition-colors">
                      Tutup
                  </button>
              </div>
          </div>
          
      </div>
  </Modal>
</template>
