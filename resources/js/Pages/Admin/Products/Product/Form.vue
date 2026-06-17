<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import RichTextEditor from "@/Components/Admin/RichTextEditor.vue";

const props = defineProps({
    product:           { type: Object, default: null },
    systemCategories:  { type: Array,  default: () => [] },
});

const isEdit = !!props.product;

const selectedSystemId = ref(props.product?.system_category_id ?? "");

const selectedSystem = computed(() =>
    props.systemCategories.find((s) => s.id == selectedSystemId.value) ?? null
);

const availableSubs = computed(() => selectedSystem.value?.children ?? []);

const selectedSub = computed(() =>
    availableSubs.value.find((s) => s.id == form.product_category_id) ?? null
);

const form = useForm({
    product_category_id:   props.product?.product_category_id ?? "",
    name:                  props.product?.name                ?? "",
    slug:                  props.product?.slug                ?? "",
    short_description:     props.product?.short_description   ?? "",
    full_description:      props.product?.full_description     ?? "",
    specs:                 props.product?.specs               ?? [],
    highlights:            (props.product?.highlights ?? []).map(h => ({
                               title:       h.title       ?? "",
                               description: h.description ?? "",
                               image_url:   h.image_url   ?? "",
                           })),
    highlight_image:       (() => { const v = props.product?.highlight_image; return v?.startsWith('http') ? v : ""; })(),
    highlight_image_file:  null,
    clear_highlight_image: false,
    highlight_item_images: (props.product?.highlights ?? []).map(() => null),
    details:                (props.product?.details ?? []).map(d => ({
                               title:      d.title      ?? "",
                               content:    d.content    ?? "",
                               icon_image: d.icon_image ?? "",
                               image:      d.image      ?? "",
                           })),
    detail_icon_images:    (props.product?.details ?? []).map(() => null),
    detail_body_images:    (props.product?.details ?? []).map(() => null),
    solutions_title:        props.product?.solutions_title       ?? "",
    solutions_description:  props.product?.solutions_description ?? "",
    solutions:               (props.product?.solutions ?? []).map(s => ({
                               label:     s.label     ?? "",
                               video_url: s.video_url ?? "",
                               content:   s.content   ?? "",
                           })),
    media_items:             (props.product?.media_items ?? []).map(m => ({
                               type:      m.type      ?? "image",
                               title:     m.title      ?? "",
                               image:     m.image      ?? "",
                               video_url: m.video_url  ?? "",
                           })),
    media_images:           (props.product?.media_items ?? []).map(() => null),
    model_overview:          (props.product?.model_overview ?? []).map(s => ({
                               label: s.label ?? "",
                               value: s.value ?? "",
                           })),
    downloads:               (props.product?.downloads ?? []).map(d => ({
                               title: d.title ?? "",
                               file:  d.file  ?? "",
                               size:  d.size  ?? "",
                           })),
    download_files:         (props.product?.downloads ?? []).map(() => null),
    is_active:             props.product?.is_active           ?? true,
    image:                 null,
    clear_image:           false,
    image_url:             props.product?.image_url           ?? "",
    video_url:             props.product?.video_url           ?? "",
});

watch(selectedSystemId, () => {
    form.product_category_id = "";
});

// image_mode: 'upload' | 'url'
const imageMode    = ref(props.product?.image_url ? 'url' : 'upload');
const imagePreview = ref(props.product?.image_preview ?? null);
const imageInput   = ref(null);

function onImageChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.image       = file;
    form.clear_image = false;
    form.image_url   = "";
    imagePreview.value = URL.createObjectURL(file);
}

function clearImage() {
    form.image       = null;
    form.clear_image = true;
    imagePreview.value = null;
    if (imageInput.value) imageInput.value.value = "";
}

function switchImageMode(mode) {
    imageMode.value = mode;
    if (mode === 'url') {
        clearImage();
    } else {
        form.image_url = "";
    }
}

// Specs management
function addSpec() {
    form.specs.push({ label: "", value: "" });
}

function removeSpec(index) {
    form.specs.splice(index, 1);
}

// Highlight section image
const highlightImageMode = ref(
    (() => { const v = props.product?.highlight_image; return v?.startsWith('http') ? 'url' : 'upload'; })()
);
const highlightImagePreview = ref(
    (() => { const v = props.product?.highlight_image; return v && !v.startsWith('http') ? v : null; })()
);
const highlightImageInput = ref(null);

function switchHighlightImageMode(mode) {
    highlightImageMode.value = mode;
    if (mode === 'url') {
        form.highlight_image_file = null;
        highlightImagePreview.value = null;
    } else {
        form.highlight_image = "";
    }
}

function onHighlightImageChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.highlight_image_file  = file;
    form.highlight_image       = "";
    highlightImagePreview.value = URL.createObjectURL(file);
}

function clearHighlightImage() {
    form.highlight_image_file    = null;
    form.highlight_image         = "";
    form.clear_highlight_image   = true;
    highlightImagePreview.value  = null;
    if (highlightImageInput.value) highlightImageInput.value.value = "";
}

