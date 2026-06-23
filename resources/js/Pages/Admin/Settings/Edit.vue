<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ setting: Object });

const form = useForm({
    _method:          "PUT",
    site_name:        props.setting.site_name        ?? "",
    meta_description: props.setting.meta_description ?? "",
    og_image:         (() => { const v = props.setting.og_image_url; return v?.startsWith('http') ? v : ""; })(),
    og_image_file:    null,
    clear_og_image:   false,
    favicon:          null,
    clear_favicon:    false,
    phone:            props.setting.phone     ?? "",
    whatsapp:         props.setting.whatsapp  ?? "",
    email:            props.setting.email     ?? "",
    address:          props.setting.address   ?? "",
    youtube:          props.setting.youtube   ?? "",
    instagram:        props.setting.instagram ?? "",
    tiktok:           props.setting.tiktok    ?? "",
    facebook:         props.setting.facebook  ?? "",
});

const ogImageMode = ref(
    (() => { const v = props.setting.og_image_url; return v?.startsWith('http') ? 'url' : 'upload'; })()
);
const ogPreview = ref(
    (() => { const v = props.setting.og_image_url; return v && !v.startsWith('http') ? v : null; })()
);
const faviconPreview = ref(props.setting.favicon_url  ?? null);
const ogInput        = ref(null);
const faviconInput   = ref(null);

function switchOgImageMode(mode) {
    ogImageMode.value = mode;
    if (mode === 'url') {
        form.og_image_file = null;
        ogPreview.value = null;
        if (ogInput.value) ogInput.value.value = "";
    } else {
        form.og_image = "";
    }
}

function onOgChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.og_image_file  = file;
    form.og_image       = "";
    form.clear_og_image = false;
    ogPreview.value     = URL.createObjectURL(file);
}
function clearOg() {
    form.og_image_file  = null;
    form.og_image       = "";
    form.clear_og_image = true;
    ogPreview.value     = null;
    if (ogInput.value) ogInput.value.value = "";
}

function onFaviconChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.favicon       = file;
    form.clear_favicon = false;
    faviconPreview.value = URL.createObjectURL(file);
}
function clearFavicon() {
    form.favicon         = null;
    form.clear_favicon   = true;
    faviconPreview.value = null;
    if (faviconInput.value) faviconInput.value.value = "";
}

function submit() {
    form.post(route("admin.settings.update"), { forceFormData: true });
}
</script>

