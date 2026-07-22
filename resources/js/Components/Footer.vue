<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import {
  IconBrandFacebook,
  IconBrandInstagram,
  IconBrandFacebook,
  IconBrandYoutube,
  IconBrandTwitter,
  IconBrandWhatsapp,
  IconChevronRight,
  IconMapPin,
  IconPhone,
  IconMail,
} from "@tabler/icons-vue";

// Logo diambil dari folder public/assets (bukan resources/assets),
// jadi cukup pakai path absolut, tidak perlu di-import.
const logoImg = "/assets/Logo-kabupaten-Bangkalan.png";

const year = new Date().getFullYear();

const navItems = [
  { type: "link", title: "Beranda", href: "/" },
  {
    type: "submenu",
    title: "Profil",
    href: "#",
    links: [
      ["Sambutan Kepala Dinas", "#"],
      ["Visi & Misi", "#"],
      ["Tugas Pokok & Fungsi", "#"],
      ["Struktur Organisasi", "#"],
      ["Motto Pelayanan", "#"],
      ["Maklumat Pelayanan", "#"],
      ["Kode Etik Pelayanan", "#"],
    ],
  },
  {
    type: "submenu",
    title: "Bidang",
    href: "#",
    links: [
      ["Sekretariat", "#"],
      ["Pemerintahan Desa", "#"],
      ["Pemberdayaan Desa", "#"],
      ["Pemberdayaan Lembaga Kemasyarakatan", "#"],
    ],
  },
  { type: "link", title: "Dokumen", href: "/dokumen" },
  { type: "link", title: "Galeri", href: "/galeri" },
  { type: "link", title: "Berita", href: "/berita" },
];

const socialLinks = [
  { label: "Instagram", href: "#", icon: IconBrandInstagram },
  { label: "Facebook", href: "#", icon: IconBrandFacebook },
  { label: "YouTube", href: "#", icon: IconBrandYoutube },
  { label: "Twitter", href: "#", icon: IconBrandTwitter },
  { label: "WhatsApp", href: "#", icon: IconBrandWhatsapp },
];

const hoverKey = ref(null);

function toggleHover(title) {
  hoverKey.value = title;
}

// ===== ANIMASI SAAT SCROLL MENTOK BAWAH (footer masuk/keluar viewport) =====
const footerRef = ref(null);
const isVisible = ref(false);
let observer = null;

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        isVisible.value = entry.isIntersecting;
      });
    },
    {
      threshold: 0.15,
      rootMargin: "0px 0px -10% 0px",
    }
  );

  if (footerRef.value) {
    observer.observe(footerRef.value);
  }
});

onBeforeUnmount(() => {
  if (observer) observer.disconnect();
});
</script>

