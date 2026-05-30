<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    testimonial: { type: Object, default: null },
});

const isEdit = !!props.testimonial;

const form = useForm({
    _method:     isEdit ? "PUT" : undefined,
    name:        props.testimonial?.name        ?? "",
    position:    props.testimonial?.position    ?? "",
    company:     props.testimonial?.company     ?? "",
    quote:       props.testimonial?.quote       ?? "",
    image:       null,
    sort_order:  props.testimonial?.sort_order  ?? 0,
    is_active:   props.testimonial?.is_active   ?? true,
});

const imagePreview = ref(null);
const existingImage = ref(props.testimonial?.image ?? null);

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

function submit() {
    if (isEdit) {
        form.post(route("admin.homepage.testimonials.update", props.testimonial.id), { forceFormData: true });
    } else {
        form.post(route("admin.homepage.testimonials.store"), { forceFormData: true });
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Edit Testimoni' : 'Tambah Testimoni'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Homepage</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <Link :href="route('admin.homepage.testimonials.index')"
                    class="text-xs text-slate-400 hover:text-orange-600 transition-colors">Testimoni</Link>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">{{ isEdit ? "Edit" : "Tambah" }}</span>
            </div>
        </template>

        <div class="max-w-2xl mx-auto">
            <div class="flex flex-col gap-2 mb-5">
                <Link :href="route('admin.homepage.testimonials.index')"
                    class="text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors self-start">
                    ← Kembali
                </Link>
                <div>
                    <h1 class="text-lg font-black text-slate-900">{{ isEdit ? "Edit Testimoni" : "Tambah Testimoni" }}</h1>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <!-- Foto -->
                <div class="bg-white border border-gray-100 rounded-lg p-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-3">Foto Testimoner</label>

                    <div class="flex items-center gap-5">
                        <!-- Preview circle -->
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 shrink-0 bg-gray-50 flex items-center justify-center relative group">
                            <img v-if="imagePreview || existingImage"
                                :src="imagePreview || existingImage"
                                class="w-full h-full object-cover" alt="Preview" />
                            <svg v-else class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <button v-if="imagePreview || existingImage" type="button" @click="clearImage"
                                class="absolute inset-0 bg-black/50 text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                Hapus
                            </button>
                        </div>

                        <!-- Upload -->
                        <label class="flex-1 flex items-center gap-3 border border-dashed border-gray-200 rounded-md px-4 py-3 cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-colors">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <div>
                                <span class="text-xs font-medium text-slate-500">Upload foto</span>
                                <span class="text-[10px] text-slate-300 ml-1">JPG, PNG, WEBP — Maks. 2MB</span>
                            </div>
                            <input type="file" class="hidden" accept="image/*" @change="onImageChange" />
                        </label>
                    </div>
                    <p v-if="form.errors.image" class="mt-1.5 text-xs text-red-500">{{ form.errors.image }}</p>
                </div>

                <!-- Info -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Informasi Testimoner</p>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nama <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" placeholder="Budi Santoso"
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jabatan</label>
                            <input v-model="form.position" type="text" placeholder="General Manager"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Perusahaan</label>
                            <input v-model="form.company" type="text" placeholder="PT. Logistik Nusantara"
                                class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kutipan <span class="text-red-500">*</span></label>
                        <textarea v-model="form.quote" rows="4"
                            placeholder="Tulis testimoni klien di sini..."
                            class="w-full border border-gray-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded transition-colors resize-none"></textarea>
                        <p v-if="form.errors.quote" class="mt-1 text-xs text-red-500">{{ form.errors.quote }}</p>
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
                    <Link :href="route('admin.homepage.testimonials.index')"
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
