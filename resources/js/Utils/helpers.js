const FALLBACK_SVG = `<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'>
  <rect width='600' height='400' fill='#e5e7eb'/>
  <g fill='#9ca3af'>
    <path d='M255 165h90l15 25h35a10 10 0 0 1 10 10v95a10 10 0 0 1-10 10H235a10 10 0 0 1-10-10v-95a10 10 0 0 1 10-10h35z' fill='none' stroke='#9ca3af' stroke-width='6'/>
    <circle cx='300' cy='245' r='28' fill='none' stroke='#9ca3af' stroke-width='6'/>
  </g>
  <text x='300' y='320' font-family='sans-serif' font-size='18' fill='#9ca3af' text-anchor='middle'>Gambar tidak tersedia</text>
</svg>`;
export const FALLBACK_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(FALLBACK_SVG)}`;

export const getImageUrl = (berita) => {
    if (!berita) return FALLBACK_IMAGE;
    if (berita.thumbnail_url) return berita.thumbnail_url;
    if (berita.thumbnail) {
        return berita.thumbnail.startsWith('http') ? berita.thumbnail : '/storage/' + berita.thumbnail;
    }
    return FALLBACK_IMAGE;
};

export const getDescription = (berita) => {
    if (!berita) return '';
    if (berita.ringkasan) return berita.ringkasan;
    if (berita.deskripsi) return berita.deskripsi;
    if (berita.excerpt) return berita.excerpt;
    if (berita.konten) {
        return berita.konten.replace(/<[^>]+>/g, '').substring(0, 120) + '...';
    }
    return '';
};

export const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const tanggal = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(date);
  const jam = new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit' }).format(date);
  return `${tanggal} • ${jam.replace(':', '.')}`;
};

export const formatFullDate = (dateStr) => {
    if (!dateStr) return '';
    return new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(dateStr));
};

export const getDayNumber = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).getDate();
};

export const getShortMonth = (dateStr) => {
    if (!dateStr) return '';
    const shortMonthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];
    return shortMonthNames[new Date(dateStr).getMonth()];
};

export const openLink = (url) => {
  if (url) window.open(url, '_blank');
};

export const renderEmbed = (input) => {
  if (!input) return '';
  const str = input.trim();
  
  let extractedUrl = str;
  if (str.startsWith('<')) {
    if (str.match(/<iframe/i)) {
      return str; 
    }
    
    if (str.match(/twitter-tweet/i) || str.match(/fb-post|fb-video|fb-page/i) || str.includes('fb-xfbml-parse-ignore')) {
      return str;
    }
    
    const igMatch = str.match(/href=["'](https:\/\/(?:www\.)?instagram\.com\/[^"']+)["']/);
    if (igMatch) extractedUrl = igMatch[1];
    
    const tkMatch = str.match(/cite=["'](https:\/\/(?:www\.)?tiktok\.com\/[^"']+)["']/);
    if (tkMatch) extractedUrl = tkMatch[1];
    
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
    
    const isPage = !/\/(?:posts|photos|videos|reel|share\/p|share\/v|watch)\/|\/(?:permalink|story|photo|video)\.php|fbid=/i.test(url);
    if (isPage) {
      return `<iframe src="https://www.facebook.com/plugins/page.php?href=${encodeURIComponent(url)}&tabs=timeline&width=500&height=600" width="100%" height="600" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
    }
    
    const isVideo = /\/(?:videos|reel|reels|watch|share\/v|share\/r)\/|\/(?:video)\.php/i.test(url);
    if (isVideo) {
      return `<iframe src="https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(url)}&show_text=false&width=500" width="100%" height="700" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
    }
    
    return `<iframe src="https://www.facebook.com/plugins/post.php?href=${encodeURIComponent(url)}&show_text=true&width=500" width="100%" height="600" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" class="rounded-xl border border-gray-200 max-w-[400px]"></iframe>`;
  }

  return extractedUrl;
};
