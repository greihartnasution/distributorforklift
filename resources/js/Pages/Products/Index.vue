<template>
    <PublicLayout>
        <Head :title="activeCategory ? `${activeCategory.name} — ${systemCategory?.name}` : (systemCategory?.name ?? 'Produk')" />

        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-screen-xl mx-auto px-4 py-3 flex items-center gap-2 text-sm text-gray-400">
                <a href="/" class="hover:text-orange-600 transition-colors duration-150">Home</a>
                <span>›</span>
                <!-- System category -->
                <a v-if="activeCategory && systemCategory"
                    :href="route('products.system', systemCategory.slug)"
                    class="hover:text-orange-600 transition-colors duration-150">
                    {{ systemCategory.name }}
                </a>
                <span v-else class="text-slate-600 font-medium">{{ systemCategory?.name ?? 'Produk' }}</span>
                <!-- Sub category -->
                <template v-if="activeCategory">
                    <span>›</span>
                    <span class="text-slate-600 font-medium truncate max-w-[200px] md:max-w-xs">
                        {{ activeCategory.name }}
                    </span>
                </template>
            </div>
        </div>

        <!-- ============================================================
             HERO SECTION — same structure as Page/Show.vue
        ============================================================ -->
        <section
            class="relative w-full bg-white overflow-hidden hero-section border-b border-b-gray-100"
        >
            <div class="w-full flex flex-col md:flex-row h-full">
                <!-- Col Left -->
                <div class="hero-left-col flex flex-col justify-center">
                    <div class="inline-flex items-center gap-2 mb-5">
                        <span class="w-6 h-0.5 bg-orange-600"></span>
                        <span
                            class="text-xs font-bold text-orange-600 uppercase tracking-widest"
                        >
                            {{ hero?.label ?? "Produk" }}
                        </span>
                    </div>
                    <h1
                        class="text-3xl lg:text-4xl xl:text-[2.6rem] font-bold text-slate-900 leading-tight mb-4"
                    >
                        {{ hero?.name ?? "Produk" }}
                    </h1>
                    <p
                        v-if="hero?.description"
                        class="lg:text-2xl text-slate-500 leading-relaxed"
                    >
                        {{ hero.description }}
                    </p>
                </div>

                <!-- Col Right -->
                <div
                    class="hero-right-col overflow-hidden bg-gray-100 relative"
                >
                    <img
                        v-if="hero?.image"
                        :src="hero.image"
                        :alt="hero.name"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center"
                    >
                        <svg
                            class="w-16 h-16 text-gray-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             PRODUK LIST — Side filter + cards grid
        ============================================================ -->
        <section id="produk-list" class="bg-gray-50 py-16">
            <div class="max-w-screen-xl mx-auto px-4">
                <div class="produk-layout">
                    <!-- ── Side Filter ──────────────────────────────── -->
                    <aside class="produk-filter">
                        <div class="overflow-hidden sticky top-6">
                            <!-- Filter header -->
                            <div class="flex items-center justify-between gap-3 mb-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                                        style="background: #f2f2f2"
                                    >
                                        <svg
                                            class="w-4 h-4 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.75"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"
                                            />
                                        </svg>
                                    </div>
                                    <span class="text-xl font-medium text-slate-600"
                                        >Filter</span
                                    >
                                </div>
                                <button
                                    v-if="hasActiveFilters"
                                    type="button"
                                    @click="resetFilters"
                                    class="text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors"
                                >
                                    Reset
                                </button>
                            </div>

                            <!-- ① Kategori -->
                            <div class="border-b border-white">
                                <button
                                    @click="toggleFilter('category')"
                                    class="filter-group-header"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-5 h-5 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.75"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                            />
                                        </svg>
                                        <span
                                            class="text-lg font-bold text-slate-700"
                                            >Kategori</span
                                        >
                                    </div>
                                    <div class="filter-toggle-btn">
                                        {{ openFilters.category ? "−" : "+" }}
                                    </div>
                                </button>
                                <div
                                    v-if="openFilters.category"
                                    class="px-4 py-3 space-y-1"
                                >
                                    <Link
                                        :href="route('products.system', systemCategory?.slug)"
                                        class="filter-checkbox-row lg:text-lg py-1"
                                        :class="
                                            !activeCategory
                                                ? 'font-semibold text-orange-600'
                                                : 'text-slate-600'
                                        "
                                    >
                                        Semua
                                    </Link>
                                    <Link
                                        v-for="cat in categories"
                                        :key="cat.id"
                                        :href="route('products.sub', [systemCategory?.slug, cat.slug])"
                                        class="filter-checkbox-row lg:text-lg py-1"
                                        :class="
                                            activeCategory?.slug === cat.slug
                                                ? 'font-semibold text-orange-600'
                                                : 'text-slate-600'
                                        "
                                    >
                                        {{ cat.name }}
                                    </Link>
                                </div>
                            </div>

                            <!-- ② Kapasitas Angkat -->
                            <div class="border-b border-white">
                                <button
                                    @click="toggleFilter('capacity')"
                                    class="filter-group-header"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-5 h-5 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-for="(d, di) in SPEC_ICONS[
                                                    'Kapasitas Angkat'
                                                ]"
                                                :key="di"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                :d="d"
                                            />
                                        </svg>
                                        <span
                                            class="text-lg font-bold text-slate-700"
                                            >Kapasitas Angkat (kg)</span
                                        >
                                    </div>
                                    <div class="filter-toggle-btn">
                                        {{ openFilters.capacity ? "−" : "+" }}
                                    </div>
                                </button>
                                <div
                                    v-if="openFilters.capacity"
                                    class="px-4 pb-4"
                                >
                                    <input
                                        type="range"
                                        :min="capacityBounds.min"
                                        :max="capacityBounds.max"
                                        v-model.number="capacityMax"
                                        class="filter-slider w-full mb-3"
                                    />
                                    <div class="flex gap-2">
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >min</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="capacityMin"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >kg</span
                                            >
                                        </div>
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >max</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="capacityMax"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >kg</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ③ Tinggi Angkat Maks -->
                            <div class="border-b border-white">
                                <button
                                    @click="toggleFilter('liftHeight')"
                                    class="filter-group-header"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg
                                            class="w-5 h-5 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-for="(d, di) in SPEC_ICONS[
                                                    'Tinggi Angkat Maks'
                                                ]"
                                                :key="di"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                :d="d"
                                            />
                                        </svg>
                                        <span
                                            class="text-lg font-bold text-slate-700"
                                            >Tinggi Angkat Maks (mm)</span
                                        >
                                    </div>
                                    <div class="filter-toggle-btn">
                                        {{ openFilters.liftHeight ? "−" : "+" }}
                                    </div>
                                </button>
                                <div
                                    v-if="openFilters.liftHeight"
                                    class="px-4 pb-4"
                                >
                                    <input
                                        type="range"
                                        :min="liftHeightBounds.min"
                                        :max="liftHeightBounds.max"
                                        v-model.number="liftHeightMax"
                                        class="filter-slider w-full mb-3"
                                    />
                                    <div class="flex gap-2">
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >min</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="liftHeightMin"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >mm</span
                                            >
                                        </div>
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >max</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="liftHeightMax"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >mm</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ④ Otomatis -->
                            <div class="border-b border-white">
                                <button
                                    @click="toggleFilter('automatic')"
                                    class="filter-group-header"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg
                                            class="w-5 h-5 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-for="(d, di) in SPEC_ICONS[
                                                    'Otomatis'
                                                ]"
                                                :key="di"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                :d="d"
                                            />
                                        </svg>
                                        <span
                                            class="text-lg font-bold text-slate-700"
                                            >Otomatis</span
                                        >
                                    </div>
                                    <div class="filter-toggle-btn">
                                        {{ openFilters.automatic ? "−" : "+" }}
                                    </div>
                                </button>
                                <div
                                    v-if="openFilters.automatic"
                                    class="px-4 py-4"
                                >
                                    <label class="filter-checkbox-row">
                                        <input
                                            type="checkbox"
                                            v-model="onlyAutomatic"
                                            class="filter-checkbox"
                                        />
                                        <span
                                            class="lg:text-lg text-slate-600"
                                            >Tampilkan unit otomatis saja</span
                                        >
                                    </label>
                                </div>
                            </div>

                            <!-- ⑤ Kecepatan Jalan -->
                            <div class="border-b border-white">
                                <button
                                    @click="toggleFilter('speed')"
                                    class="filter-group-header"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg
                                            class="w-5 h-5 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-for="(d, di) in SPEC_ICONS[
                                                    'Kecepatan Jalan'
                                                ]"
                                                :key="di"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                :d="d"
                                            />
                                        </svg>
                                        <span
                                            class="text-lg font-bold text-slate-700"
                                            >Kecepatan Jalan (km/h)</span
                                        >
                                    </div>
                                    <div class="filter-toggle-btn">
                                        {{ openFilters.speed ? "−" : "+" }}
                                    </div>
                                </button>
                                <div v-if="openFilters.speed" class="px-4 pb-4">
                                    <input
                                        type="range"
                                        :min="speedBounds.min"
                                        :max="speedBounds.max"
                                        v-model.number="speedMax"
                                        class="filter-slider w-full mb-3"
                                    />
                                    <div class="flex gap-2">
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >min</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="speedMin"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >km/h</span
                                            >
                                        </div>
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >max</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="speedMax"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >km/h</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ⑥ Kapasitas Penarik -->
                            <div>
                                <button
                                    @click="toggleFilter('towCapacity')"
                                    class="filter-group-header"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg
                                            class="w-5 h-5 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-for="(d, di) in SPEC_ICONS[
                                                    'Kapasitas Penarik'
                                                ]"
                                                :key="di"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                :d="d"
                                            />
                                        </svg>
                                        <span
                                            class="text-lg font-bold text-slate-700"
                                            >Kapasitas Penarik (kg)</span
                                        >
                                    </div>
                                    <div class="filter-toggle-btn">
                                        {{
                                            openFilters.towCapacity ? "−" : "+"
                                        }}
                                    </div>
                                </button>
                                <div
                                    v-if="openFilters.towCapacity"
                                    class="px-4 pb-4"
                                >
                                    <input
                                        type="range"
                                        :min="towCapacityBounds.min"
                                        :max="towCapacityBounds.max"
                                        v-model.number="towCapacityMax"
                                        class="filter-slider w-full mb-3"
                                    />
                                    <div class="flex gap-2">
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >min</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="towCapacityMin"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >kg</span
                                            >
                                        </div>
                                        <div class="filter-input-box">
                                            <span class="filter-input-label"
                                                >max</span
                                            >
                                            <input
                                                type="number"
                                                v-model.number="towCapacityMax"
                                                class="filter-input"
                                            />
                                            <span class="filter-input-unit"
                                                >kg</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- ── Products Grid ─────────────────────────────── -->
                    <div class="produk-grid-wrapper">
                        <!-- Count -->
                        <p class="lg:text-xl text-slate-500 mb-6">
                            <span
                                v-if="activeCategory"
                                class="font-semibold text-slate-700"
                                >{{ activeCategory.name }}</span
                            >
                            <template v-if="activeCategory"> — </template>
                            <span class="font-semibold text-slate-700"
                                >{{ displayProducts.length }} produk</span
                            >
                            ditemukan
                        </p>

                        <!-- Grid -->
                        <div class="produk-grid gap-6">
                            <Link
                                v-for="product in displayProducts"
                                :key="product.id"
                                :href="
                                    route('products.show', [
                                        product.system_slug,
                                        product.category_slug,
                                        product.slug,
                                    ])
                                "
                                class="overflow-hidden transition-all duration-200 group flex flex-col"
                            >
                                <!-- Image -->
                                <div
                                    :class="product.image ? 'bg-gray-50' : 'bg-gray-100'"
                                    class="relative flex items-center justify-center h-64 overflow-hidden"
                                >
                                    <img
                                        v-if="product.image"
                                        :src="product.image"
                                        :alt="product.name"
                                        class="h-full object-contain group-hover:scale-105 transition-transform duration-500"
                                    />
                                    <svg
                                        v-else
                                        class="w-16 h-16 text-gray-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <!-- Specs strip (dynamic, up to 5 specs) -->
                                <div
                                    v-if="product.specs && product.specs.length"
                                    class="grid grid-cols-5 border-t border-gray-100 py-4"
                                >
                                    <div
                                        v-for="(spec, i) in product.specs.slice(
                                            0,
                                            5,
                                        )"
                                        :key="i"
                                        class="flex flex-col items-center gap-y-1 justify-center px-1.5"
                                        :class="{
                                            'border-l border-gray-200':
                                                i > 0,
                                            'pl-0': i === 0,
                                            'pr-0':
                                                i ===
                                                product.specs.slice(0, 5)
                                                    .length -
                                                    1,
                                        }"
                                    >
                                        <svg
                                            v-if="SPEC_ICONS[spec.label]"
                                            class="w-5 h-5 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-for="(d, di) in SPEC_ICONS[
                                                    spec.label
                                                ]"
                                                :key="di"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                :d="d"
                                            />
                                        </svg>
                                        <span
                                            class="text-[11px] text-slate-500 text-center leading-tight"
                                            >{{ spec.value }}</span
                                        >
                                    </div>
                                </div>

                                <!-- Name & CTA -->
                                <div
                                    class="py-4 border-b-2 border-gray-300 mt-auto"
                                >
                                    <h3
                                        class="text-2xl font-bold text-slate-600 mb-2 leading-snug"
                                    >
                                        {{ product.name }}
                                    </h3>
                                    <span
                                        class="inline-flex items-center gap-1 lg:text-xl font-semibold text-orange-600 group-hover:gap-2 transition-all duration-200"
                                    >
                                        Selengkapnya
                                    </span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Inquiry Section -->
        <InquirySection v-if="hero?.show_inquiry ?? true" />
    </PublicLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import InquirySection from "@/Components/Public/InquirySection.vue";
