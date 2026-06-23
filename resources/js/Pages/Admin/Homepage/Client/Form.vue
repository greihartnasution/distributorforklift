<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    client: { type: Object, default: null },
});

const isEdit = !!props.client;

const form = useForm({
    _method:    isEdit ? "PUT" : undefined,
    name:       props.client?.name      ?? "",
    website:    props.client?.website   ?? "",
    logo:       (() => { const v = props.client?.logo; return v?.startsWith('http') ? v : ""; })(),
    logo_file:  null,
    clear_logo: false,
    sort_order: props.client?.sort_order ?? 0,
    is_active:  props.client?.is_active  ?? true,
});

const logoMode = ref(
    (() => { const v = props.client?.logo; return v?.startsWith('http') ? 'url' : 'upload'; })()
);
const logoPreview = ref(
    (() => { const v = props.client?.logo; return v && !v.startsWith('http') ? v : null; })()
);

function switchLogoMode(mode) {
    logoMode.value = mode;
    if (mode === 'url') {
        form.logo_file = null;
        logoPreview.value = null;
    } else {
        form.logo = "";
    }
}

function onLogoChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.logo_file = file;
    form.logo = "";
    form.clear_logo = false;
    const reader = new FileReader();
    reader.onload = (ev) => (logoPreview.value = ev.target.result);
    reader.readAsDataURL(file);
}

function clearLogo() {
    form.logo_file = null;
    form.logo = "";
    form.clear_logo = true;
    logoPreview.value = null;
}

function submit() {
    if (isEdit) {
        form.post(route("admin.homepage.clients.update", props.client.id), { forceFormData: true });
    } else {
        form.post(route("admin.homepage.clients.store"), { forceFormData: true });
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Edit Klien' : 'Tambah Klien'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Homepage</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <Link :href="route('admin.homepage.clients.index')"
                    class="text-xs text-slate-400 hover:text-orange-600 transition-colors">Klien</Link>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">{{ isEdit ? "Edit" : "Tambah" }}</span>
            </div>
        </template>

        <div class="max-w-2xl mx-auto">
            <div class="flex flex-col gap-2 mb-5">
                <Link :href="route('admin.homepage.clients.index')"
                    class="text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors self-start">
                    ← Kembali
                </Link>
                <h1 class="text-lg font-black text-slate-900">{{ isEdit ? "Edit Klien" : "Tambah Klien" }}</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <!-- Logo -->
                <div class="bg-white border border-gray-100 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Logo Klien</label>
                        <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                            <button type="button" @click="switchLogoMode('upload')"
                                class="px-3 py-1.5 transition-colors duration-150"
                                :class="logoMode === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                Upload
                            </button>
                            <button type="button" @click="switchLogoMode('url')"
                                class="px-3 py-1.5 transition-colors duration-150"
                                :class="logoMode === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                URL Eksternal
                            </button>
                        </div>
                    </div>

                    <template v-if="logoMode === 'upload'">
                        <div class="flex items-center gap-5">
                            <!-- Preview box (landscape, seperti logo pada umumnya) -->
                            <div class="w-32 h-16 rounded border-2 border-gray-200 shrink-0 bg-gray-50 flex items-center justify-center relative group overflow-hidden">
                                <img v-if="logoPreview"
                                    :src="logoPreview"
                                    class="w-full h-full object-contain p-1" alt="Preview" />
                                <svg v-else class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <button v-if="logoPreview" type="button" @click="clearLogo"
                                    class="absolute inset-0 bg-black/50 text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    Hapus
                                </button>
                            </div>

                            <!-- Upload -->
                            <label class="flex-1 flex items-center gap-3 border border-dashed border-gray-200 rounded-md px-4 py-3 cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-colors">
                                <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <div>
                                    <span class="text-xs font-medium text-slate-500">Upload logo</span>
                                    <span class="text-[10px] text-slate-300 ml-1">PNG, SVG, WEBP — Transparan lebih baik</span>
                                </div>
                                <input type="file" class="hidden" accept="image/*" @change="onLogoChange" />
                            </label>
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex items-center gap-5">
                            <div class="w-32 h-16 rounded border-2 border-gray-200 shrink-0 bg-gray-50 flex items-center justify-center overflow-hidden">
                                <img v-if="form.logo" :src="form.logo" class="w-full h-full object-contain p-1"
                                    @error="(e) => e.target.style.display = 'none'" alt="Preview" />
                                <svg v-else class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input v-model="form.logo" type="url" placeholder="https://example.com/logo.png"
                                class="flex-1 border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        </div>
                    </template>
                    <p v-if="form.errors.logo" class="mt-1.5 text-xs text-red-500">{{ form.errors.logo }}</p>
                    <p v-if="form.errors.logo_file" class="mt-1.5 text-xs text-red-500">{{ form.errors.logo_file }}</p>
                </div>

                <!-- Info -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Informasi Klien</p>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" placeholder="PT. Logistik Nusantara"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Website <span class="text-xs font-normal text-slate-300">(opsional)</span></label>
                        <input v-model="form.website" type="url" placeholder="https://example.com"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <p v-if="form.errors.website" class="mt-1 text-xs text-red-500">{{ form.errors.website }}</p>
                    </div>
                </div>

                <!-- Pengaturan -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Pengaturan</p>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600">Aktif</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{ form.is_active ? "Tampil di homepage" : "Disembunyikan" }}
                            </p>
                        </div>
                        <button type="button" @click="form.is_active = !form.is_active"
                            class="relative w-9 h-5 rounded-full transition-colors duration-200 shrink-0"
                            :class="form.is_active ? 'bg-orange-600' : 'bg-gray-200'">
                            <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                                :class="form.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-8 pb-2">
                    <Link :href="route('admin.homepage.clients.index')"
                        class="text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">Batal</Link>
                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 disabled:opacity-50 text-white text-sm font-bold px-6 py-2.5 transition-colors duration-150">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        {{ form.processing ? "Menyimpan..." : (isEdit ? "Perbarui" : "Simpan") }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
