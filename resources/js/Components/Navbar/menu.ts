export interface NavChild {
    label: string
    href: string
}

export interface NavItem {
    label: string
    href?: string
    children?: NavChild[]
}

export const menu: NavItem[] = [
    {
        label: "Beranda",
        href: "/"
    },

    {
        label: "Profil Dinas",
        children: [
            {
                label: "Profil Singkat",
                href: "/profil"
            },
            {
                label: "Visi & Misi",
                href: "/visi-misi"
            },
            {
                label: "Struktur Organisasi",
                href: "/struktur-organisasi"
            }
        ]
    },

    {
        label: "Bidang Tugas",
        children: [
            {
                label: "Bidang Pemerintahan Desa",
                href: "/bidang/pemerintahan-desa"
            },
            {
                label: "Bidang Pemberdayaan Masyarakat",
                href: "/bidang/pemberdayaan"
            },
            {
                label: "Sekretariat",
                href: "/sekretariat"
            }
        ]
    },

    {
        label: "Publikasi",
        children: [
            {
                label: "Berita",
                href: "/berita"
            },
            {
                label: "Galeri",
                href: "/galeri"
            },
            {
                label: "Dokumen",
                href: "/dokumen-dan-peraturan"
            }
        ]
    },

    {
        label: "Kontak",
        href: "/kontak"
    }
]