import { SPEC_ICONS } from "@/Utils/specIcons";

const props = defineProps({
    systemCategory: { type: Object, default: null },
    categories:     { type: Array,  default: () => [] },
    products:       { type: Array,  default: () => [] },
    activeCategory: { type: Object, default: null },
    hero:           { type: Object, default: null },
});

// ── Filter state ─────────────────────────────────────────────────
const openFilters = ref({
    category: true,
    capacity: false,
    liftHeight: false,
    automatic: false,
    speed: false,
    towCapacity: false,
});
function toggleFilter(key) {
    openFilters.value[key] = !openFilters.value[key];
}

// The 5 fixed spec labels (same ones used across the product detail page)
const SPEC_LABELS = {
    capacity: "Kapasitas Angkat",
    liftHeight: "Tinggi Angkat Maks",
    automatic: "Otomatis",
    speed: "Kecepatan Jalan",
    towCapacity: "Kapasitas Penarik",
};

function getSpecValue(product, label) {
    return product.specs?.find((s) => s.label === label)?.value ?? null;
}

// Spec values are free-text (e.g. "1.400 kg", "7,2 km/h", "1.500 – 5.000 kg").
// This pulls out the first number, handling ID/EU formatting (. = thousands, , = decimal).
function parseSpecNumber(raw) {
    if (!raw) return null;
    const match = String(raw).match(/[\d.,]+/);
    if (!match) return null;
    let s = match[0];
    if (s.includes(",")) {
        s = s.replace(/\./g, "").replace(",", ".");
    } else if (/\.\d{3}(\D|$)/.test(s)) {
        s = s.replace(/\./g, "");
    }
    const n = parseFloat(s);
    return Number.isNaN(n) ? null : n;
}

