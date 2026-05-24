<template>
    <PublicLayout>
        <Head title="Berita & Artikel" />

        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-100">
            <div
                class="max-w-screen-xl mx-auto px-4 py-3 flex items-center gap-2 text-sm text-gray-400"
            >
                <a
                    href="/"
                    class="hover:text-orange-600 transition-colors duration-150"
                    >Home</a
                >
                <span>›</span>
                <span class="text-slate-600 font-medium">Berita & Artikel</span>
            </div>
        </div>

        <!-- ============================================================
             HERO SECTION — Full-width image, dark box bottom-left
        ============================================================ -->
        <section class="relative w-full h-[360px] md:h-[480px] overflow-hidden">
            <img
                src="/static/bg-news.webp"
                alt="Berita & Artikel"
                class="w-full h-full object-cover"
            />
            <!-- Dark text box — vertically centered, aligned to container -->
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-screen-xl mx-auto w-full px-4">
                    <div
                        class="inline-block px-8 py-7 md:px-10 md:py-8"
                        style="background-color: rgba(15, 15, 15, 0.82)"
                    >
                        <p class="text-sm text-white/60 font-normal mb-1">
                            Perusahaan
                        </p>
                        <h1
                            class="text-2xl md:text-4xl font-black text-white leading-tight"
                        >
                            Berita & Artikel
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             NEWS GRID
        ============================================================ -->
        <section class="bg-white py-16 md:py-20">
            <div class="max-w-screen-xl mx-auto px-4">
                <!-- Empty state -->
                <div v-if="news.data.length === 0" class="text-center py-24">
                    <p class="text-2xl font-black text-slate-300 mb-2">
                        Belum ada artikel
                    </p>
                    <p class="text-slate-400">
                        Artikel akan segera hadir. Pantau terus!
                    </p>
                </div>

                <!-- Grid -->
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16"
                >
                    <article
                        v-for="article in news.data"
                        :key="article.id"
                        class="group flex flex-col"
                    >
                        <a
                            :href="route('news.show', article.slug)"
                            class="overflow-hidden mb-5 block"
                        >
                            <img
                                :src="article.image"
                                :alt="article.title"
                                class="w-full h-[286px] object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                        </a>
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="md:text-xl font-bold text-orange-600 uppercase tracking-wider"
                            >
                                {{ article.category }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="md:text-xl text-gray-400">{{
                                article.date
                            }}</span>
                        </div>
                        <h3
                            class="text-lg md:text-2xl font-bold text-slate-900 mb-2 leading-snug group-hover:text-orange-600 transition-colors duration-200"
                        >
                            {{ article.title }}
                        </h3>
                        <p
                            class="md:text-xl text-slate-500 leading-relaxed mb-4 flex-1"
                        >
                            {{ article.excerpt }}
                        </p>
                        <a
                            :href="route('news.show', article.slug)"
                            class="inline-flex items-center gap-1.5 md:text-xl font-semibold text-orange-600 hover:text-orange-700 transition-colors duration-200"
                        >
                            Baca Selengkapnya
                            <svg
                                class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </a>
                    </article>
                </div>

                <!-- Pagination -->
                <div
                    v-if="news.last_page > 1"
                    class="flex items-center justify-center gap-1"
                >
                    <!-- Prev -->
                    <a
                        v-if="news.prev_page_url"
                        :href="news.prev_page_url"
                        class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-slate-600 hover:text-orange-600 border border-gray-200 hover:border-orange-300 transition-colors duration-200"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Sebelumnya
                    </a>
                    <span
                        v-else
                        class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-300 border border-gray-100 cursor-not-allowed"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Sebelumnya
                    </span>

                    <!-- Page numbers -->
                    <template v-for="link in pageLinks" :key="link.label">
                        <a
                            v-if="link.url && !link.active"
                            :href="link.url"
                            class="w-10 h-10 flex items-center justify-center text-sm font-semibold text-slate-600 hover:text-orange-600 border border-gray-200 hover:border-orange-300 transition-colors duration-200"
                        >
                            {{ link.label }}
                        </a>
                        <span
                            v-else-if="link.active"
                            class="w-10 h-10 flex items-center justify-center text-sm font-bold text-white bg-orange-600 border border-orange-600"
                        >
                            {{ link.label }}
                        </span>
                        <span
                            v-else-if="link.label === '...'"
                            class="w-10 h-10 flex items-center justify-center text-sm text-gray-400"
                        >
                            ...
                        </span>
                    </template>

                    <!-- Next -->
                    <a
                        v-if="news.next_page_url"
                        :href="news.next_page_url"
                        class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-slate-600 hover:text-orange-600 border border-gray-200 hover:border-orange-300 transition-colors duration-200"
                    >
                        Berikutnya
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a>
                    <span
                        v-else
                        class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-300 border border-gray-100 cursor-not-allowed"
                    >
                        Berikutnya
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </span>
                </div>

                <!-- Page info -->
                <p
                    v-if="news.total > 0"
                    class="text-center text-sm text-gray-400 mt-6"
                >
                    Menampilkan {{ news.from }}–{{ news.to }} dari
                    {{ news.total }} artikel
                </p>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    news: {
        type: Object,
        default: () => ({
            data: [
                {
                    id: 1,
                    slug: "tren-otomasi-gudang-2026",
                    category: "Industri",
                    date: "15 Mei 2026",
                    title: "Tren Otomasi Gudang 2026: Peran Forklift Elektrik",
                    excerpt:
                        "Perkembangan teknologi forklift elektrik semakin mendominasi industri logistik modern di Indonesia.",
                    image: "https://picsum.photos/seed/news1/600/400",
                },
                {
                    id: 2,
                    slug: "peluncuran-reach-truck-terbaru",
                    category: "Produk",
                    date: "10 Mei 2026",
                    title: "Peluncuran Seri Forklift Reach Truck Terbaru 2026",
                    excerpt:
                        "Kami memperkenalkan seri reach truck generasi terbaru dengan kapasitas angkat hingga 2,5 ton dan efisiensi energi superior.",
                    image: "https://picsum.photos/seed/news2/600/400",
                },
                {
                    id: 3,
                    slug: "panduan-perawatan-forklift",
                    category: "Tips & Panduan",
                    date: "05 Mei 2026",
                    title: "Panduan Lengkap Perawatan Forklift agar Awet",
                    excerpt:
                        "Perawatan rutin adalah kunci umur panjang forklift Anda. Berikut panduan lengkap yang wajib dilakukan setiap bulan.",
                    image: "https://picsum.photos/seed/news3/600/400",
                },
                {
                    id: 4,
                    slug: "forklift-cold-storage",
                    category: "Industri",
                    date: "01 Mei 2026",
                    title: "Memilih Forklift yang Tepat untuk Cold Storage",
                    excerpt:
                        "Operasional di lingkungan suhu rendah memerlukan spesifikasi forklift khusus. Simak panduan memilihnya.",
                    image: "https://picsum.photos/seed/news4/600/400",
                },
                {
                    id: 5,
                    slug: "keselamatan-operator-forklift",
                    category: "Tips & Panduan",
                    date: "28 Apr 2026",
                    title: "Standar Keselamatan Operator Forklift di Gudang",
                    excerpt:
                        "Keselamatan operator adalah prioritas utama. Pahami standar K3 yang wajib diterapkan di area operasional forklift.",
                    image: "https://picsum.photos/seed/news5/600/400",
                },
                {
                    id: 6,
                    slug: "forklift-sewa-vs-beli",
                    category: "Produk",
                    date: "20 Apr 2026",
                    title: "Sewa vs Beli Forklift: Mana yang Lebih Menguntungkan?",
                    excerpt:
                        "Keputusan sewa atau beli forklift bergantung pada kebutuhan operasional dan kondisi keuangan perusahaan Anda.",
                    image: "https://picsum.photos/seed/news6/600/400",
                },
            ],
            total: 6,
            from: 1,
            to: 6,
            last_page: 1,
            prev_page_url: null,
            next_page_url: null,
            links: [],
        }),
    },
});

const pageLinks = computed(() =>
    props.news.links.filter(
        (l) => l.label !== "&laquo; Previous" && l.label !== "Next &raquo;",
    ),
);
</script>
