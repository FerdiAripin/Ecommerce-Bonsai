@extends('layouts.app')

@section('content')
    <header class="bg-header" style="background-image: url({{ asset('img/home-hero-bg.jpg') }})">
        <div class="min-h-screen flex flex-col items-center justify-center" style="z-index: 1 ">
            <p class="text-center text-white tracking-wider uppercase mb-7">
                Selamat datang di Ruang Bonsai
            </p>
            <h1 class="text-7xl font-medium text-center text-white md:w-[60%] mx-auto mb-10">
                Temukan Keindahan Alam di Ujung Jari Anda
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('home.products') }}" class="btn-primary font-semibold">
                    Belanja Sekarang
                </a>
                @auth
                    @if (Auth::user()->roles == 'Customer')
                        <a href="{{ route('orders.index') }}" class="items-center flex gap-2 btn-secondary font-semibold px-10">
                            <i class='bx bx-shopping-bag-alt text-xl'></i> Pesanan Saya
                        </a>
                    @endif
                    {{-- <a href="{{ route('filament.app.pages.dashboard') }}"
                        class="items-center flex gap-2 btn-secondary font-semibold px-10">
                        <i class='bx bx-user-group text-xl'></i> Dashboard Admin
                    </a> --}}
                @else
                    <a href="{{ route('filament.app.auth.login') }}"
                        class="items-center flex gap-2 btn-secondary font-semibold px-10">
                        Log In
                    </a>
                @endauth
            </div>
        </div>
    </header>
    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 items-center justify-between gap-5 md:gap-10">
                <article>
                    <div class="bg-yellow-100 p-4 flex items-center justify-center rounded-full w-max h-max mx-auto">
                        <i class='bx bxs-credit-card text-3xl'></i>
                    </div>
                    <p class="text-center text-xl font-medium mt-5 mb-0">Pembayaran Aman</p>
                    <p class="text-center text-gray-700">Transaksi terlindungi dengan sistem keamanan terbaik</p>
                </article>
                <article>
                    <div class="bg-yellow-100 p-4 flex items-center justify-center rounded-full w-max h-max mx-auto">
                        <i class='bx bxs-check-shield text-3xl'></i>
                    </div>
                    <p class="text-center text-xl font-medium mt-5 mb-0">Produk Berkualitas Terjamin</p>
                    <p class="text-center text-gray-700">Menyediakan produk dengan standar kualitas tertinggi</p>
                </article>
                <article>
                    <div class="bg-yellow-100 p-4 flex items-center justify-center rounded-full w-max h-max mx-auto">
                        <i class='bx bxs-heart text-3xl'></i>
                    </div>
                    <p class="text-center text-xl font-medium mt-5 mb-0">Pengiriman Dengan Hati</p>
                    <p class="text-center text-gray-700">Produk dikemas rapi dan dikirim dengan penuh perhatian</p>
                </article>
                <article>
                    <div class="bg-yellow-100 p-4 flex items-center justify-center rounded-full w-max h-max mx-auto">
                        <i class='bx bxs-message-rounded-dots text-3xl'></i>
                    </div>
                    <p class="text-center text-xl font-medium mt-5 mb-0">Layanan Prima</p>
                    <p class="text-center text-gray-700">Kami siap membantu dengan respons cepat dan ramah</p>
                </article>
            </div>

            <hr class="mt-10">
        </div>
    </section>
    <section class="py-10 md:pb-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl text-center font-semibold mb-10">Produk Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    <div class="relative py-10 md:py-24 bg-cover bg-center" style="background-image: url({{ asset('img/banner.jpg') }})">
        <!-- Overlay hitam transparan -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Konten -->
        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <div class="md:w-[40%] mx-auto">
                <h2 class="text-center text-white text-5xl font-medium mb-5">
                    Promo Spesial : Pilihan Eksklusif Terbatas!
                </h2>
                <p class="text-center text-white mb-7">
                    Dapatkan penawaran menarik untuk produk pilihan kami. Waktu terbatas — jangan lewatkan kesempatan ini!
                </p>

                <a href="{{ route('home.products') }}"
                    class="block mx-auto w-max py-3 px-6 rounded-full text-white bg-transparent border border-white hover:bg-white hover:text-black transition">
                    Belanja Sekarang
                </a>
            </div>
        </div>
    </div>

    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl text-center font-semibold mb-10">Kategori Pilihan</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                @foreach ($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>
        </div>
    </section>
    <div class="bg-[#ecf4d3] py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-24">
                <img src="{{ asset('img/bonsai2.jpg') }}" alt="" class="w-full h-[550px] object-cover rounded-lg">
                <div>
                    <h2 class="text-5xl font-normal mb-5">
                        Destinasi Utama <br class="hidden md:block">Untuk Semua <br class="hidden md:block">Kebutuhan Hijau
                        Anda.
                    </h2>
                    <p class="text-gray-900">
                        Di Ruang Bonsai, kami percaya pada kekuatan tanaman untuk mengubah suasana. Baik Anda seorang
                        penghobi
                        berpengalaman atau baru memulai perjalanan hijau, koleksi tanaman pilihan kami siap menginspirasi
                        dan
                        memperindah ruang hidup Anda.
                    </p>
                    <hr class="my-10">
                    <div class="grid grid-cols-2 gap-10">
                        <div>
                            <h3 class="text-4xl font-medium mb-2">
                                <span id="satisfaction-counter" class="text-black-600">0</span>%
                            </h3>
                            <p class="text-gray-700">Kepuasan Pelanggan</p>
                        </div>
                        <div>
                            <h3 class="text-4xl font-medium mb-2">
                                <span id="plants-counter" class="text-black-600">0</span>
                            </h3>
                            <p class="text-gray-700">Tanaman Terjual</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="py-10 md:pb-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl text-center font-semibold mb-10">Produk Unggulan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($products->sortBy('created_at') as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <script>
    // Function to animate counter
    function animateCounter(elementId, targetValue, duration = 2000) {
        const element = document.getElementById(elementId);
        const startValue = 0;
        const increment = targetValue / (duration / 30); // 60fps
        let currentValue = startValue;

        const timer = setInterval(() => {
            currentValue += increment;

            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(timer);
            }

            // Format number with commas for large numbers
            const displayValue = Math.floor(currentValue);
            element.textContent = displayValue.toLocaleString('id-ID');
        }, 16);
    }

    // Function to check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Function to start animation when element comes into view
    function startStatsAnimation() {
        const satisfactionElement = document.getElementById('satisfaction-counter');
        const plantsElement = document.getElementById('plants-counter');

        if (isInViewport(satisfactionElement) && !satisfactionElement.dataset.animated) {
            animateCounter('satisfaction-counter', 98, 2000);
            satisfactionElement.dataset.animated = 'true';
        }

        if (isInViewport(plantsElement) && !plantsElement.dataset.animated) {
            animateCounter('plants-counter', 3576, 2500);
            plantsElement.dataset.animated = 'true';
        }
    }

    // Start animation when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Delay animation start
        setTimeout(() => {
            startStatsAnimation();
        }, 500);

        // Also check on scroll
        window.addEventListener('scroll', startStatsAnimation);
    });
</script>


@endsection
