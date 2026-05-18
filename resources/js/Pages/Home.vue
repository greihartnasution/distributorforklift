<template>
  <PublicLayout>

    <!-- ============================================================
         HERO SECTION — Full-width carousel with dark overlay
    ============================================================ -->
    <section class="relative w-full h-[88vh] min-h-[540px] overflow-hidden">

      <!-- Slides -->
      <div class="relative w-full h-full">
        <transition-group name="hero-fade">
          <div
            v-for="(slide, index) in heroSlides"
            :key="slide.id"
            v-show="currentSlide === index"
            class="absolute inset-0"
          >
            <img
              :src="slide.image"
              :alt="slide.title"
              class="w-full h-full object-cover"
            />
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/50 to-transparent"></div>

            <!-- Content -->
            <div class="absolute inset-0 flex items-center">
              <div class="max-w-screen-xl mx-auto px-4 w-full">
                <div class="max-w-2xl">
                  <div class="inline-flex items-center gap-2 bg-orange-600 px-3 py-1.5 mb-5">
                    <span class="text-white text-xs font-bold uppercase tracking-widest">{{ slide.tag }}</span>
                  </div>
                  <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-5 font-['Barlow']">
                    {{ slide.title }}
                  </h1>
                  <p class="text-base md:text-lg text-gray-200 leading-relaxed mb-8 max-w-lg">
                    {{ slide.description }}
                  </p>
                  <div class="flex flex-wrap items-center gap-4">
                    <a href="#" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold px-7 py-3.5 transition-colors duration-200">
                      Lihat Produk
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                      </svg>
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 border-2 border-white text-white hover:border-orange-500 hover:text-orange-500 font-bold px-7 py-3.5 transition-colors duration-200">
                      Hubungi Kami
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <!-- Slide Controls -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
        <button
          v-for="(slide, index) in heroSlides"
          :key="index"
          @click="currentSlide = index"
          class="transition-all duration-300"
          :class="currentSlide === index ? 'w-8 h-2 bg-orange-600' : 'w-2 h-2 bg-white/50 hover:bg-white/80'"
        ></button>
      </div>

      <!-- Arrow Controls -->
      <button @click="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/10 hover:bg-orange-600 border border-white/20 text-white flex items-center justify-center transition-colors duration-200 z-10">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button @click="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/10 hover:bg-orange-600 border border-white/20 text-white flex items-center justify-center transition-colors duration-200 z-10">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
        </svg>
      </button>

    </section>

    <!-- ============================================================
         STATEMENT BANNER — "Leading provider" full-width
    ============================================================ -->
    <section class="bg-white border-b border-gray-100 py-14">
      <div class="max-w-screen-xl mx-auto px-4 text-center">
        <p class="text-xs font-bold text-orange-600 uppercase tracking-widest mb-3">Solusi Material Handling Terpercaya</p>
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-tight max-w-4xl mx-auto font-['Barlow']">
          Distributor Forklift terdepan dalam solusi material handling industri di Indonesia
        </h2>
      </div>
    </section>

    <!-- ============================================================
         PRODUCT & SERVICE CARDS GRID — 3 columns
    ============================================================ -->
    <section class="bg-gray-50 py-16">
      <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-gray-200">
          <div
            v-for="card in serviceCards"
            :key="card.title"
            class="group bg-white hover:bg-orange-600 transition-colors duration-300 cursor-pointer overflow-hidden"
          >
            <div class="overflow-hidden h-52">
              <img
                :src="card.image"
                :alt="card.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
            </div>
            <div class="p-7">
              <div class="flex items-center gap-2 mb-3">
                <div class="w-5 h-0.5 bg-orange-600 group-hover:bg-white transition-colors duration-300"></div>
                <span class="text-xs font-bold text-orange-600 group-hover:text-orange-100 uppercase tracking-widest transition-colors duration-300">{{ card.category }}</span>
              </div>
              <h3 class="text-lg font-bold text-slate-900 group-hover:text-white mb-2 font-['Barlow'] transition-colors duration-300">{{ card.title }}</h3>
              <p class="text-sm text-slate-500 group-hover:text-orange-100 leading-relaxed mb-5 transition-colors duration-300">{{ card.description }}</p>
              <div class="flex items-center gap-1.5 text-sm font-semibold text-orange-600 group-hover:text-white transition-colors duration-300">
                <span>Pelajari Lebih Lanjut</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================
         SOLUTIONS SECTION — Industrial atmosphere, 2-col
    ============================================================ -->
    <section class="bg-slate-900 py-20 overflow-hidden">
      <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

          <!-- Left: Text -->
          <div>
            <p class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-4">Solusi Industri</p>
            <h2 class="text-3xl md:text-4xl font-black text-white leading-tight mb-6 font-['Barlow']">
              Solusi lengkap untuk kebutuhan material handling industri Anda
            </h2>
            <p class="text-gray-400 leading-relaxed mb-8">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
            </p>
            <div class="space-y-4 mb-10">
              <div v-for="feature in solutions" :key="feature.title" class="flex items-start gap-4">
                <div class="w-10 h-10 bg-orange-600/20 border border-orange-600/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-white mb-1">{{ feature.title }}</h4>
                  <p class="text-sm text-gray-400 leading-relaxed">{{ feature.desc }}</p>
                </div>
              </div>
            </div>
            <a href="#" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold px-7 py-3.5 transition-colors duration-200">
              Lihat Semua Solusi
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>

          <!-- Right: Image Grid -->
          <div class="grid grid-cols-2 gap-3">
            <div class="overflow-hidden">
              <img src="https://picsum.photos/seed/sol1/500/400" alt="Solution 1" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="overflow-hidden mt-8">
              <img src="https://picsum.photos/seed/sol2/500/400" alt="Solution 2" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="overflow-hidden -mt-8">
              <img src="https://picsum.photos/seed/sol3/500/300" alt="Solution 3" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="overflow-hidden">
              <img src="https://picsum.photos/seed/sol4/500/300" alt="Solution 4" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ============================================================
         STATS SECTION — Key numbers
    ============================================================ -->
    <section class="bg-orange-600 py-12">
      <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
          <div v-for="stat in stats" :key="stat.label">
            <div class="text-4xl md:text-5xl font-black font-['Barlow'] mb-1">{{ stat.value }}</div>
            <div class="text-sm font-semibold text-orange-100 uppercase tracking-wide">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================
         NEWS SECTION — Horizontal cards
    ============================================================ -->
    <section class="bg-white py-20">
      <div class="max-w-screen-xl mx-auto px-4">

        <!-- Section Header -->
        <div class="flex items-end justify-between mb-10">
          <div>
            <p class="text-xs font-bold text-orange-600 uppercase tracking-widest mb-2">Terbaru</p>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 font-['Barlow']">Berita & Artikel</h2>
          </div>
          <a href="#" class="hidden md:inline-flex items-center gap-2 text-sm font-bold text-orange-600 hover:text-orange-700 border-b border-orange-600 pb-0.5 transition-colors duration-200">
            Lihat Semua Berita
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article
            v-for="article in news"
            :key="article.title"
            class="group flex flex-col"
          >
            <div class="overflow-hidden mb-5">
              <img
                :src="article.image"
                :alt="article.title"
                class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500"
              />
            </div>
            <div class="flex items-center gap-2 mb-3">
              <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">{{ article.category }}</span>
              <span class="text-gray-300">•</span>
              <span class="text-xs text-gray-400">{{ article.date }}</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug group-hover:text-orange-600 transition-colors duration-200 font-['Barlow']">
              {{ article.title }}
            </h3>
            <p class="text-sm text-slate-500 leading-relaxed mb-4 flex-1">{{ article.excerpt }}</p>
            <a href="#" class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors duration-200">
              Baca Selengkapnya
              <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </article>
        </div>

        <!-- Mobile: Lihat Semua -->
        <div class="mt-8 md:hidden text-center">
          <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-orange-600 border border-orange-600 px-6 py-3 hover:bg-orange-600 hover:text-white transition-colors duration-200">
            Lihat Semua Berita
          </a>
        </div>

      </div>
    </section>

    <!-- ============================================================
         COMPANY OVERVIEW — 2-col image + text
    ============================================================ -->
    <section class="bg-gray-50 py-20">
      <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

          <!-- Left: Image -->
          <div class="relative">
            <div class="overflow-hidden">
              <img
                src="https://picsum.photos/seed/company/800/600"
                alt="Tentang Distributor Forklift"
                class="w-full h-[420px] object-cover hover:scale-105 transition-transform duration-700"
              />
            </div>
            <!-- Accent box -->
            <div class="absolute -bottom-6 -right-6 w-40 h-40 bg-orange-600 hidden lg:flex flex-col items-center justify-center text-white text-center">
              <div class="text-3xl font-black font-['Barlow']">20+</div>
              <div class="text-xs font-semibold uppercase tracking-wide mt-1 px-3">Tahun Pengalaman</div>
            </div>
          </div>

          <!-- Right: Text -->
          <div class="lg:pl-4">
            <p class="text-xs font-bold text-orange-600 uppercase tracking-widest mb-4">Tentang Kami</p>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-6 font-['Barlow']">
              Mitra terpercaya solusi material handling industri Indonesia
            </h2>
            <p class="text-slate-600 leading-relaxed mb-4">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Kami telah melayani berbagai industri besar di Indonesia dengan dedikasi tinggi.
            </p>
            <p class="text-slate-600 leading-relaxed mb-8">
              Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Dengan armada produk lengkap dan tim teknisi berpengalaman, kami siap mendukung operasional bisnis Anda.
            </p>

            <!-- Features list -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
              <div v-for="item in companyFeatures" :key="item" class="flex items-center gap-2.5">
                <div class="w-5 h-5 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <span class="text-sm font-medium text-slate-700">{{ item }}</span>
              </div>
            </div>

            <a href="#" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-orange-600 text-white font-bold px-7 py-3.5 transition-colors duration-200">
              Lebih Lanjut Tentang Kami
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>

        </div>
      </div>
    </section>

    <!-- ============================================================
         BRANDS / PARTNERS — Logo strip
    ============================================================ -->
    <section class="bg-white border-y border-gray-100 py-12">
      <div class="max-w-screen-xl mx-auto px-4">
        <p class="text-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">Merek yang Kami Distribusikan</p>
        <div class="flex flex-wrap items-center justify-center gap-10 lg:gap-16">
          <div v-for="brand in brands" :key="brand" class="text-2xl font-black text-gray-200 hover:text-orange-600 transition-colors duration-200 tracking-tight font-['Barlow'] cursor-pointer">
            {{ brand }}
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================
         CTA / CONTACT SECTION
    ============================================================ -->
    <section class="relative py-24 overflow-hidden">
      <div class="absolute inset-0">
        <img src="https://picsum.photos/seed/cta/1920/600" alt="CTA Background" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-slate-900/85"></div>
      </div>
      <div class="relative max-w-screen-xl mx-auto px-4 text-center">
        <p class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-4">Siap Bekerja Sama?</p>
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight mb-5 font-['Barlow'] max-w-3xl mx-auto">
          Konsultasikan kebutuhan material handling Anda bersama kami
        </h2>
        <p class="text-gray-300 leading-relaxed mb-10 max-w-xl mx-auto">
          Tim ahli kami siap membantu Anda menemukan solusi forklift yang tepat sesuai kebutuhan dan anggaran bisnis Anda.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
          <a href="#" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold px-8 py-4 text-base transition-colors duration-200">
            Minta Penawaran
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
          </a>
          <a href="https://wa.me/6221000000" class="inline-flex items-center gap-2 border-2 border-white text-white hover:border-orange-500 hover:text-orange-400 font-bold px-8 py-4 text-base transition-colors duration-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Chat WhatsApp
          </a>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

