<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    clients: { type: Array, default: () => [] },
});

function destroy(id) {
    if (!confirm("Hapus klien ini?")) return;
    router.delete(route("admin.homepage.clients.destroy", id));
}
</script>

<template>
    <Head title="Klien" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Homepage</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">Klien</span>
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-lg font-black text-slate-900">Klien</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola daftar logo klien yang ditampilkan di homepage.</p>
                </div>
                <Link :href="route('admin.homepage.clients.create')"
                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold px-5 py-2.5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Klien
                </Link>
            </div>

            <!-- Empty -->
            <div v-if="!clients.length"
                class="bg-white border border-gray-100 rounded-lg p-12 text-center">
                <p class="text-slate-400 text-sm">Belum ada klien. Tambahkan yang pertama.</p>
            </div>

            <!-- List -->
            <div v-else class="space-y-3">
                <div v-for="c in clients" :key="c.id"
                    class="bg-white border border-gray-100 rounded-lg p-4 flex items-center gap-4">

                    <!-- Logo -->
                    <div class="w-20 h-12 shrink-0 rounded border border-gray-100 overflow-hidden bg-gray-50 flex items-center justify-center">
                        <img v-if="c.logo" :src="'/storage/' + c.logo" :alt="c.name"
                            class="w-full h-full object-contain p-1" />
                        <svg v-else class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-900">{{ c.name }}</span>
                            <span v-if="!c.is_active"
                                class="text-[10px] font-semibold bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">
                                Nonaktif
                            </span>
                        </div>
                        <a v-if="c.website" :href="c.website" target="_blank" rel="noopener noreferrer"
                            class="text-xs text-slate-400 hover:text-orange-600 transition-colors truncate block mt-0.5">
                            {{ c.website }}
                        </a>
                        <span v-else class="text-xs text-slate-300 mt-0.5 block">—</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <Link :href="route('admin.homepage.clients.edit', c.id)"
                            class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </Link>
                        <button @click="destroy(c.id)"
                            class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