// Per-item image modes / previews
const itemImageModes = ref(
    (props.product?.highlights ?? []).map(h =>
        h.image_url ? (h.image_url.startsWith('http') ? 'url' : 'upload') : 'upload'
    )
);
const itemImagePreviews = ref(
    (props.product?.highlights ?? []).map(h =>
        h.image_url && !h.image_url.startsWith('http') ? h.image_url : null
    )
);

function switchItemMode(i, mode) {
    itemImageModes.value[i] = mode;
    if (mode === 'url') {
        form.highlight_item_images[i] = null;
        itemImagePreviews.value[i] = null;
    } else {
        form.highlights[i].image_url = "";
    }
}

function onItemImageChange(i, e) {
    const file = e.target.files[0];
    if (!file) return;
    form.highlight_item_images[i] = file;
    form.highlights[i].image_url  = "";
    itemImagePreviews.value[i]    = URL.createObjectURL(file);
}

function clearItemImage(i) {
    form.highlight_item_images[i] = null;
    form.highlights[i].image_url  = "";
    itemImagePreviews.value[i]    = null;
}

// Highlights management
function addHighlight() {
    form.highlights.push({ title: "", description: "", image_url: "" });
    form.highlight_item_images.push(null);
    itemImageModes.value.push('upload');
    itemImagePreviews.value.push(null);
}

function removeHighlight(index) {
    form.highlights.splice(index, 1);
    form.highlight_item_images.splice(index, 1);
    itemImageModes.value.splice(index, 1);
    itemImagePreviews.value.splice(index, 1);
}

// Detail items — per-item icon (header) & image (body) modes / previews
const detailIconModes = ref(
    (props.product?.details ?? []).map(d =>
        d.icon_image ? (d.icon_image.startsWith('http') ? 'url' : 'upload') : 'upload'
    )
);
const detailIconPreviews = ref(
    (props.product?.details ?? []).map(d =>
        d.icon_image && !d.icon_image.startsWith('http') ? d.icon_image : null
    )
);
const detailImageModes = ref(
    (props.product?.details ?? []).map(d =>
        d.image ? (d.image.startsWith('http') ? 'url' : 'upload') : 'upload'
    )
);
const detailImagePreviews = ref(
    (props.product?.details ?? []).map(d =>
        d.image && !d.image.startsWith('http') ? d.image : null
    )
);

function switchDetailIconMode(i, mode) {
    detailIconModes.value[i] = mode;
    if (mode === 'url') {
        form.detail_icon_images[i] = null;
        detailIconPreviews.value[i] = null;
    } else {
        form.details[i].icon_image = "";
    }
}

function onDetailIconChange(i, e) {
    const file = e.target.files[0];
    if (!file) return;
    form.detail_icon_images[i] = file;
    form.details[i].icon_image = "";
    detailIconPreviews.value[i] = URL.createObjectURL(file);
}

function clearDetailIcon(i) {
    form.detail_icon_images[i] = null;
    form.details[i].icon_image = "";
    detailIconPreviews.value[i] = null;
}

function switchDetailImageMode(i, mode) {
    detailImageModes.value[i] = mode;
    if (mode === 'url') {
        form.detail_body_images[i] = null;
        detailImagePreviews.value[i] = null;
    } else {
        form.details[i].image = "";
    }
}

function onDetailImageChange(i, e) {
    const file = e.target.files[0];
    if (!file) return;
    form.detail_body_images[i] = file;
    form.details[i].image = "";
    detailImagePreviews.value[i] = URL.createObjectURL(file);
}

function clearDetailImage(i) {
    form.detail_body_images[i] = null;
    form.details[i].image = "";
    detailImagePreviews.value[i] = null;
}

// Detail items management
function addDetail() {
    form.details.push({ title: "", content: "", icon_image: "", image: "" });
    form.detail_icon_images.push(null);
    form.detail_body_images.push(null);
    detailIconModes.value.push('upload');
    detailIconPreviews.value.push(null);
    detailImageModes.value.push('upload');
    detailImagePreviews.value.push(null);
}

function removeDetail(index) {
    form.details.splice(index, 1);
    form.detail_icon_images.splice(index, 1);
    form.detail_body_images.splice(index, 1);
    detailIconModes.value.splice(index, 1);
    detailIconPreviews.value.splice(index, 1);
    detailImageModes.value.splice(index, 1);
    detailImagePreviews.value.splice(index, 1);
}

// Solutions (tabs) management
function addSolution() {
    form.solutions.push({ label: "", video_url: "", content: "" });
}

function removeSolution(index) {
    form.solutions.splice(index, 1);
}

// Media Center management
const mediaImageModes = ref(
    (props.product?.media_items ?? []).map(m =>
        m.image ? (m.image.startsWith('http') ? 'url' : 'upload') : 'upload'
    )
);
const mediaImagePreviews = ref(
    (props.product?.media_items ?? []).map(m =>
        m.image && !m.image.startsWith('http') ? m.image : null
    )
);

function switchMediaImageMode(i, mode) {
    mediaImageModes.value[i] = mode;
    if (mode === 'url') {
        form.media_images[i] = null;
        mediaImagePreviews.value[i] = null;
    } else {
        form.media_items[i].image = "";
    }
}

