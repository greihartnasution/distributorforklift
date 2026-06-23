<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import RichTextEditor from "@/Components/Admin/RichTextEditor.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    contact: Object,
});

const form = useForm({
    _method:       "PUT",
    heading:       props.contact.heading ?? "",
    description:   props.contact.description ?? "",
    image:         (() => { const v = props.contact.image; return v?.startsWith('http') ? v : ""; })(),
    image_file:    null,
    clear_image:   false,
    content_left:  props.contact.content_left ?? "",
    content_right: props.contact.content_right ?? "",
    show_inquiry:  props.contact.show_inquiry ?? true,
});

const imageMode = ref(
    (() => { const v = props.contact.image; return v?.startsWith('http') ? 'url' : 'upload'; })()
);
const imagePreview = ref(null);
const existingImage = ref(
    (() => { const v = props.contact.image; return v && !v.startsWith('http') ? v : null; })()
);

function switchImageMode(mode) {
    imageMode.value = mode;
    if (mode === 'url') {
        form.image_file = null;
        imagePreview.value = null;
        existingImage.value = null;
    } else {
        form.image = "";
    }
}

function onImageChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.image_file = file;
    form.image = "";
    form.clear_image = false;
    const reader = new FileReader();
    reader.onload = (ev) => (imagePreview.value = ev.target.result);
    reader.readAsDataURL(file);
}

function clearNewImage() {
    form.image_file = null;
    form.image = "";
    form.clear_image = true;
    imagePreview.value = null;
    existingImage.value = null;
}

function submit() {
    form.post(route("admin.contact.update"), { forceFormData: true });
}
</script>

<template>
    <Head title="Halaman Kontak" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Konten</span>
                <svg
                    class="w-3 h-3 text-slate-300"
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
                <span class="text-xs font-semibold text-slate-700"
                    >Halaman Kontak</span
                >
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <div class="flex flex-col gap-2 mb-5">
                <div>
                    <h1 class="text-lg font-black text-slate-900">
                        Halaman Kontak
                    </h1>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Kelola konten halaman /kontak.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Gambar Hero -->
                <div class="bg-white border border-gray-100 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide"
                            >Gambar Hero</label
                        >
                        <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                            <button type="button" @click="switchImageMode('upload')"
                                class="px-3 py-1.5 transition-colors duration-150"
                                :class="imageMode === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                Upload
                            </button>
                            <button type="button" @click="switchImageMode('url')"
                                class="px-3 py-1.5 transition-colors duration-150"
                                :class="imageMode === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                URL Eksternal
                            </button>
                        </div>
                    </div>

                    <template v-if="imageMode === 'upload'">
                        <div v-if="imagePreview" class="mb-3 relative group w-full">
                            <img
                                :src="imagePreview"
                                class="w-full h-52 object-cover rounded-md"
                            />
                            <div
                                class="absolute top-2 left-2 bg-orange-600 text-white text-[10px] font-bold px-2 py-0.5 rounded"
                            >
                                Gambar Baru
                            </div>
                            <button
                                type="button"
                                @click="clearNewImage"
                                class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                            >
                                <svg
                                    class="w-3.5 h-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <div
                            v-else-if="existingImage"
                            class="mb-3 relative group w-full"
                        >
                            <img
                                :src="existingImage"
                                class="w-full h-52 object-cover rounded-md"
                            />
                            <div
                                class="absolute top-2 left-2 bg-slate-700 text-white text-[10px] font-bold px-2 py-0.5 rounded"
                            >
                                Gambar Saat Ini
                            </div>
                        </div>

                        <label
                            class="flex items-center gap-3 w-full border border-dashed border-gray-200 rounded-md px-4 py-3 cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-colors"
                        >
                            <svg
                                class="w-5 h-5 text-gray-300 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                />
                            </svg>
                            <div>
                                <span class="text-xs font-medium text-slate-500">{{
                                    existingImage ? "Ganti gambar" : "Upload gambar"
                                }}</span>
                                <span class="text-[10px] text-slate-300 ml-1"
                                    >JPG, PNG, WEBP — Maks. 3MB</span
                                >
                            </div>
                            <input
                                type="file"
                                class="hidden"
                                accept="image/*"
                                @change="onImageChange"
                            />
                        </label>
                    </template>
                    <template v-else>
                        <input v-model="form.image" type="url" placeholder="https://example.com/gambar.jpg"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <div v-if="form.image" class="mt-3 w-full h-52 border border-gray-200 rounded-md overflow-hidden bg-gray-50">
                            <img :src="form.image" class="w-full h-full object-cover"
                                @error="(e) => e.target.style.display = 'none'" />
                        </div>
                    </template>
                    <p
                        v-if="form.errors.image"
                        class="mt-1.5 text-xs text-red-500"
                    >
                        {{ form.errors.image }}
                    </p>
                    <p v-if="form.errors.image_file" class="mt-1.5 text-xs text-red-500">
                        {{ form.errors.image_file }}
                    </p>
                </div>

                <!-- Informasi Halaman -->
                <div
                    class="bg-white border border-gray-100 rounded-lg p-5 space-y-4"
                >
                    <p
                        class="text-xs font-bold text-slate-700 uppercase tracking-wide"
                    >
                        Informasi Halaman
                    </p>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Heading Statement
                            <span class="font-normal text-slate-400 ml-1">— teks besar bold di bawah hero</span>
                        </label>
                        <textarea
                            v-model="form.heading"
                            rows="2"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"
                        ></textarea>
                        <p v-if="form.errors.heading" class="mt-1 text-xs text-red-500">
                            {{ form.errors.heading }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Deskripsi
                            <span class="font-normal text-slate-400 ml-1">— teks pendek di bawah judul</span>
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-xs text-red-500">
                            {{ form.errors.description }}
                        </p>
                    </div>
                </div>

                <!-- Konten -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Konten Halaman</p>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500">Kolom Kiri</label>
                            <RichTextEditor v-model="form.content_left" />
                            <p v-if="form.errors.content_left" class="text-xs text-red-500">
                                {{ form.errors.content_left }}
                            </p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500">Kolom Kanan</label>
                            <RichTextEditor v-model="form.content_right" />
                            <p v-if="form.errors.content_right" class="text-xs text-red-500">
                                {{ form.errors.content_right }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Publikasi -->
                <div
                    class="bg-white border border-gray-100 rounded-lg p-5 space-y-4"
                >
                    <p
                        class="text-xs font-bold text-slate-700 uppercase tracking-wide"
                    >
                        Pengaturan Publikasi
                    </p>

                    <!-- show_inquiry toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600">
                                Sertakan Formulir Kontak
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{
                                    form.show_inquiry
                                        ? "Formulir kontak ditampilkan di halaman ini"
                                        : "Halaman tidak menampilkan formulir kontak"
                                }}
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="form.show_inquiry = !form.show_inquiry"
                            class="relative w-9 h-5 rounded-full transition-colors duration-200 shrink-0"
                            :class="
                                form.show_inquiry
                                    ? 'bg-orange-600'
                                    : 'bg-gray-200'
                            "
                        >
                            <span
                                class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                                :class="
                                    form.show_inquiry
                                        ? 'translate-x-4'
                                        : 'translate-x-0'
                                "
                            ></span>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end pb-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 disabled:opacity-50 text-white text-sm font-bold px-6 py-2.5 transition-colors duration-150"
                    >
                        <svg
                            v-if="form.processing"
                            class="w-4 h-4 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v8z"
                            />
                        </svg>
                        {{
                            form.processing
                                ? "Menyimpan..."
                                : "Simpan Perubahan"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
