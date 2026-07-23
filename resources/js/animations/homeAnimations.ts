import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initHomeAnimations() {
  // Use matchMedia for accessibility (prefers-reduced-motion)
  let mm = gsap.matchMedia();

  mm.add("(prefers-reduced-motion: no-preference)", () => {
    
    // 1. Navbar Sticky Shrink
    ScrollTrigger.create({
      start: 'top -50',
      end: 99999,
      toggleClass: { className: 'shadow-md', targets: '.nav-container' },
    });

    // 2. Hero Section
    const heroTimeline = gsap.timeline();
    
    // Masked Slide Text for Title
    // Requires the title to have overflow-hidden and the inner text wrapped in a span
    heroTimeline.fromTo(
      '.hero-title-inner',
      { y: '120%', opacity: 0 },
      { y: '0%', opacity: 1, duration: 1.2, ease: 'power4.out' }
    )
    // Fade up for description, badge, button
    .fromTo(
      '.hero-fade-up',
      { y: 30, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: 'power3.out' },
      "-=0.6" // Start slightly before title finishes
    );

    // Hero Images Staggered Pop
    gsap.fromTo(
      ['.hero-img-1', '.hero-img-2', '.hero-img-3'],
      { y: 50, opacity: 0, scale: 0.9 },
      { 
        y: 0, 
        opacity: 1, 
        scale: 1, 
        duration: 0.8, 
        stagger: 0.2, 
        ease: 'back.out(1.2)', 
        delay: 0.2 
      }
    );

    // 3. Pengumuman Terkini & Berita Terkini (Staggered Fade-Up)
    const sections = ['.section-pengumuman', '.section-berita'];
    
    sections.forEach((section) => {
      // Header fade-up
      gsap.fromTo(
        `${section} .section-header`,
        { y: 30, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.8,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: section,
            start: 'top 85%',
          }
        }
      );

      // Cards staggered fade-up
      gsap.fromTo(
        `${section} .card-item`,
        { y: 40, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.6,
          stagger: 0.15,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: `${section} .card-container`,
            start: 'top 85%',
          }
        }
      );
    });

    // 4. Hubungi Kami
    const contactSection = '.section-kontak';
    gsap.fromTo(
      `${contactSection} .kontak-kiri`,
      { x: -50, opacity: 0 },
      {
        x: 0,
        opacity: 1,
        duration: 0.8,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: contactSection,
          start: 'top 75%',
        }
      }
    );
    gsap.fromTo(
      `${contactSection} .kontak-kanan`,
      { x: 50, opacity: 0 },
      {
        x: 0,
        opacity: 1,
        duration: 0.8,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: contactSection,
          start: 'top 75%',
        }
      }
    );

    // 5. Peta Lokasi
    const mapSection = '.section-peta';
    gsap.fromTo(
      `${mapSection} .peta-container`,
      { scale: 0.95, opacity: 0 },
      {
        scale: 1,
        opacity: 1,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: mapSection,
          start: 'top 80%',
        }
      }
    );

    return () => { 
      // Cleanup happens automatically by matchMedia
    };
  });
}