// ── Hero Slider ──────────────────────────────────────────────────
const currentSlide = ref(0)
let autoplayTimer = null

const heroSlides = [
  {
    id: 1,
    tag: 'Produk Unggulan',
    title: 'Forklift Bertenaga Tinggi untuk Industri Modern',
    description: 'Temukan rangkaian forklift premium kami yang dirancang untuk efisiensi maksimal di lingkungan industri berat.',
    image: 'https://picsum.photos/seed/hero1/1920/1080',
  },
  {
    id: 2,
    tag: 'Solusi Gudang',
    title: 'Optimalkan Operasional Gudang Anda',
    description: 'Dari reach truck hingga hand pallet, kami menyediakan solusi material handling lengkap untuk gudang Anda.',
    image: 'https://picsum.photos/seed/hero2/1920/1080',
  },
  {
    id: 3,
    tag: 'Layanan Purna Jual',
    title: 'Servis & Perawatan Profesional',
    description: 'Tim teknisi bersertifikat kami siap memastikan forklift Anda selalu dalam kondisi prima sepanjang waktu.',
    image: 'https://picsum.photos/seed/hero3/1920/1080',
  },
]

function nextSlide() {
  currentSlide.value = (currentSlide.value + 1) % heroSlides.length
  resetAutoplay()
}

