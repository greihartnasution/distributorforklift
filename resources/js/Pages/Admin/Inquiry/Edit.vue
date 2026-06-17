<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({ inquiry: Object });

const form = useForm({
    _method:             "PUT",
    heading:             props.inquiry.heading             ?? "",
    intro:               props.inquiry.intro               ?? "",
    hours:               props.inquiry.hours               ?? "",
    consultant_name:     props.inquiry.consultant_name     ?? "",
    consultant_title:    props.inquiry.consultant_title    ?? "",
    consultant_company:  props.inquiry.consultant_company  ?? "",
    consultant_phone:    props.inquiry.consultant_phone    ?? "",
    consultant_email:    props.inquiry.consultant_email    ?? "",
    consultant_photo:    null,
    clear_photo:         false,
    instagram:           props.inquiry.instagram           ?? "",
    tiktok:              props.inquiry.tiktok              ?? "",
    youtube:             props.inquiry.youtube             ?? "",
    facebook:            props.inquiry.facebook            ?? "",
});

const photoPreview = ref(props.inquiry.consultant_photo ?? null);
const photoInput   = ref(null);

function onPhotoChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.consultant_photo = file;
    form.clear_photo      = false;
    photoPreview.value    = URL.createObjectURL(file);
}

function clearPhoto() {
    form.consultant_photo = null;
    form.clear_photo      = true;
    photoPreview.value    = null;
    if (photoInput.value) photoInput.value.value = "";
}

function submit() {
    form.post(route("admin.inquiry.update"), { forceFormData: true });
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

                <!-- Teks Section -->
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

                <!-- Konsultan -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Kartu Konsultan</p>

                    <!-- Foto -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Foto Konsultan</label>
                        <div v-if="photoPreview" class="relative w-20 h-20 mb-3">
                            <img :src="photoPreview" alt="Foto" class="w-20 h-20 rounded-full object-cover border border-gray-200" />
                            <button type="button" @click="clearPhoto"
                                class="absolute -top-1 -right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                ×
                            </button>
                        </div>
                        <input ref="photoInput" type="file" accept="image/*" @change="onPhotoChange"
                            class="block text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                        <p class="text-xs text-slate-400 mt-1">Format JPG, PNG, WebP. Maks 1 MB. Disarankan foto square.</p>
                        <p v-if="form.errors.consultant_photo" class="mt-1 text-xs text-red-500">{{ form.errors.consultant_photo }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nama</label>
                            <input v-model="form.consultant_name" type="text" placeholder="Marchelya Ariny"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jabatan</label>
                            <input v-model="form.consultant_title" type="text" placeholder="Account Manager"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Perusahaan</label>
                            <input v-model="form.consultant_company" type="text" placeholder="PT Eurotrucks Mekanika Prima"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Mobile</label>
                            <input v-model="form.consultant_phone" type="text" placeholder="085156373175"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.consultant_phone" class="mt-1 text-xs text-red-500">{{ form.errors.consultant_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Email</label>
                            <input v-model="form.consultant_email" type="email" placeholder="marchelya@eurotrucks-id.com"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.consultant_email" class="mt-1 text-xs text-red-500">{{ form.errors.consultant_email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Media Sosial -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Media Sosial Konsultan</p>
                    <p class="text-xs text-slate-400 -mt-2">URL lengkap, contoh: https://www.instagram.com/username. Kosongkan jika tidak ada.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mb-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                Instagram
                            </label>
                            <input v-model="form.instagram" type="url" placeholder="https://www.instagram.com/username"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.instagram" class="mt-1 text-xs text-red-500">{{ form.errors.instagram }}</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mb-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.19a8.27 8.27 0 004.84 1.55V6.29a4.85 4.85 0 01-1.07-.6z"/></svg>
                                TikTok
                            </label>
                            <input v-model="form.tiktok" type="url" placeholder="https://www.tiktok.com/@username"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.tiktok" class="mt-1 text-xs text-red-500">{{ form.errors.tiktok }}</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mb-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                YouTube
                            </label>
                            <input v-model="form.youtube" type="url" placeholder="https://www.youtube.com/@channel"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.youtube" class="mt-1 text-xs text-red-500">{{ form.errors.youtube }}</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mb-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </label>
                            <input v-model="form.facebook" type="url" placeholder="https://www.facebook.com/page"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.facebook" class="mt-1 text-xs text-red-500">{{ form.errors.facebook }}</p>
                        </div>
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