<template>
  <footer class="footer" ref="footerRef">
    <div class="footer-inner" :class="{ 'is-visible': isVisible }">

      <div class="top-row">

        <!-- Logo & Description -->
        <div class="brand-col">
          <div class="brand-header">
            <img :src="logoImg" alt="Logo DPMD" class="brand-logo" />
            <div class="brand-text">
              <span class="brand-label">Website Resmi</span>
              <h4 class="brand-title">Dinas Pemberdayaan Masyarakat dan Desa</h4>
              <span class="brand-subtitle">Kabupaten Bangkalan</span>
            </div>
          </div>

          <p class="brand-desc">
            Portal Digital DPMD, media informasi, layanan, dan publikasi
            resmi kepada masyarakat.
          </p>

          <div class="social-row">
            <a
              v-for="social in socialLinks"
              :key="social.label"
              :href="social.href"
              class="social-link"
              :aria-label="social.label"
            >
              <component :is="social.icon" :size="16" />
            </a>
          </div>
        </div>

        <!-- MENU (flat list + hover popup) -->
        <div class="menu-grid">
          <h5 class="col-title">Navigasi</h5>

          <div class="nav-list">

            <template v-for="item in navItems" :key="item.title">

              <a v-if="item.type === 'link'" :href="item.href" class="nav-row">
                <span class="footer-nav-link">
                  <span class="bullet"></span>
                  {{ item.title }}
                </span>
              </a>

              <div
                v-else
                class="nav-row has-submenu"
                :class="{ 'is-hover': hoverKey === item.title }"
                @mouseenter="toggleHover(item.title)"
                @mouseleave="hoverKey = null"
              >
                <a :href="item.href" class="footer-nav-link">
                  <span class="bullet"></span>
                  {{ item.title }}
                </a>
                <IconChevronRight :size="14" class="chevron" />

                <Transition name="popup">
                  <div v-if="hoverKey === item.title" class="submenu-popup">
                    <ul class="submenu-list">
                      <li v-for="link in item.links" :key="link[0]">
                        <a :href="link[1]" class="submenu-link">
                          <span class="bullet"></span>
                          {{ link[0] }}
                        </a>
                      </li>
                    </ul>
                  </div>
                </Transition>
              </div>

            </template>

          </div>
        </div>

        <!-- KONTAK INFO -->
        <div class="kontak-block">
          <h5 class="col-title">Hubungi Kami</h5>
          <div class="kontak-col">

            <div class="kontak-item">
              <span class="kontak-icon"><IconMapPin :size="17" /></span>
              <div class="kontak-value">
                <span class="kontak-text">Dinas Pemberdayaan Masyarakat dan Desa</span>
              </div>
            </div>

            <div class="kontak-item">
              <span class="kontak-icon"><IconPhone :size="17" /></span>
              <div class="kontak-value">
                <span class="kontak-label">Telepon</span>
                <span class="kontak-text">(031) xxxx-xxxx</span>
              </div>
            </div>

            <div class="kontak-item">
              <span class="kontak-icon"><IconMail :size="17" /></span>
              <div class="kontak-value">
                <span class="kontak-label">Email</span>
                <span class="kontak-text">info@dpmd.go.id</span>
              </div>
            </div>

          </div>
        </div>

      </div>

      <div class="bottom-block">
        <span class="watermark">Bangkalan.</span>
        <span class="copyright">© {{ year }} Portal DPMD Kabupaten Bangkalan. Seluruh hak cipta dilindungi.</span>
      </div>
    </div>
  </footer>
</template>

<style scoped>
* { box-sizing: border-box; }

.footer {
  font-family: "Plus Jakarta Sans", sans-serif;
  background: #0f1b3d;
  padding: 32px 24px 0;
  overflow: hidden;
}

