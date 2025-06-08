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
            <a href="{{ route('orders.index') }}"
                class="{{ Request::is('orders*') ? (Request::is('/') ? 'text-green-300' : 'text-green-600') : (Request::is('/') ? 'text-white hover:text-green-300' : 'text-gray-800 hover:text-green-600') }} py-4 transition-colors">
                Pesanan Saya
            </a>

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
                <a href="{{ route('orders.index') }}"
                    class="text-white text-xl my-4 hover:text-green-300 transition-colors">
                    Pesanan Saya
                </a>

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
</nav>
