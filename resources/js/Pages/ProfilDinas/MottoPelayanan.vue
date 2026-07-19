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
    title: "Motto Pelayanan",

    subtitle:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",

    image: "",

    motto: "Melayani Dengan Integritas, Membangun Desa Dengan Hati",

    values: [
        "Profesional",
        "Integritas",
        "Transparan",
        "Pelayanan Prima"
    ],

    content: `
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat.
        </p>

        <p>
            Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
            cupidatat non proident, sunt in culpa qui officia deserunt mollit
            anim id est laborum.
        </p>

        <h2>Lorem Ipsum Heading</h2>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
            facilisis, risus nec fermentum faucibus, sapien libero bibendum
            augue, sed gravida odio sapien vel magna. Pellentesque habitant
            morbi tristique senectus et netus et malesuada fames ac turpis
            egestas.
        </p>

        <h2>Another Section</h2>

        <ul>
            <li>Lorem ipsum dolor sit amet.</li>
            <li>Consectetur adipiscing elit.</li>
            <li>Sed do eiusmod tempor incididunt.</li>
            <li>Ut labore et dolore magna aliqua.</li>
            <li>Ut enim ad minim veniam.</li>
            <li>Quis nostrud exercitation ullamco.</li>
        </ul>

        <h2>Final Section</h2>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aute
            irure dolor in reprehenderit in voluptate velit esse cillum dolore
            eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est
            laborum.
        </p>
    `,
};
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

        <section class="motto-box">

            <span class="motto-label">

                Motto Pelayanan

            </span>

            <h2>

                "{{ profile.motto }}"

            </h2>

        </section>

        <section class="content-card">

            <div v-html="profile.content"></div>

            <div class="values-section">

                <h3>Nilai Pelayanan</h3>

                <ul>

                    <li
                        v-for="value in profile.values"
                        :key="value"
                    >
                        {{ value }}
                    </li>

                </ul>

            </div>

        </section>

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

.motto-box{

    background:#F8FAFC;

    border-radius:20px;

    padding:40px;

    margin-bottom:36px;

    text-align:center;

    border:1px solid #E2E8F0;

}

.motto-label{

    display:inline-block;

    background:#DBEAFE;

    color:#1D4ED8;

    padding:8px 16px;

    border-radius:999px;

    font-size:14px;

    font-weight:600;

    margin-bottom:20px;

}

.motto-box h2{

    font-size:32px;

    color:#0F172A;

    line-height:1.6;

    font-style:italic;

    font-weight:700;

}

.values-section{

    margin-top:40px;

    padding-top:32px;

    border-top:1px solid #E5E7EB;

}

.values-section h3{

    margin-bottom:20px;

    font-size:22px;

    color:#0F172A;

}

.values-section ul{

    display:grid;

    grid-template-columns:repeat(2,1fr);

    gap:14px;

    list-style:none;

    padding:0;

}

.values-section li{

    background:#F8FAFC;

    padding:16px;

    border-radius:12px;

    border:1px solid #E5E7EB;

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