function specBounds(label) {
    const vals = props.products
        .map((p) => parseSpecNumber(getSpecValue(p, label)))
        .filter((v) => v !== null);
    if (!vals.length) return { min: 0, max: 0 };
    return {
        min: Math.floor(Math.min(...vals)),
        max: Math.ceil(Math.max(...vals)),
    };
}

const capacityBounds = specBounds(SPEC_LABELS.capacity);
const liftHeightBounds = specBounds(SPEC_LABELS.liftHeight);
const speedBounds = specBounds(SPEC_LABELS.speed);
const towCapacityBounds = specBounds(SPEC_LABELS.towCapacity);

const capacityMin = ref(capacityBounds.min);
const capacityMax = ref(capacityBounds.max);
const liftHeightMin = ref(liftHeightBounds.min);
const liftHeightMax = ref(liftHeightBounds.max);
const speedMin = ref(speedBounds.min);
const speedMax = ref(speedBounds.max);
const towCapacityMin = ref(towCapacityBounds.min);
const towCapacityMax = ref(towCapacityBounds.max);
const onlyAutomatic = ref(false);

function resetFilters() {
    capacityMin.value = capacityBounds.min;
    capacityMax.value = capacityBounds.max;
    liftHeightMin.value = liftHeightBounds.min;
    liftHeightMax.value = liftHeightBounds.max;
    speedMin.value = speedBounds.min;
    speedMax.value = speedBounds.max;
    towCapacityMin.value = towCapacityBounds.min;
    towCapacityMax.value = towCapacityBounds.max;
    onlyAutomatic.value = false;
}

