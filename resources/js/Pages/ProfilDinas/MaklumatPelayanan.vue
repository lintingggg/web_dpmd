<script setup>
import Navbar from "@/Components/Navbar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Footer from "@/Components/Footer.vue";

/*
|--------------------------------------------------------------------------
| Dummy Data
| Nanti cukup diganti dengan data dari API.
|--------------------------------------------------------------------------
*/
const profile = {
    title: "Maklumat Pelayanan",

    subtitle:
        "Pernyataan komitmen Dinas Pemberdayaan Masyarakat dan Desa dalam memberikan pelayanan publik yang berkualitas kepada masyarakat.",

    image: "",

    content: `
        <p>
            Dinas Pemberdayaan Masyarakat dan Desa menyatakan sanggup
            menyelenggarakan pelayanan sesuai dengan standar pelayanan
            yang telah ditetapkan serta terus melakukan perbaikan secara
            berkelanjutan demi kepuasan masyarakat.
        </p>

        <h2>Komitmen Pelayanan</h2>

        <p>
            Kami berkomitmen memberikan pelayanan yang cepat, tepat,
            transparan, akuntabel, dan profesional sesuai dengan
            ketentuan peraturan perundang-undangan.
        </p>

        <h2>Janji Pelayanan</h2>

        <ul>
            <li>Memberikan pelayanan sesuai standar pelayanan.</li>
            <li>Memberikan pelayanan tanpa diskriminasi.</li>
            <li>Memberikan informasi yang jelas dan mudah dipahami.</li>
            <li>Menindaklanjuti setiap pengaduan masyarakat.</li>
            <li>Melakukan evaluasi dan peningkatan kualitas pelayanan secara berkala.</li>
        </ul>

        <h2>Tanggung Jawab</h2>

        <p>
            Apabila dalam penyelenggaraan pelayanan tidak sesuai dengan
            standar yang telah ditetapkan, kami siap menerima kritik,
            saran, dan masukan sebagai bahan evaluasi untuk meningkatkan
            kualitas pelayanan kepada masyarakat.
        </p>
    `,
};
</script>

<template>
    <Navbar />

    <Breadcrumb />

    <section class="page-header">
        <div class="container">
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
                <div class="placeholder-icon">🖼</div>

                <h4>IMAGE NOT FOUND</h4>

                <p>Gambar akan ditampilkan setelah diunggah.</p>
            </div>

        </section>

        <section
            class="content-card"
            v-html="profile.content"
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

.page-header{

    padding:24px 0 20px;

}

.page-header h1{

    position:relative;

    display:inline-block;

    font-size:36px;

    font-weight:700;

    color:#1e293b;

    margin-bottom:20px;

}

.page-header p{

    color:#64748b;

    line-height:1.8;

    max-width:700px;

}
/*baru ditambahkan */
.page-header h1::after{

    content:"";

    position:absolute;

    left:0;

    bottom:-10px;

    width:70px;

    height:4px;

    background:#0f4c81;

    border-radius:10px;

}

/* ========================= */

.image-section{

    margin-bottom:32px;

}

.image-section img{

    width:100%;

    height:240px;

    object-fit:cover;

    border-radius:12px;

    display:block;

}

.image-placeholder{

    width:100%;

    height:240px;

    border:2px dashed #cbd5e1;

    border-radius:12px;

    background:#f8fafc;

    display:flex;

    flex-direction:column;

    justify-content:center;

    align-items:center;

    color:#64748b;

}

.placeholder-icon{

    font-size:56px;

    opacity:.7;

}

.image-placeholder h4{

    margin-top:10px;

    font-size:18px;

    font-weight:600;

    letter-spacing:1px;

}

.image-placeholder p{

    margin-top:8px;

    font-size:14px;

    color:#64748b;

}

/* ========================= */

.content-card{

    background:white;

    border-radius:12px;

    padding:32px;

    box-shadow:0 2px 12px rgba(0,0,0,.06);

    margin-bottom:60px;

    font-size:17px;

    line-height:2;

    color:#374151;

}


/*baru ditambahkan */

.content-card :deep(h2){

    margin-top:34px;

    margin-bottom:18px;

    padding-left:12px;

    border-left:5px solid #0f4c81;

    color:#0f4c81;

    font-size:24px;

}
.content-card :deep(ul){

    padding-left:24px;

    margin:18px 0;

    list-style:disc;

}

.content-card :deep(li){

    margin-bottom:12px;

    display:list-item;

}
/**/
.content-card :deep(p){

    margin-bottom:16px;

}

/* ========================= */

@media(max-width:768px){

    .page-header h1{

        font-size:28px;

    }

    .image-placeholder{

        height:220px;

    }

    .content-card{

        padding:20px;

    }

}

</style>