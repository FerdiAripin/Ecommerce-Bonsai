<nav
    class="{{ Request::is('/') ? 'bg-transparent absolute top-0 left-0 w-full z-10' : 'bg-white border-b border-gray-300' }} transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-4 px-6 flex items-center justify-between">
        <!-- Bagian Logo -->
        <div class="flex items-center">
            <a href="/" class="text-2xl font-semibold {{ Request::is('/') ? 'text-white' : 'text-black' }}">
                Ruang Bonsai
            </a>
        </div>

        <!-- Tombol Menu Mobile -->
        <div class="md:hidden">
            <button id="mobile-menu-button"
                class="{{ Request::is('/') ? 'text-white' : 'text-gray-800' }} focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Navigasi Desktop -->
        <div class="hidden md:flex items-center space-x-8">
            <a href="{{ route('home') }}"
                class="{{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} py-4 transition-colors">
                Beranda
            </a>
            <a href="{{ route('home.products') }}"
                class="{{ Request::is('products*') ? (Request::is('/products*') ? 'text-green-300' : 'text-green-600') : (Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600') }} py-4 transition-colors">
                Produk
            </a>
            <a href="{{ route('about') }}"
                class="{{ Request::is('about*') ? (Request::is('/') ? 'text-green-300' : 'text-green-600') : (Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600') }} py-4 transition-colors">Tentang</a>
            <a href="{{ route('contact') }}"
                class="{{ Request::is('contact*') ? (Request::is('/') ? 'text-green-300' : 'text-green-600') : (Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600') }} py-4 transition-colors">Kontak</a>
            <a href="{{ route('blogs.index') }}"
                class="{{ Request::is('blog*') ? (Request::is('/') ? 'text-green-300' : 'text-green-600') : (Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600') }} py-4 transition-colors">Artikel</a>

            <!-- Kondisi untuk menampilkan Info Penggunaan atau Pesanan Saya -->
            @auth
                <a href="{{ route('orders.index') }}"
                    class="{{ Request::is('orders*') ? (Request::is('/') ? 'text-green-300' : 'text-green-600') : (Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600') }} py-4 transition-colors">
                    Pesanan Saya
                </a>
            @else
                <button id="info-penggunaan-btn"
                    class="{{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} py-4 transition-colors">
                    Info Penggunaan
                </button>
            @endauth

            <!-- Icon Media Sosial -->
            <div class="flex items-center space-x-4">
                <a href="https://wa.me/6285932538234?text=Halo%20saya%20ingin%20bertanya"
                    class="{{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} text-xl transition-colors">
                    <i class='bx bxl-whatsapp'></i>
                </a>
                <a href="https://www.instagram.com/ruangbonsai_offc?igsh=MXkxcm93a21qamQyMw=="
                    class="{{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} text-xl transition-colors">
                    <i class='bx bxl-instagram'></i>
                </a>
                <a href="https://youtube.com/@ruangbonsai?si=TRzAj1HHZ6scQtEk"
                    class="{{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} text-xl transition-colors">
                    <i class='bx bxl-youtube'></i>
                </a>
                <a href="https://www.tiktok.com/@ruangbonsai?_t=ZS-8wpRe6hxrVY&_r=1"
                    class="{{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} text-xl transition-colors">
                    <i class='bx bxl-tiktok'></i>
                </a>

                <!-- Icon keranjang dengan jumlah -->
                <a href="{{ route('cart.index') }}"
                    class="relative {{ Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600' }} text-2xl transition-colors">
                    <i class="bx bx-cart"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        @auth
                            {{ Auth::user()->orders->where('status', 'in_cart')->count() > 0 ? Auth::user()->orders->where('status', 'in_cart')->first()->details()->sum('quantity') : 0 }}
                        @else
                            0
                        @endauth
                    </span>
                </a>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="{{ Request::is('/') ? 'text-white hover:text-red-400' : 'text-gray-800 hover:text-red-600' }}
                   flex items-center space-x-2 px-3 py-2 rounded-md transition-colors">
                            <i class='bx bx-log-out text-xl'></i>
                            <span class="text-sm">Logout</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Menu Navigasi Mobile (Tersembunyi Secara Default) -->
        <div id="mobile-menu" class="hidden fixed inset-0 bg-gray-900 bg-opacity-90 z-50">
            <div class="flex justify-end p-4">
                <button id="close-menu-button" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col items-center justify-center h-full">
                <a href="{{ route('home') }}" class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                    Beranda
                </a>
                <a href="{{ route('home.products') }}"
                    class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                    Produk
                </a>
                <a href="{{ route('about') }}" class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                    Tentang
                </a>
                <a href="{{ route('contact') }}"
                    class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                    Kontak
                </a>
                <a href="{{ route('blogs.index') }}"
                    class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                    Artikel
                </a>

                <!-- Kondisi untuk mobile menu -->
                @auth
                    <a href="{{ route('orders.index') }}"
                        class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                        Pesanan Saya
                    </a>
                @else
                    <button id="info-penggunaan-mobile-btn"
                        class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                        Info Penggunaan
                    </button>
                @endauth

                <!-- Icon Media Sosial (Mobile) -->
                <div class="flex items-center space-x-6 my-8">
                    <a href="#" class="text-white hover:text-green-300 text-2xl transition-colors">
                        <i class='bx bxl-facebook-circle'></i>
                    </a>
                    <a href="#" class="text-white hover:text-green-300 text-2xl transition-colors">
                        <i class='bx bxl-instagram'></i>
                    </a>
                    <a href="#" class="text-white hover:text-green-300 text-2xl transition-colors">
                        <i class='bx bxl-youtube'></i>
                    </a>
                    <a href="#" class="text-white hover:text-green-300 text-2xl transition-colors">
                        <i class='bx bxl-twitter'></i>
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="text-white hover:text-green-300 text-2xl transition-colors relative">
                        <i class="bx bx-cart"></i>
                        <span
                            class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            @auth
                                {{ Auth::user()->orders->where('status', 'in_cart')->count() > 0 ? Auth::user()->orders->where('status', 'in_cart')->first()->details()->sum('quantity') : 0 }}
                            @else
                                0
                            @endauth
                        </span>
                    </a>
                </div>
                <!-- Di dalam menu mobile -->
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="w-full text-center">
                        @csrf
                        <button type="submit"
                            class="text-white text-xl my-4 hover:text-green-300 transition-colors w-full py-2">
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <!-- Pop-up Info Penggunaan -->
    <div id="info-penggunaan-modal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Header Modal -->
            <div
                class="bg-gradient-to-r from-[#90ac34] to-[#7a9629] text-white p-6 rounded-t-lg relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full bg-white"></div>
                    <div class="absolute top-8 -left-4 w-16 h-16 rounded-full bg-white"></div>
                    <div class="absolute bottom-2 right-8 w-8 h-8 rounded-full bg-white"></div>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <h2 class="text-2xl font-bold flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class='bx bx-info-circle text-2xl'></i>
                        </div>
                        Info Penggunaan Website
                    </h2>
                    <button id="close-modal"
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 p-2 rounded-full transition-all duration-300 hover:scale-110">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <!-- Content Modal -->
            <div class="p-8">
                <!-- Selamat Datang -->
                <div
                    class="mb-8 bg-gradient-to-br from-[#90ac34] to-[#7a9629] p-6 rounded-xl text-white relative overflow-hidden">
                    <!-- Background decorative elements -->
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-white bg-opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>

                    <div class="flex items-center mb-4 relative z-10">
                        <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                            <i class='bx bx-home text-2xl'></i>
                        </div>
                        <h3 class="text-xl font-semibold">Selamat Datang di Ruang Bonsai!</h3>
                    </div>
                    <p class="leading-relaxed relative z-10 text-green-50">
                        Ruang Bonsai adalah destinasi online terbaik untuk pecinta bonsai. Kami menyediakan berbagai
                        tanaman bonsai berkualitas tinggi, peralatan, dan panduan lengkap untuk membantu Anda dalam
                        perjalanan seni bonsai.
                    </p>
                </div>

                <!-- Fitur Utama -->
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-[#90ac34] to-[#7a9629] p-3 rounded-full mr-4">
                            <i class='bx bx-star text-white text-2xl'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Fitur Utama Website</h3>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="group bg-gradient-to-br from-[#90ac34] to-[#7a9629] p-6 rounded-xl text-white hover:shadow-xl transition-all duration-300 hover:scale-105 relative overflow-hidden">
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
                            <h4 class="font-semibold mb-3 flex items-center relative z-10">
                                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                                    <i class='bx bx-store-alt text-lg'></i>
                                </div>
                                Katalog Produk
                            </h4>
                            <p class="text-green-50 text-sm relative z-10">Jelajahi koleksi bonsai dan peralatan
                                lengkap dengan deskripsi detail dan harga terbaru.</p>
                        </div>
                        <div
                            class="group bg-gradient-to-br from-[#7a9629] to-[#90ac34] p-6 rounded-xl text-white hover:shadow-xl transition-all duration-300 hover:scale-105 relative overflow-hidden">
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
                            <h4 class="font-semibold mb-3 flex items-center relative z-10">
                                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                                    <i class='bx bx-cart text-lg'></i>
                                </div>
                                Keranjang Belanja
                            </h4>
                            <p class="text-green-50 text-sm relative z-10">Tambahkan produk favorit ke keranjang dan
                                lakukan pemesanan dengan mudah.</p>
                        </div>
                        <div
                            class="group bg-gradient-to-br from-[#90ac34] to-[#7a9629] p-6 rounded-xl text-white hover:shadow-xl transition-all duration-300 hover:scale-105 relative overflow-hidden">
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
                            <h4 class="font-semibold mb-3 flex items-center relative z-10">
                                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                                    <i class='bx bx-book-open text-lg'></i>
                                </div>
                                Artikel & Tips
                            </h4>
                            <p class="text-green-50 text-sm relative z-10">Baca artikel informatif tentang perawatan
                                bonsai dan tips dari para ahli.</p>
                        </div>
                        <div
                            class="group bg-gradient-to-br from-[#7a9629] to-[#90ac34] p-6 rounded-xl text-white hover:shadow-xl transition-all duration-300 hover:scale-105 relative overflow-hidden">
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
                            <h4 class="font-semibold mb-3 flex items-center relative z-10">
                                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                                    <i class='bx bx-message-dots text-lg'></i>
                                </div>
                                Konsultasi
                            </h4>
                            <p class="text-green-50 text-sm relative z-10">Hubungi kami langsung melalui WhatsApp untuk
                                konsultasi dan bantuan.</p>
                        </div>
                    </div>
                </div>

                <!-- Cara Berbelanja -->
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-[#90ac34] to-[#7a9629] p-3 rounded-full mr-4">
                            <i class='bx bx-shopping-bag text-white text-2xl'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Cara Berbelanja</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start group">
                            <div
                                class="bg-gradient-to-br from-[#90ac34] to-[#7a9629] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1 text-lg font-bold shadow-lg group-hover:scale-110 transition-transform duration-300">
                                1</div>
                            <div
                                class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl flex-1 border-l-4 border-[#90ac34]">
                                <h4 class="font-semibold text-gray-800 mb-2">Pilih Produk</h4>
                                <p class="text-gray-600 text-sm">Jelajahi katalog produk kami dan pilih bonsai atau
                                    peralatan yang Anda inginkan.</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div
                                class="bg-gradient-to-br from-[#7a9629] to-[#90ac34] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1 text-lg font-bold shadow-lg group-hover:scale-110 transition-transform duration-300">
                                2</div>
                            <div
                                class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl flex-1 border-l-4 border-[#7a9629]">
                                <h4 class="font-semibold text-gray-800 mb-2">Tambah ke Keranjang</h4>
                                <p class="text-gray-600 text-sm">Klik tombol "Tambah ke Keranjang" pada produk yang
                                    ingin dibeli.</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div
                                class="bg-gradient-to-br from-[#90ac34] to-[#7a9629] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1 text-lg font-bold shadow-lg group-hover:scale-110 transition-transform duration-300">
                                3</div>
                            <div
                                class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl flex-1 border-l-4 border-[#90ac34]">
                                <h4 class="font-semibold text-gray-800 mb-2">Login atau Daftar</h4>
                                <p class="text-gray-600 text-sm">Untuk melanjutkan pemesanan, Anda perlu login atau
                                    membuat akun terlebih dahulu.</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div
                                class="bg-gradient-to-br from-[#7a9629] to-[#90ac34] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1 text-lg font-bold shadow-lg group-hover:scale-110 transition-transform duration-300">
                                4</div>
                            <div
                                class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl flex-1 border-l-4 border-[#7a9629]">
                                <h4 class="font-semibold text-gray-800 mb-2">Checkout & Pembayaran</h4>
                                <p class="text-gray-600 text-sm">Isi detail pengiriman dan lakukan pembayaran melalui
                                    metode yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keunggulan -->
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-[#90ac34] to-[#7a9629] p-3 rounded-full mr-4">
                            <i class='bx bx-trophy text-white text-2xl'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Mengapa Memilih Ruang Bonsai?</h3>
                    </div>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div
                            class="group text-center p-6 bg-gradient-to-b from-white to-green-50 rounded-xl border border-green-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div
                                class="bg-gradient-to-r from-[#90ac34] to-[#7a9629] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-check-shield text-white text-3xl'></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-3">Kualitas Terjamin</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Semua produk telah melewati seleksi ketat
                                untuk memastikan kualitas terbaik.</p>
                        </div>
                        <div
                            class="group text-center p-6 bg-gradient-to-b from-white to-green-50 rounded-xl border border-green-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div
                                class="bg-gradient-to-r from-[#7a9629] to-[#90ac34] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-support text-white text-3xl'></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-3">Layanan Prima</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Tim customer service kami siap membantu
                                Anda 24/7 melalui berbagai channel.</p>
                        </div>
                        <div
                            class="group text-center p-6 bg-gradient-to-b from-white to-green-50 rounded-xl border border-green-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div
                                class="bg-gradient-to-r from-[#90ac34] to-[#7a9629] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-package text-white text-3xl'></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-3">Pengiriman Aman</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Kemasan khusus dan pengiriman yang aman
                                untuk menjaga kondisi tanaman.</p>
                        </div>
                    </div>
                </div>

                <!-- Kontak -->
                <div
                    class="bg-gradient-to-br from-[#90ac34] to-[#7a9629] p-8 rounded-2xl text-white relative overflow-hidden">
                    <!-- Background decorative elements -->
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white bg-opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
                    <div class="absolute top-1/2 right-1/4 w-4 h-4 bg-white bg-opacity-20 rounded-full"></div>
                    <div class="absolute bottom-1/4 left-1/3 w-6 h-6 bg-white bg-opacity-15 rounded-full"></div>

                    <div class="flex items-center mb-6 relative z-10">
                        <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                            <i class='bx bx-phone text-2xl'></i>
                        </div>
                        <h3 class="text-xl font-semibold">Butuh Bantuan?</h3>
                    </div>
                    <p class="mb-6 relative z-10 text-green-50 leading-relaxed">
                        Tim kami siap membantu Anda! Jangan ragu untuk menghubungi kami melalui:
                    </p>
                    <div class="flex flex-wrap gap-4 relative z-10">
                        <a href="https://wa.me/6285932538234?text=Halo%20saya%20ingin%20bertanya"
                            class="group flex items-center bg-white bg-opacity-20 backdrop-blur-sm text-white px-6 py-3 rounded-full hover:bg-opacity-30 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            <i class='bx bxl-whatsapp mr-2 text-xl group-hover:animate-bounce'></i>
                            <span class="font-medium">WhatsApp</span>
                        </a>
                        <a href="https://www.instagram.com/ruangbonsai_offc?igsh=MXkxcm93a21qamQyMw=="
                            class="group flex items-center bg-white bg-opacity-20 backdrop-blur-sm text-white px-6 py-3 rounded-full hover:bg-opacity-30 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            <i class='bx bxl-instagram mr-2 text-xl group-hover:animate-bounce'></i>
                            <span class="font-medium">Instagram</span>
                        </a>
                        <a href="mailto:info@ruangbonsai.com"
                            class="group flex items-center bg-white bg-opacity-20 backdrop-blur-sm text-white px-6 py-3 rounded-full hover:bg-opacity-30 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            <i class='bx bx-envelope mr-2 text-xl group-hover:animate-bounce'></i>
                            <span class="font-medium">Email</span>
                        </a>
                    </div>
                </div>
                <!-- Footer Modal -->
                <div class="text-center mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-500 text-sm">
                        Terima kasih telah memilih Ruang Bonsai. Selamat berbelanja! 🌿
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</nav>

<!-- JavaScript untuk Pop-up -->
<script>
    // Fungsi untuk membuka modal
    function openModal() {
        document.getElementById('info-penggunaan-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Mencegah scroll background
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        document.getElementById('info-penggunaan-modal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Mengembalikan scroll
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Button Info Penggunaan Desktop
        const infoPenggunaanBtn = document.getElementById('info-penggunaan-btn');
        if (infoPenggunaanBtn) {
            infoPenggunaanBtn.addEventListener('click', openModal);
        }

        // Button Info Penggunaan Mobile
        const infoPenggunaanMobileBtn = document.getElementById('info-penggunaan-mobile-btn');
        if (infoPenggunaanMobileBtn) {
            infoPenggunaanMobileBtn.addEventListener('click', function() {
                // Tutup mobile menu terlebih dahulu
                document.getElementById('mobile-menu').classList.add('hidden');
                // Buka modal
                openModal();
            });
        }

        // Close modal button
        const closeModalBtn = document.getElementById('close-modal');
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        // Close modal when clicking outside
        const modal = document.getElementById('info-penggunaan-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Mobile menu functionality (existing code)
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenuButton = document.getElementById('close-menu-button');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('hidden');
            });
        }

        if (closeMenuButton) {
            closeMenuButton.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        }
    });
</script>

<!-- Tambahkan BoxIcons CDN jika belum ada -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<!-- Custom CSS untuk animasi modal -->
<style>
    #info-penggunaan-modal {
        animation: fadeIn 0.3s ease-out;
    }

    #info-penggunaan-modal .bg-white {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Smooth scrollbar untuk modal */
    #info-penggunaan-modal .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    #info-penggunaan-modal .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    #info-penggunaan-modal .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    #info-penggunaan-modal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
