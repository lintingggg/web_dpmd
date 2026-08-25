<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    tanggalAdaAcara: Array,
    bulan: Number,
    tahun: Number,
    modelValue: String // Bind selected date (YYYY-MM-DD)
});

const emit = defineEmits(['update:modelValue']);

const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

// Compute 42-day calendar grid (6 rows of 7 columns, starting Monday)
const calendarGrid = computed(() => {
    const year = props.tahun;
    const month = props.bulan - 1; // 0-indexed
    
    // Day of the week for the 1st of the month (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
    const firstDayRaw = new Date(year, month, 1).getDay();
    // Adjust so Monday is 0, Sunday is 6
    const firstDay = firstDayRaw === 0 ? 6 : firstDayRaw - 1;
    
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const grid = [];
    let day = 1;
    let nextMonthDay = 1;

    for (let i = 0; i < 6; i++) {
        const week = [];
        for (let j = 0; j < 7; j++) {
            const cellIndex = i * 7 + j;
            if (cellIndex < firstDay) {
                // Previous month days padding
                const prevDay = daysInPrevMonth - firstDay + cellIndex + 1;
                const prevMonthNum = month === 0 ? 12 : month;
                const prevYearNum = month === 0 ? year - 1 : year;
                const dateStr = `${prevYearNum}-${String(prevMonthNum).padStart(2, '0')}-${String(prevDay).padStart(2, '0')}`;
                week.push({
                    day: prevDay,
                    dateStr,
                    isCurrentMonth: false,
                    hasEvent: props.tanggalAdaAcara?.includes(dateStr)
                });
            } else if (day > daysInMonth) {
                // Next month days padding
                const nextMonthNum = month === 11 ? 1 : month + 2;
                const nextYearNum = month === 11 ? year + 1 : year;
                const dateStr = `${nextYearNum}-${String(nextMonthNum).padStart(2, '0')}-${String(nextMonthDay).padStart(2, '0')}`;
                week.push({
                    day: nextMonthDay,
                    dateStr,
                    isCurrentMonth: false,
                    hasEvent: props.tanggalAdaAcara?.includes(dateStr)
                });
                nextMonthDay++;
            } else {
                // Current month days
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                week.push({
                    day,
                    dateStr,
                    isCurrentMonth: true,
                    hasEvent: props.tanggalAdaAcara?.includes(dateStr)
                });
                day++;
            }
        }
        grid.push(week);
    }
    return grid;
});

const prevMonth = () => {
    let newMonth = props.bulan - 1;
    let newYear = props.tahun;
    if (newMonth < 1) { newMonth = 12; newYear--; }
    router.get('/', { month: newMonth, year: newYear }, { preserveState: true, preserveScroll: true, only: ['agendaList', 'tanggalAdaAcara', 'bulan', 'tahun'] });
};

const nextMonth = () => {
    let newMonth = props.bulan + 1;
    let newYear = props.tahun;
    if (newMonth > 12) { newMonth = 1; newYear++; }
    router.get('/', { month: newMonth, year: newYear }, { preserveState: true, preserveScroll: true, only: ['agendaList', 'tanggalAdaAcara', 'bulan', 'tahun'] });
};

const selectDate = (cell) => {
    emit('update:modelValue', cell.dateStr);
};
</script>

<template>
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 flex flex-col justify-between w-full select-none">
        <div>
            <!-- Calendar Header -->
            <div class="flex items-center justify-between mb-6 px-1">
                <button @click="prevMonth" class="p-2 hover:bg-slate-50 rounded-xl transition-all text-slate-500 hover:text-slate-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <h3 class="font-bold text-slate-800 text-[16px] tracking-tight">
                    {{ monthNames[bulan - 1] }} {{ tahun }}
                </h3>
                <button @click="nextMonth" class="p-2 hover:bg-slate-50 rounded-xl transition-all text-slate-500 hover:text-slate-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
            
            <!-- Day Names -->
            <div class="grid grid-cols-7 gap-1.5 mb-4 text-[13px] font-bold text-slate-400">
                <div v-for="(day, index) in dayNames" :key="day" 
                     class="text-center py-1"
                     :class="{ 'text-red-500': index === 6 }">
                    {{ day }}
                </div>
            </div>
            
            <!-- Calendar Cells -->
            <div class="space-y-1.5">
                <div v-for="(week, wIdx) in calendarGrid" :key="wIdx" class="grid grid-cols-7 gap-1.5">
                    <button v-for="(cell, cIdx) in week" :key="cIdx" 
                        @click="selectDate(cell)"
                        class="aspect-square relative w-full flex flex-col items-center justify-center rounded-2xl border transition-all duration-200 overflow-hidden outline-none"
                        :class="[
                            cell.isCurrentMonth ? 'bg-white text-slate-700 font-bold border-slate-100 hover:border-slate-300' : 'bg-slate-50/50 text-slate-300 border-slate-50 hover:border-slate-200',
                            cIdx === 6 ? 'text-red-500/90' : '',
                            modelValue === cell.dateStr ? 'border-2 border-sky-400 shadow-sm' : ''
                        ]"
                    >
                        <!-- Date Number -->
                        <span class="text-[14px]">{{ cell.day }}</span>
                        
                        <!-- Event Indicator (Bottom Right Corner Curve) -->
                        <div v-if="cell.hasEvent" 
                             class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-sky-200 rounded-tl-full transition-colors pointer-events-none"
                             :class="modelValue === cell.dateStr ? 'bg-sky-400' : 'bg-sky-200'">
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Scoped custom styling if needed */
</style>
