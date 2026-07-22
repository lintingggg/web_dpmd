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
                label: "Struktur Organisasi",
                href: "/struktur-organisasi",
            },
            {
                label: "Tugas Pokok & Fungsi",
                href: "/tugas-pokok-fungsi",
            },
                        {
                label: "Kode Etik Pelayanan",
                href: "/kode-etik-pelayanan",
            },
                        {
                label: "Maklumat Pelayanan",
                href: "/maklumat-pelayanan",
            },
        ],
    },

    {
        label: "Bidang Tugas",
        children: [
            {
                label: "Bidang Pemerintahan Desa",
                href: "/bidang-tugas/pemerintahan-desa",
            },
            {
                label: "Bidang Pemberdayaan Desa",
                href: "/bidang-tugas/pemberdayaan-desa",
            },
            {
                label: "Bidang Pemberdayaan Lembaga Kemasyarakatan",
                href: "/bidang-tugas/pemberdayaan-lembaga-kemasyarakatan",
            },
            {
                label: "Bidang Sekeretariat",
                href: "/bidang-tugas/sekretariat",
            },
            
        ],
    },

    {
        label: "Publikasi Dokumen",
        href: "/dokumen-dan-peraturan",
    },

    {
        label: "Berita",
        href: "/berita",
    },
];