function prevSlide() {
  currentSlide.value = (currentSlide.value - 1 + heroSlides.length) % heroSlides.length
  resetAutoplay()
}

function resetAutoplay() {
  clearInterval(autoplayTimer)
  autoplayTimer = setInterval(nextSlide, 5000)
}

onMounted(() => {
  autoplayTimer = setInterval(nextSlide, 5000)
})

onUnmounted(() => {
  clearInterval(autoplayTimer)
})

// ── Service Cards ────────────────────────────────────────────────
const serviceCards = [
  {
    category: 'Produk',
    title: 'Forklift Baru',
    description: 'Koleksi forklift terbaru dari merek-merek ternama dunia dengan teknologi terkini.',
    image: 'https://picsum.photos/seed/card1/600/400',
  },
  {
    category: 'Produk',
    title: 'Forklift Sewa',
    description: 'Solusi sewa forklift fleksibel untuk kebutuhan jangka pendek maupun jangka panjang.',
    image: 'https://picsum.photos/seed/card2/600/400',
  },
  {
    category: 'Produk',
    title: 'Forklift Bekas',
    description: 'Unit forklift bekas berkualitas dengan harga kompetitif, telah melalui inspeksi ketat.',
    image: 'https://picsum.photos/seed/card3/600/400',
  },
  {
    category: 'Peralatan',
    title: 'Reach Truck',
    description: 'Reach truck ideal untuk operasional gudang dengan rak tinggi dan lorong sempit.',
    image: 'https://picsum.photos/seed/card4/600/400',
  },
  {
    category: 'Layanan',
    title: 'Servis & Perawatan',
    description: 'Layanan perawatan berkala dan perbaikan oleh teknisi bersertifikat resmi.',
    image: 'https://picsum.photos/seed/card5/600/400',
  },
  {
    category: 'Layanan',
    title: 'Hubungi Kami',
    description: 'Konsultasikan kebutuhan material handling Anda dengan tim ahli kami.',
    image: 'https://picsum.photos/seed/card6/600/400',
  },
]

