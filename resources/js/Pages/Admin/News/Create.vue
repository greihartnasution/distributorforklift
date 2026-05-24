<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import RichTextEditor from "@/Components/Admin/RichTextEditor.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const CATEGORIES = ["Industri", "Produk", "Tips & Panduan", "Perusahaan", "Promo"];

const form = useForm({
    title: "",
    slug: "",
    category: "",
    excerpt: "",
    content: "",
    image: null,
    is_published: false,
    published_at: new Date().toISOString().slice(0, 10),
});

const imagePreview = ref(null);

function onImageChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.image = file;
    const reader = new FileReader();
    reader.onload = (ev) => (imagePreview.value = ev.target.result);
    reader.readAsDataURL(file);
}

function clearImage() {
    form.image = null;
    imagePreview.value = null;
}

function autoSlug() {
    if (form.slug) return;
    form.slug = form.title
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, "")
        .trim()
        .replace(/\s+/g, "-");
}

function submit() {
    form.post(route("admin.news.store"), { forceFormData: true });
}
</script>

<template>
    <Head title="Tambah Artikel" />

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
                <Link
                    :href="route('admin.news.index')"
                    class="text-xs text-slate-400 hover:text-orange-600 transition-colors"
                    >Berita & Artikel</Link
                >
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
                <span class="text-xs font-semibold text-slate-700">Tambah</span>
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <div class="flex flex-col gap-2 mb-5">
                <Link
                    :href="route('admin.news.index')"
                    class="text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors self-start"
                >
                    ← Kembali
                </Link>
                <div>
                    <h1 class="text-lg font-black text-slate-900">
                        Tambah Artikel
                    </h1>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Buat artikel berita baru untuk ditampilkan di website.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Gambar Featured -->
                <div class="bg-white border border-gray-100 rounded-lg p-5">
                    <label
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-3"
                    >
                        Gambar Artikel
                    </label>

                    <div v-if="imagePreview" class="mb-3 relative group w-full">
                        <img
                            :src="imagePreview"
                            class="w-full h-52 object-cover rounded-md"
                        />
                        <button
                            type="button"
                            @click="clearImage"
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

                    <label
                        v-else
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 rounded-md cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-colors"
                    >
                        <svg
                            class="w-8 h-8 text-gray-300 mb-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <span class="text-xs font-medium text-slate-400"
                            >Klik untuk upload gambar</span
                        >
                        <span class="text-[10px] text-slate-300 mt-0.5"
                            >JPG, PNG, WEBP — Maks. 3MB</span
                        >
                        <input
                            type="file"
                            class="hidden"
                            accept="image/*"
                            @change="onImageChange"
                        />
                    </label>

                    <p v-if="form.errors.image" class="mt-1.5 text-xs text-red-500">
                        {{ form.errors.image }}
                    </p>
                </div>

                <!-- Info Artikel -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Informasi Artikel
                    </p>

                    <!-- Judul -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Judul artikel..."
                            @blur="autoSlug"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-xs text-red-500">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Slug URL
                            <span class="font-normal text-slate-400 ml-1"
                                >— kosongkan untuk generate otomatis</span
                            >
                        </label>
                        <div class="flex items-center border border-gray-200 rounded focus-within:border-orange-500 focus-within:ring-1 focus-within:ring-orange-500 transition-colors overflow-hidden">
                            <span class="px-3 py-2.5 text-xs text-slate-400 bg-gray-50 border-r border-gray-200 whitespace-nowrap">/berita/</span>
                            <input
                                v-model="form.slug"
                                type="text"
                                placeholder="judul-artikel"
                                class="flex-1 px-3 py-2.5 text-sm text-slate-900 focus:outline-none bg-transparent"
                            />
                        </div>
                        <p v-if="form.errors.slug" class="mt-1 text-xs text-red-500">
                            {{ form.errors.slug }}
                        </p>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.category"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors bg-white"
                        >
                            <option value="" disabled>Pilih kategori...</option>
                            <option v-for="cat in CATEGORIES" :key="cat" :value="cat">
                                {{ cat }}
                            </option>
                        </select>
                        <p v-if="form.errors.category" class="mt-1 text-xs text-red-500">
                            {{ form.errors.category }}
                        </p>
                    </div>

                    <!-- Ringkasan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Ringkasan / Excerpt
                            <span class="font-normal text-slate-400 ml-1"
                                >— ditampilkan pada daftar berita</span
                            >
                        </label>
                        <textarea
                            v-model="form.excerpt"
                            rows="3"
                            maxlength="500"
                            placeholder="Ringkasan singkat artikel ini..."
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"
                        ></textarea>
                        <div class="flex justify-between mt-1">
                            <p v-if="form.errors.excerpt" class="text-xs text-red-500">
                                {{ form.errors.excerpt }}
                            </p>
                            <p class="text-[10px] text-slate-300 ml-auto">
                                {{ form.excerpt.length }}/500
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Konten -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-3">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Konten Artikel <span class="text-red-500">*</span>
                    </p>
                    <RichTextEditor v-model="form.content" />
                    <p v-if="form.errors.content" class="text-xs text-red-500">
                        {{ form.errors.content }}
                    </p>
                </div>

                <!-- Pengaturan Publikasi -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Pengaturan Publikasi
                    </p>

                    <!-- Status -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600">Status Publikasi</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{ form.is_published ? "Artikel akan ditampilkan di website" : "Artikel disimpan sebagai draft" }}
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="form.is_published = !form.is_published"
                            class="relative w-9 h-5 rounded-full transition-colors duration-200 shrink-0"
                            :class="form.is_published ? 'bg-orange-600' : 'bg-gray-200'"
                        >
                            <span
                                class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                                :class="form.is_published ? 'translate-x-4' : 'translate-x-0'"
                            ></span>
                        </button>
                    </div>

                    <!-- Tanggal Publikasi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Tanggal Publikasi
                        </label>
                        <input
                            v-model="form.published_at"
                            type="date"
                            class="border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors"
                        />
                        <p v-if="form.errors.published_at" class="mt-1 text-xs text-red-500">
                            {{ form.errors.published_at }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-8 pb-2">
                    <Link
                        :href="route('admin.news.index')"
                        class="text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        Batal
                    </Link>
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
                        {{ form.processing ? "Menyimpan..." : "Simpan Artikel" }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
