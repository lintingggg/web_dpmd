<script setup lang="ts">
import OrganizationCard from "./OrganizationCard.vue";

const kepala = {
    name: "Nama Kepala DPMD",
    position: "Kepala DPMD",
};

const sekretaris = {
    name: "Nama Sekretaris",
    position: "Sekretaris",
};

const bidang = [
    {
        name: "Nama Kabid",
        position: "Bidang Pemerintahan Desa",
    },
    {
        name: "Nama Kabid",
        position: "Bidang Pemberdayaan Masyarakat",
    },
    {
        name: "Nama Kabid",
        position: "Bidang Keuangan Desa",
    },
    {
        name: "Nama Kabid",
        position: "Bidang Administrasi Desa",
    },
];
</script>

<template>

    <!-- ====================== -->
    <!-- DESKTOP -->
    <!-- ====================== -->

    <div class="hidden lg:block overflow-x-auto py-10">

        <div class="min-w-max flex flex-col items-center">

            <!-- Kepala -->

            <OrganizationCard
                :name="kepala.name"
                :position="kepala.position"
            />

            <div class="h-12 w-px bg-neutral-300"></div>

            <!-- Sekretaris -->

            <OrganizationCard
                :name="sekretaris.name"
                :position="sekretaris.position"
            />

            <div class="h-12 w-px bg-neutral-300"></div>

            <!-- Garis Horizontal -->

            <div
                class="h-px bg-neutral-300 w-full max-w-6xl"
            ></div>

            <!-- Bidang -->

            <div
                class="mt-10 grid gap-10"
                :style="{
                    gridTemplateColumns: `repeat(${bidang.length}, minmax(250px,1fr))`,
                }"
            >

                <div
                    v-for="item in bidang"
                    :key="item.position"
                    class="flex flex-col items-center"
                >

                    <div class="h-10 w-px bg-neutral-300"></div>

                    <OrganizationCard
                        :name="item.name"
                        :position="item.position"
                    />

                </div>

            </div>

        </div>

    </div>

    <!-- ====================== -->
    <!-- MOBILE -->
    <!-- ====================== -->

    <div class="lg:hidden py-6">

        <div class="flex flex-col items-center">

            <!-- Kepala -->

            <OrganizationCard
                :name="kepala.name"
                :position="kepala.position"
            />

            <div class="h-8 w-px bg-neutral-300"></div>

            <!-- Sekretaris -->

            <OrganizationCard
                :name="sekretaris.name"
                :position="sekretaris.position"
            />

            <div class="h-8 w-px bg-neutral-300"></div>

            <!-- Bidang -->

            <div
                class="w-full flex flex-col gap-8"
            >

                <div
                    v-for="item in bidang"
                    :key="item.position"
                    class="flex flex-col items-center"
                >

                    <div class="h-8 w-px bg-neutral-300"></div>

                    <OrganizationCard
                        :name="item.name"
                        :position="item.position"
                    />

                </div>

            </div>

        </div>

    </div>

</template>