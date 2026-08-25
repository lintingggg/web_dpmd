<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, reactive, onMounted, onUnmounted, watch } from 'vue';
import { 
  IconMapPin, 
  IconMail, 
  IconBrandWhatsapp, 
  IconBrandFacebook, 
  IconBrandInstagram, 
  IconBrandTiktok,
  IconBrandX, 
  IconBrandYoutube,
  IconMenu2, 
  IconX, 
  IconChevronDown, 
  IconChevronLeft,
  IconChevronRight,
  IconClock,
  IconSend, 
  IconBell, 
  IconFileDescription, 
  IconArrowRight, 
  IconCalendar,
  IconCalendarEvent
} from '@tabler/icons-vue';
import { initHomeAnimations } from '../animations/homeAnimations';
import Navbar from '../Components/Navbar/Navbar.vue';
import Footer from '../Components/Footer.vue';
import UpButton from '../Components/UpButton.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import Modal from '../Components/Modal.vue';
import { router } from '@inertiajs/vue3';
import CardNews from '../Components/CardNews.vue';
import CalendarAgenda from '../Components/CalendarAgenda.vue';
import { usePage, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const FALLBACK_SVG = `<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'>
  <rect width='600' height='400' fill='#e5e7eb'/>
  <g fill='#9ca3af'>
    <path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/>
    <circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/>
  </g>
  <text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text>
</svg>`;
const FALLBACK_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(FALLBACK_SVG)}`;

const getImageUrl = (berita: any) => {
    if (berita.thumbnail_url) return berita.thumbnail_url;
    if (berita.thumbnail) {
        return berita.thumbnail.startsWith('http') ? berita.thumbnail : '/storage/' + berita.thumbnail;
    }
    return FALLBACK_IMAGE;
};

// Sama seperti Index.vue & Detail.vue: fallback ke konten (strip HTML + potong)
// kalau ringkasan/deskripsi/excerpt kosong
const getDescription = (berita: any) => {
    if (berita.ringkasan) return berita.ringkasan;
    if (berita.deskripsi) return berita.deskripsi;
    if (berita.excerpt) return berita.excerpt;
    if (berita.konten) {
        return berita.konten.replace(/<[^>]+>/g, '').substring(0, 120) + '...';
    }
    return '';
};

const props = defineProps<{
  beritaTerkini: any[];
  agendaList: any[];
  galeriHighlight: any[];
  pengaturanBeranda?: any;
  tanggalAdaAcara: string[];
  bulan: number;
  tahun: number;
  timezoneLabel: string;
}>();

const page = usePage();
const kontak = computed(() => (page.props.kontak as any) || {});

// Format date helper
const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const tanggal = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(date);
  const jam = new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit' }).format(date);
  return `${tanggal} • ${jam.replace(':', '.')}`;
};

// State for mobile menu
const isMobileMenuOpen = ref(false);

// State for dropdowns (desktop)
const isProfilOpen = ref(false);
const isBidangOpen = ref(false);

// State for mobile accordions
const isMobileProfilOpen = ref(false);
const isMobileBidangOpen = ref(false);

// Agenda Calendar Month Helper
const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];


// Unused local modal states and methods removed
const selectedDate = ref(null);
const isDetailOpen = ref(false);
const selectedAgenda = ref<any>(null);

const openDetail = (item: any) => {
    selectedAgenda.value = item;
    isDetailOpen.value = true;
};
const closeDetail = () => {
    isDetailOpen.value = false;
    setTimeout(() => { selectedAgenda.value = null; }, 300);
};
const formatFullDate = (dateStr: string) => {
    return new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(dateStr));
};
const getDayNumber = (dateStr: string) => new Date(dateStr).getDate();
const getShortMonth = (dateStr: string) => {
    const shortMonthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];
    return shortMonthNames[new Date(dateStr).getMonth()];
};

// Filtered agenda list by selected date
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

// Data BeritaTerkini sekarang berasal dari props database
const form = useForm({
  nama: '',
  email: '',
  subjek: '',
  pesan: '',
  'g-recaptcha-response': ''
});

// Social Media Tabs State
const socialTabs = computed(() => {
  const tabs = [];
  
  const isShow = (val: any) => {
    if (val === undefined || val === null) return true; // Default to true if not set yet
    return val === 1 || val === '1' || val === true || val === 'true';
  };

  if (isShow(kontak.value.show_instagram) && (kontak.value.instagram_embed_1 || kontak.value.instagram_embed_2)) 
    tabs.push({ id: 'instagram', name: 'Instagram', icon: IconBrandInstagram, color: 'text-[#E4405F]' });
  
  if (isShow(kontak.value.show_tiktok) && (kontak.value.tiktok_embed_1 || kontak.value.tiktok_embed_2)) 
    tabs.push({ id: 'tiktok', name: 'TikTok', icon: IconBrandTiktok, color: 'text-black' });
    
  if (isShow(kontak.value.show_youtube) && (kontak.value.youtube_embed_1 || kontak.value.youtube_embed_2)) 
    tabs.push({ id: 'youtube', name: 'YouTube', icon: IconBrandYoutube, color: 'text-[#FF0000]' });
    
  if (isShow(kontak.value.show_facebook) && (kontak.value.facebook_embed_1 || kontak.value.facebook_embed_2 || kontak.value.facebook_url)) 
    tabs.push({ id: 'facebook', name: 'Facebook', icon: IconBrandFacebook, color: 'text-[#1877F2]' });
    
  if (isShow(kontak.value.show_twitter) && (kontak.value.twitter_embed_1 || kontak.value.twitter_embed_2)) 
    tabs.push({ id: 'twitter', name: 'X (Twitter)', icon: IconBrandX, color: 'text-black' });
    
  return tabs;
});

const activeSocialTab = ref<string>('');

// Set default active tab
watch(socialTabs, (newTabs) => {
  if (newTabs.length > 0 && newTabs.filter(t => t.id === activeSocialTab.value).length === 0) {
    activeSocialTab.value = newTabs[0].id;
  }
}, { immediate: true });

watch(activeSocialTab, (newTab) => {
  if (newTab === 'twitter') {
    setTimeout(() => {
      if ((window as any).twttr && (window as any).twttr.widgets) {
        (window as any).twttr.widgets.load();
      }
    }, 100);
  }
  if (newTab === 'facebook') {
    setTimeout(() => {
      if ((window as any).FB && (window as any).FB.XFBML) {
        (window as any).FB.XFBML.parse();
      }
    }, 100);
  }
});

const renderEmbed = (input: string) => {
  if (!input) return '';
  const str = input.trim();
  
  let extractedUrl = str;
  if (str.startsWith('<')) {
    // If it's already an iframe, return as is
    if (str.match(/<iframe/i)) {
      return str; 
    }
    
    // For Facebook and Twitter, if they provide HTML (like blockquote or div), 
    // we should return it directly because we have their SDKs loaded.
    if (str.match(/twitter-tweet/i) || str.match(/fb-post|fb-video|fb-page/i) || str.includes('fb-xfbml-parse-ignore')) {
      return str;
    }
    
    // Extract Instagram link from blockquote
    const igMatch = str.match(/href=["'](https:\/\/(?:www\.)?instagram\.com\/[^"']+)["']/);
    if (igMatch) extractedUrl = igMatch[1];

    
    // Extract TikTok link
    const tkMatch = str.match(/cite=["'](https:\/\/(?:www\.)?tiktok\.com\/[^"']+)["']/);
    if (tkMatch) extractedUrl = tkMatch[1];
    
    // If we didn't extract anything and it's raw HTML, just return it
    if (extractedUrl === str) {
      return str;
    }
  }
  
  if (extractedUrl.includes('instagram.com/')) {
    let url = extractedUrl.split('?')[0]; 
    if (!url.endsWith('/')) url += '/';
    return `<iframe src="${url}embed" width="100%" height="480" frameborder="0" scrolling="no" allowtransparency="true" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
  }
  
  if (extractedUrl.includes('tiktok.com/')) {
    const match = extractedUrl.match(/video\/(\d+)|photo\/(\d+)/);
    const id = match ? (match[1] || match[2]) : null;
    if (id) {
      return `<iframe src="https://www.tiktok.com/embed/v2/${id}" width="100%" height="600" frameborder="0" scrolling="no" allow="encrypted-media;" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
    }
  }

  if (extractedUrl.includes('youtube.com/') || extractedUrl.includes('youtu.be/')) {
    let videoId = '';
    if (extractedUrl.includes('youtu.be/')) {
      videoId = extractedUrl.split('youtu.be/')[1].split('?')[0];
    } else if (extractedUrl.includes('youtube.com/watch')) {
      try {
        const urlObj = new URL(extractedUrl);
        videoId = urlObj.searchParams.get('v') || '';
      } catch (e) {}
    } else if (extractedUrl.includes('youtube.com/shorts/')) {
      videoId = extractedUrl.split('youtube.com/shorts/')[1].split('?')[0];
    }
    if (videoId) {
      return `<iframe src="https://www.youtube.com/embed/${videoId}" width="100%" height="400" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
    }
  }

  if (extractedUrl.includes('facebook.com/')) {
    let url = extractedUrl.trim();
    
    // Facebook Embed API (both iframe and SDK) strictly rejects mobile share shortlinks (share/r, share/v, share/p).
    // It returns a blank white screen. We must warn the user to use the canonical URL.
    if (/\/(?:share\/[rvp])\//i.test(url)) {
      return `
        <div class="w-full flex items-center justify-center bg-[#fff8e1] rounded-2xl border border-[#ffe082] min-h-[400px] max-w-[400px] p-6 text-center">
          <div>
            <div class="inline-flex items-center justify-center p-3 bg-[#ffecb3] rounded-full mb-4">
              <span class="material-symbols-outlined text-[#f57c00] text-3xl">warning</span>
            </div>
            <h3 class="text-lg font-bold text-[#f57c00] mb-2">Tautan Facebook Tidak Mendukung Embed</h3>
            <p class="text-[#5d4037] text-sm">
              Tautan pendek dari tombol bagikan HP (<strong>share/r</strong>, <strong>share/v</strong>) diblokir oleh Facebook untuk fitur embed.<br><br>
              Mohon salin <strong>URL asli (dari address bar browser di komputer)</strong> atau gunakan <strong>Kode Embed Iframe</strong> dari Facebook.
            </p>
          </div>
        </div>
      `;
    }
    
    // determine if it's a page or post
    const isPage = !/\/(?:posts|photos|videos|reel|share\/p|share\/v|watch)\/|\/(?:permalink|story|photo|video)\.php|fbid=/i.test(url);
    if (isPage) {
      return `<iframe src="https://www.facebook.com/plugins/page.php?href=${encodeURIComponent(url)}&tabs=timeline&width=500&height=600" width="100%" height="600" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
    }
    
    const isVideo = /\/(?:videos|reel|reels|watch|share\/v|share\/r)\/|\/(?:video)\.php/i.test(url);
    if (isVideo) {
      return `<iframe src="https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(url)}&show_text=false&width=500" width="100%" height="700" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
    }
    
    // For all other posts, use the post.php iframe endpoint
    return `<iframe src="https://www.facebook.com/plugins/post.php?href=${encodeURIComponent(url)}&show_text=true&width=500" width="100%" height="600" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
  }

  // Remove the old Twitter URL iframe fallback here so Twitter ONLY renders native embeds
  return extractedUrl;
};

const openLink = (url: string) => {
  window.open(url, '_blank');
};

const submitForm = () => {
  const token = (window as any).grecaptcha ? (window as any).grecaptcha.getResponse() : '';
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
      if ((window as any).grecaptcha) {
        (window as any).grecaptcha.reset();
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

// Close dropdowns on outside click for better UX
const closeDropdowns = (e: Event) => {
  if (!(e.target as HTMLElement).closest('.dropdown-container')) {
    isProfilOpen.value = false;
    isBidangOpen.value = false;
  }
};

onMounted(() => {
  // Load Google reCAPTCHA script
  if (!document.getElementById('recaptcha-script')) {
    const script = document.createElement('script');
    script.id = 'recaptcha-script';
    script.src = 'https://www.google.com/recaptcha/api.js';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  }

  document.addEventListener('click', closeDropdowns);
  // Initialize GSAP animations
  initHomeAnimations();

  // Load Twitter SDK if not loaded
  if (!document.getElementById('twitter-wjs')) {
    const script = document.createElement('script');
    script.id = 'twitter-wjs';
    script.src = 'https://platform.twitter.com/widgets.js';
    script.async = true;
    script.onload = () => {
      if (activeSocialTab.value === 'twitter' && (window as any).twttr) {
        (window as any).twttr.widgets.load();
      }
    };
    document.body.appendChild(script);
  }

  // Load Facebook SDK if not loaded
  if (!document.getElementById('facebook-jssdk')) {
    const script = document.createElement('script');
    script.id = 'facebook-jssdk';
    script.src = 'https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v17.0';
    script.async = true;
    script.defer = true;
    script.crossOrigin = 'anonymous';
    script.onload = () => {
      if (activeSocialTab.value === 'facebook' && (window as any).FB) {
        (window as any).FB.XFBML.parse();
      }
    };
    document.body.appendChild(script);
  }
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});
</script>

<template>
  <Head title="Beranda - DPMD Bangkalan" />

  <div class="min-h-screen bg-gray-50 font-sans text-gray-800">
    <!-- NAVBAR -->
    <Navbar />

    <!-- HERO SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
      <div class="flex flex-col lg:flex-row items-center gap-12">
        
        <!-- TEXT CONTENT -->
        <div class="lg:w-5/12 flex flex-col justify-center z-10">
          <h1 class="text-4xl lg:text-[2.75rem] font-extrabold text-[#0F172A] leading-[1.15] mb-6 overflow-hidden">
            <span class="inline-block hero-title-inner">{{ props.pengaturanBeranda?.hero_title || 'Membangun Desa Bangkalan yang Mandiri dan Sejahtera.' }}</span>
          </h1>
          <div 
            class="text-[#646A79] text-lg mb-10 leading-relaxed hero-fade-up font-medium"
            v-html="props.pengaturanBeranda?.hero_description || 'Dinas Pemberdayaan Masyarakat dan Desa (DPMD) Kabupaten Bangkalan berkomitmen penuh dalam mendorong kemajuan potensi desa di seluruh wilayah Bangkalan.'"
          ></div>
        </div>

        <!-- IMAGE COLLAGE -->
        <div class="lg:w-7/12 relative min-h-[400px] sm:min-h-[500px] lg:min-h-[600px] w-full flex items-center justify-center p-8 bg-transparent">
          
          <div class="relative w-full max-w-[600px] h-full flex items-center justify-center">
            
            <!-- Image 1 (Left/Back) -->
            <div class="absolute left-[0%] top-[15%] w-2/5 aspect-[4/5] rounded-3xl overflow-hidden shadow-xl border-4 border-[#FFFFFF] hero-img-1 z-0 -rotate-6">
              <img :src="props.pengaturanBeranda?.hero_image_1 ? '/storage/' + props.pengaturanBeranda.hero_image_1 : '/assets/Pengukuhan TP. PKK Kecamatan Kabupaten Bangkalan.jpg.jpeg'" alt="Kegiatan 1" class="w-full h-full object-cover" />
            </div>

            <!-- Image 2 (Right/Back) -->
            <div class="absolute right-[0%] top-[30%] w-2/5 aspect-[4/5] rounded-3xl overflow-hidden shadow-xl border-4 border-[#FFFFFF] hero-img-2 z-0 rotate-6">
              <img :src="props.pengaturanBeranda?.hero_image_2 ? '/storage/' + props.pengaturanBeranda.hero_image_2 : '/assets/PKK Mengikuti Seminar Bagi Perempuan Dalam Menghadapi Era Digital.jpg.jpeg'" alt="Kegiatan 2" class="w-full h-full object-cover" />
            </div>

            <!-- Image 3 (Center/Front) -->
            <div class="relative w-1/2 aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl border-4 border-[#FFFFFF] hero-img-3 z-10 translate-y-[-10%]">
              <img :src="props.pengaturanBeranda?.hero_image_3 ? '/storage/' + props.pengaturanBeranda.hero_image_3 : '/assets/Rapat Pleno PKK.jpg.jpeg'" alt="Kegiatan 3" class="w-full h-full object-cover" />
            </div>

          </div>
        </div>

      </div>
    </section>

    <!-- BERITA TERKINI -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 section-berita">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 border-b border-gray-200 pb-4 section-header">
        <div>
          <h2 class="text-3xl font-bold text-[#0F172A] mb-2">Berita Terkini</h2>
          <p class="text-[#646A79]">Informasi terbaru seputar kegiatan dan program DPMD Bangkalan.</p>
        </div>
        <a href="/berita" class="mt-4 sm:mt-0 flex items-center text-sm font-semibold text-[#0F172A] hover:underline">
          Lihat Semua
          <IconArrowRight class="ml-1 w-4 h-4" />
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 card-container" v-if="beritaTerkini && beritaTerkini.length > 0">
        <Link
          v-for="berita in beritaTerkini"
          :key="berita.id"
          :href="`/berita/${berita.slug}`"
          class="block h-full rounded-3xl transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl cursor-pointer card-item"
        >
          <CardNews
            :title="berita.judul"
            :description="getDescription(berita)"
            :date="formatDate(berita.published_at)"
            :author="berita.penulis || 'Humas DPMD'"
            :tags="berita.tags"
            :image="getImageUrl(berita)"
          />
        </Link>
      </div>
      <div v-else class="text-center py-12 text-[#646A79] border border-dashed border-gray-200 rounded-xl">
        Belum ada berita terbaru.
      </div>
    </section>

    <!-- AGENDA MENDATANG -->
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
                v-slot:default
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

    <!-- SOSIAL MEDIA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 section-sosmed">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 border-b border-gray-200 pb-4 section-header">
        <div>
          <h2 class="text-3xl font-bold text-[#0F172A] mb-2">Media Sosial Resmi</h2>
          <p class="text-[#646A79]">Ikuti berbagai kegiatan, dokumentasi asli, dan informasi terbaru melalui kanal media sosial resmi DPMD Kabupaten Bangkalan.</p>
        </div>
      </div>

      <div class="flex flex-col md:flex-row gap-8 max-w-6xl mx-auto items-start">
        <!-- Sidebar Tabs -->
        <div class="w-full md:w-1/3 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <button 
            v-for="tab in socialTabs" :key="tab.id"
            @click="activeSocialTab = tab.id"
            :class="[
              'flex items-center gap-4 px-6 py-5 text-left font-semibold transition-colors duration-200 border-l-4',
              activeSocialTab === tab.id 
                ? 'bg-[#0056b3] text-white border-transparent' 
                : 'text-[#0F172A] hover:bg-gray-50 border-transparent border-b-gray-100'
            ]"
          >
            <div :class="[
              'p-2 rounded-full flex-shrink-0 flex items-center justify-center',
              activeSocialTab === tab.id ? 'bg-white text-gray-900' : 'bg-gray-100 ' + tab.color
            ]">
              <component :is="tab.icon" class="w-5 h-5" />
            </div>
            {{ tab.name }}
          </button>
        </div>

        <!-- Content Area -->
        <div class="w-full md:w-2/3 bg-transparent min-h-[500px]">
          
          <!-- Instagram Embed -->
          <div v-show="activeSocialTab === 'instagram'" class="w-full flex flex-col gap-6 slide-in-tab">
             <div v-if="!kontak.instagram_embed_1 && !kontak.instagram_embed_2" class="w-full flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[400px]">
                 <div class="text-center p-8">
                   <div class="inline-flex items-center justify-center p-4 bg-gray-50 rounded-full mb-4">
                     <IconFileDescription class="w-8 h-8 text-gray-400" />
                   </div>
                   <h3 class="text-xl font-bold text-[#0F172A] mb-2">Belum Ada Konten</h3>
                   <p class="text-[#646A79]">Konten Instagram sedang dalam persiapan.</p>
                 </div>
             </div>
             <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center">
                <div v-if="kontak.instagram_embed_1" v-html="renderEmbed(kontak.instagram_embed_1)" class="w-full flex justify-center"></div>
                <div v-if="kontak.instagram_embed_2" v-html="renderEmbed(kontak.instagram_embed_2)" class="w-full flex justify-center"></div>
             </div>
             <div v-if="kontak.instagram_url" class="flex justify-center mt-4">
               <PrimaryButton type="button" @click="openLink(kontak.instagram_url)">
                 <IconBrandInstagram class="w-5 h-5 mr-1" />
                 Kunjungi Instagram Kami
               </PrimaryButton>
             </div>
          </div>
          
          <!-- TikTok Embed -->
          <div v-show="activeSocialTab === 'tiktok'" class="w-full flex flex-col gap-6 slide-in-tab">
             <div v-if="!kontak.tiktok_embed_1 && !kontak.tiktok_embed_2" class="w-full flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[400px]">
                 <div class="text-center p-8">
                   <div class="inline-flex items-center justify-center p-4 bg-gray-50 rounded-full mb-4">
                     <IconFileDescription class="w-8 h-8 text-gray-400" />
                   </div>
                   <h3 class="text-xl font-bold text-[#0F172A] mb-2">Belum Ada Konten</h3>
                   <p class="text-[#646A79]">Konten TikTok sedang dalam persiapan.</p>
                 </div>
             </div>
             <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center">
                <div v-if="kontak.tiktok_embed_1" v-html="renderEmbed(kontak.tiktok_embed_1)" class="w-full flex justify-center"></div>
                <div v-if="kontak.tiktok_embed_2" v-html="renderEmbed(kontak.tiktok_embed_2)" class="w-full flex justify-center"></div>
             </div>
             <div v-if="kontak.tiktok_url" class="flex justify-center mt-4">
               <PrimaryButton type="button" @click="openLink(kontak.tiktok_url)">
                 <IconBrandTiktok class="w-5 h-5 mr-1" />
                 Kunjungi TikTok Kami
               </PrimaryButton>
             </div>
          </div>

          <!-- YouTube Embed -->
          <div v-show="activeSocialTab === 'youtube'" class="w-full flex flex-col gap-6 slide-in-tab">
             <div v-if="!kontak.youtube_embed_1 && !kontak.youtube_embed_2" class="w-full flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[400px]">
                 <div class="text-center p-8">
                   <div class="inline-flex items-center justify-center p-4 bg-gray-50 rounded-full mb-4">
                     <IconFileDescription class="w-8 h-8 text-gray-400" />
                   </div>
                   <h3 class="text-xl font-bold text-[#0F172A] mb-2">Belum Ada Konten</h3>
                   <p class="text-[#646A79]">Konten YouTube sedang dalam persiapan.</p>
                 </div>
             </div>
             <div v-else class="grid grid-cols-1 gap-6 w-full justify-items-center">
                <div v-if="kontak.youtube_embed_1" v-html="renderEmbed(kontak.youtube_embed_1)" class="w-full flex justify-center"></div>
                <div v-if="kontak.youtube_embed_2" v-html="renderEmbed(kontak.youtube_embed_2)" class="w-full flex justify-center"></div>
             </div>
             <div v-if="kontak.youtube_url" class="flex justify-center mt-4">
               <PrimaryButton type="button" @click="openLink(kontak.youtube_url)">
                 <IconBrandYoutube class="w-5 h-5 mr-1" />
                 Kunjungi YouTube Kami
               </PrimaryButton>
             </div>
          </div>

          <!-- Facebook Embed -->
          <div v-show="activeSocialTab === 'facebook'" class="w-full flex flex-col gap-6 slide-in-tab">
             <div v-if="!kontak.facebook_embed_1 && !kontak.facebook_embed_2" class="w-full flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[400px]">
                 <div class="text-center p-8">
                   <div class="inline-flex items-center justify-center p-4 bg-gray-50 rounded-full mb-4">
                     <IconFileDescription class="w-8 h-8 text-gray-400" />
                   </div>
                   <h3 class="text-xl font-bold text-[#0F172A] mb-2">Belum Ada Konten</h3>
                   <p class="text-[#646A79]">Konten Facebook sedang dalam persiapan.</p>
                 </div>
             </div>
             <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center">
                <div v-if="kontak.facebook_embed_1" v-html="renderEmbed(kontak.facebook_embed_1)" class="w-full flex justify-center"></div>
                <div v-if="kontak.facebook_embed_2" v-html="renderEmbed(kontak.facebook_embed_2)" class="w-full flex justify-center"></div>
             </div>
             <div v-if="kontak.facebook_url" class="flex justify-center mt-4">
               <PrimaryButton type="button" @click="openLink(kontak.facebook_url)">
                 <IconBrandFacebook class="w-5 h-5 mr-1" />
                 Kunjungi Facebook Kami
               </PrimaryButton>
             </div>
          </div>

          <!-- Twitter Embed -->
          <div v-show="activeSocialTab === 'twitter'" class="w-full flex flex-col gap-6 slide-in-tab">
             <div v-if="!kontak.twitter_embed_1 && !kontak.twitter_embed_2" class="w-full flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[400px]">
                 <div class="text-center p-8">
                   <div class="inline-flex items-center justify-center p-4 bg-gray-50 rounded-full mb-4">
                     <IconFileDescription class="w-8 h-8 text-gray-400" />
                   </div>
                   <h3 class="text-xl font-bold text-[#0F172A] mb-2">Belum Ada Konten</h3>
                   <p class="text-[#646A79]">Konten Twitter/X sedang dalam persiapan.</p>
                 </div>
             </div>
             <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center">
                <div v-if="kontak.twitter_embed_1" v-html="renderEmbed(kontak.twitter_embed_1)" class="w-full flex justify-center"></div>
                <div v-if="kontak.twitter_embed_2" v-html="renderEmbed(kontak.twitter_embed_2)" class="w-full flex justify-center"></div>
             </div>
             <div v-if="kontak.twitter_url" class="flex justify-center mt-4">
               <PrimaryButton type="button" @click="openLink(kontak.twitter_url)">
                 <IconBrandX class="w-5 h-5 mr-1" />
                 Kunjungi X (Twitter) Kami
               </PrimaryButton>
             </div>
          </div>

          <!-- Other Empty States -->
          <div v-show="['instagram', 'tiktok', 'youtube', 'facebook', 'twitter'].indexOf(activeSocialTab) === -1" class="w-full flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[500px]">
             <div class="text-center p-8">
               <div class="inline-flex items-center justify-center p-4 bg-gray-50 rounded-full mb-4">
                 <IconFileDescription class="w-8 h-8 text-gray-400" />
               </div>
               <h3 class="text-xl font-bold text-[#0F172A] mb-2">Belum Ada Konten</h3>
               <p class="text-[#646A79]">Konten dari platform ini sedang dalam persiapan.</p>
             </div>
          </div>

        </div>
      </div>
    </section>

    <!-- HUBUNGI KAMI & LOKASI -->
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
        
        <!-- Kolom Kanan: Form Kontak -->
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

    <!-- FOOTER -->
    <Footer />



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

    <UpButton />
  </div>
</template>