.footer-inner {
  max-width: 1280px;
  margin: 0 auto;

  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
              transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
.footer-inner.is-visible {
  opacity: 1;
  transform: translateY(0);
}

/* ===== TOP ===== */
.top-row {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media (min-width: 768px) {
  .top-row {
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
    gap: 40px;
  }
}

.brand-col { max-width: 380px; flex: 1 1 300px; }
.brand-header { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 12px; }
.brand-logo {
  width: 96px; height: 96px; border-radius: 16px;
  object-fit: contain;
  flex-shrink: 0;
}
.brand-text { display: flex; flex-direction: column; gap: 4px; }
.brand-label { font-size: 11px; font-weight: 700; color: #5b8cff; letter-spacing: 1.5px; text-transform: uppercase; }
.brand-title { font-size: 20px; font-weight: 800; color: #fff; line-height: 1.3; margin: 0; }
.brand-subtitle { font-size: 13px; font-weight: 500; font-style: italic; color: #8993ab; }
.brand-desc { font-size: 14px; font-weight: 500; color: #9499a8; line-height: 1.6; margin: 0 0 12px; }

.social-row { display: flex; flex-wrap: wrap; gap: 10px; }
.social-link {
  display: inline-flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  color: #c7cad4;
  transition: border-color .15s ease, color .15s ease, background-color .15s ease;
}
.social-link:hover {
  border-color: #fff; color: #fff; background: rgba(255, 255, 255, 0.06);
}

/* ===== MENU (flat list + hover popup) ===== */
.menu-grid { flex: 1 1 220px; max-width: 260px; }
.col-title {
  font-size: 12px; font-weight: 700; color: #5b8cff;
  letter-spacing: 1.5px; text-transform: uppercase;
  margin: 0 0 14px; padding-bottom: 12px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}
.nav-list { display: flex; flex-direction: column; gap: 2px; }
.nav-row {
  position: relative; display: flex; align-items: center; justify-content: space-between;
  padding: 10px 12px; border-radius: 10px; text-decoration: none;
  transition: background-color .15s ease;
}
.nav-row.has-submenu.is-hover { background: rgba(91,140,255,0.12); }
.footer-nav-link {
  display: flex; align-items: center; gap: 8px;
  font-size: 14px; font-weight: 500; color: #9499a8;
  text-decoration: none; transition: color .15s ease;
}
.nav-row.is-hover .footer-nav-link { color: #5b8cff; font-weight: 600; }
.footer-nav-link:hover { color: #5b8cff; }
.bullet { width: 5px; height: 5px; border-radius: 50%; background: #3d5aad; flex-shrink: 0; }
.chevron { color: #6b7080; transition: color .15s ease, transform .15s ease; }
.nav-row.is-hover .chevron { color: #5b8cff; transform: rotate(90deg); }

.submenu-popup {
  position: absolute; top: calc(100% + 6px); left: 12px;
  min-width: 220px; width: max-content; max-width: 280px;
  background: #16213f; border: 1.5px solid #5b8cff; border-radius: 16px;
  box-shadow: 0 16px 32px -12px rgba(0,0,0,0.5);
  padding: 14px 16px; z-index: 30;
}
.submenu-list { display: flex; flex-direction: column; gap: 12px; list-style: none; margin: 0; padding: 0; }
.submenu-link { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 500; color: #c7cad4; text-decoration: none; transition: color .15s ease; }
.submenu-link:hover { color: #5b8cff; }

.popup-enter-active,
.popup-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.popup-enter-from,
.popup-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

/* ===== KONTAK ===== */
.kontak-block { flex: 1 1 240px; max-width: 280px; }
.kontak-col { display: flex; flex-direction: column; gap: 12px; }
.kontak-item { display: flex; align-items: center; gap: 12px; }
.kontak-icon {
  display: flex; align-items: center; justify-content: center;
  width: 34px; height: 34px; flex-shrink: 0; border-radius: 10px;
  background: linear-gradient(180deg, rgba(91,140,255,0.16) 0%, rgba(91,140,255,0.08) 100%);
  border: 1px solid rgba(91,140,255,0.25); color: #5b8cff;
  box-shadow: 0 2px 4px -2px rgba(91,140,255,0.25);
}
.kontak-value { display: flex; flex-direction: column; gap: 2px; }
.kontak-label { font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6b7080; }
.kontak-text { font-size: 14px; font-weight: 500; color: #c7cad4; line-height: 1.5; }

/* ===== BOTTOM: watermark fade + copyright di samping ===== */
.bottom-block {
  display: flex;
  align-items: flex-end;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 4px 8px;
  margin-top: 16px;
  padding-bottom: 4px;
}

.watermark {
  display: block;
  font-weight: 800;
  font-size: clamp(32px, 8.5vw, 140px);
  line-height: 1;
  letter-spacing: 1px;
  white-space: nowrap;
  user-select: none;
  pointer-events: none;
  color: rgba(255, 255, 255, 0.07);

  -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 95%);
          mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 95%);
}

.copyright {
  font-size: 13px;
  font-weight: 500;
  color: #6b7080;
  white-space: nowrap;
  margin-bottom: clamp(6px, 3vw, 26px);
}
</style>
