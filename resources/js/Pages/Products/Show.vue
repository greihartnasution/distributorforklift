<template>
    <PublicLayout>
        <Head :title="product.name + ' — Produk'" />

        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-100">
            <div
                class="max-w-screen-xl mx-auto px-4 py-3 flex items-center gap-2 text-sm text-gray-400"
            >
                <a
                    href="/"
                    class="hover:text-orange-600 transition-colors duration-150"
                    >Home</a
                >
                <span>›</span>
                <a
                    :href="route('products.system', systemCategory.slug)"
                    class="hover:text-orange-600 transition-colors duration-150"
                    >{{ systemCategory.name }}</a
                >
                <span>›</span>
                <a
                    :href="
                        route('products.sub', [
                            systemCategory.slug,
                            subCategory.slug,
                        ])
                    "
                    class="hover:text-orange-600 transition-colors duration-150"
                    >{{ subCategory.name }}</a
                >
                <span>›</span>
                <span
                    class="text-slate-600 font-medium truncate max-w-[200px] md:max-w-xs"
                    >{{ product.name }}</span
                >
            </div>
        </div>

        <!-- Hero: mobile = stacked flow below image, md+ = overlay on image -->

        <!-- Mobile layout -->
        <section class="md:hidden bg-white">
            <!-- Background: image / video (plain block, not overlaid) -->
            <div class="relative w-full h-64 overflow-hidden bg-slate-900">
                <video
                    v-if="product.video_url"
                    :src="product.video_url"
                    class="w-full h-full object-cover"
                    autoplay
                    muted
                    loop
                    playsinline
                />
                <img
                    v-else-if="product.image_url || product.image"
                    :src="product.image_url || product.image"
                    :alt="product.name"
                    class="w-full h-full object-cover"
                />
            </div>

            <div class="px-4 pt-6 pb-2">
                <p class="text-sm font-bold text-slate-400 mb-2">
                    {{ subCategory.name }}
                </p>

                <h1
                    class="text-3xl font-bold text-slate-900 leading-tight mb-3"
                >
                    {{ product.name }}
                </h1>

                <p
                    v-if="product.short_description"
                    class="text-lg font-semibold text-slate-400 leading-snug"
                >
                    {{ product.short_description }}
                </p>

                <!-- Certification badges -->
                <div
                    v-if="heroCertBadges.length"
                    class="flex items-center gap-3 mt-5"
                >
                    <div
                        v-for="(badge, i) in heroCertBadges.slice(0, 2)"
                        :key="i"
                        class="w-14 h-14"
                    >
                        <img
                            :src="badge.image"
                            :alt="`${product.name} certification badge`"
                            class="w-full h-full object-contain drop-shadow-md"
                        />
                    </div>
                </div>

                <!-- Specs list -->
                <div v-if="product.specs?.length" class="w-3/5 mt-5">
                    <div
                        v-for="spec in product.specs.slice(0, 5)"
                        :key="spec.label"
                        class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0"
                    >
                        <svg
                            v-if="SPEC_ICONS[spec.label]"
                            class="w-4 h-4 text-slate-500 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                v-for="(d, di) in SPEC_ICONS[spec.label]"
                                :key="di"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                :d="d"
                            />
                        </svg>
                        <span class="text-sm font-semibold text-slate-600">{{
                            spec.value
                        }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Desktop / tablet layout -->
        <section
            class="hero-section relative w-full overflow-hidden bg-slate-900 hidden md:block"
        >
            <!-- Background: image / video (full cover) -->
            <div class="absolute inset-0">
                <video
                    v-if="product.video_url"
                    :src="product.video_url"
                    class="w-full h-full object-cover"
                    autoplay
                    muted
                    loop
                    playsinline
                />
                <img
                    v-else-if="product.image_url || product.image"
                    :src="product.image_url || product.image"
                    :alt="product.name"
                    class="w-full h-full object-cover"
                />
            </div>

            <!-- Gradient overlays -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 lg:via-black/40 to-transparent pointer-events-none"
            ></div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"
            ></div>

            <!-- Certification badges (max 2, top-right, aligned to content container) -->
            <div
                v-if="heroCertBadges.length"
                class="absolute inset-x-0 top-4 lg:top-8 z-20 max-w-screen-xl mx-auto px-4 lg:px-8 flex justify-end pointer-events-none"
            >
                <div class="flex flex-col gap-3 pointer-events-auto">
                    <div
                        v-for="(badge, i) in heroCertBadges.slice(0, 2)"
                        :key="i"
                        class="w-16 h-16 lg:w-28 lg:h-28"
                    >
                        <img
                            :src="badge.image"
                            :alt="`${product.name} certification badge`"
                            class="w-full h-full object-contain drop-shadow-lg"
                        />
                    </div>
                </div>
            </div>

            <!-- Content (full-height flex: top = heading, bottom = specs) -->
            <div
                class="hero-content relative z-10 flex flex-col justify-between"
            >
                <!-- Center: category · title · description -->
                <div
                    class="flex-1 flex items-center max-w-screen-xl mx-auto w-full px-4 lg:px-8"
                >
                    <div class="max-w-xl">
                        <p class="md:text-xl font-bold text-white/70 mb-6">
                            {{ subCategory.name }}
                        </p>

                        <h1
                            class="text-4xl md:text-5xl xl:text-6xl font-bold text-white leading-tight mb-10"
                        >
                            {{ product.name }}
                        </h1>

                        <p
                            v-if="product.short_description"
                            class="text-2xl md:text-3xl font-bold text-white/60 leading-snug"
                        >
                            {{ product.short_description }}
                        </p>
                    </div>
                </div>

                <!-- Bottom: key specs list (max 4, overlaid on image) -->
                <div
                    v-if="product.specs?.length"
                    class="max-w-screen-xl mx-auto w-full px-4 lg:px-8 pb-10 lg:pb-14"
                >
                    <div class="w-72 lg:w-80">
                        <div
                            v-for="spec in product.specs.slice(0, 4)"
                            :key="spec.label"
                            class="flex items-center gap-3 py-3 border-b border-white/80 last:border-0"
                        >
                            <svg
                                v-if="SPEC_ICONS[spec.label]"
                                class="w-6 h-6 md:w-7 md:h-7 text-white shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    v-for="(d, di) in SPEC_ICONS[spec.label]"
                                    :key="di"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    :d="d"
                                />
                            </svg>
                            <span
                                class="md:text-xl font-bold text-white whitespace-nowrap"
                                >{{ spec.value }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sticky Content Navigation Bar -->
        <div class="max-w-screen-xl mx-auto">
            <div
                class="border-y border-gray-200 sticky top-0 z-20 mt-10 bg-white"
            >
                <div
                    class="px-4 lg:px-8 h-14 flex items-center justify-between"
                >
                    <!-- Left: Content dropdown -->
                    <div class="relative">
                        <button
                            @click="contentMenuOpen = !contentMenuOpen"
                            class="flex items-center gap-2 text-base font-bold text-slate-800 py-2 transition-colors duration-150"
                            :class="
                                contentMenuOpen
                                    ? 'text-slate-900'
                                    : 'hover:text-orange-600'
                            "
                        >
                            Konten
                            <svg
                                class="w-3 h-3 transition-transform duration-200"
                                :class="{ 'rotate-180': contentMenuOpen }"
                                fill="currentColor"
                                viewBox="0 0 10 6"
                            >
                                <path d="M0 0l5 6 5-6z" />
                            </svg>
                        </button>

                        <!-- Dropdown backdrop -->
                        <div
                            v-if="contentMenuOpen"
                            class="fixed inset-0 z-10"
                            @click="contentMenuOpen = false"
                        ></div>

                        <!-- Dropdown menu -->
                        <div
                            v-if="contentMenuOpen"
                            class="absolute top-full left-0 z-30 min-w-[220px] bg-[#efefef]"
                        >
                            <a
                                v-for="section in pageSections"
                                :key="section.href"
                                :href="section.href"
                                @click="contentMenuOpen = false"
                                class="relative block px-5 py-3.5 text-sm text-slate-700 hover:text-orange-600 transition-colors duration-150 after:absolute after:bottom-0 after:left-4 after:right-4 after:border-b after:border-gray-300/50 last:after:hidden"
                            >
                                {{ section.label }}
                            </a>
                        </div>
                    </div>

                    <!-- Right: CTA -->
                    <a
                        :href="whatsappCtaHref ?? '#inquiry'"
                        :target="whatsappCtaHref ? '_blank' : undefined"
                        :rel="whatsappCtaHref ? 'noopener' : undefined"
                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white font-bold px-5 py-2 transition-colors duration-200"
                    >
                        Dapatkan Penawaran
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
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Overview (full description) -->
        <section
            v-if="product.full_description"
            id="overview"
            class="bg-white py-14 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <div
                    class="max-w-3xl product-content"
                    v-html="product.full_description"
                ></div>
            </div>
        </section>

        <!-- Highlights -->
        <section
            v-if="product.highlights?.length"
            id="highlights"
            class="bg-white py-16 md:py-20 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <h2
                    class="text-3xl md:text-4xl font-bold text-[#302e2c] text-center mb-12 md:mb-16"
                >
                    Highlights {{ product.name }}
                </h2>
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center"
                >
                    <!-- Left: highlight list -->
                    <div>
                        <div
                            v-for="(item, i) in product.highlights"
                            :key="i"
                            class="flex gap-5 py-6 border-b border-gray-200 last:border-0 items-center"
                        >
                            <div class="flex-shrink-0 mt-0.5">
                                <img
                                    v-if="item.image_url"
                                    :src="item.image_url"
                                    :alt="item.title"
                                    class="w-10 h-10 object-contain"
                                />
                                <svg
                                    v-else
                                    class="w-8 h-8 text-orange-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        :d="
                                            highlightIcons[
                                                i % highlightIcons.length
                                            ]
                                        "
                                    />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="font-bold text-lg md:text-xl text-[#545454] mb-1"
                                >
                                    {{ item.title }}
                                </p>
                                <p
                                    v-if="item.description"
                                    class="text-slate-500 md:text-xl leading-relaxed"
                                >
                                    {{ item.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Right: product image -->
                    <div class="flex justify-center items-center">
                        <img
                            v-if="highlightImage"
                            :src="highlightImage"
                            :alt="product.name"
                            class="max-h-[480px] w-full object-contain"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Detail (Accordion) -->
        <section
            v-if="productDetails.length"
            id="detail"
            class="bg-white py-16 md:py-20 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <h2
                    class="text-3xl md:text-4xl font-bold text-[#302e2c] text-center mb-12 md:mb-16"
                >
                    {{ product.name }} secara Detail
                </h2>

                <div class="border-t border-gray-200">
                    <div
                        v-for="(detail, i) in productDetails"
                        :key="i"
                        class="border-b border-gray-200"
                        :class="{ 'bg-[#f5f5f5]': activeDetailIndex === i }"
                    >
                        <!-- Header -->
                        <button
                            type="button"
                            @click="toggleDetail(i)"
                            class="group w-full flex items-center gap-5 md:gap-0 px-4 md:px-8 py-5 md:py-6 text-left"
                        >
                            <!-- Col 1: Icon/Image — 25% on desktop -->
                            <div
                                class="flex-shrink-0 flex items-center justify-center md:w-1/4"
                            >
                                <span
                                    class="flex-shrink-0 w-12 h-12 md:w-24 md:h-24 flex items-center justify-center transition-colors duration-200"
                                >
                                    <img
                                        v-if="detail.icon_image"
                                        :src="detail.icon_image"
                                        :alt="detail.title"
                                        class="w-full h-full object-contain"
                                    />
                                    <svg
                                        v-else
                                        class="w-full h-full transition-colors duration-200 text-[#c9c9c9]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            :d="detail.icon"
                                        />
                                    </svg>
                                </span>
                            </div>

                            <!-- Col 2: Title — 50% on desktop -->
                            <div class="flex-1 md:w-1/2">
                                <span
                                    class="font-bold text-xl md:text-3xl text-[#302e2c]"
                                    >{{ detail.title }}</span
                                >
                            </div>

                            <!-- Col 3: Arrow — 25% on desktop -->
                            <div
                                class="flex-shrink-0 flex justify-end md:w-1/4"
                            >
                                <span
                                    class="w-[45px] h-[45px] rounded-full flex items-center justify-center bg-transparent group-hover:bg-orange-600 transition-colors duration-200"
                                >
                                    <svg
                                        class="w-5 h-5 text-orange-600 group-hover:text-white transition-all duration-200"
                                        :class="{
                                            'rotate-180':
                                                activeDetailIndex === i,
                                        }"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </span>
                            </div>
                        </button>

                        <!-- Body -->
                        <div
                            v-show="activeDetailIndex === i"
                            class="px-4 md:px-8 pb-8 md:pb-10"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center"
                            >
                                <img
                                    v-if="detail.image"
                                    :src="detail.image"
                                    :alt="detail.title"
                                    class="w-full max-h-[360px] object-contain"
                                />
                                <div
                                    class="detail-content"
                                    v-html="detail.content"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Solutions (Tabs) -->
        <section
            v-if="solutions.items.length"
            id="solutions"
            class="bg-white py-16 md:py-20 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <div
                    v-if="solutions.title || solutions.description"
                    class="max-w-2xl mx-auto text-center mb-12 md:mb-16"
                >
                    <h2
                        v-if="solutions.title"
                        class="text-3xl md:text-4xl font-bold text-[#302e2c] mb-12 md:mb-16"
                    >
                        {{ solutions.title }}
                    </h2>
                    <p
                        v-if="solutions.description"
                        class="text-slate-500 md:text-xl leading-relaxed"
                    >
                        {{ solutions.description }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-16">
                    <!-- Col 5: Tab list -->
                    <div class="md:col-span-5">
                        <div
                            v-for="(item, i) in solutions.items"
                            :key="i"
                            class="border-b border-gray-200 last:border-0"
                        >
                            <button
                                type="button"
                                @click="activeSolutionIndex = i"
                                class="w-full flex items-center justify-between gap-3 py-4 text-left font-bold text-lg md:text-xl transition-colors duration-150"
                                :class="
                                    activeSolutionIndex === i
                                        ? 'text-[#302e2c]'
                                        : 'text-orange-600 hover:text-orange-700'
                                "
                            >
                                {{ item.label }}
                                <span
                                    v-if="activeSolutionIndex === i"
                                    class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center flex-shrink-0"
                                >
                                    <svg
                                        class="w-4 h-4 text-slate-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 12h14M13 6l6 6-6 6"
                                        />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Col 7: Content panel -->
                    <div v-if="activeSolution" class="md:col-span-7">
                        <div
                            v-if="activeSolutionVideoEmbed"
                            class="w-full aspect-video bg-slate-100 mb-8"
                        >
                            <iframe
                                :src="activeSolutionVideoEmbed"
                                class="w-full h-full"
                                frameborder="0"
                                allowfullscreen
                            ></iframe>
                        </div>
                        <div
                            class="detail-content"
                            v-html="activeSolution.content"
                        ></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Media Center -->
        <section
            v-if="mediaImages.length || mediaVideos.length"
            id="media"
            class="bg-white py-16 md:py-20 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <h2
                    class="text-3xl md:text-4xl font-bold text-[#302e2c] text-center mb-12 md:mb-16"
                >
                    Media Center
                </h2>

                <!-- Image grid -->
                <div
                    v-if="mediaImages.length"
                    class="grid grid-cols-2 md:grid-cols-4 gap-4"
                >
                    <button
                        v-for="(img, i) in visibleMediaImages"
                        :key="i"
                        type="button"
                        @click="openLightbox(i)"
                        class="relative aspect-[331/166] overflow-hidden bg-gray-50 border border-gray-200 group"
                    >
                        <img
                            :src="img.image"
                            :alt="img.title || `${product.name} ${i + 1}`"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                        <div
                            v-if="
                                i === visibleMediaImages.length - 1 &&
                                remainingMediaImageCount > 0
                            "
                            class="absolute inset-0 bg-black/60 flex items-center justify-center"
                        >
                            <span class="text-white text-3xl font-bold"
                                >+{{ remainingMediaImageCount }}</span
                            >
                        </div>
                    </button>
                </div>

                <!-- Videos -->
                <div
                    v-if="mediaVideos.length"
                    :class="mediaImages.length ? 'mt-16' : ''"
                >
                    <p
                        class="text-lg md:text-2xl font-semibold text-slate-500 mb-10"
                    >
                        {{ mediaVideos.length }}
                        {{ mediaVideos.length > 1 ? "Videos" : "Video" }}
                    </p>

                    <!-- Main video -->
                    <div v-if="activeVideo" class="mb-10">
                        <div class="w-full aspect-video bg-slate-100 mb-5">
                            <iframe
                                v-if="activeVideoEmbed"
                                :src="activeVideoEmbed"
                                class="w-full h-full"
                                frameborder="0"
                                allow="autoplay; fullscreen"
                                allowfullscreen
                            ></iframe>
                        </div>

                        <div
                            class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 md:gap-4 mb-2"
                        >
                            <h3
                                v-if="activeVideo.title"
                                class="order-2 md:order-1 text-xl md:text-2xl font-bold text-[#302e2c]"
                            >
                                {{ activeVideo.title }}
                            </h3>
                            <div
                                class="order-1 md:order-2 flex items-center gap-2 flex-shrink-0"
                            >
                                <button
                                    type="button"
                                    @click="downloadVideo(activeVideo)"
                                    class="inline-flex items-center gap-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold px-4 py-2 transition-colors duration-150"
                                >
                                    Download
                                    <svg
                                        class="w-3.5 h-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"
                                        />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="shareVideo(activeVideo)"
                                    class="w-9 h-9 flex items-center justify-center border border-gray-300 text-slate-500 hover:text-orange-600 hover:border-orange-600 transition-colors duration-150"
                                    aria-label="Bagikan video"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8.684 13.342a3 3 0 100-2.684m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.658m-6.632-6.342l6.632-3.658m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <p
                            v-if="videoMoreInfoOpen && activeVideo.description"
                            class="text-slate-500 leading-relaxed mb-5 mt-10"
                        >
                            {{ activeVideo.description }}
                        </p>

                        <p
                            v-if="activeVideo.date"
                            class="text-sm text-slate-400 mb-5"
                        >
                            {{ activeVideo.date }}
                        </p>

                        <div
                            v-if="activeVideo.description"
                            class="border-b-2 border-gray-100 pb-4"
                        >
                            <button
                                type="button"
                                @click="videoMoreInfoOpen = !videoMoreInfoOpen"
                                class="flex flex-col items-center gap-1 mx-auto text-orange-600 font-semibold text-sm transition-colors duration-150 hover:text-orange-700"
                            >
                                <span v-if="!videoMoreInfoOpen"
                                    >More Information</span
                                >
                                <svg
                                    class="w-8 h-8 transition-transform duration-200"
                                    :class="{
                                        'rotate-180': videoMoreInfoOpen,
                                    }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Other videos grid -->
                    <div
                        v-if="mediaVideos.length > 1"
                        class="grid grid-cols-2 md:grid-cols-4 gap-6"
                    >
                        <button
                            v-for="(video, i) in mediaVideos"
                            :key="i"
                            type="button"
                            @click="selectVideo(i)"
                            class="text-left group"
                        >
                            <div
                                class="relative aspect-video overflow-hidden bg-gray-100 mb-2"
                            >
                                <img
                                    v-if="video.thumbnail"
                                    :src="video.thumbnail"
                                    :alt="video.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-slate-800"
                                ></div>
                                <div
                                    class="absolute inset-0 bg-black/20 flex items-center justify-center"
                                >
                                    <span
                                        class="w-9 h-9 rounded-full bg-white/90 flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-4 h-4 text-slate-900 translate-x-0.5"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <p
                                v-if="video.title"
                                class="md:text-xl font-bold leading-snug mb-1 line-clamp-2 min-h-[2.75rem] md:min-h-[3.5rem]"
                                :class="
                                    i === activeVideoIndex
                                        ? 'text-orange-600'
                                        : 'text-[#302e2c]'
                                "
                            >
                                {{ video.title }}
                            </p>
                            <p v-if="video.date" class="text-xs text-slate-400">
                                {{ video.date }}
                            </p>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lightbox (images) -->
            <div
                v-if="lightboxOpen"
                class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center"
                @click.self="closeLightbox"
            >
                <button
                    type="button"
                    @click="closeLightbox"
                    class="absolute top-5 right-5 text-white/80 hover:text-white transition-colors"
                >
                    <svg
                        class="w-7 h-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

                <button
                    type="button"
                    @click="prevMedia"
                    class="absolute left-3 md:left-8 text-white/80 hover:text-white transition-colors"
                >
                    <svg
                        class="w-9 h-9"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </button>

                <div
                    class="flex flex-col items-center max-w-[88vw] md:max-w-[70vw]"
                    @click.stop
                >
                    <img
                        :src="activeLightboxImage?.image"
                        :alt="`${product.name} ${lightboxIndex + 1}`"
                        class="max-h-[75vh] max-w-[88vw] md:max-w-[70vw] object-contain"
                    />
                    <p
                        v-if="activeLightboxImage?.title"
                        class="mt-4 text-white/90 text-sm md:text-base font-medium text-center px-4"
                    >
                        {{ activeLightboxImage.title }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="nextMedia"
                    class="absolute right-3 md:right-8 text-white/80 hover:text-white transition-colors"
                >
                    <svg
                        class="w-9 h-9"
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
                </button>

                <div
                    class="absolute bottom-5 left-1/2 -translate-x-1/2 text-white/70 text-sm font-medium"
                >
                    {{ lightboxIndex + 1 }} / {{ mediaImages.length }}
                </div>
            </div>
        </section>

        <!-- Model Overview -->
        <section
            v-if="product.model_overview?.length"
            id="model-overview"
            class="bg-white py-16 md:py-20 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <h2
                    class="text-3xl md:text-4xl font-bold text-[#302e2c] text-center mb-12 md:mb-16"
                >
                    Model Overview
                </h2>

                <div class="space-y-8">
                    <div
                        v-for="(row, ri) in modelOverviewRows"
                        :key="ri"
                        class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-6"
                    >
                        <div
                            v-for="(item, ci) in row"
                            :key="ci"
                            :class="{
                                'md:text-right': !(ri === 0 && ci === 0),
                            }"
                        >
                            <p class="font-bold text-[#969492] mb-3">
                                {{ item.label }}
                            </p>
                            <p
                                class="text-lg"
                                :class="
                                    ri === 0 && ci === 0
                                        ? 'text-orange-600'
                                        : 'text-slate-800'
                                "
                            >
                                {{ item.value }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Downloads -->
        <section
            v-if="downloads.length"
            id="downloads"
            class="bg-white py-16 md:py-20 border-b border-gray-100"
        >
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8">
                <h2
                    class="text-3xl md:text-4xl font-bold text-[#302e2c] text-center mb-12 md:mb-16"
                >
                    Downloads
                </h2>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10"
                >
                    <div v-for="(file, i) in downloads" :key="i">
                        <svg
                            class="w-10 h-10 text-orange-600 mb-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M13 3v6h6"
                            />
                        </svg>
                        <p class="font-bold text-[#302e2c] mb-2">
                            {{ file.title }}
                        </p>
                        <p class="text-sm text-slate-400 mb-3">
                            {{ file.type }}, {{ file.size }}
                        </p>
                        <a
                            :href="file.url"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-1.5 text-sm font-bold text-orange-600 hover:text-orange-700 transition-colors"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"
                                />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Inquiry -->
        <InquirySection id="inquiry" />
    </PublicLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import InquirySection from "@/Components/Public/InquirySection.vue";
import { SPEC_ICONS } from "@/Utils/specIcons";

const props = defineProps({
    product: { type: Object, required: true },
    systemCategory: { type: Object, required: true },
    subCategory: { type: Object, required: true },
});

const contentMenuOpen = ref(false);

const siteSettings = computed(() => usePage().props.site_settings ?? {});

const whatsappCtaHref = computed(() => {
    const wa = siteSettings.value.whatsapp;
    if (!wa) return null;

    const message =
        props.systemCategory.slug === "sewa-forklift"
            ? `Halo, saya tertarik untuk menyewa *${props.product.name}*. Mohon info lebih lanjut.`
            : `Halo, saya tertarik untuk membeli *${props.product.name}*. Mohon info lebih lanjut.`;

    return `https://wa.me/${wa}?text=${encodeURIComponent(message)}`;
});

const highlightIcons = [
    "M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z",
    "M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15",
    "M8 7h12m0 0l-4-4m4 4l-4 4m0 5H4m0 0l4 4m-4-4l4-4",
    "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z",
];

const highlightImage = computed(
    () =>
        props.product.highlight_image ||
        props.product.image_url ||
        props.product.image,
);

const heroCertBadges = computed(() =>
    [props.product.hero_cert_badge_1, props.product.hero_cert_badge_2]
        .filter(Boolean)
        .map((image) => ({ image })),
);

const detailFallbackIcons = [
    "M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.657l-2.121 2.121",
    "M13 10V3L4 14h7v7l9-11h-7z",
    "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z",
    "M8 7h12m0 0l-4-4m4 4l-4 4m0 5H4m0 0l4 4m-4-4l4-4",
    "M21 12a9 9 0 11-18 0 9 9 0 0118 0zM3.6 9h16.8M3.6 15h16.8M12 3a15.3 15.3 0 010 18 15.3 15.3 0 010-18z",
];

const activeDetailIndex = ref(0);

function toggleDetail(i) {
    activeDetailIndex.value = activeDetailIndex.value === i ? -1 : i;
}

const productDetails = computed(() =>
    (props.product.details ?? []).map((d, i) => ({
        title: d.title,
        icon_image: d.icon_image || null,
        icon: detailFallbackIcons[i % detailFallbackIcons.length],
        image: d.image || highlightImage.value,
        content: d.content,
    })),
);

const activeSolutionIndex = ref(0);

const solutions = computed(() => ({
    title: props.product.solutions_title,
    description: props.product.solutions_description,
    items: props.product.solutions ?? [],
}));

const activeSolution = computed(
    () => solutions.value.items[activeSolutionIndex.value] ?? null,
);

function toEmbedVideoUrl(url) {
    if (!url) return null;

    let u;
    try {
        u = new URL(url);
    } catch {
        return url;
    }

    // youtu.be/VIDEO_ID
    if (u.hostname === "youtu.be") {
        const id = u.pathname.slice(1);
        return id ? `https://www.youtube.com/embed/${id}` : url;
    }

    // youtube.com/watch?v=VIDEO_ID
    if (u.hostname.includes("youtube.com")) {
        if (u.pathname === "/watch") {
            const id = u.searchParams.get("v");
            return id ? `https://www.youtube.com/embed/${id}` : url;
        }
        return url; // already an embed (or other youtube.com path) URL
    }

    // vimeo.com/VIDEO_ID
    if (u.hostname.includes("vimeo.com") && !u.hostname.includes("player.")) {
        const id = u.pathname.split("/").filter(Boolean)[0];
        return id ? `https://player.vimeo.com/video/${id}` : url;
    }

    return url;
}

const activeSolutionVideoEmbed = computed(() =>
    toEmbedVideoUrl(activeSolution.value?.video_url),
);

function getYoutubeId(url) {
    if (!url) return null;
    try {
        const u = new URL(url);
        if (u.hostname === "youtu.be") return u.pathname.slice(1) || null;
        if (u.hostname.includes("youtube.com")) {
            if (u.pathname === "/watch") return u.searchParams.get("v");
            if (u.pathname.startsWith("/embed/"))
                return u.pathname.split("/")[2] ?? null;
        }
    } catch {
        return null;
    }
    return null;
}

// Media Center: images only (grid + lightbox)
const mediaImages = computed(() =>
    (props.product.media_items ?? [])
        .filter((m) => m.type === "image" && m.image)
        .map((m) => ({ image: m.image, title: m.title || null })),
);

const visibleMediaImages = computed(() => mediaImages.value.slice(0, 4));
const remainingMediaImageCount = computed(() =>
    Math.max(mediaImages.value.length - 4, 0),
);

const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

const activeLightboxImage = computed(
    () => mediaImages.value[lightboxIndex.value] ?? null,
);

function openLightbox(i) {
    lightboxIndex.value = i;
    lightboxOpen.value = true;
}

function closeLightbox() {
    lightboxOpen.value = false;
}

function prevMedia() {
    lightboxIndex.value =
        (lightboxIndex.value - 1 + mediaImages.value.length) %
        mediaImages.value.length;
}

function nextMedia() {
    lightboxIndex.value = (lightboxIndex.value + 1) % mediaImages.value.length;
}

function onLightboxKeydown(e) {
    if (!lightboxOpen.value) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowLeft") prevMedia();
    if (e.key === "ArrowRight") nextMedia();
}

onMounted(() => window.addEventListener("keydown", onLightboxKeydown));
onUnmounted(() => window.removeEventListener("keydown", onLightboxKeydown));

// Media Center: videos (separate block below the image grid)
const mediaVideos = computed(() =>
    (props.product.media_items ?? [])
        .filter((m) => m.type === "video" && m.video_url)
        .map((m) => {
            const id = getYoutubeId(m.video_url);
            return {
                title: m.title || null,
                video_url: m.video_url,
                date: m.date || null,
                description: m.description || null,
                thumbnail: id
                    ? `https://img.youtube.com/vi/${id}/hqdefault.jpg`
                    : null,
            };
        }),
);

const activeVideoIndex = ref(0);
const activeVideo = computed(
    () => mediaVideos.value[activeVideoIndex.value] ?? null,
);
const activeVideoEmbed = computed(() =>
    toEmbedVideoUrl(activeVideo.value?.video_url),
);
const videoMoreInfoOpen = ref(false);

function selectVideo(i) {
    activeVideoIndex.value = i;
    videoMoreInfoOpen.value = false;
}

function shareVideo(video) {
    if (!video?.video_url) return;
    if (navigator.share) {
        navigator
            .share({
                title: video.title ?? props.product.name,
                url: video.video_url,
            })
            .catch(() => {});
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(video.video_url);
    }
}

function downloadVideo(video) {
    if (!video?.video_url) return;
    window.open(video.video_url, "_blank", "noopener");
}

const modelOverviewRows = computed(() => {
    const items = [
        { label: "Model", value: props.product.name },
        ...(props.product.model_overview ?? []),
    ];

    const rows = [];
    for (let i = 0; i < items.length; i += 4) {
        rows.push(items.slice(i, i + 4));
    }
    return rows;
});

function getFileExtension(url) {
    if (!url) return "FILE";
    const clean = url.split("?")[0].split("#")[0];
    const ext = clean.split(".").pop();
    return ext ? ext.toUpperCase() : "FILE";
}

const downloads = computed(() =>
    (props.product.downloads ?? [])
        .filter((d) => d.file)
        .map((d) => ({
            title: d.title,
            type: getFileExtension(d.file),
            size: d.size,
            url: d.file,
        })),
);

const pageSections = computed(() => {
    const sections = [];
    if (props.product.highlights?.length)
        sections.push({ label: "Highlights", href: "#highlights" });
    if (props.product.full_description)
        sections.push({ label: "Ringkasan", href: "#overview" });
    if (productDetails.value.length)
        sections.push({ label: "Detail", href: "#detail" });
    if (solutions.value.items.length)
        sections.push({ label: "Solusi", href: "#solutions" });
    if (mediaImages.value.length || mediaVideos.value.length)
        sections.push({ label: "Media Center", href: "#media" });
    if (props.product.model_overview?.length)
        sections.push({ label: "Model Overview", href: "#model-overview" });
    if (props.product.specs?.length)
        sections.push({ label: "Spesifikasi", href: "#specs" });
    if (downloads.value.length)
        sections.push({ label: "Downloads", href: "#downloads" });
    sections.push({ label: "Formulir Inquiry", href: "#inquiry" });
    return sections;
});
</script>

<style scoped>
@media (min-width: 768px) {
    .hero-section {
        min-height: 680px;
    }
    .hero-content {
        min-height: 680px;
    }
}

.product-content :deep(p) {
    color: #475569;
    line-height: 1.8;
    margin-bottom: 1.25rem;
    font-size: 1rem;
}
@media (min-width: 768px) {
    .product-content :deep(p) {
        font-size: 1.125rem;
    }
}
.product-content :deep(h2) {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f172a;
    margin: 2.5rem 0 1rem;
}
.product-content :deep(h3) {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 2rem 0 0.75rem;
}
.product-content :deep(ul) {
    padding-left: 1.5rem;
    color: #475569;
    line-height: 1.8;
    margin-bottom: 1.25rem;
    list-style: disc;
}
.product-content :deep(li) {
    margin-bottom: 0.5rem;
    font-size: 1rem;
}
.product-content :deep(strong) {
    font-weight: 700;
    color: #1e293b;
}

.detail-content {
    font-size: 16px;
    color: #969492;
}
@media (min-width: 768px) {
    .detail-content {
        font-size: 20px;
    }
}
.detail-content :deep(p) {
    margin-bottom: 1.25rem;
}
.detail-content :deep(p:last-child) {
    margin-bottom: 0;
}
.detail-content :deep(h1),
.detail-content :deep(h2),
.detail-content :deep(h3),
.detail-content :deep(h4),
.detail-content :deep(h5),
.detail-content :deep(h6) {
    font-weight: 700;
    color: #000;
    font-size: 1em;
    margin-bottom: 0.25rem;
}
.detail-content :deep(strong),
.detail-content :deep(b) {
    font-weight: 700;
    color: #000;
}
.detail-content :deep(span) {
    color: inherit;
}
</style>
