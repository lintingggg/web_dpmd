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
                label: "Profil Dinas",
                href: "/profil",
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
        ],
    },

    {
        label: "Bidang",
        children: [
            {
                label: "Bidang Pemerintahan Desa",
                href: "/bidang/pemerintahan",
            },
            {
                label: "Bidang Pemberdayaan",
                href: "/bidang/pemberdayaan",
            },
            {
                label: "Bidang Administrasi",
                href: "/bidang/administrasi",
            },
        ],
    },

    {
        label: "Publikasi",
        children: [
            {
                label: "Dokumen",
                href: "/dokumen",
            },
            {
                label: "Berita",
                href: "/berita",
            },
        ],
    },

    {
        label: "Kontak",
        href: "/kontak",
    },
];