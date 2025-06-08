<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title . ' - Ruang Bonsai' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app-CbpMtXIO.css') }}">
    <script src="{{ asset('build/assets/app-T1DpEqax.js') }}"></script> --}}

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    @stack('addon-style')
</head>

<body class="font-sans antialiased">
    @include('components.navbar')

    @yield('content')

    <footer class="py-10 bg-[#ecf4d3]">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-3 items-start justify-between gap-8 mb-8">
                <!-- Brand Section -->
                <div>
                    <a href="#" class="text-2xl font-semibold mb-4 block">Ruang Bonsai</a>
                    <p class="text-gray-700 text-sm mb-4">
                        Spesialis tanaman bonsai berkualitas tinggi untuk semua tingkatan.
                    </p>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class='bx bxs-map text-primary'></i>
                            <span>Tasikmalaya, Jawa Barat</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class='bx bxs-phone text-primary'></i>
                            <span>+62 859-3253-8234</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="flex justify-center">
                    <div class="flex flex-row gap-x-6">
                        <a href="{{ route('home') }}"
                            class="text-black hover:text-primary transition-colors">Beranda</a>
                        <a href="{{ route('home.products') }}"
                            class="text-black hover:text-primary transition-colors">Produk</a>
                        <a href="{{ route('about') }}"
                            class="text-black hover:text-primary transition-colors">Tentang</a>
                        <a href="{{ route('contact') }}"
                            class="text-black hover:text-primary transition-colors">Kontak</a>
                        <a href="{{ route('blogs.index') }}"
                            class="text-black hover:text-primary transition-colors">Artikel</a>
                    </div>
                </div>


                <!-- Social Media -->
                <div class="flex md:justify-end items-center gap-4">
                    <a href="https://wa.me/6285932538234?text=Halo%20saya%20ingin%20bertanya"
                        class="text-black hover:text-primary text-xl transition-colors">
                        <i class='bx bxl-whatsapp'></i>
                    </a>
                    <a href="https://www.instagram.com/ruangbonsai_offc?igsh=MXkxcm93a21qamQyMw=="
                        class="text-black hover:text-primary text-xl transition-colors">
                        <i class='bx bxl-instagram'></i>
                    </a>
                    <a href="https://youtube.com/@ruangbonsai?si=TRzAj1HHZ6scQtEk"
                        class="text-black hover:text-primary text-xl transition-colors">
                        <i class='bx bxl-youtube'></i>
                    </a>
                    <a href="https://www.tiktok.com/@ruangbonsai?_t=ZS-8wpRe6hxrVY&_r=1"
                        class="text-black hover:text-primary text-xl transition-colors">
                        <i class='bx bxl-tiktok'></i>
                    </a>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="border-t border-gray-300 pt-6 mb-6">
                <div class="text-center">
                    <h4 class="font-semibold text-gray-900 mb-4">Metode Pembayaran & Pengiriman</h4>
                    <div class="flex flex-wrap justify-center gap-4">
                        <!-- E-Wallet dengan logo SVG -->
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/dana.png') }}" alt="DANA"
                                class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/gopay.png') }}" alt="GoPay"
                                class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/ovo.png') }}" alt="OVO"
                                class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/sopi.png') }}"
                                alt="ShopeePay" class="h-6 w-auto">
                        </div>

                        <!-- Bank Transfer -->
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/bca.png') }}" alt="BCA"
                                class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/bni.png') }}" alt="BNI"
                                class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/bri.png') }}" alt="BRI"
                                class="h-6 w-auto">
                        </div>
                        <!-- Delivery -->
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/jne.png') }}" alt="JNE"
                                class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/jnt.png') }}"
                                alt="J&T Express" class="h-6 w-auto">
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <img src="{{ asset('img/tiki.png') }}" alt="TIKI"
                                class="h-6 w-auto">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-300 pt-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex gap-4 text-sm text-gray-600">
                        <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
                        <span class="text-gray-400">•</span>
                        <a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a>
                        <span class="text-gray-400">•</span>
                        <a href="#" class="hover:text-primary transition-colors">Bantuan</a>
                    </div>
                    <p class="text-gray-500 text-sm">
                        © 2025 Ruang Bonsai. Semua hak dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </footer>


    {{-- style baru --}}
    {{-- <style>
        /* Fallback jika gambar tidak load */
        .bg-white img {
            max-height: 24px;
            object-fit: contain;
        }

        /* Hover effect untuk payment cards */
        .bg-white:hover {
            transform: translateY(-1px);
        }
    </style> --}}

    <script>
        // JavaScript for mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.remove('hidden');
        });

        document.getElementById('close-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    </script>
    @stack('addon-script')
</body>

</html>
