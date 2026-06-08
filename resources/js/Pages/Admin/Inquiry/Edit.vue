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
    links_label:         props.inquiry.links_label         ?? "",
    link_1:              props.inquiry.link_1              ?? "",
    link_2:              props.inquiry.link_2              ?? "",
    link_3:              props.inquiry.link_3              ?? "",
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

                <!-- Tautan Tambahan -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Tautan Tambahan</p>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Label
                            <span class="font-normal text-slate-400 ml-1">— teks di atas daftar tautan</span>
                        </label>
                        <input v-model="form.links_label" type="text" placeholder="For further information please visit"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                    </div>

                    <div class="space-y-2">
                        <div v-for="n in [1, 2, 3]" :key="n">
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Tautan {{ n }}</label>
                            <input v-model="form[`link_${n}`]" type="text" :placeholder="['still.co.uk', 'eurotrucks-id.com', 'tiktok.com/@forkliftstill'][n - 1]"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
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
