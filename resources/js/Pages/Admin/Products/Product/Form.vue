<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import RichTextEditor from "@/Components/Admin/RichTextEditor.vue";
import { SPEC_ICONS } from "@/Utils/specIcons";

const props = defineProps({
    product:           { type: Object, default: null },
    systemCategories:  { type: Array,  default: () => [] },
});

const isEdit = !!props.product;

// Fixed spec labels — also used as filter criteria on the product catalog page
const SPEC_LABELS = [
    "Kapasitas Angkat",
    "Tinggi Angkat Maks",
    "Otomatis",
    "Kecepatan Jalan",
    "Kapasitas Penarik",
];

// "Otomatis" is a boolean spec (checkbox) rather than free text — stored as
// "Ya" when checked, empty when unchecked (so it's hidden per the empty-spec rule)
const BOOLEAN_SPEC_LABELS = ["Otomatis"];

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
    specs:                 SPEC_LABELS.map(label => ({
                               label,
                               value: props.product?.specs?.find(s => s.label === label)?.value ?? "",
                           })),
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
    solutions:               (props.product?.solutions ?? []).map(g => ({
                               label: g.label ?? "",
                               items: (g.items ?? []).map(item => ({
                                   label:     item.label     ?? "",
                                   video_url: item.video_url ?? "",
                                   content:   item.content   ?? "",
                               })),
                           })),
    media_items:             (props.product?.media_items ?? []).map(m => ({
                               type:        m.type        ?? "image",
                               title:       m.title       ?? "",
                               image:       m.image       ?? "",
                               video_url:   m.video_url   ?? "",
                               date:        m.date        ?? "",
                               description: m.description ?? "",
                           })),
    media_images:           (props.product?.media_items ?? []).map(() => null),
    model_overview: {
        columns: [...(props.product?.model_overview?.columns ?? [])],
        rows:    (props.product?.model_overview?.rows ?? []).map(row => [...row]),
    },
    downloads:               (props.product?.downloads ?? []).map(d => ({
                               title: d.title ?? "",
                               file:  d.file  ?? "",
                               size:  d.size  ?? "",
                           })),
    download_files:         (props.product?.downloads ?? []).map(() => null),
    is_active:             props.product?.is_active           ?? true,
    thumbnail:             null,
    clear_thumbnail:       false,
    thumbnail_url:         props.product?.thumbnail_url       ?? "",
    image:                 null,
    clear_image:           false,
    image_url:             props.product?.image_url           ?? "",
    hero_cert_badge_1:       (() => { const v = props.product?.hero_cert_badge_1; return v?.startsWith('http') ? v : ""; })(),
    hero_cert_badge_1_file:  null,
    clear_hero_cert_badge_1: false,
    hero_cert_badge_2:       (() => { const v = props.product?.hero_cert_badge_2; return v?.startsWith('http') ? v : ""; })(),
    hero_cert_badge_2_file:  null,
    clear_hero_cert_badge_2: false,
    video_url:             props.product?.video_url           ?? "",
});

watch(selectedSystemId, () => {
    form.product_category_id = "";
});

// thumbnail (foto card produk)
const thumbnailMode    = ref(props.product?.thumbnail_url ? 'url' : 'upload');
const thumbnailPreview = ref(props.product?.thumbnail_preview ?? null);
const thumbnailInput   = ref(null);

function onThumbnailChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.thumbnail       = file;
    form.clear_thumbnail = false;
    form.thumbnail_url   = "";
    thumbnailPreview.value = URL.createObjectURL(file);
}

function clearThumbnail() {
    form.thumbnail       = null;
    form.clear_thumbnail = true;
    thumbnailPreview.value = null;
    if (thumbnailInput.value) thumbnailInput.value.value = "";
}

function switchThumbnailMode(mode) {
    thumbnailMode.value = mode;
    if (mode === 'url') {
        clearThumbnail();
    } else {
        form.thumbnail_url = "";
    }
}

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

// Hero certification badges (fixed, max 2)
const heroBadgeModes = ref([1, 2].map(i => {
    const v = props.product?.[`hero_cert_badge_${i}`];
    return v?.startsWith('http') ? 'url' : 'upload';
}));
const heroBadgePreviews = ref([1, 2].map(i => {
    const v = props.product?.[`hero_cert_badge_${i}`];
    return v && !v.startsWith('http') ? v : null;
}));

