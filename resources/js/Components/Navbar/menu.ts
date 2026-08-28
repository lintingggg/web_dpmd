// resources/js/Components/Navbar/menu.ts

export interface NavChild {
    label: string;
    href: string;
}

export interface NavItem {
    label: string;
    href?: string;
    children?: NavChild[];
}

export const menu: NavItem[] = [
    {
        label: "Beranda",
        href: "/",
    },

    {
        label: "Profil",
        children: [
            {
                label: "Sambutan Kepala Dinas",
                href: "/profil/sambutan-kepala-dinas",
            },
            {
                label: "Visi & Misi",
                href: "/profil/visi-misi",
            },
            {
                label: "Tugas Pokok & Fungsi",
                href: "/profil/tugas-pokok-fungsi",
            },
            {
                label: "Struktur Organisasi",
                href: "/profil/struktur-organisasi",
            },
            {
                label: "Motto Pelayanan",
                href: "/profil/motto-pelayanan",
            },
            {
                label: "Maklumat Pelayanan",
                href: "/profil/maklumat-pelayanan",
            },
            {
                label: "Kode Etik Pelayanan",
                href: "/profil/kode-etik-pelayanan",
            },
        ],
    },

    {
        label: "Bidang",
        children: [
            {
                label: "Sekretariat",
                href: "/bidang-tugas/sekretariat",
            },
            {
                label: "Pemerintahan Desa",
                href: "/bidang-tugas/pemerintahan-desa",
            },
            {
                label: "Pemberdayaan Desa",
                href: "/bidang-tugas/pemberdayaan-desa",
            },
            {
                label: "Pemberdayaan Lembaga Kemasyarakatan",
                href: "/bidang-tugas/pemberdayaan-lembaga-kemasyarakatan",
            },
        ],
    },

    {
        label: "Dokumen",
        children: [
            { label: "Data Perencanaan", href: "/dokumen-dan-peraturan?kategori=perencanaan" },
            { label: "LAKIP", href: "/dokumen-dan-peraturan?kategori=lakip" },
            { label: "Perjanjian Kinerja", href: "/dokumen-dan-peraturan?kategori=perjanjian-kinerja" },
            { label: "SAKIP", href: "/dokumen-dan-peraturan?kategori=sakip" },
            { label: "Transparansi APBD", href: "/dokumen-dan-peraturan?kategori=transparansi-apbd" },
        ],
    },
    
    {
        label: "Galeri",
        href: "/galeri",
    },

    {
        label: "Berita",
        href: "/berita",
    },
];