<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    testimonials: { type: Array, default: () => [] },
});

function destroy(id) {
    if (!confirm("Hapus testimoni ini?")) return;
    router.delete(route("admin.homepage.testimonials.destroy", id));
}
</script>

<template>
    <Head title="Testimoni" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Homepage</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">Testimoni</span>
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-lg font-black text-slate-900">Testimoni</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola testimoni klien yang ditampilkan di homepage.</p>
                </div>
                <Link :href="route('admin.homepage.testimonials.create')"
                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold px-5 py-2.5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Testimoni
                </Link>
            </div>

            <!-- Empty -->
            <div v-if="!testimonials.length"
                class="bg-white border border-gray-100 rounded-lg p-12 text-center">
                <p class="text-slate-400 text-sm">Belum ada testimoni. Tambahkan yang pertama.</p>
            </div>

            <!-- List -->
            <div v-else class="space-y-3">
                <div v-for="t in testimonials" :key="t.id"
                    class="bg-white border border-gray-100 rounded-lg p-5 flex items-start gap-4">

                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-orange-200 shrink-0 bg-gray-100 flex items-center justify-center">
                        <img v-if="t.image" :src="t.image" :alt="t.name" class="w-full h-full object-cover" />
                        <svg v-else class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-slate-900">{{ t.name }}</span>
                            <span v-if="!t.is_active"
                                class="text-[10px] font-semibold bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">
                                Nonaktif
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mb-2">
                            {{ t.position }}<span v-if="t.position && t.company"> · </span>{{ t.company }}
                        </p>
                        <p class="text-sm text-slate-500 italic line-clamp-2">"{{ t.quote }}"</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <Link :href="route('admin.homepage.testimonials.edit', t.id)"
                            class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </Link>
                        <button @click="destroy(t.id)"
                            class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
