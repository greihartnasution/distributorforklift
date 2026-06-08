<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import InquiryForm from "@/Components/Public/InquiryForm.vue";

const props = usePage().props;

const heading = computed(
    () => props.inquiry_settings?.heading || "Kami siap membantu Anda.",
);
const intro = computed(
    () =>
        props.inquiry_settings?.intro ||
        "Apakah Anda memiliki pertanyaan atau membutuhkan saran personal? Tim ahli kami siap membantu Anda menemukan solusi yang tepat — secara individual dan cepat.",
);
const hours = computed(
    () => props.inquiry_settings?.hours || "Senin hingga Jumat",
);
const phone = computed(() => props.site_settings?.phone || "+62 21 000 0000");
const whatsapp = computed(() => props.site_settings?.whatsapp || "6200000000");

const phoneHref = computed(
    () => `tel:${phone.value.replace(/\s|\-|\(|\)/g, "")}`,
);

const consultantName    = computed(() => props.inquiry_settings?.consultant_name    || "");
const consultantTitle   = computed(() => props.inquiry_settings?.consultant_title   || "");
const consultantCompany = computed(() => props.inquiry_settings?.consultant_company || "");
const consultantPhone   = computed(() => props.inquiry_settings?.consultant_phone   || "");
const consultantEmail   = computed(() => props.inquiry_settings?.consultant_email   || "");
const consultantPhoto   = computed(() =>
    props.inquiry_settings?.consultant_photo
        ? '/storage/' + props.inquiry_settings.consultant_photo
        : null
);
const linksLabel = computed(() => props.inquiry_settings?.links_label || "");
const links = computed(() =>
    [props.inquiry_settings?.link_1, props.inquiry_settings?.link_2, props.inquiry_settings?.link_3]
        .filter(Boolean)
);
</script>

<template>
    <section id="inquiry" style="background-color: #f2f2f2" class="py-20">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
            <div
                class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start"
            >
                <div>
                    <h2
                        class="text-xl md:text-3xl font-black text-slate-900 leading-tight mb-5"
                    >
                        {{ heading }}
                    </h2>
                    <p class="text-slate-500 leading-relaxed mb-10 md:text-xl">
                        {{ intro }}
                    </p>
                    <h3
                        class="text-lg md:text-3xl font-black text-slate-900 mb-1"
                    >
                        Hubungi konsultan kami
                    </h3>
                    <p class="md:text-xl text-slate-500 mb-4">
                        Siap membantu Anda — {{ hours }}
                    </p>
                    <a
                        :href="phoneHref"
                        class="inline-flex items-center gap-3 bg-orange-600 hover:bg-orange-700 text-white md:text-xl font-bold px-6 py-3 transition-colors duration-200 mb-8"
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
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                            />
                        </svg>
                        {{ phone }}
                    </a>

                    <!-- Consultant Card -->
                    <div v-if="consultantName" class="flex items-start gap-5 bg-white p-5 mb-12">
                        <div class="shrink-0">
                            <img v-if="consultantPhoto"
                                :src="consultantPhoto"
                                alt="Konsultan"
                                class="w-20 h-20 rounded-full object-cover border-2 border-gray-100"
                            />
                            <div v-else class="w-20 h-20 rounded-full bg-slate-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-slate-900 text-base">{{ consultantName }}</p>
                            <p v-if="consultantTitle" class="text-sm text-slate-500">{{ consultantTitle }}</p>
                            <p v-if="consultantCompany" class="text-sm text-slate-500 mb-3">{{ consultantCompany }}</p>
                            <div class="space-y-1 text-sm text-slate-600">
                                <p v-if="consultantPhone">Mobile : <a :href="`tel:${consultantPhone}`" class="font-semibold hover:text-orange-600 transition-colors">{{ consultantPhone }}</a></p>
                                <p v-if="consultantEmail">Email : <a :href="`mailto:${consultantEmail}`" class="font-semibold hover:text-orange-600 transition-colors">{{ consultantEmail }}</a></p>
                            </div>
                            <div v-if="links.length" class="mt-3 pt-3 border-t border-gray-100">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                    {{ linksLabel || 'For further information please visit' }}
                                </p>
                                <div class="space-y-0.5 text-sm font-semibold text-orange-600">
                                    <p v-for="link in links" :key="link">{{ link }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3
                        class="text-lg md:text-3xl font-black text-slate-900 mb-4"
                    >
                        Apa yang terjadi setelah Anda mengirim?
                    </h3>
                    <ol class="space-y-2.5">
                        <li class="md:text-xl text-slate-500 italic">
                            1. Formulir membuka WhatsApp dengan pesan yang sudah
                            terisi otomatis
                        </li>
                        <li class="md:text-xl text-slate-500 italic">
                            2. Cukup tekan <em>Kirim</em> — tim kami langsung
                            menerima permintaan Anda
                        </li>
                        <li class="md:text-xl text-slate-500 italic">
                            3. Anda akan mendapat respons personal dalam 1×24
                            jam kerja
                        </li>
                    </ol>
                </div>

                <div class="bg-white p-5 sm:p-8 lg:p-10">
                    <h3
                        class="text-xl md:text-3xl font-black text-slate-900 mb-5"
                    >
                        Gunakan formulir kontak
                    </h3>
                    <p class="md:text-xl text-slate-400 mb-10">
                        Tulis kepada kami — kami akan segera menghubungi Anda
                    </p>
                    <InquiryForm :whatsapp-number="whatsapp" />
                </div>
            </div>
        </div>
    </section>
</template>
