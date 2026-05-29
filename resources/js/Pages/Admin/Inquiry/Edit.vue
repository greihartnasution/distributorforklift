<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({ inquiry: Object });

const form = useForm({
    _method: "PUT",
    heading: props.inquiry.heading ?? "",
    intro:   props.inquiry.intro ?? "",
    hours:   props.inquiry.hours ?? "",
});

function submit() {
    form.post(route("admin.inquiry.update"));
}
</script>

<template>
    <Head title="Pengaturan Inquiry" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Sistem</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">Formulir Inquiry</span>
            </div>
        </template>

        <div class="max-w-2xl mx-auto">
            <div class="mb-5">
                <h1 class="text-lg font-black text-slate-900">Formulir Inquiry</h1>
                <p class="text-xs text-slate-400 mt-0.5">Narasi section formulir kontak yang tampil di seluruh halaman.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Teks Section</p>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Heading
                            <span class="font-normal text-slate-400 ml-1">— judul utama section</span>
                        </label>
                        <input v-model="form.heading" type="text"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <p v-if="form.errors.heading" class="mt-1 text-xs text-red-500">{{ form.errors.heading }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Intro
                            <span class="font-normal text-slate-400 ml-1">— paragraf di bawah heading</span>
                        </label>
                        <textarea v-model="form.intro" rows="3"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"></textarea>
                        <p v-if="form.errors.intro" class="mt-1 text-xs text-red-500">{{ form.errors.intro }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Jam Operasional
                            <span class="font-normal text-slate-400 ml-1">— contoh: Senin hingga Jumat</span>
                        </label>
                        <input v-model="form.hours" type="text"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <p v-if="form.errors.hours" class="mt-1 text-xs text-red-500">{{ form.errors.hours }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end pb-2">
                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 disabled:opacity-50 text-white text-sm font-bold px-6 py-2.5 transition-colors duration-150">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        {{ form.processing ? "Menyimpan..." : "Simpan Perubahan" }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
