<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { Button } from '@idds/vue';
</script>

<template>
    <Head title="404 - Halaman Tidak Ditemukan" />

    <div class="min-h-screen flex flex-col bg-neutral-50 font-sans">
        <Navbar />

        <main class="flex-1 flex items-center justify-center py-16 px-4">
            
            <div class="flex flex-col items-center justify-center gap-6 lg:gap-8 max-w-2xl mx-auto w-full text-center">
                
                <!-- Gambar 404 -->
                <img src="/assets/illustrasi-404.svg" alt="Illustrasi 404 Section Centered" class="w-full max-w-sm h-auto mx-auto" />
                
                <!-- Teks 404 -->
                <div class="flex flex-col items-center gap-3 lg:gap-5 max-w-md mx-auto">
                    <div class="space-y-2 lg:space-y-3">
                        <h3 class="text-2xl md:text-3xl font-bold text-slate-800 text-center">
                            404: Halaman Tidak Ditemukan
                        </h3>
                        <p class="text-base text-slate-600 text-center leading-relaxed">
                            Maaf, halaman yang Anda cari tidak tersedia. Halaman tersebut mungkin telah dipindahkan
                            atau tidak lagi dapat diakses.
                        </p>
                    </div>
                    
                    <!-- Tombol Kembali -->
                    <div class="mt-4">
                        <Link href="/">
                            <Button hierarchy="secondary" class="shadow-sm hover:shadow-md transition-shadow">
                                Kembali ke Beranda
                            </Button>
                        </Link>
                    </div>
                </div>

            </div>

        </main>

        <Footer />
    </div>
</template>
