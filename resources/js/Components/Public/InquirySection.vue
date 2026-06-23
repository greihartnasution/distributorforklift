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
const consultantPhoto   = computed(() => props.inquiry_settings?.consultant_photo || null);
const socialInstagram = computed(() => props.inquiry_settings?.instagram || null);
const socialTiktok    = computed(() => props.inquiry_settings?.tiktok    || null);
const socialYoutube   = computed(() => props.inquiry_settings?.youtube   || null);
const socialFacebook  = computed(() => props.inquiry_settings?.facebook  || null);
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
                    <div v-if="consultantName" class="bg-white p-5 mb-12">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4 sm:gap-6">
                        <div class="shrink-0 flex sm:flex-col items-center gap-4 sm:gap-3">
                            <img v-if="consultantPhoto"
                                :src="consultantPhoto"
                                alt="Konsultan"
                                class="w-28 h-28 rounded-full object-cover border-2 border-gray-100"
                            />
                            <div v-else class="w-28 h-28 rounded-full bg-slate-200 flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <!-- Social media icons -->
                            <div class="flex gap-1.5">
                                <a v-if="socialInstagram" :href="socialInstagram" target="_blank" rel="noopener" class="w-7 h-7 bg-slate-100 hover:bg-orange-600 flex items-center justify-center rounded-full transition-colors duration-200 group" title="Instagram">
                                    <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                                <a v-if="socialTiktok" :href="socialTiktok" target="_blank" rel="noopener" class="w-7 h-7 bg-slate-100 hover:bg-orange-600 flex items-center justify-center rounded-full transition-colors duration-200 group" title="TikTok">
                                    <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.19a8.27 8.27 0 004.84 1.55V6.29a4.85 4.85 0 01-1.07-.6z"/>
                                    </svg>
                                </a>
                                <a v-if="socialYoutube" :href="socialYoutube" target="_blank" rel="noopener" class="w-7 h-7 bg-slate-100 hover:bg-orange-600 flex items-center justify-center rounded-full transition-colors duration-200 group" title="YouTube">
                                    <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                </a>
                                <a v-if="socialFacebook" :href="socialFacebook" target="_blank" rel="noopener" class="w-7 h-7 bg-slate-100 hover:bg-orange-600 flex items-center justify-center rounded-full transition-colors duration-200 group" title="Facebook">
                                    <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
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
