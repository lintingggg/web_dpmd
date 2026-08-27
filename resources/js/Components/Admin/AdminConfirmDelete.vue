<script setup>
import Modal from '@/Components/Modal.vue';

defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Hapus Data?'
    },
    message: String
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <Modal :show="show" @close="emit('close')" maxWidth="sm">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-[#ffebee] flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px] text-[#d32f2f]">warning</span>
            </div>
            <h3 class="text-[20px] font-bold text-slate-900 mb-2">{{ title }}</h3>
            <p class="text-[14px] text-slate-500 mb-6" v-html="message"></p>
            <div class="flex justify-center gap-3">
                <button @click="emit('close')" class="px-5 py-2.5 rounded-full border border-[#c7dafa] bg-white text-slate-700 font-bold text-[14px] hover:bg-[#eaf1fb] transition-all active:scale-95">
                    Batal
                </button>
                <button @click="emit('confirm')" class="px-5 py-2.5 rounded-full bg-[#d32f2f] text-white font-bold text-[14px] hover:bg-[#b71c1c] shadow-[0_4px_12px_rgba(211,47,47,0.2)] transition-all active:scale-95">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </Modal>
</template>
