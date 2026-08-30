<script setup>
import { ref, computed, watch } from 'vue';
import { 
  IconBrandInstagram, 
  IconBrandTiktok, 
  IconBrandYoutube, 
  IconBrandFacebook, 
  IconBrandX,
  IconFileDescription
} from '@tabler/icons-vue';
import PrimaryButton from '../PrimaryButton.vue';
import { renderEmbed, openLink } from '../../Utils/helpers';

const props = defineProps({
  kontak: Object
});

const socialTabs = computed(() => {
  const tabs = [];
  
  const isShow = (val) => {
    if (val === undefined || val === null) return true;
    return val === 1 || val === '1' || val === true || val === 'true';
  };

  if (isShow(props.kontak.show_instagram) && (props.kontak.instagram_embed_1 || props.kontak.instagram_embed_2)) 
    tabs.push({ id: 'instagram', name: 'Instagram', icon: IconBrandInstagram, color: 'text-[#E4405F]' });
  
  if (isShow(props.kontak.show_tiktok) && (props.kontak.tiktok_embed_1 || props.kontak.tiktok_embed_2)) 
    tabs.push({ id: 'tiktok', name: 'TikTok', icon: IconBrandTiktok, color: 'text-black' });
    
  if (isShow(props.kontak.show_youtube) && (props.kontak.youtube_embed_1 || props.kontak.youtube_embed_2)) 
    tabs.push({ id: 'youtube', name: 'YouTube', icon: IconBrandYoutube, color: 'text-[#FF0000]' });
    
  if (isShow(props.kontak.show_facebook) && (props.kontak.facebook_embed_1 || props.kontak.facebook_embed_2 || props.kontak.facebook_url)) 
    tabs.push({ id: 'facebook', name: 'Facebook', icon: IconBrandFacebook, color: 'text-[#1877F2]' });
    
  if (isShow(props.kontak.show_twitter) && (props.kontak.twitter_embed_1 || props.kontak.twitter_embed_2)) 
    tabs.push({ id: 'twitter', name: 'X (Twitter)', icon: IconBrandX, color: 'text-black' });
    
  return tabs;
});

const activeSocialTab = ref('');

watch(socialTabs, (newTabs) => {
  if (newTabs.length > 0 && newTabs.filter(t => t.id === activeSocialTab.value).length === 0) {
    activeSocialTab.value = newTabs[0].id;
  }
}, { immediate: true });

const sdkLoaded = { twitter: false, facebook: false };

function loadTwitterSDK() {
  if (sdkLoaded.twitter || document.getElementById('twitter-wjs')) {
    sdkLoaded.twitter = true;
    return;
  }
  const script = document.createElement('script');
  script.id = 'twitter-wjs';
  script.src = 'https://platform.twitter.com/widgets.js';
  script.async = true;
  script.onload = () => {
    sdkLoaded.twitter = true;
    if (window.twttr) window.twttr.widgets.load();
  };
  document.body.appendChild(script);
}

function loadFacebookSDK() {
  if (sdkLoaded.facebook || document.getElementById('facebook-jssdk')) {
    sdkLoaded.facebook = true;
    // Parse jika SDK sudah ada tapi tab baru diklik
    setTimeout(() => {
      if (window.FB && window.FB.XFBML) window.FB.XFBML.parse();
    }, 100);
    return;
  }
  const script = document.createElement('script');
  script.id = 'facebook-jssdk';
  script.src = 'https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v17.0';
  script.async = true;
  script.defer = true;
  script.crossOrigin = 'anonymous';
  script.onload = () => {
    sdkLoaded.facebook = true;
    if (window.FB && window.FB.XFBML) window.FB.XFBML.parse();
  };
  document.body.appendChild(script);
}

watch(activeSocialTab, (newTab) => {
  if (newTab === 'twitter') {
    loadTwitterSDK();
    // Jika sudah terload, render ulang
    setTimeout(() => {
      if (window.twttr && window.twttr.widgets) window.twttr.widgets.load();
    }, 100);
  }
  if (newTab === 'facebook') {
    loadFacebookSDK();
  }
});
</script>

<template>
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
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center" v-else>
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
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center" v-else>
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
           <div class="grid grid-cols-1 gap-6 w-full justify-items-center" v-else>
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
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center" v-else>
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
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full justify-items-center" v-else>
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
</template>