function switchHeroBadgeMode(i, mode) {
    heroBadgeModes.value[i - 1] = mode;
    if (mode === 'url') {
        form[`hero_cert_badge_${i}_file`] = null;
        heroBadgePreviews.value[i - 1] = null;
    } else {
        form[`hero_cert_badge_${i}`] = "";
    }
}

function onHeroBadgeChange(i, e) {
    const file = e.target.files[0];
    if (!file) return;
    form[`hero_cert_badge_${i}_file`] = file;
    form[`hero_cert_badge_${i}`] = "";
    heroBadgePreviews.value[i - 1] = URL.createObjectURL(file);
}

function clearHeroBadge(i) {
    form[`hero_cert_badge_${i}_file`]  = null;
    form[`hero_cert_badge_${i}`]       = "";
    form[`clear_hero_cert_badge_${i}`] = true;
    heroBadgePreviews.value[i - 1] = null;
}

// Specs management
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
function addSolutionGroup() {
    form.solutions.push({ label: "", items: [] });
}
function removeSolutionGroup(gi) {
    form.solutions.splice(gi, 1);
}
function addSolutionItem(gi) {
    form.solutions[gi].items.push({ label: "", video_url: "", content: "" });
}
function removeSolutionItem(gi, ii) {
    form.solutions[gi].items.splice(ii, 1);
}

// Media Center management
const mediaTab = ref('image'); // 'image' | 'video' — splits the CRUD UI into tabs

const mediaImageEntries = computed(() =>
    form.media_items
        .map((item, i) => ({ item, i }))
        .filter((entry) => entry.item.type === 'image')
);
const mediaVideoEntries = computed(() =>
    form.media_items
        .map((item, i) => ({ item, i }))
        .filter((entry) => entry.item.type === 'video')
);

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
    form.media_items.push({ type, title: "", image: "", video_url: "", date: "", description: "" });
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
function addModelOverviewColumn() {
    form.model_overview.columns.push("");
    form.model_overview.rows.forEach(row => row.push(""));
}

function removeModelOverviewColumn(i) {
    form.model_overview.columns.splice(i, 1);
    form.model_overview.rows.forEach(row => row.splice(i + 1, 1));
}

function addModelOverviewRow() {
    form.model_overview.rows.push(["", ...form.model_overview.columns.map(() => "")]);
}