function onMediaImageChange(i, e) {
    const file = e.target.files[0];
    if (!file) return;
    form.media_images[i] = file;
    form.media_items[i].image = "";
    mediaImagePreviews.value[i] = URL.createObjectURL(file);
}

function clearMediaImage(i) {
    form.media_images[i] = null;
    form.media_items[i].image = "";
    mediaImagePreviews.value[i] = null;
}

function addMedia(type) {
    form.media_items.push({ type, title: "", image: "", video_url: "" });
    form.media_images.push(null);
    mediaImageModes.value.push('upload');
    mediaImagePreviews.value.push(null);
}

function removeMedia(index) {
    form.media_items.splice(index, 1);
    form.media_images.splice(index, 1);
    mediaImageModes.value.splice(index, 1);
    mediaImagePreviews.value.splice(index, 1);
}

// Model Overview management
function addModelOverview() {
    form.model_overview.push({ label: "", value: "" });
}

function removeModelOverview(index) {
    form.model_overview.splice(index, 1);
}

// Downloads management
const downloadFileModes = ref(
    (props.product?.downloads ?? []).map(d =>
        d.file ? (d.file.startsWith('http') ? 'url' : 'upload') : 'upload'
    )
);
const downloadFileNames = ref(
    (props.product?.downloads ?? []).map(d =>
        d.file && !d.file.startsWith('http') ? d.file.split('/').pop() : null
    )
);

function switchDownloadFileMode(i, mode) {
    downloadFileModes.value[i] = mode;
    if (mode === 'url') {
        form.download_files[i] = null;
        downloadFileNames.value[i] = null;
    } else {
        form.downloads[i].file = "";
        form.downloads[i].size = "";
    }
}

function onDownloadFileChange(i, e) {
    const file = e.target.files[0];
    if (!file) return;
    form.download_files[i] = file;
    form.downloads[i].file = "";
    downloadFileNames.value[i] = file.name;
}

function clearDownloadFile(i) {
    form.download_files[i] = null;
    form.downloads[i].file = "";
    form.downloads[i].size = "";
    downloadFileNames.value[i] = null;
}

function addDownload() {
    form.downloads.push({ title: "", file: "", size: "" });
    form.download_files.push(null);
    downloadFileModes.value.push('upload');
    downloadFileNames.value.push(null);
}

function removeDownload(index) {
    form.downloads.splice(index, 1);
    form.download_files.splice(index, 1);
    downloadFileModes.value.splice(index, 1);
    downloadFileNames.value.splice(index, 1);
}

