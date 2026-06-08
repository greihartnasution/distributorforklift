<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    products:          { type: Array, default: () => [] },
    systemCategories:  { type: Array, default: () => [] },
});

const activeFilter = ref("semua");

const filteredProducts = computed(() => {
    if (activeFilter.value === "semua") return props.products;
    return props.products.filter((p) => p.system_slug === activeFilter.value);
});

function destroy(id) {
    if (!confirm("Hapus produk ini?")) return;
    router.delete(route("admin.products.destroy", id));
}
</script>

<template>
    <Head title="Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Produk</span>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-semibold text-slate-700">Daftar Produk</span>
            </div>
        </template>

        <div class="max-w-5xl mx-auto">

            <!-- Header row -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div>
                    <h1 class="text-lg font-black text-slate-900">Daftar Produk</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola produk katalog yang ditampilkan di website.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <Link :href="route('admin.product-categories.index')"
                        class="inline-flex items-center gap-1.5 border border-gray-200 hover:border-orange-400 text-slate-600 hover:text-orange-600 text-xs font-semibold px-3 py-2.5 rounded transition-colors duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H7m6 6l-6-6 6-6" />
                        </svg>
                        Kategori
                    </Link>
                    <Link :href="route('admin.products.create')"
                        class="inline-flex w-fit items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold px-4 py-2.5 transition-colors duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk
                    </Link>
                </div>
            </div>

            <!-- Filter tabs -->
            <div class="flex items-center gap-1 mb-4 border-b border-gray-100">
                <button
                    @click="activeFilter = 'semua'"
                    :class="activeFilter === 'semua'
                        ? 'text-orange-600 border-b-2 border-orange-600'
                        : 'text-slate-500 border-b-2 border-transparent hover:text-slate-700'"
                    class="px-4 py-2.5 text-xs font-semibold transition-colors duration-150 -mb-px">
                    Semua
                    <span class="ml-1.5 text-[10px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full">
                        {{ products.length }}
                    </span>
                </button>
                <button v-for="sys in systemCategories" :key="sys.slug"
                    @click="activeFilter = sys.slug"
                    :class="activeFilter === sys.slug
                        ? 'text-orange-600 border-b-2 border-orange-600'
                        : 'text-slate-500 border-b-2 border-transparent hover:text-slate-700'"
                    class="px-4 py-2.5 text-xs font-semibold transition-colors duration-150 -mb-px">
                    {{ sys.name }}
                    <span class="ml-1.5 text-[10px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full">
                        {{ products.filter(p => p.system_slug === sys.slug).length }}
                    </span>
                </button>
            </div>

            <!-- Empty -->
            <div v-if="!filteredProducts.length"
                class="bg-white border border-gray-100 rounded-lg p-12 text-center">
                <p class="text-slate-400 text-sm">Belum ada produk di kategori ini.</p>
            </div>

            <!-- Table -->
            <div v-else class="bg-white border border-gray-100 rounded-lg overflow-x-auto">
                <table class="w-full min-w-[640px] text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">Foto</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Kategori</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">URL</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 w-32"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="p in filteredProducts" :key="p.id" class="hover:bg-gray-50/50 transition-colors">
                            <!-- Thumbnail -->
                            <td class="px-4 py-3">
                                <div class="w-12 h-12 rounded border border-gray-100 overflow-hidden bg-gray-50 flex items-center justify-center">
                                    <img v-if="p.image" :src="p.image" :alt="p.name"
                                        class="w-full h-full object-cover" />
                                    <svg v-else class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </td>
                            <!-- Name -->
                            <td class="px-4 py-3">
                                <span class="font-semibold text-slate-900">{{ p.name }}</span>
                                <!-- Category visible on mobile only -->
                                <p class="text-[11px] text-slate-400 mt-0.5 sm:hidden">{{ p.system_name }} › {{ p.category_name }}</p>
                            </td>
                            <!-- Category — hidden on mobile -->
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="text-xs text-slate-400">{{ p.system_name }}</span>
                                <span class="text-xs text-slate-300 mx-1">›</span>
                                <span class="text-xs text-slate-600 font-medium">{{ p.category_name ?? "—" }}</span>
                            </td>
                            <!-- URL — hidden on mobile/tablet -->
                            <td class="px-4 py-3 hidden md:table-cell">
                                <code class="text-[11px] bg-gray-50 px-1.5 py-0.5 rounded text-slate-400">
                                    /{{ p.system_slug }}/{{ p.category_slug }}/{{ p.slug }}
                                </code>
                            </td>
                            <!-- Status -->
                            <td class="px-4 py-3">
                                <span :class="p.is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-400'"
                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    {{ p.is_active ? "Aktif" : "Nonaktif" }}
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 justify-end">
                                    <Link :href="route('admin.products.edit', p.id)"
                                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded transition-colors duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </Link>
                                    <button @click="destroy(p.id)"
                                        class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded transition-colors duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
