<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { 
  IconMapPin, 
  IconSend,
  IconArrowRight
} from '@tabler/icons-vue';

defineProps({
  kontak: Object
});

const page = usePage();

const form = useForm({
  nama: '',
  email: '',
  subjek: '',
  pesan: '',
  'g-recaptcha-response': ''
});

const submitForm = () => {
  const token = window.grecaptcha ? window.grecaptcha.getResponse() : '';
  form['g-recaptcha-response'] = token;

  if(!form.nama || !form.email || !form.pesan) {
    alert('Mohon lengkapi form kontak!');
    return;
  }
  
  if(!form['g-recaptcha-response']) {
    alert('Mohon selesaikan verifikasi reCAPTCHA!');
    return;
  }
  
  form.post(route('kontak.kirim'), {
    preserveScroll: true,
    onSuccess: () => {
      alert('Pesan Anda berhasil dikirim!');
      form.reset();
      if (window.grecaptcha) {
        window.grecaptcha.reset();
      }
    },
    onError: (errors) => {
      if (errors['g-recaptcha-response']) {
        alert(errors['g-recaptcha-response']);
      } else if (errors['pesan']) {
        alert(errors['pesan']);
      } else {
        alert('Terjadi kesalahan saat mengirim pesan.');
      }
    }
  });
};
</script>

<template>
  <section id="kontak" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 section-kontak overflow-hidden">
    <div class="mb-12 text-center section-header">
      <h2 class="text-3xl font-bold text-[#0F172A] mb-4">Hubungi Kami & Lokasi Kantor</h2>
      <p class="text-[#646A79] max-w-2xl mx-auto">Kami siap membantu menjawab pertanyaan Anda dan memberikan informasi seputar layanan DPMD Kabupaten Bangkalan.</p>
    </div>

    <div class="bg-white rounded-[2rem] shadow-lg overflow-hidden flex flex-col lg:flex-row border border-gray-100">
      
      <!-- Kolom Kiri: Embed Map -->
      <div class="lg:w-1/2 flex flex-col h-[500px] lg:h-auto border-b lg:border-b-0 lg:border-r border-gray-100 relative bg-gray-50 peta-container">
        <!-- Embed Google Maps -->
        <iframe 
          v-if="kontak.koordinat_map"
          :src="`https://maps.google.com/maps?q=${kontak.koordinat_map}&hl=id&z=15&output=embed`" 
          class="w-full h-full border-0 absolute inset-0" 
          allowfullscreen="true" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-8 text-center absolute inset-0">
          <IconMapPin class="w-12 h-12 mb-4 text-gray-300" />
          <p>Koordinat map belum diatur.</p>
        </div>
        
        <!-- Overlay Alamat -->
        <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-sm p-4 rounded-xl shadow-lg border border-gray-100 flex items-center justify-between gap-4">
          <div class="flex items-center text-gray-700 text-sm flex-1">
            <IconMapPin class="w-8 h-8 mr-3 text-[#0F172A] flex-shrink-0" />
            <span class="line-clamp-2 leading-relaxed">{{ kontak.alamat || 'Jl. Halim Perdana Kusuma No. 1, Bangkalan' }}</span>
          </div>
          <a v-if="kontak.koordinat_map" :href="`https://maps.google.com/?q=${kontak.koordinat_map}`" target="_blank" rel="noopener noreferrer" class="flex-shrink-0 bg-[#0F172A] text-white p-3 rounded-xl hover:bg-gray-800 transition-colors shadow-sm" title="Buka di Google Maps">
            <IconArrowRight class="w-5 h-5" />
          </a>
        </div>
      </div>
      
      <!-- Kolom Ranan: Form Kontak -->
      <div class="p-10 lg:p-14 lg:w-1/2 bg-white flex flex-col justify-center kontak-kanan">
        <h3 class="text-2xl font-bold text-[#0F172A] mb-8">Kirim Pesan via Email</h3>
        <form @submit.prevent="submitForm" class="space-y-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
              <input v-model="form.nama" type="text" placeholder="Masukkan nama Anda" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm" required>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
              <input v-model="form.email" type="email" placeholder="email@contoh.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm" required>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek</label>
            <input v-model="form.subjek" type="text" placeholder="Perihal pesan" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm">
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
            <textarea v-model="form.pesan" rows="4" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0F1B3D] focus:border-[#0F1B3D] outline-none transition-all text-sm resize-none" required></textarea>
          </div>
          
          <!-- Google reCAPTCHA widget -->
          <div v-if="page.props.recaptcha_sitekey" class="my-4">
            <div class="g-recaptcha" :data-sitekey="page.props.recaptcha_sitekey"></div>
          </div>
          
          <button type="submit" :disabled="form.processing" class="inline-flex items-center justify-center w-full sm:w-auto gap-2 bg-[#0F1B3D] text-white px-8 py-3.5 rounded-full font-semibold hover:bg-opacity-90 transition-colors shadow-sm disabled:opacity-50">
            <IconSend class="w-5 h-5" v-if="!form.processing" />
            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin" v-else></div>
            Kirim via Email
          </button>
        </form>
      </div>

    </div>
  </section>
</template>