function removeModelOverviewRow(i) {
    form.model_overview.rows.splice(i, 1);
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
            .transform((data) => ({
                ...data,
                specs: data.specs.filter(s => s.value.trim() !== ""),
                _method: "PUT",
            }))
            .post(route("admin.products.update", props.product.id), {
                forceFormData: true,
            });
    } else {
        form
            .transform((data) => ({
                ...data,
                specs: data.specs.filter(s => s.value.trim() !== ""),
            }))
            .post(route("admin.products.store"), {
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

                <!-- Foto Card Produk -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <div>
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Foto Card Produk</h2>
                        <p class="text-xs text-slate-400 mt-1">Gambar yang tampil di grid/card halaman katalog produk. Jika kosong, menggunakan foto hero produk.</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-700">Foto</p>
                        <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                            <button type="button" @click="switchThumbnailMode('upload')"
                                class="px-3 py-1.5 transition-colors duration-150"
                                :class="thumbnailMode === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                Upload
                            </button>
                            <button type="button" @click="switchThumbnailMode('url')"
                                class="px-3 py-1.5 transition-colors duration-150"
                                :class="thumbnailMode === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                URL Eksternal
                            </button>
                        </div>
                    </div>

                    <!-- Upload mode -->
                    <template v-if="thumbnailMode === 'upload'">
                        <div v-if="thumbnailPreview"
                            class="relative w-48 h-32 border border-gray-200 rounded overflow-hidden bg-gray-50">
                            <img :src="thumbnailPreview" alt="Preview" class="w-full h-full object-contain" />
                            <button type="button" @click="clearThumbnail"
                                class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                ×
                            </button>
                        </div>
                        <div>
                            <input ref="thumbnailInput" type="file" accept="image/*" @change="onThumbnailChange"
                                class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                            <p class="text-xs text-slate-400 mt-1">Format JPG, PNG, WebP. Maks 3 MB. Disarankan rasio 4:3 atau 1:1.</p>
                            <p v-if="form.errors.thumbnail" class="text-xs text-red-500 mt-1">{{ form.errors.thumbnail }}</p>
                        </div>
                    </template>

                    <!-- URL mode -->
                    <template v-else>
                        <div>
                            <input v-model="form.thumbnail_url" type="url" placeholder="https://example.com/thumbnail-produk.jpg"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p class="text-xs text-slate-400 mt-1">URL lengkap menuju file gambar (JPG, PNG, WebP).</p>
                            <p v-if="form.errors.thumbnail_url" class="text-xs text-red-500 mt-1">{{ form.errors.thumbnail_url }}</p>
                        </div>
                        <div v-if="form.thumbnail_url"
                            class="relative w-48 h-32 border border-gray-200 rounded overflow-hidden bg-gray-50">
                            <img :src="form.thumbnail_url" alt="Preview URL" class="w-full h-full object-contain"
                                @error="(e) => e.target.style.display = 'none'" />
                            <button type="button" @click="form.thumbnail_url = ''"
                                class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                ×
                            </button>
                        </div>
                    </template>
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
                                class="relative w-64 h-40 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img :src="form.image_url" alt="Preview URL" class="w-full h-full object-cover"
                                    @error="(e) => e.target.style.display = 'none'" />
                                <button type="button" @click="form.image_url = ''"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                    ×
                                </button>
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

                <!-- Badge Sertifikasi (Hero) -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Badge Sertifikasi (Hero)</h2>
                    <p class="text-xs text-slate-400">Maks 2 badge bulat di pojok kanan atas section hero. Kosongkan kalau tidak ada.</p>

                    <div v-for="i in [1, 2]" :key="i" class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">Badge {{ i }}</p>
                            <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                <button type="button" @click="switchHeroBadgeMode(i, 'upload')"
                                    class="px-2.5 py-1 transition-colors duration-150"
                                    :class="heroBadgeModes[i - 1] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                    Upload
                                </button>
                                <button type="button" @click="switchHeroBadgeMode(i, 'url')"
                                    class="px-2.5 py-1 transition-colors duration-150"
                                    :class="heroBadgeModes[i - 1] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                    URL
                                </button>
                            </div>
                        </div>

                        <template v-if="heroBadgeModes[i - 1] === 'upload'">
                            <div v-if="heroBadgePreviews[i - 1]"
                                class="relative w-20 h-20 border border-gray-200 rounded bg-white">
                                <img :src="heroBadgePreviews[i - 1]" class="w-full h-full object-contain p-2 drop-shadow" />
                                <button type="button" @click="clearHeroBadge(i)"
                                    class="absolute top-0.5 right-0.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs font-bold leading-none">
                                    ×
                                </button>
                            </div>
                            <input type="file" accept="image/*"
                                @change="onHeroBadgeChange(i, $event)"
                                class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                            <p class="text-xs text-slate-400">Bentuk gambar (persegi/lingkaran) mengikuti file asli, tidak dipaksa bulat.</p>
                        </template>

                        <template v-else>
                            <input v-model="form[`hero_cert_badge_${i}`]" type="url"
                                placeholder="https://example.com/badge-sertifikat.png"
                                class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                            <div v-if="form[`hero_cert_badge_${i}`]"
                                class="relative w-20 h-20 border border-gray-200 rounded bg-white">
                                <img :src="form[`hero_cert_badge_${i}`]" class="w-full h-full object-contain p-2 drop-shadow"
                                    @error="(e) => e.target.style.display = 'none'" />
                                <button type="button" @click="clearHeroBadge(i)"
                                    class="absolute top-0.5 right-0.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs font-bold leading-none">
                                    ×
                                </button>
                            </div>
                        </template>
                        <p v-if="form.errors[`hero_cert_badge_${i}_file`]" class="text-xs text-red-500">{{ form.errors[`hero_cert_badge_${i}_file`] }}</p>
                        <p v-if="form.errors[`hero_cert_badge_${i}`]" class="text-xs text-red-500">{{ form.errors[`hero_cert_badge_${i}`] }}</p>
                    </div>
                </div>

                <!-- Specs -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-3">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Spesifikasi</h2>
                    <p class="text-xs text-slate-400">Isi nilai yang relevan saja — kosongkan yang tidak berlaku, tidak akan ditampilkan di halaman produk. Kelima spesifikasi ini juga dipakai untuk filter di halaman katalog produk.</p>

                    <div class="space-y-2">
                        <div v-for="spec in form.specs" :key="spec.label"
                            class="flex items-center gap-2">
                            <span class="w-2/5 flex items-center gap-2 text-sm text-slate-600 shrink-0">
                                <svg v-if="SPEC_ICONS[spec.label]" class="w-5 h-5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-for="(d, di) in SPEC_ICONS[spec.label]" :key="di" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="d" />
                                </svg>
                                {{ spec.label }}
                            </span>
                            <label v-if="BOOLEAN_SPEC_LABELS.includes(spec.label)"
                                class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                    :checked="spec.value === 'Ya'"
                                    @change="spec.value = $event.target.checked ? 'Ya' : ''"
                                    class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-400" />
                                <span class="text-sm text-slate-700">Ya</span>
                            </label>
                            <input v-else v-model="spec.value" type="text" :placeholder="`Nilai ${spec.label}, e.g. 1.500 kg`"
                                class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                        </div>
                    </div>
                    <p v-if="form.errors.specs" class="text-xs text-red-500">{{ form.errors.specs }}</p>
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
                                class="relative w-48 h-32 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img :src="form.highlight_image" class="w-full h-full object-contain"
                                    @error="(e) => e.target.style.display = 'none'" />
                                <button type="button" @click="form.highlight_image = ''"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold leading-none">
                                    ×
                                </button>
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
                    <div>
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Solusi (Tabs)</h2>
                        <p class="text-xs text-slate-400 mt-1">Struktur dua level: Grup (tab parent) → Item (sub-tab dengan konten). Video per item bersifat opsional.</p>
                    </div>

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

                    <!-- Grup list -->
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-700">Grup (Tab Parent)</p>
                        <button type="button" @click="addSolutionGroup"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Grup
                        </button>
                    </div>

                    <div v-if="form.solutions.length" class="space-y-4">
                        <div v-for="(group, gi) in form.solutions" :key="gi"
                            class="border border-gray-200 rounded-lg p-4 space-y-4 bg-gray-50/30">

                            <!-- Group header -->
                            <div class="flex items-center gap-3">
                                <input v-model="group.label" type="text"
                                    placeholder="Label grup, e.g. Safety"
                                    class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm font-semibold text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                <button type="button" @click="removeSolutionGroup(gi)"
                                    class="text-red-400 hover:text-red-600 transition-colors shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="form.errors[`solutions.${gi}.label`]" class="text-xs text-red-500 -mt-2">{{ form.errors[`solutions.${gi}.label`] }}</p>

                            <!-- Items inside group -->
                            <div class="pl-3 border-l-2 border-orange-200 space-y-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-semibold text-slate-500">Item (Sub-Tab)</p>
                                    <button type="button" @click="addSolutionItem(gi)"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Item
                                    </button>
                                </div>

                                <div v-if="group.items.length" class="space-y-3">
                                    <div v-for="(item, ii) in group.items" :key="ii"
                                        class="border border-gray-100 rounded p-3 space-y-3 bg-white">

                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-slate-400">Item {{ ii + 1 }}</span>
                                            <button type="button" @click="removeSolutionItem(gi, ii)"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div>
                                            <input v-model="item.label" type="text"
                                                placeholder="Label item, e.g. Acoustic signal when reversing"
                                                class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                            <p v-if="form.errors[`solutions.${gi}.items.${ii}.label`]" class="text-xs text-red-500 mt-1">{{ form.errors[`solutions.${gi}.items.${ii}.label`] }}</p>
                                        </div>

                                        <div>
                                            <input v-model="item.video_url" type="url"
                                                placeholder="https://youtu.be/... (opsional)"
                                                class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                            <p class="text-xs text-slate-400 mt-1">Link YouTube/Vimeo, otomatis dikonversi ke embed.</p>
                                        </div>

                                        <div>
                                            <RichTextEditor v-model="item.content" />
                                        </div>
                                    </div>
                                </div>

                                <p v-else class="text-xs text-slate-300 italic">Belum ada item di grup ini.</p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-300 italic">Belum ada grup.</p>
                </div>

                <!-- Media Center -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Media Center</h2>
                        <button type="button" @click="addMedia(mediaTab)"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah {{ mediaTab === 'video' ? 'Video' : 'Gambar' }}
                        </button>
                    </div>
                    <p class="text-xs text-slate-400">Galeri foto & video yang tampil di section Media Center halaman produk.</p>

                    <!-- Tabs -->
                    <div class="flex items-center border-b border-gray-200">
                        <button type="button" @click="mediaTab = 'image'"
                            class="px-4 py-2 text-xs font-semibold border-b-2 -mb-px transition-colors duration-150"
                            :class="mediaTab === 'image' ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-400 hover:text-slate-600'">
                            Gambar ({{ mediaImageEntries.length }})
                        </button>
                        <button type="button" @click="mediaTab = 'video'"
                            class="px-4 py-2 text-xs font-semibold border-b-2 -mb-px transition-colors duration-150"
                            :class="mediaTab === 'video' ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-400 hover:text-slate-600'">
                            Video ({{ mediaVideoEntries.length }})
                        </button>
                    </div>

                    <!-- Gambar tab -->
                    <div v-if="mediaTab === 'image'">
                        <div v-if="mediaImageEntries.length" class="space-y-3">
                            <div v-for="(entry, n) in mediaImageEntries" :key="entry.i"
                                class="border border-gray-100 rounded-lg p-3 space-y-3 bg-gray-50/50">

                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-400">Gambar {{ n + 1 }}</span>
                                    <button type="button" @click="removeMedia(entry.i)"
                                        class="text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div>
                                    <input v-model="entry.item.title" type="text"
                                        placeholder="Judul (opsional, ditampilkan di bawah foto saat lightbox)"
                                        class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <p v-if="form.errors[`media_items.${entry.i}.title`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${entry.i}.title`] }}</p>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-slate-500">Gambar</p>
                                        <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                            <button type="button" @click="switchMediaImageMode(entry.i, 'upload')"
                                                class="px-2.5 py-1 transition-colors duration-150"
                                                :class="mediaImageModes[entry.i] === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                                Upload
                                            </button>
                                            <button type="button" @click="switchMediaImageMode(entry.i, 'url')"
                                                class="px-2.5 py-1 transition-colors duration-150"
                                                :class="mediaImageModes[entry.i] === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                                URL
                                            </button>
                                        </div>
                                    </div>

                                    <template v-if="mediaImageModes[entry.i] === 'upload'">
                                        <div v-if="mediaImagePreviews[entry.i] || (entry.item.image && !entry.item.image.startsWith('http'))"
                                            class="relative w-32 h-20 border border-gray-200 rounded overflow-hidden bg-white">
                                            <img :src="mediaImagePreviews[entry.i] || entry.item.image"
                                                class="w-full h-full object-cover" />
                                            <button type="button" @click="clearMediaImage(entry.i)"
                                                class="absolute top-0.5 right-0.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs font-bold leading-none">
                                                ×
                                            </button>
                                        </div>
                                        <input type="file" accept="image/*"
                                            @change="onMediaImageChange(entry.i, $event)"
                                            class="block text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                                    </template>

                                    <template v-else>
                                        <input v-model="entry.item.image" type="url"
                                            placeholder="https://example.com/foto.jpg"
                                            class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                        <div v-if="entry.item.image"
                                            class="w-32 h-20 border border-gray-200 rounded bg-white">
                                            <img :src="entry.item.image" class="w-full h-full object-cover"
                                                @error="(e) => e.target.style.display = 'none'" />
                                        </div>
                                    </template>
                                    <p v-if="form.errors[`media_images.${entry.i}`]" class="text-xs text-red-500">{{ form.errors[`media_images.${entry.i}`] }}</p>
                                    <p v-if="form.errors[`media_items.${entry.i}.image`]" class="text-xs text-red-500">{{ form.errors[`media_items.${entry.i}.image`] }}</p>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-xs text-slate-300 italic">Belum ada gambar.</p>
                    </div>

                    <!-- Video tab -->
                    <div v-else>
                        <div v-if="mediaVideoEntries.length" class="space-y-3">
                            <div v-for="(entry, n) in mediaVideoEntries" :key="entry.i"
                                class="border border-gray-100 rounded-lg p-3 space-y-3 bg-gray-50/50">

                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-400">Video {{ n + 1 }}</span>
                                    <button type="button" @click="removeMedia(entry.i)"
                                        class="text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div>
                                    <input v-model="entry.item.title" type="text"
                                        placeholder="Judul (ditampilkan di main video & grid video lainnya)"
                                        class="w-full border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <p v-if="form.errors[`media_items.${entry.i}.title`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${entry.i}.title`] }}</p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs text-slate-500">Video</p>
                                    <input v-model="entry.item.video_url" type="url"
                                        placeholder="https://youtu.be/... atau link YouTube/Vimeo"
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <p class="text-xs text-slate-400 mt-1">Bisa pakai link share biasa dari YouTube/Vimeo, otomatis dikonversi ke format embed (sama seperti di Solusi).</p>
                                    <p v-if="form.errors[`media_items.${entry.i}.video_url`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${entry.i}.video_url`] }}</p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs text-slate-500">Tanggal</p>
                                    <input v-model="entry.item.date" type="date"
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                    <p v-if="form.errors[`media_items.${entry.i}.date`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${entry.i}.date`] }}</p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs text-slate-500">Deskripsi <span class="font-normal text-slate-400">(ditampilkan di accordion "More Information")</span></p>
                                    <textarea v-model="entry.item.description" rows="3"
                                        placeholder="Deskripsi singkat video..."
                                        class="w-full border border-gray-200 rounded px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 resize-none"></textarea>
                                    <p v-if="form.errors[`media_items.${entry.i}.description`]" class="text-xs text-red-500 mt-1">{{ form.errors[`media_items.${entry.i}.description`] }}</p>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-xs text-slate-300 italic">Belum ada video.</p>
                    </div>
                </div>

                <!-- Model Overview -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-5">
                    <div>
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Model Overview</h2>
                        <p class="text-xs text-slate-400 mt-1">Tabel perbandingan varian model. Kolom pertama ("Model") selalu ada. Tambah kolom spesifikasi sesuai kebutuhan, lalu isi baris per varian.</p>
                    </div>

                    <!-- Column headers -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">Kolom Spesifikasi</p>
                            <button type="button" @click="addModelOverviewColumn"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kolom
                            </button>
                        </div>

                        <!-- Fixed "Model" column -->
                        <div class="flex items-center gap-2">
                            <input type="text" value="Model" disabled
                                class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-400 bg-gray-50 cursor-not-allowed" />
                            <span class="w-4 h-4 shrink-0"></span>
                        </div>

                        <!-- Dynamic columns -->
                        <div v-for="(col, ci) in form.model_overview.columns" :key="ci" class="flex items-center gap-2">
                            <input v-model="form.model_overview.columns[ci]" type="text"
                                placeholder="e.g. Kapasitas Angkat (kg)"
                                class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                            <button type="button" @click="removeModelOverviewColumn(ci)"
                                class="text-red-400 hover:text-red-600 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p v-if="!form.model_overview.columns.length" class="text-xs text-slate-300 italic">Belum ada kolom spesifikasi.</p>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <!-- Rows -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">Baris Model</p>
                            <button type="button" @click="addModelOverviewRow"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Baris
                            </button>
                        </div>

                        <div v-if="form.model_overview.rows.length" class="space-y-2">
                            <div v-for="(row, ri) in form.model_overview.rows" :key="ri"
                                class="border border-gray-100 rounded p-3 bg-gray-50/50 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-400">Baris {{ ri + 1 }}</span>
                                    <button type="button" @click="removeModelOverviewRow(ri)"
                                        class="text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <!-- Model name (index 0) -->
                                <div class="flex items-center gap-2">
                                    <span class="w-32 shrink-0 text-xs text-slate-400">Model</span>
                                    <input v-model="row[0]" type="text" placeholder="e.g. FM-X 10 / Li-Ion"
                                        class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                </div>
                                <!-- Dynamic column values (index 1+) -->
                                <div v-for="(col, ci) in form.model_overview.columns" :key="ci" class="flex items-center gap-2">
                                    <span class="w-32 shrink-0 text-xs text-slate-400 truncate">{{ col || `Kolom ${ci + 1}` }}</span>
                                    <input v-model="row[ci + 1]" type="text" :placeholder="`Nilai ${col || `kolom ${ci + 1}`}`"
                                        class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400" />
                                </div>
                            </div>
                        </div>

                        <p v-else class="text-xs text-slate-300 italic">Belum ada baris model.</p>
                    </div>
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