<template>
    <Head title="Pengaturan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Sistem</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">Pengaturan</span>
            </div>
        </template>

        <div class="max-w-2xl mx-auto">
            <div class="mb-5">
                <h1 class="text-lg font-black text-slate-900">Pengaturan Situs</h1>
                <p class="text-xs text-slate-400 mt-0.5">Informasi perusahaan dan media sosial.</p>
            </div>

            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-5">

                <!-- SEO & Identitas -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">SEO & Identitas Situs</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Digunakan pada meta tag, Open Graph, dan tab browser.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Nama Situs
                            <span class="font-normal text-slate-400 ml-1">— muncul di tab browser & OG title</span>
                        </label>
                        <input v-model="form.site_name" type="text" placeholder="Distributor Forklift Indonesia"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Meta Description
                            <span class="font-normal text-slate-400 ml-1">— maks. 160 karakter, tampil di hasil pencarian Google</span>
                        </label>
                        <textarea v-model="form.meta_description" rows="3" maxlength="300"
                            placeholder="Distributor forklift dan solusi material handling terpercaya untuk industri Indonesia."
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"></textarea>
                        <p class="text-[10px] text-slate-400 mt-1">
                            {{ (form.meta_description ?? "").length }} / 300 karakter
                        </p>
                    </div>

                    <!-- OG Image -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-500">
                                OG Image (Social Share)
                                <span class="font-normal text-slate-400 ml-1">— ukuran ideal 1200×630 px</span>
                            </label>
                            <div class="flex items-center border border-gray-200 rounded overflow-hidden text-xs font-semibold">
                                <button type="button" @click="switchOgImageMode('upload')"
                                    class="px-3 py-1.5 transition-colors duration-150"
                                    :class="ogImageMode === 'upload' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                    Upload
                                </button>
                                <button type="button" @click="switchOgImageMode('url')"
                                    class="px-3 py-1.5 transition-colors duration-150"
                                    :class="ogImageMode === 'url' ? 'bg-orange-600 text-white' : 'text-slate-500 hover:text-slate-700'">
                                    URL Eksternal
                                </button>
                            </div>
                        </div>

                        <template v-if="ogImageMode === 'upload'">
                            <div v-if="ogPreview" class="relative mb-2 w-full max-w-xs">
                                <img :src="ogPreview" alt="OG Preview" class="w-full rounded border border-gray-200 object-cover" style="aspect-ratio:1200/630" />
                                <button type="button" @click="clearOg"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">×</button>
                            </div>
                            <input ref="ogInput" type="file" accept="image/*" @change="onOgChange"
                                class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, WebP. Maks 1.5 MB.</p>
                        </template>
                        <template v-else>
                            <input v-model="form.og_image" type="url" placeholder="https://example.com/og-image.jpg"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <div v-if="form.og_image" class="mt-2 w-full max-w-xs rounded border border-gray-200 overflow-hidden bg-gray-50" style="aspect-ratio:1200/630">
                                <img :src="form.og_image" class="w-full h-full object-cover"
                                    @error="(e) => e.target.style.display = 'none'" />
                            </div>
                        </template>
                        <p v-if="form.errors.og_image" class="text-xs text-red-500 mt-1">{{ form.errors.og_image }}</p>
                        <p v-if="form.errors.og_image_file" class="text-xs text-red-500 mt-1">{{ form.errors.og_image_file }}</p>
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                            Favicon
                            <span class="font-normal text-slate-400 ml-1">— PNG 32×32 atau 64×64 px</span>
                        </label>
                        <div v-if="faviconPreview" class="relative mb-2 inline-flex">
                            <img :src="faviconPreview" alt="Favicon Preview" class="w-10 h-10 rounded border border-gray-200 object-contain bg-gray-50" />
                            <button type="button" @click="clearFavicon"
                                class="absolute -top-1 -right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold">×</button>
                        </div>
                        <input ref="faviconInput" type="file" accept="image/png,image/x-icon,image/webp" @change="onFaviconChange"
                            class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer" />
                        <p class="text-[10px] text-slate-400 mt-1">PNG atau WebP. Maks 256 KB. Jika kosong, menggunakan favicon.ico default.</p>
                        <p v-if="form.errors.favicon" class="text-xs text-red-500 mt-1">{{ form.errors.favicon }}</p>
                    </div>
                </div>

                <!-- Informasi Kontak -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Informasi Kontak</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nomor Telepon</label>
                            <input v-model="form.phone" type="text" placeholder="+62 21 0000 0000"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.phone" class="mt-1 text-xs text-red-500">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nomor WhatsApp <span class="font-normal text-slate-400">— tanpa tanda +</span></label>
                            <input v-model="form.whatsapp" type="text" placeholder="6281200000000"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.whatsapp" class="mt-1 text-xs text-red-500">{{ form.errors.whatsapp }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Email Perusahaan</label>
                        <input v-model="form.email" type="email" placeholder="info@perusahaan.co.id"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Alamat</label>
                        <textarea v-model="form.address" rows="3" placeholder="Jl. Raya Industri No. 1, Jakarta"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"></textarea>
                        <p v-if="form.errors.address" class="mt-1 text-xs text-red-500">{{ form.errors.address }}</p>
                    </div>
                </div>

                <!-- Media Sosial -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Media Sosial</p>
                    <p class="text-[11px] text-slate-400 -mt-2">Isi username atau URL lengkap profil akun.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Instagram</label>
                            <div class="flex items-center border border-gray-200 rounded focus-within:border-orange-500 focus-within:ring-1 focus-within:ring-orange-500 transition-colors">
                                <span class="px-3 text-xs text-slate-400 border-r border-gray-200 py-2.5">instagram.com/</span>
                                <input v-model="form.instagram" type="text" placeholder="username"
                                    class="flex-1 px-3 py-2.5 text-sm text-slate-900 focus:outline-none bg-transparent" />
                            </div>
                            <p v-if="form.errors.instagram" class="mt-1 text-xs text-red-500">{{ form.errors.instagram }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">TikTok</label>
                            <div class="flex items-center border border-gray-200 rounded focus-within:border-orange-500 focus-within:ring-1 focus-within:ring-orange-500 transition-colors">
                                <span class="px-3 text-xs text-slate-400 border-r border-gray-200 py-2.5">tiktok.com/@</span>
                                <input v-model="form.tiktok" type="text" placeholder="username"
                                    class="flex-1 px-3 py-2.5 text-sm text-slate-900 focus:outline-none bg-transparent" />
                            </div>
                            <p v-if="form.errors.tiktok" class="mt-1 text-xs text-red-500">{{ form.errors.tiktok }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Facebook</label>
                            <div class="flex items-center border border-gray-200 rounded focus-within:border-orange-500 focus-within:ring-1 focus-within:ring-orange-500 transition-colors">
                                <span class="px-3 text-xs text-slate-400 border-r border-gray-200 py-2.5">facebook.com/</span>
                                <input v-model="form.facebook" type="text" placeholder="username"
                                    class="flex-1 px-3 py-2.5 text-sm text-slate-900 focus:outline-none bg-transparent" />
                            </div>
                            <p v-if="form.errors.facebook" class="mt-1 text-xs text-red-500">{{ form.errors.facebook }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">YouTube <span class="font-normal text-slate-400">— URL channel</span></label>
                            <input v-model="form.youtube" type="text" placeholder="https://youtube.com/@channel"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                            <p v-if="form.errors.youtube" class="mt-1 text-xs text-red-500">{{ form.errors.youtube }}</p>
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
