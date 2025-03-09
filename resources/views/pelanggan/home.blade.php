@extends('layouts.app')

@section('title', 'Halaman Beranda')

@section('content')
    <section class="flex flex-col relative w-full mt-4 mb-4">
        <img src="{{ asset(optional($beranda)->gambar_utama ?: 'images/default.jpg') }}" class="w-full block rounded-lg object-cover" alt="...">
    </section>

        <!-- Feature Section -->
    <section class="border p-4 mb-8 text-center bg-white rounded-lg">
        <div class="flex justify-center space-x-8">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle text-teal-500"></i>
                <span>{{ optional($beranda)->sec2_text1 }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-shipping-fast text-teal-500"></i>
                <span>{{ optional($beranda)->sec2_text2 }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-thumbs-up text-teal-500"></i>
                <span>{{ optional($beranda)->sec2_text3 }}</span>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section id="default-carousel" class="relative w-full mb-4" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
        <!-- Item 1 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img 
                src="{{ asset(optional($beranda)->gambar_carousel1 ?: 'images/default.jpg') }}" 
                class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 cursor-pointer" 
                alt="Carousel 1"
                onclick="redirectToProductPage('{{ optional($beranda)->link1_g1 }}')"
            >
        </div>
        <!-- Item 2 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img 
                src="{{ asset(optional($beranda)->gambar_carousel2 ?: 'images/default.jpg') }}" 
                class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 cursor-pointer" 
                alt="Carousel 2"
                onclick="redirectToProductPage({{ optional($beranda)->link1_g2 }})"
            >
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
        </div>
        <!-- Slider controls -->
        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </section>

    <!-- Footer Section -->
    <h2 class="text-xl font-lg mt-4 text-center text-white">{{ optional($beranda)->sec3_judul }}</h2>
    <section class="p-4 mb-8 bg-teal-600 mt-4 rounded-lg text-white">
        <div class="flex justify-between items-start">
            <!-- Left side text -->
            <div class="w-1/2 pr-4 flex flex-col justify-center mt-24 text-xl">
                <div class="flex items-start space-x-2 mb-4">
                    <i class="fas fa-print"></i>
                    <p>{{ optional($beranda)->sec3_text1 }}</p>
                </div>
                <div class="flex items-start space-x-2 mb-4">
                    <i class="fas fa-clock"></i>
                    <p>{{ optional($beranda)->sec3_text2 }}</p>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-paint-brush"></i>
                    <p>{{ optional($beranda)->sec3_text3 }}</p>
                </div>
            </div>
            <!-- Right side (Google map iframe) -->
            <div class="w-1/2 pl-4 text-center flex flex-col">
                <h3 class="font-bold text-xl mb-2">LOKASI INDUSTRI</h3>
                <iframe src="{{ optional($beranda)->sec3_map }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg "></iframe>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    function redirectToProductPage(productId) {
        window.location.href = "{{ route('product.index') }}" + "?highlight=" + productId;
    }
</script>
@endsection