function submit() {
    if (isEdit) {
        form
            .transform((data) => ({ ...data, _method: "PUT" }))
            .post(route("admin.products.update", props.product.id), {
                forceFormData: true,
            });
    } else {
        form.post(route("admin.products.store"), {
            forceFormData: true,
        });
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Edit Produk' : 'Tambah Produk'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Produk</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <Link :href="route('admin.products.index')"
                    class="text-xs text-slate-400 hover:text-orange-600 transition-colors">
                    Daftar Produk
                </Link>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">{{ isEdit ? "Edit" : "Tambah" }}</span>
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-5">
                <h1 class="text-lg font-black text-slate-900">
                    {{ isEdit ? "Edit Produk" : "Tambah Produk" }}
                </h1>
            </div>

            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-5">

                <!-- Basic Info -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Informasi Produk</h2>

                    <!-- Step 1: System kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Jenis Produk <span class="text-red-500">*</span>
                        </label>
                        <select v-model="selectedSystemId" required
                            class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 bg-white">
                            <option value="" disabled>-- Pilih jenis produk --</option>
                            <option v-for="sys in systemCategories" :key="sys.id" :value="sys.id">
                                {{ sys.name }} (/{{ sys.slug }})
                            </option>
                        </select>
                    </div>

                    <!-- Step 2: Sub-kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Sub-Kategori <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.product_category_id" required :disabled="!selectedSystemId"
                            class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 bg-white disabled:bg-gray-50 disabled:text-slate-400 disabled:cursor-not-allowed">
                            <option value="" disabled>{{ selectedSystemId ? '-- Pilih sub-kategori --' : '-- Pilih jenis produk dulu --' }}</option>
                            <option v-for="sub in availableSubs" :key="sub.id" :value="sub.id">
                                {{ sub.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.product_category_id" class="text-xs text-red-500 mt-1">{{ form.errors.product_category_id }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" required
                            class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400"
                            placeholder="e.g. Reach Truck 1.5 Ton" />
                        <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Slug URL</label>
                        <div class="flex items-center border border-gray-200 rounded overflow-hidden focus-within:border-orange-400 focus-within:ring-1 focus-within:ring-orange-400">
                            <span class="bg-gray-50 border-r border-gray-200 px-3 py-2 text-xs text-slate-400 shrink-0">
                                /{{ selectedSystem?.slug ?? '...' }}/{{ selectedSub?.slug ?? '...' }}/
                            </span>
                            <input v-model="form.slug" type="text"
                                class="flex-1 px-3 py-2 text-sm text-slate-800 focus:outline-none"
                                placeholder="reach-truck-15-ton" />
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Kosongkan untuk generate otomatis dari nama.</p>
                        <p v-if="form.errors.slug" class="text-xs text-red-500 mt-1">{{ form.errors.slug }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Deskripsi Singkat</label>
                        <textarea v-model="form.short_description" rows="2"
                            class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 resize-none"
                            placeholder="Deskripsi singkat untuk card produk..."></textarea>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_active" type="checkbox"
                                class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-400" />
                            <span class="text-sm text-slate-700 font-medium">Produk Aktif (tampil di website)</span>
                        </label>
                    </div>
                </div>

                <!-- Media Produk -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Media Produk</h2>

                    <!-- Foto -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">Foto</p>
                            <!-- Mode toggle -->
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

                        <!-- Upload mode -->
                        <template v-if="imageMode === 'upload'">
                            <div v-if="imagePreview"
                                class="relative w-64 h-40 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                                <button type="button" @click="clearImage"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                    ×
                                </button>
                            </div>
                            <div>
                                <input ref="imageInput" type="file" accept="image/*" @change="onImageChange"
                                    class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                <p class="text-xs text-slate-400 mt-1">Format JPG, PNG, WebP. Maks 3 MB. Disarankan rasio 16:9.</p>
                                <p v-if="form.errors.image" class="text-xs text-red-500 mt-1">{{ form.errors.image }}</p>
                            </div>
                        </template>

                        <!-- URL mode -->
                        <template v-else>
                            <div>
                                <input v-model="form.image_url" type="url" placeholder="https://example.com/foto-produk.jpg"
                                    class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                                <p class="text-xs text-slate-400 mt-1">URL lengkap menuju file gambar (JPG, PNG, WebP).</p>
                                <p v-if="form.errors.image_url" class="text-xs text-red-500 mt-1">{{ form.errors.image_url }}</p>
                            </div>
                            <div v-if="form.image_url"
                                class="w-64 h-40 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img :src="form.image_url" alt="Preview URL" class="w-full h-full object-cover"
                                    @error="(e) => e.target.style.display = 'none'" />
                            </div>
                        </template>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>

                    <!-- Video -->
                    <div class="space-y-3">
                        <p class="text-xs font-semibold text-slate-700">Video <span class="font-normal text-slate-400">(opsional)</span></p>
                        <div>
                            <input v-model="form.video_url" type="url" placeholder="https://example.com/video.mp4 atau link YouTube"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p class="text-xs text-slate-400 mt-1">Support MP4 direct link atau YouTube. Jika diisi, video akan menggantikan foto di halaman produk.</p>
                            <p v-if="form.errors.video_url" class="text-xs text-red-500 mt-1">{{ form.errors.video_url }}</p>
                        </div>
                        <div v-if="form.video_url" class="flex items-center gap-2 text-xs text-green-600 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            URL video tersimpan
                        </div>
                    </div>
                </div>

                <!-- Specs -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Spesifikasi</h2>
                        <button type="button" @click="addSpec"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Spesifikasi
                        </button>
                    </div>

                    <p class="text-xs text-slate-400">Contoh: Label = "Kapasitas Angkat", Nilai = "1.500 kg"</p>

                    <div v-if="form.specs.length" class="space-y-2">
                        <div v-for="(spec, i) in form.specs" :key="i"
                            class="flex items-center gap-2">
                            <input v-model="spec.label" type="text" placeholder="Label"
                                class="w-2/5 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                            <input v-model="spec.value" type="text" placeholder="Nilai"
                                class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                            <button type="button" @click="removeSpec(i)"
                                class="text-red-400 hover:text-red-600 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada spesifikasi.</p>
                </div>

                <!-- Overview -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-3">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Overview</h2>
                    <RichTextEditor v-model="form.full_description" />
                    <p v-if="form.errors.full_description" class="text-xs text-red-500">{{ form.errors.full_description }}</p>
                </div>

                <!-- Highlights -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Highlights</h2>

                    <!-- Foto section -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">Foto Section</p>
                            <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                <button type="button" @click="switchHighlightImageMode('upload')"
                                    class="px-3 py-1.5 transition-colors duration-150"
                                    :class="highlightImageMode === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                    Upload
                                </button>
                                <button type="button" @click="switchHighlightImageMode('url')"
                                    class="px-3 py-1.5 transition-colors duration-150"
                                    :class="highlightImageMode === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                    URL Eksternal
                                </button>
                            </div>
                        </div>

                        <template v-if="highlightImageMode === 'upload'">
                            <div v-if="highlightImagePreview"
                                class="relative w-48 h-32 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img :src="highlightImagePreview" class="w-full h-full object-contain" />
                                <button type="button" @click="clearHighlightImage"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                    ×
                                </button>
                            </div>
                            <div>
                                <input ref="highlightImageInput" type="file" accept="image/*"
                                    @change="onHighlightImageChange"
                                    class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                <p class="text-xs text-slate-400 mt-1">Jika kosong, menggunakan foto produk utama.</p>
                            </div>
                        </template>

                        <template v-else>
                            <input v-model="form.highlight_image" type="url"
                                placeholder="https://example.com/foto-produk.jpg"
                                class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                            <div v-if="form.highlight_image"
                                class="w-48 h-32 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img :src="form.highlight_image" class="w-full h-full object-contain"
                                    @error="(e) => e.target.style.display = 'none'" />
                            </div>
                            <p class="text-xs text-slate-400">Jika kosong, menggunakan foto produk utama.</p>
                        </template>
                        <p v-if="form.errors.highlight_image" class="text-xs text-red-500">{{ form.errors.highlight_image }}</p>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <!-- Item list -->
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-700">Item Highlight</p>
                        <button type="button" @click="addHighlight"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <div v-if="form.highlights.length" class="space-y-3">
                        <div v-for="(item, i) in form.highlights" :key="i"
                            class="border border-gray-100 rounded-lg p-3 space-y-3 bg-gray-50/50">

                            <!-- Row: nomor + hapus -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">Item {{ i + 1 }}</span>
                                <button type="button" @click="removeHighlight(i)"
                                    class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Judul & deskripsi -->
                            <div>
                                <input v-model="item.title" type="text"
                                    placeholder="Judul highlight, e.g. Efisiensi tinggi..."
                                    class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <p v-if="form.errors[`highlights.${i}.title`]" class="text-xs text-red-500 mt-1">{{ form.errors[`highlights.${i}.title`] }}</p>
                            </div>
                            <textarea v-model="item.description" rows="2"
                                placeholder="Deskripsi singkat (opsional)..."
                                class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 resize-none"></textarea>

                            <!-- Ikon / image item -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-semibold text-slate-600">Ikon / Gambar</p>
                                    <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                        <button type="button" @click="switchItemMode(i, 'upload')"
                                            class="px-2.5 py-1 transition-colors duration-150"
                                            :class="itemImageModes[i] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                            Upload
                                        </button>
                                        <button type="button" @click="switchItemMode(i, 'url')"
                                            class="px-2.5 py-1 transition-colors duration-150"
                                            :class="itemImageModes[i] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                            URL
                                        </button>
                                    </div>
                                </div>

                                <template v-if="itemImageModes[i] === 'upload'">
                                    <div v-if="itemImagePreviews[i] || (item.image_url && !item.image_url.startsWith('http'))"
                                        class="relative w-20 h-20 border border-gray-200 rounded overflow-hidden bg-white">
                                        <img :src="itemImagePreviews[i] || item.image_url"
                                            class="w-full h-full object-contain" />
                                        <button type="button" @click="clearItemImage(i)"
                                            class="absolute top-0.5 right-0.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs font-bold leading-none">
                                            ×
                                        </button>
                                    </div>
                                    <input type="file" accept="image/*"
                                        @change="onItemImageChange(i, $event)"
                                        class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                    <p class="text-xs text-slate-400">Biarkan kosong untuk gunakan ikon default.</p>
                                </template>

                                <template v-else>
                                    <input v-model="item.image_url" type="url"
                                        placeholder="https://example.com/icon.png"
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <div v-if="item.image_url"
                                        class="w-20 h-20 border border-gray-200 rounded bg-white">
                                        <img :src="item.image_url" class="w-full h-full object-contain"
                                            @error="(e) => e.target.style.display = 'none'" />
                                    </div>
                                </template>
                                <p v-if="form.errors[`highlight_item_images.${i}`]" class="text-xs text-red-500">{{ form.errors[`highlight_item_images.${i}`] }}</p>
                                <p v-if="form.errors[`highlights.${i}.image_url`]" class="text-xs text-red-500">{{ form.errors[`highlights.${i}.image_url`] }}</p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada item highlight.</p>
                </div>

                <!-- Detail Produk (Accordion) -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Detail Produk</h2>
                        <button type="button" @click="addDetail"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Detail
                        </button>
                    </div>
                    <p class="text-xs text-slate-400">Ditampilkan sebagai accordion di halaman produk. Setiap item punya ikon header dan konten body (gambar + teks).</p>

                    <div v-if="form.details.length" class="space-y-4">
                        <div v-for="(detail, i) in form.details" :key="i"
                            class="border border-gray-100 rounded-lg p-3 space-y-4 bg-gray-50/50">

                            <!-- Row: nomor + hapus -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">Detail {{ i + 1 }}</span>
                                <button type="button" @click="removeDetail(i)"
                                    class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Header: icon + title -->
                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-slate-600">Header</p>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-slate-500">Ikon</p>
                                        <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                            <button type="button" @click="switchDetailIconMode(i, 'upload')"
                                                class="px-2.5 py-1 transition-colors duration-150"
                                                :class="detailIconModes[i] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                                Upload
                                            </button>
                                            <button type="button" @click="switchDetailIconMode(i, 'url')"
                                                class="px-2.5 py-1 transition-colors duration-150"
                                                :class="detailIconModes[i] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                                URL
                                            </button>
                                        </div>
                                    </div>

                                    <template v-if="detailIconModes[i] === 'upload'">
                                        <div v-if="detailIconPreviews[i] || (detail.icon_image && !detail.icon_image.startsWith('http'))"
                                            class="relative w-16 h-16 border border-gray-200 rounded overflow-hidden bg-white">
                                            <img :src="detailIconPreviews[i] || detail.icon_image"
                                                class="w-full h-full object-contain" />
                                            <button type="button" @click="clearDetailIcon(i)"
                                                class="absolute top-0.5 right-0.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs font-bold leading-none">
                                                ×
                                            </button>
                                        </div>
                                        <input type="file" accept="image/*"
                                            @change="onDetailIconChange(i, $event)"
                                            class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                        <p class="text-xs text-slate-400">Biarkan kosong untuk gunakan ikon default.</p>
                                    </template>

                                    <template v-else>
                                        <input v-model="detail.icon_image" type="url"
                                            placeholder="https://example.com/icon.svg"
                                            class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                        <div v-if="detail.icon_image"
                                            class="w-16 h-16 border border-gray-200 rounded bg-white">
                                            <img :src="detail.icon_image" class="w-full h-full object-contain"
                                                @error="(e) => e.target.style.display = 'none'" />
                                        </div>
                                    </template>
                                    <p v-if="form.errors[`detail_icon_images.${i}`]" class="text-xs text-red-500">{{ form.errors[`detail_icon_images.${i}`] }}</p>
                                    <p v-if="form.errors[`details.${i}.icon_image`]" class="text-xs text-red-500">{{ form.errors[`details.${i}.icon_image`] }}</p>
                                </div>

                                <div>
                                    <input v-model="detail.title" type="text"
                                        placeholder="Judul detail, e.g. Desain Ergonomis..."
                                        class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <p v-if="form.errors[`details.${i}.title`]" class="text-xs text-red-500 mt-1">{{ form.errors[`details.${i}.title`] }}</p>
                                </div>
                            </div>

                            <div class="border-t border-gray-200"></div>

                            <!-- Body: image + content -->
                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-slate-600">Body</p>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-slate-500">Gambar</p>
                                        <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                            <button type="button" @click="switchDetailImageMode(i, 'upload')"
                                                class="px-2.5 py-1 transition-colors duration-150"
                                                :class="detailImageModes[i] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                                Upload
                                            </button>
                                            <button type="button" @click="switchDetailImageMode(i, 'url')"
                                                class="px-2.5 py-1 transition-colors duration-150"
                                                :class="detailImageModes[i] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                                URL
                                            </button>
                                        </div>
                                    </div>

                                    <template v-if="detailImageModes[i] === 'upload'">
                                        <div v-if="detailImagePreviews[i] || (detail.image && !detail.image.startsWith('http'))"
                                            class="relative w-48 h-32 border border-gray-200 rounded overflow-hidden bg-white">
                                            <img :src="detailImagePreviews[i] || detail.image"
                                                class="w-full h-full object-contain" />
                                            <button type="button" @click="clearDetailImage(i)"
                                                class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                                ×
                                            </button>
                                        </div>
                                        <input type="file" accept="image/*"
                                            @change="onDetailImageChange(i, $event)"
                                            class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                    </template>

                                    <template v-else>
                                        <input v-model="detail.image" type="url"
                                            placeholder="https://example.com/gambar-detail.jpg"
                                            class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                        <div v-if="detail.image"
                                            class="w-48 h-32 border border-gray-200 rounded bg-white">
                                            <img :src="detail.image" class="w-full h-full object-contain"
                                                @error="(e) => e.target.style.display = 'none'" />
                                        </div>
                                    </template>
                                    <p v-if="form.errors[`detail_body_images.${i}`]" class="text-xs text-red-500">{{ form.errors[`detail_body_images.${i}`] }}</p>
                                    <p v-if="form.errors[`details.${i}.image`]" class="text-xs text-red-500">{{ form.errors[`details.${i}.image`] }}</p>
                                </div>

                                <div>
                                    <RichTextEditor v-model="detail.content" />
                                    <p v-if="form.errors[`details.${i}.content`]" class="text-xs text-red-500 mt-1">{{ form.errors[`details.${i}.content`] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada detail produk.</p>
                </div>

                <!-- Solutions (Tabs) -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Solusi (Tabs)</h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Section</label>
                        <input v-model="form.solutions_title" type="text"
                            placeholder="e.g. Kami Punya Solusi yang Tepat"
                            class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                        <p v-if="form.errors.solutions_title" class="text-xs text-red-500 mt-1">{{ form.errors.solutions_title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Deskripsi Section</label>
                        <textarea v-model="form.solutions_description" rows="2"
                            placeholder="Deskripsi singkat di bawah judul..."
                            class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 resize-none"></textarea>
                        <p v-if="form.errors.solutions_description" class="text-xs text-red-500 mt-1">{{ form.errors.solutions_description }}</p>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-700">Item Tab</p>
                        <button type="button" @click="addSolution"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Tab
                        </button>
                    </div>
                    <p class="text-xs text-slate-400">Video bersifat opsional — jika kosong, area video tidak akan ditampilkan di halaman produk.</p>

                    <div v-if="form.solutions.length" class="space-y-4">
                        <div v-for="(item, i) in form.solutions" :key="i"
                            class="border border-gray-100 rounded-lg p-3 space-y-3 bg-gray-50/50">

                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">Tab {{ i + 1 }}</span>
                                <button type="button" @click="removeSolution(i)"
                                    class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div>
                                <input v-model="item.label" type="text"
                                    placeholder="Label tab, e.g. Rak Tinggi (High Bay)..."
                                    class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <p v-if="form.errors[`solutions.${i}.label`]" class="text-xs text-red-500 mt-1">{{ form.errors[`solutions.${i}.label`] }}</p>
                            </div>

                            <div>
                                <input v-model="item.video_url" type="url"
                                    placeholder="https://youtu.be/... atau link YouTube/Vimeo (opsional)"
                                    class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <p class="text-xs text-slate-400 mt-1">Bisa pakai link share biasa dari YouTube/Vimeo, otomatis dikonversi ke format embed.</p>
                                <p v-if="form.errors[`solutions.${i}.video_url`]" class="text-xs text-red-500 mt-1">{{ form.errors[`solutions.${i}.video_url`] }}</p>
                            </div>

                            <div>
                                <RichTextEditor v-model="item.content" />
                                <p v-if="form.errors[`solutions.${i}.content`]" class="text-xs text-red-500 mt-1">{{ form.errors[`solutions.${i}.content`] }}</p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada item tab.</p>
                </div>

                <!-- Media Center -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Media Center</h2>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="addMedia('image')"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Gambar
                            </button>
                            <button type="button" @click="addMedia('video')"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Video
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">Galeri foto & video yang tampil di section Media Center halaman produk.</p>

                    <div v-if="form.media_items.length" class="space-y-3">
                        <div v-for="(item, i) in form.media_items" :key="i"
                            class="border border-gray-100 rounded-lg p-3 space-y-3 bg-gray-50/50">

                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">
                                    {{ item.type === 'video' ? 'Video' : 'Gambar' }} {{ i + 1 }}
                                </span>
                                <button type="button" @click="removeMedia(i)"
                                    class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div>
                                <input v-model="item.title" type="text"
                                    placeholder="Judul (opsional, ditampilkan di bawah foto/video saat lightbox)"
                                    class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <p v-if="form.errors[`media_items.${i}.title`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${i}.title`] }}</p>
                            </div>

                            <!-- Image item -->
                            <div v-if="item.type === 'image'" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-slate-500">Gambar</p>
                                    <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                        <button type="button" @click="switchMediaImageMode(i, 'upload')"
                                            class="px-2.5 py-1 transition-colors duration-150"
                                            :class="mediaImageModes[i] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                            Upload
                                        </button>
                                        <button type="button" @click="switchMediaImageMode(i, 'url')"
                                            class="px-2.5 py-1 transition-colors duration-150"
                                            :class="mediaImageModes[i] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                            URL
                                        </button>
                                    </div>
                                </div>

                                <template v-if="mediaImageModes[i] === 'upload'">
                                    <div v-if="mediaImagePreviews[i] || (item.image && !item.image.startsWith('http'))"
                                        class="relative w-32 h-20 border border-gray-200 rounded overflow-hidden bg-white">
                                        <img :src="mediaImagePreviews[i] || item.image"
                                            class="w-full h-full object-cover" />
                                        <button type="button" @click="clearMediaImage(i)"
                                            class="absolute top-0.5 right-0.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs font-bold leading-none">
                                            ×
                                        </button>
                                    </div>
                                    <input type="file" accept="image/*"
                                        @change="onMediaImageChange(i, $event)"
                                        class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                </template>

                                <template v-else>
                                    <input v-model="item.image" type="url"
                                        placeholder="https://example.com/foto.jpg"
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <div v-if="item.image"
                                        class="w-32 h-20 border border-gray-200 rounded bg-white">
                                        <img :src="item.image" class="w-full h-full object-cover"
                                            @error="(e) => e.target.style.display = 'none'" />
                                    </div>
                                </template>
                                <p v-if="form.errors[`media_images.${i}`]" class="text-xs text-red-500">{{ form.errors[`media_images.${i}`] }}</p>
                                <p v-if="form.errors[`media_items.${i}.image`]" class="text-xs text-red-500">{{ form.errors[`media_items.${i}.image`] }}</p>
                            </div>

                            <!-- Video item -->
                            <div v-else class="space-y-1">
                                <p class="text-xs text-slate-500">Video</p>
                                <input v-model="item.video_url" type="url"
                                    placeholder="https://youtu.be/... atau link YouTube/Vimeo"
                                    class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <p class="text-xs text-slate-400 mt-1">Bisa pakai link share biasa dari YouTube/Vimeo, otomatis dikonversi ke format embed (sama seperti di Solusi).</p>
                                <p v-if="form.errors[`media_items.${i}.video_url`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${i}.video_url`] }}</p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada item media.</p>
                </div>

                <!-- Model Overview -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Model Overview</h2>
                        <button type="button" @click="addModelOverview"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <p class="text-xs text-slate-400">Ditampilkan sebagai grid 4 kolom di halaman produk. Item pertama ("Model") otomatis terisi dari nama produk dan tidak bisa dihapus. Contoh: Label = "Kapasitas Angkat", Nilai = "1.500 kg"</p>

                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="text" value="Model" disabled
                                class="w-2/5 border border-gray-200 rounded px-3 py-2 text-sm text-slate-400 bg-gray-50 cursor-not-allowed" />
                            <input type="text" :value="form.name || '(nama produk)'" disabled
                                class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-400 bg-gray-50 cursor-not-allowed" />
                            <span class="w-4 h-4 shrink-0"></span>
                        </div>
                    </div>

                    <div v-if="form.model_overview.length" class="space-y-2">
                        <div v-for="(item, i) in form.model_overview" :key="i">
                            <div class="flex items-center gap-2">
                                <input v-model="item.label" type="text" placeholder="Label"
                                    class="w-2/5 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <input v-model="item.value" type="text" placeholder="Nilai"
                                    class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <button type="button" @click="removeModelOverview(i)"
                                    class="text-red-400 hover:text-red-600 transition-colors shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="form.errors[`model_overview.${i}.label`] || form.errors[`model_overview.${i}.value`]"
                                class="text-xs text-red-500 mt-1">{{ form.errors[`model_overview.${i}.label`] || form.errors[`model_overview.${i}.value`] }}</p>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada item Model Overview.</p>
                </div>

                <!-- Downloads -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Downloads</h2>
                        <button type="button" @click="addDownload"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah File
                        </button>
                    </div>
                    <p class="text-xs text-slate-400">Daftar file (PDF, dokumen, dll) yang bisa diunduh pengunjung di halaman produk.</p>

                    <div v-if="form.downloads.length" class="space-y-3">
                        <div v-for="(item, i) in form.downloads" :key="i"
                            class="border border-gray-100 rounded-lg p-3 space-y-3 bg-gray-50/50">

                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">File {{ i + 1 }}</span>
                                <button type="button" @click="removeDownload(i)"
                                    class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div>
                                <input v-model="item.title" type="text"
                                    placeholder="Judul file, e.g. Data sheet FM-X iGo"
                                    class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <p v-if="form.errors[`downloads.${i}.title`]" class="text-xs text-red-500 mt-1">{{ form.errors[`downloads.${i}.title`] }}</p>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-slate-500">File</p>
                                    <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                        <button type="button" @click="switchDownloadFileMode(i, 'upload')"
                                            class="px-2.5 py-1 transition-colors duration-150"
                                            :class="downloadFileModes[i] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                            Upload
                                        </button>
                                        <button type="button" @click="switchDownloadFileMode(i, 'url')"
                                            class="px-2.5 py-1 transition-colors duration-150"
                                            :class="downloadFileModes[i] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                            URL
                                        </button>
                                    </div>
                                </div>

                                <template v-if="downloadFileModes[i] === 'upload'">
                                    <div v-if="downloadFileNames[i] || (item.file && !item.file.startsWith('http'))"
                                        class="flex items-center gap-2 text-xs text-slate-600 bg-white border border-gray-200 rounded px-3 py-2">
                                        <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate flex-1">{{ downloadFileNames[i] || item.file.split('/').pop() }}</span>
                                        <button type="button" @click="clearDownloadFile(i)"
                                            class="text-red-400 hover:text-red-600 flex-shrink-0 font-bold leading-none">
                                            ×
                                        </button>
                                    </div>
                                    <input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                                        @change="onDownloadFileChange(i, $event)"
                                        class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                    <p class="text-xs text-slate-400">PDF, Word, Excel, atau ZIP. Maks 10 MB. Ukuran file dihitung otomatis.</p>
                                </template>

                                <template v-else>
                                    <input v-model="item.file" type="url"
                                        placeholder="https://example.com/datasheet.pdf"
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <input v-model="item.size" type="text"
                                        placeholder="Ukuran file, e.g. 2 MB"
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                </template>
                                <p v-if="form.errors[`download_files.${i}`]" class="text-xs text-red-500">{{ form.errors[`download_files.${i}`] }}</p>
                                <p v-if="form.errors[`downloads.${i}.file`]" class="text-xs text-red-500">{{ form.errors[`downloads.${i}.file`] }}</p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada file unduhan.</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-8 pb-2">
                    <Link :href="route('admin.products.index')"
                        class="text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                        Batal
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 disabled:opacity-50 text-white text-sm font-bold px-6 py-2.5 transition-colors duration-150">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        {{ form.processing ? "Menyimpan..." : (isEdit ? "Perbarui Produk" : "Simpan Produk") }}
                    </button>
                </div>

                <div v-if="Object.keys(form.errors).length"
                    class="bg-red-50 border border-red-200 rounded p-3 text-xs text-red-600 space-y-1">
                    <p class="font-semibold">Terdapat kesalahan pada form. Periksa kembali isian Anda.</p>
                    <ul class="list-disc list-inside">
                        <li v-for="(message, field) in form.errors" :key="field">{{ field }}: {{ message }}</li>
                    </ul>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
