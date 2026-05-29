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
                        class="inline-flex items-center gap-3 bg-orange-600 hover:bg-orange-700 text-white md:text-xl font-bold px-6 py-3 transition-colors duration-200 mb-12"
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
