<script setup>
import Navbar from "@/Components/Navbar/Navbar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Footer from "@/Components/Footer.vue";

/*
|--------------------------------------------------------------------------
| Dummy Data
| Nanti cukup diganti dengan data dari API.
|--------------------------------------------------------------------------
*/
const profile = {
    title: "Sambutan Kepala Dinas",

    subtitle:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",

    image: "",

    name: "Nama Kepala Dinas",

    position: "Kepala Dinas Pemberdayaan Masyarakat dan Desa",

    greeting: `
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            Ut enim ad minim veniam, quis nostrud exercitation ullamco
            laboris nisi ut aliquip ex ea commodo consequat.
        </p>

        <p>
            Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur.
        </p>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Integer facilisis, risus nec fermentum faucibus.
        </p>
    `
}
</script>

<template>
    <Navbar />

    <Breadcrumb />

    <section class="hero-section">

        <div class="container">

            <span class="hero-badge">
                Profil Dinas
            </span>

            <h1>{{ profile.title }}</h1>

            <p>{{ profile.subtitle }}</p>

        </div>

    </section>

    <main class="container">

        <section class="image-section">

            <img
                v-if="profile.image"
                :src="profile.image"
                :alt="profile.title"
            >

            <div
                v-else
                class="image-placeholder"
            >

                <div class="placeholder-icon">

                    🖼️

                </div>

                <h4>Image Not Available</h4>

                <p>

                    Gambar akan ditampilkan
                    setelah diunggah oleh administrator.

                </p>

            </div>

        </section>

        <div class="leader-info">

            <h3>{{ profile.name }}</h3>

            <p>{{ profile.position }}</p>

        </div>

        <section
            class="content-card"
            v-html="profile.greeting"
        />

    </main>

    <Footer />
</template>

<style scoped>

.container{

    max-width:960px;

    margin:auto;

    padding:0 20px;

}

/* ========================= */

.hero-section{

    background:linear-gradient(180deg,#F8FAFC 0%,#FFFFFF 100%);

    padding:56px 0 48px;

    margin-bottom:36px;

}

.hero-badge{

    display:inline-block;

    padding:8px 18px;

    background:#E0F2FE;

    color:#0369A1;

    font-size:14px;

    font-weight:600;

    border-radius:999px;

    margin-bottom:20px;

}

.hero-section h1{

    font-size:42px;

    font-weight:700;

    color:#0F172A;

    line-height:1.2;

    margin-bottom:18px;

    position:relative;

    display:inline-block;

}

.hero-section h1::after{

    content:"";

    position:absolute;

    left:0;

    bottom:-10px;

    width:90px;

    height:4px;

    border-radius:10px;

    background:#2563EB;

}

.hero-section p{

    margin-top:26px;

    max-width:720px;

    color:#64748B;

    line-height:1.9;

    font-size:17px;

}

/* ========================= */

.image-section{

    margin-bottom:32px;

}

.image-section img{

    width:100%;

    height:260px;

    object-fit:cover;

    border-radius:18px;

    display:block;

}

.image-placeholder{

    width:100%;

    height:260px;

    border:2px dashed #CBD5E1;

    border-radius:18px;

    background:#F8FAFC;

    display:flex;

    flex-direction:column;

    justify-content:center;

    align-items:center;

    text-align:center;

}

.placeholder-icon{

    font-size:60px;

    margin-bottom:18px;

    opacity:.7;

}

.image-placeholder h4{

    font-size:20px;

    color:#1E293B;

    margin-bottom:10px;

}

.image-placeholder p{

    color:#64748B;

    max-width:320px;

    line-height:1.7;

}

/* ========================= */

.content-card{

    background:#FFF;

    border-radius:20px;

    padding:40px;

    box-shadow:0 10px 30px rgba(15,23,42,.06);

    margin-bottom:60px;

    font-size:17px;

    line-height:2;

}

.content-card :deep(h2){

    display:flex;

    align-items:center;

    gap:12px;

    margin-top:42px;

    margin-bottom:18px;

    color:#0F172A;

    font-size:24px;

    font-weight:700;

}

.content-card :deep(h2)::before{

    content:"📘";

    font-size:24px;

}

.content-card :deep(ul){

    padding-left:28px;

    margin:20px 0;

}

.content-card :deep(li){

    margin-bottom:14px;

    color:#374151;

}

.content-card :deep(p){

    margin-bottom:16px;

}

/* ========================= */

.leader-info{

    margin-top:24px;

    text-align:center;

}

.leader-info h3{

    font-size:26px;

    font-weight:700;

    color:#0F172A;

    margin-bottom:8px;

}

.leader-info p{

    color:#64748B;

    font-size:16px;

}

/* ========================= */

@media(max-width:768px){

    .hero-section{

        padding:42px 0;

    }

    .hero-section h1{

        font-size:32px;

    }

    .image-placeholder{

        height:220px;

    }

    .content-card{

        padding:24px;

    }

}

</style>