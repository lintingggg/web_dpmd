<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, computed, nextTick } from 'vue';
import { initHomeAnimations } from '../animations/homeAnimations';
import Navbar from '../Components/Navbar/Navbar.vue';
import Footer from '../Components/Footer.vue';
import UpButton from '../Components/UpButton.vue';

// Import Domain Components
import HomeHero from '../Components/Home/HomeHero.vue';
import HomeNews from '../Components/Home/HomeNews.vue';
import HomeAgenda from '../Components/Home/HomeAgenda.vue';
import HomeSocial from '../Components/Home/HomeSocial.vue';
import HomeContact from '../Components/Home/HomeContact.vue';

const props = defineProps({
  beritaTerkini: Array,
  agendaList: Array,
  galeriHighlight: Array,
  pengaturanBeranda: Object,
  tanggalAdaAcara: Array,
  bulan: Number,
  tahun: Number,
  timezoneLabel: String
});

const page = usePage();
const kontak = computed(() => page.props.kontak || {});

onMounted(() => {
  nextTick(() => {
    // Initialize GSAP animations
    initHomeAnimations();

    // Lazy-load reCAPTCHA hanya saat user mendekati section kontak
    // Ini menghemat load time untuk user yang tidak scroll ke bawah
    const contactSection = document.querySelector('#kontak');
    if (contactSection) {
      const observer = new IntersectionObserver(
        (entries) => {
          if (entries[0].isIntersecting) {
            if (!document.getElementById('recaptcha-script')) {
              const script = document.createElement('script');
              script.id = 'recaptcha-script';
              script.src = 'https://www.google.com/recaptcha/api.js';
              script.async = true;
              script.defer = true;
              document.head.appendChild(script);
            }
            observer.disconnect(); // Hanya load sekali
          }
        },
        { rootMargin: '200px' } // Preload 200px sebelum section masuk viewport
      );
      observer.observe(contactSection);
    }
  });
});
</script>

<template>
  <Head title="Beranda - DPMD Bangkalan" />

  <div class="min-h-screen bg-gray-50 font-sans text-gray-800">
    <!-- NAVBAR -->
    <Navbar />

    <!-- HERO SECTION -->
    <HomeHero :pengaturan-beranda="props.pengaturanBeranda" />

    <!-- BERITA TERKINI -->
    <HomeNews :berita-terkini="props.beritaTerkini" />

    <!-- AGENDA MENDATANG -->
    <HomeAgenda 
      :agenda-list="props.agendaList"
      :tanggal-ada-acara="props.tanggalAdaAcara"
      :bulan="props.bulan"
      :tahun="props.tahun"
      :timezone-label="props.timezoneLabel"
    />

    <!-- SOSIAL MEDIA -->
    <HomeSocial :kontak="kontak" />

    <!-- HUBUNGI KAMI & LOKASI -->
    <HomeContact :kontak="kontak" />

    <!-- FOOTER -->
    <Footer />

    <UpButton />
  </div>
</template>
