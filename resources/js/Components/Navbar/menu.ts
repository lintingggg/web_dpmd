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
                href: "/sambutan-kepala-dinas",
            },
            {
                label: "Visi & Misi",
                href: "/visi-misi",
            },
            {
                label: "Tugas Pokok & Fungsi",
                href: "/tugas-pokok-fungsi",
            },
            {
                label: "Struktur Organisasi",
                href: "/struktur-organisasi",
            },
            {
                label: "Motto Pelayanan",
                href: "/motto-pelayanan",
            },
            {
                label: "Maklumat Pelayanan",
                href: "/maklumat-pelayanan",
            },
            {
                label: "Kode Etik Pelayanan",
                href: "/kode-etik-pelayanan",
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
        href: "/dokumen-dan-peraturan",
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