// ── Solutions ────────────────────────────────────────────────────
const solutions = [
  {
    title: 'Solusi Gudang & Logistik',
    desc: 'Sistem material handling terintegrasi untuk efisiensi gudang dan distribusi.',
  },
  {
    title: 'Solusi Manufaktur',
    desc: 'Peralatan andal untuk lini produksi dengan uptime tinggi dan perawatan mudah.',
  },
  {
    title: 'Solusi Cold Storage',
    desc: 'Forklift berteknologi khusus untuk operasional di lingkungan suhu rendah.',
  },
]

// ── Stats ────────────────────────────────────────────────────────
const stats = [
  { value: '500+', label: 'Klien Aktif' },
  { value: '20+', label: 'Tahun Pengalaman' },
  { value: '1200+', label: 'Unit Terjual' },
  { value: '24/7', label: 'Dukungan Teknis' },
]

// ── News ─────────────────────────────────────────────────────────
const news = [
  {
    category: 'Industri',
    date: '15 Mei 2026',
    title: 'Tren Otomasi Gudang 2026: Peran Forklift Elektrik',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Perkembangan teknologi forklift elektrik semakin mendominasi industri logistik modern.',
    image: 'https://picsum.photos/seed/news1/600/400',
  },
  {
    category: 'Produk',
    date: '10 Mei 2026',
    title: 'Peluncuran Seri Forklift Reach Truck Terbaru 2026',
    excerpt: 'Kami dengan bangga memperkenalkan seri reach truck generasi terbaru dengan kapasitas angkat hingga 2,5 ton dan efisiensi energi superior.',
    image: 'https://picsum.photos/seed/news2/600/400',
  },
  {
    category: 'Tips & Panduan',
    date: '05 Mei 2026',
    title: 'Panduan Lengkap Perawatan Forklift agar Awet',
    excerpt: 'Perawatan rutin adalah kunci umur panjang forklift Anda. Berikut panduan lengkap perawatan yang wajib dilakukan setiap bulan.',
    image: 'https://picsum.photos/seed/news3/600/400',
  },
]

// ── Company Features ─────────────────────────────────────────────
const companyFeatures = [
  'Distributor Resmi Bersertifikat',
  'Garansi Resmi Pabrik',
  'Teknisi Berpengalaman',
  'Suku Cadang Original',
  'Layanan After-Sales 24/7',
  'Harga Kompetitif',
]

// ── Brands ───────────────────────────────────────────────────────
const brands = ['TOYOTA', 'KOMATSU', 'LINDE', 'HYSTER', 'CROWN', 'MITSUBISHI']
</script>

<style scoped>
.hero-fade-enter-active,
.hero-fade-leave-active {
  transition: opacity 0.8s ease;
}
.hero-fade-enter-from,
.hero-fade-leave-to {
  opacity: 0;
}
</style>