const hasActiveFilters = computed(
    () =>
        Number(capacityMin.value) > capacityBounds.min ||
        Number(capacityMax.value) < capacityBounds.max ||
        Number(liftHeightMin.value) > liftHeightBounds.min ||
        Number(liftHeightMax.value) < liftHeightBounds.max ||
        Number(speedMin.value) > speedBounds.min ||
        Number(speedMax.value) < speedBounds.max ||
        Number(towCapacityMin.value) > towCapacityBounds.min ||
        Number(towCapacityMax.value) < towCapacityBounds.max ||
        onlyAutomatic.value,
);

// A product without a given spec filled in is never excluded by that
// spec's filter (avoids hiding products that simply have incomplete data).
const displayProducts = computed(() =>
    props.products.filter((p) => {
        const cap = parseSpecNumber(getSpecValue(p, SPEC_LABELS.capacity));
        if (
            cap !== null &&
            (cap < Number(capacityMin.value) || cap > Number(capacityMax.value))
        )
            return false;

        const height = parseSpecNumber(
            getSpecValue(p, SPEC_LABELS.liftHeight),
        );
        if (
            height !== null &&
            (height < Number(liftHeightMin.value) ||
                height > Number(liftHeightMax.value))
        )
            return false;

        const speed = parseSpecNumber(getSpecValue(p, SPEC_LABELS.speed));
        if (
            speed !== null &&
            (speed < Number(speedMin.value) || speed > Number(speedMax.value))
        )
            return false;

        const tow = parseSpecNumber(
            getSpecValue(p, SPEC_LABELS.towCapacity),
        );
        if (
            tow !== null &&
            (tow < Number(towCapacityMin.value) ||
                tow > Number(towCapacityMax.value))
        )
            return false;

        if (onlyAutomatic.value) {
            if (getSpecValue(p, SPEC_LABELS.automatic) !== "Ya")
                return false;
        }

        return true;
    }),
);
</script>

