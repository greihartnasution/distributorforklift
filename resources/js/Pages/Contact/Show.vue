<template>
    <PublicLayout>
        <Head :title="page.title ?? 'Kontak'" />

        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-screen-xl mx-auto px-4 py-3 flex items-center gap-2 text-sm text-gray-400">
                <a href="/" class="hover:text-orange-600 transition-colors duration-150">Home</a>
                <span>›</span>
                <span class="text-slate-600 font-medium">Kontak</span>
            </div>
        </div>

        <!-- HERO SECTION — image only, contained -->
        <section class="bg-white border-b border-gray-100 py-10">
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <div
                    class="w-full h-64 md:h-80 lg:h-96 bg-gray-100 overflow-hidden"
                >
                    <img
                        v-if="page.image"
                        :src="page.image"
                        :alt="page.title"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center"
                    >
                        <svg
                            class="w-16 h-16 text-gray-200"
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
                </div>
            </div>
        </section>

        <!-- STATEMENT BANNER -->
        <section
            v-if="page.heading || page.description"
            class="bg-white pt-14 pb-10"
        >
            <div class="max-w-screen-xl mx-auto px-4">
                <h2
                    v-if="page.heading"
                    class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-tight md:leading-[60px] lg:leading-[68px] max-w-4xl"
                >
                    {{ page.heading }}
                </h2>
                <p
                    v-if="page.description"
                    class="mt-12 mb-12 text-slate-500 text-base md:text-xl leading-relaxed max-w-3xl"
                >
                    {{ page.description }}
                </p>
            </div>
        </section>

        <!-- CONTENT — two columns (col-6 col-6, mobile full width) -->
        <section
            v-if="page.content_left || page.content_right"
            class="bg-white pb-24"
            :class="page.heading || page.description ? 'pt-6' : 'pt-14'"
        >
            <div class="max-w-screen-xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                    <div
                        v-if="page.content_left"
                        class="about-long-desc"
                        v-html="page.content_left"
                    ></div>
                    <div
                        v-if="page.content_right"
                        class="about-long-desc"
                        v-html="page.content_right"
                    ></div>
                </div>
            </div>
        </section>

        <!-- INQUIRY / CONTACT FORM SECTION -->
        <InquirySection v-if="page.show_inquiry" />
    </PublicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import InquirySection from "@/Components/Public/InquirySection.vue";

defineProps({
    page: {
        type: Object,
        default: () => ({
            heading: "Kami siap membantu Anda.",
            description: null,
            image: null,
            content_left: null,
            content_right: null,
            show_inquiry: true,
        }),
    },
});
</script>

<style scoped>
/* ── About long description (v-html rendered content) ────── */
.about-long-desc :deep(p) {
    color: #475569;
    line-height: 1.75;
    margin-bottom: 0.75rem;
}
@media (min-width: 768px) {
    .about-long-desc :deep(p) {
        font-size: 20px;
    }
}
.about-long-desc :deep(h3) {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin: 1.25rem 0 0.5rem;
}
.about-long-desc :deep(ul) {
    list-style: disc;
    padding-left: 1.25rem;
    color: #475569;
    line-height: 1.75;
    margin-bottom: 0.75rem;
}
.about-long-desc :deep(ol) {
    list-style: decimal;
    padding-left: 1.25rem;
    color: #475569;
    line-height: 1.75;
    margin-bottom: 0.75rem;
}
.about-long-desc :deep(strong) {
    font-weight: 700;
}
.about-long-desc :deep(em) {
    font-style: italic;
}
</style>
