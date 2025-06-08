@extends('layouts.app')

@section('content')
    <style>
        .bg-custom-green { background-color: #90ac34; }
        .bg-custom-green-light { background-color: #90ac3410; }
        .bg-custom-green-50 { background-color: #90ac3420; }
        .bg-custom-green-100 { background-color: #90ac3430; }
        .text-custom-green { color: #90ac34; }
        .text-custom-green-dark { color: #7a9429; }
        .border-custom-green { border-color: #90ac34; }
        .border-custom-green-light { border-color: #90ac3450; }
        .hover-custom-green:hover { color: #90ac34; }
        .hover-bg-custom-green:hover { background-color: #7a9429; }
        .focus-custom-green:focus {
            border-color: #90ac34;
            box-shadow: 0 0 0 3px rgba(144, 172, 52, 0.1);
        }
        .gradient-custom-green { background: linear-gradient(135deg, #90ac34, #7a9429); }
        .gradient-custom-green-light { background: linear-gradient(135deg, #90ac3420, #90ac3430); }
    </style>

    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-16">
                <!-- Bagian Gambar Bonsai yang Diperbaiki -->
                <div class="space-y-3">
                    <!-- Gambar Utama Bonsai -->
                    <div class="aspect-square overflow-hidden rounded-xl bg-gradient-to-b from-green-50 to-brown-50 shadow-lg border-custom-green-light" style="border-width: 1px;">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-500 cursor-zoom-in"
                             alt="{{ $product->name }}"
                             onclick="openImageModal(this.src)">
                    </div>

                    <!-- Info Visual Bonsai -->
                    <div class="bg-custom-green-50 border-custom-green-light rounded-lg p-3" style="border-width: 1px;">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-1">
                                <span class="text-custom-green">📦</span>
                                <span class="text-custom-green-dark">Stok: {{ $product->stock ?? 'tersedia' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-brown-600">🏷️</span>
                                <span class="text-brown-700">{{ $product->categories->categories_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Informasi Produk -->
                <div>
                    <div class="flex gap-2 mb-2 text-gray-500">
                        <a href="{{ route('home') }}" class="text-dark hover-custom-green transition-colors">Beranda</a> /
                        <a href="#" class="text-dark hover-custom-green transition-colors">{{ $product->categories->categories_name }}</a> /
                        {{ $product->name }}
                    </div>

                    <a href="#" class="text-white bg-custom-green px-3 py-1 rounded-full text-sm font-medium inline-block mb-3">
                         {{ $product->categories->categories_name }}
                    </a>

                    <h4 class="text-2xl md:text-4xl font-bold mt-3 text-gray-800">{{ $product->name }}</h4>

                    <!-- Tampilan Rating dengan Styling Lebih Baik -->
                    <div class="flex items-center mb-4 bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="text-xl {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="ml-3 text-gray-700 font-medium">{{ number_format($product->average_rating, 1) }}</span>
                        <span class="ml-2 text-gray-600 text-sm">({{ $product->total_reviews }} ulasan)</span>
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <h5 class="text-3xl font-bold text-custom-green">Rp {{ number_format($product->price) }}</h5>
                        @if($product->stock > 0)
                            <span class="bg-custom-green-100 text-custom-green-dark px-3 py-1 rounded-full text-sm font-medium">
                                📦 Stok: {{ $product->stock }}
                            </span>
                        @endif
                    </div>

                    <!-- Care Instructions untuk Bonsai -->
                    {{-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h6 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                            <span>🌿</span> Panduan Perawatan
                        </h6>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span>💧</span>
                                <span class="text-blue-700">Siram 2-3x seminggu</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>☀️</span>
                                <span class="text-blue-700">Sinar matahari tidak langsung</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>✂️</span>
                                <span class="text-blue-700">Pangkas rutin</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>🏠</span>
                                <span class="text-blue-700">Cocok indoor/outdoor</span>
                            </div>
                        </div>
                    </div> --}}

                    <div class="text-gray-700 mb-6 bg-gray-50 p-4 rounded-lg border">
                        <h6 class="font-semibold mb-2 text-gray-800">Deskripsi Produk:</h6>
                        {!! $product->description !!}
                    </div>

                    <!-- Form Cart yang Diperbaiki -->
                    @if ($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center gap-3 mt-10">
                                <div class="flex items-center border-2 border-custom-green rounded-lg overflow-hidden">
                                    <button type="button" onclick="decreaseQuantity()" class="px-4 py-3 bg-custom-green-100 hover-bg-custom-green text-custom-green-dark font-bold transition-colors">-</button>
                                    <input type="number" name="quantity" id="quantity"
                                        class="py-3 px-4 w-20 text-center border-0 outline-none bg-white"
                                        value="1" min="1" max="{{ $product->stock }}" readonly>
                                    <button type="button" onclick="increaseQuantity()" class="px-4 py-3 bg-custom-green-100 hover-bg-custom-green text-custom-green-dark font-bold transition-colors">+</button>
                                </div>
                                <button class="btn-primary flex items-center gap-2 bg-custom-green hover-bg-custom-green px-6 py-3 text-white rounded-lg font-semibold transition-colors" type="submit">
                                    <span>🛒</span>
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mt-10">
                            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-300 text-red-700 px-6 py-4 rounded-lg shadow-sm">
                                <p class="font-bold text-center text-lg mb-1">Oops! Stok Habis 📦</p>
                                <div class="text-center">
                                    <p class="text-xs text-red-600 italic">Jangan khawatir, kami akan segera restock ✨</p>
                                    <p class="text-xs text-red-500 mt-1">Cek lagi nanti ya~ 💕</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mt-5 bg-custom-green-100 border border-custom-green text-custom-green-dark px-4 py-3 rounded-lg flex items-center gap-2">
                            <span>✅</span>
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bagian Ulasan (Tetap sama tapi dengan styling yang diperbaiki) -->
            <div class="mb-16">
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span>💬</span> Ulasan Pelanggan
                </h3>

                @if ($product->reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach ($product->reviews as $review)
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 rounded-full gradient-custom-green flex items-center justify-center mr-3 text-white font-bold">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $review->user->name }}</h4>
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                            <span class="ml-2 text-gray-500 text-sm">{{ $review->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($review->comment)
                                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg italic">"{{ $review->comment }}"</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <div class="text-6xl mb-4">🌱</div>
                        <p class="text-gray-500 text-lg">Belum ada ulasan untuk bonsai ini!</p>
                        <p class="text-gray-400 text-sm">Jadilah yang pertama berbagi pengalaman Anda</p>
                    </div>
                @endif

                <!-- Form Tambah Ulasan (dengan styling yang diperbaiki) -->
                @auth
                    @if (auth()->user()->hasPurchased($product->id))
                        <div class="mt-10 gradient-custom-green-light p-6 rounded-lg shadow-md border-custom-green-light" style="border-width: 1px;">
                            <h4 class="text-lg font-medium mb-4 flex items-center gap-2">
                                <span>✍️</span> Bagikan Pengalaman Anda
                            </h4>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2 font-medium">Berikan Rating</label>
                                    <div class="flex items-center space-x-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <div class="relative">
                                                <input type="radio" id="rating-{{ $i }}" name="rating"
                                                    value="{{ $i }}"
                                                    class="absolute opacity-0 w-full h-full cursor-pointer peer"
                                                    {{ $i == 5 ? 'checked' : '' }}>
                                                <label for="rating-{{ $i }}"
                                                    class="block text-3xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 cursor-pointer transition-colors">
                                                    ★
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="block text-gray-700 mb-2 font-medium">Ceritakan pengalaman Anda (opsional)</label>
                                    <textarea name="comment" id="comment" rows="4"
                                        class="w-full border border-gray-300 rounded-lg p-3 focus-custom-green outline-none transition-all"
                                        placeholder="Bagaimana pengalaman Anda dengan bonsai ini? Bagaimana perawatannya?"></textarea>
                                </div>

                                <button type="submit" class="btn-primary bg-custom-green hover-bg-custom-green text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
                                    <span>📝</span>
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Produk Terkait -->
            <div class="mb-16">
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span>🌿</span> Produk Lainnya yang Mungkin Anda Suka
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach ($relatedProducts as $relatedProduct)
                        @include('components.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript untuk Quantity Controls -->
    <script>
        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        }

        // Optional: Image Modal Function
        function openImageModal(src) {
            // Implementasi modal gambar jika diperlukan
            console.log('Open image modal for:', src);
        }
    </script>
@endsection
