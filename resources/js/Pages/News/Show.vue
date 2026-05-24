<template>
    <PublicLayout>
        <Head :title="article.title" />

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
                <a
                    :href="route('news.index')"
                    class="hover:text-orange-600 transition-colors duration-150"
                    >Berita & Artikel</a
                >
                <span>›</span>
                <span
                    class="text-slate-600 font-medium truncate max-w-[200px] md:max-w-xs"
                    >{{ article.title }}</span
                >
            </div>
        </div>

        <!-- ============================================================
             ARTICLE
        ============================================================ -->
        <article class="bg-white py-12 md:py-16">
            <div class="max-w-screen-xl mx-auto px-4">
                <!-- Category & Date -->
                <div class="flex items-center gap-3 mb-5">
                    <span
                        class="text-xs font-bold text-white bg-orange-600 uppercase tracking-widest px-3 py-1"
                    >
                        {{ article.category }}
                    </span>
                    <span class="text-sm text-gray-400">{{
                        article.date
                    }}</span>
                </div>

                <!-- Title -->
                <h1
                    class="text-2xl md:text-4xl font-black text-slate-900 leading-tight mb-8"
                >
                    {{ article.title }}
                </h1>

                <!-- Main Image -->
                <div v-if="article.image" class="overflow-hidden mb-10">
                    <img
                        :src="article.image"
                        :alt="article.title"
                        class="w-full h-[300px] md:h-[480px] object-cover"
                    />
                </div>
                <div
                    v-else
                    class="w-full h-[300px] md:h-[480px] bg-gray-100 flex items-center justify-center mb-10"
                >
                    <svg
                        class="w-16 h-16 text-gray-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                </div>

                <!-- Excerpt -->
                <p
                    v-if="article.excerpt"
                    class="text-lg md:text-xl text-slate-500 leading-relaxed mb-8 border-l-4 border-orange-600 pl-5 italic"
                >
                    {{ article.excerpt }}
                </p>

                <!-- Divider -->
                <hr class="border-gray-100 mb-8" />

                <!-- Content -->
                <div class="article-content" v-html="article.content"></div>

                <!-- Back Button -->
                <div class="mt-14 pt-8 border-t border-gray-100">
                    <a
                        :href="route('news.index')"
                        class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-orange-600 transition-colors duration-200"
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
                        Kembali ke Berita & Artikel
                    </a>
                </div>
            </div>
        </article>
    </PublicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

defineProps({
    article: {
        type: Object,
        default: () => ({
            title: "Tren Otomasi Gudang 2026: Peran Forklift Elektrik",
            category: "Industri",
            date: "15 Mei 2026",
            excerpt:
                "Perkembangan teknologi forklift elektrik semakin mendominasi industri logistik modern di Indonesia.",
            image: "https://picsum.photos/seed/news1/800/480",
            content: `
                <p>Industri logistik Indonesia tengah mengalami transformasi besar. Forklift elektrik kini menjadi pilihan utama bagi perusahaan yang ingin meningkatkan efisiensi sekaligus mengurangi emisi karbon di area operasional mereka.</p>
                <p>Berbeda dengan forklift berbahan bakar diesel atau LPG, forklift elektrik menawarkan biaya operasional yang lebih rendah, perawatan lebih mudah, serta nol emisi gas buang — menjadikannya ideal untuk operasional indoor seperti gudang dan pusat distribusi.</p>
                <h3>Keunggulan Forklift Elektrik</h3>
                <ul>
                    <li>Biaya operasional lebih rendah hingga 40% dibanding forklift konvensional</li>
                    <li>Emisi nol — aman untuk lingkungan indoor</li>
                    <li>Perawatan lebih sederhana tanpa mesin pembakaran</li>
                    <li>Tingkat kebisingan jauh lebih rendah</li>
                    <li>Teknologi baterai lithium-ion dengan pengisian cepat</li>
                </ul>
                <p>Dengan semakin terjangkaunya teknologi baterai lithium-ion, tren adopsi forklift elektrik diperkirakan akan terus meningkat pesat di Indonesia hingga 2030.</p>
                <h3>Prospek Pasar di Indonesia</h3>
                <p>Pasar forklift elektrik di Indonesia diproyeksikan tumbuh rata-rata 12% per tahun. Sektor e-commerce, manufaktur, dan cold chain logistics menjadi pendorong utama pertumbuhan ini.</p>
            `,
        }),
    },
});
</script>

<style scoped>
.article-content :deep(p) {
    color: #475569;
    line-height: 1.8;
    margin-bottom: 1.25rem;
    font-size: 1rem;
}
@media (min-width: 768px) {
    .article-content :deep(p) {
        font-size: 1.125rem;
    }
}
.article-content :deep(h2) {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    margin: 2rem 0 1rem;
    line-height: 1.3;
}
.article-content :deep(h3) {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 1.75rem 0 0.75rem;
}
.article-content :deep(ul),
.article-content :deep(ol) {
    padding-left: 1.5rem;
    color: #475569;
    line-height: 1.8;
    margin-bottom: 1.25rem;
}
.article-content :deep(ul) {
    list-style: disc;
}
.article-content :deep(ol) {
    list-style: decimal;
}
.article-content :deep(li) {
    margin-bottom: 0.4rem;
}
.article-content :deep(strong) {
    font-weight: 700;
    color: #1e293b;
}
.article-content :deep(em) {
    font-style: italic;
}
.article-content :deep(a) {
    color: #ea580c;
    text-decoration: underline;
}
.article-content :deep(blockquote) {
    border-left: 4px solid #ea580c;
    padding-left: 1.25rem;
    color: #64748b;
    font-style: italic;
    margin: 1.5rem 0;
}
</style>