<style scoped>
/* ── Hero responsive layout (sama persis dengan Page/Show.vue) ── */
.hero-section {
    height: 560px;
}

.hero-left-col {
    width: 100%;
    padding: 2.5rem 1.5rem 2rem;
}

.hero-right-col {
    width: 100%;
    height: 240px;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .hero-section {
        height: 510px;
    }

    .hero-left-col {
        width: 45%;
        flex-shrink: 0;
        padding-top: 0;
        padding-bottom: 0;
        padding-right: 3rem;
        padding-left: max(calc((100vw - 80rem) / 2 + 1.5rem), 1.5rem);
    }

    .hero-right-col {
        flex: 1;
        height: auto;
    }
}

/* ── Filter components ────────────────────────────────────────── */
.filter-group-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: #f2f2f2;
    cursor: pointer;
    transition: background 0.15s;
}
.filter-group-header:hover {
    background: #f3f4f6;
}

.filter-toggle-btn {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    background: #969492;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 500;
    color: #fff;
    line-height: 1;
    flex-shrink: 0;
}

.filter-slider {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 2px;
    background: #e2e8f0;
    outline: none;
    border-radius: 1px;
    margin-top: 0.5rem;
}
.filter-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 14px;
    height: 22px;
    background: #ea580c;
    cursor: pointer;
    border-radius: 2px;
}
.filter-slider::-moz-range-thumb {
    width: 14px;
    height: 22px;
    background: #ea580c;
    cursor: pointer;
    border-radius: 2px;
    border: none;
}

.filter-input-box {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    /* border: 1px solid #e5e7eb; */
    border-radius: 0.25rem;
    padding: 0.375rem 0.5rem;
    flex: 1;
}
.filter-input-label {
    font-size: 10px;
    color: #9ca3af;
}
.filter-input-unit {
    font-size: 10px;
    color: #9ca3af;
}
.filter-input {
    width: 100%;
    font-size: 0.75rem;
    color: #1e293b;
    outline: none;
    background: transparent;
    min-width: 0;
}

.filter-checkbox-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
}
.filter-checkbox {
    width: 1rem;
    height: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 2px;
    cursor: pointer;
    accent-color: #ea580c;
    flex-shrink: 0;
}

/* ── Produk layout ────────────────────────────────────────────── */
.produk-layout {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.produk-filter {
    width: 100%;
    flex-shrink: 0;
}

.produk-grid-wrapper {
    flex: 1;
    min-width: 0;
}

.produk-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 640px) {
    .produk-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .produk-layout {
        flex-direction: row;
    }

    .produk-filter {
        width: 280px;
    }

    .produk-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
