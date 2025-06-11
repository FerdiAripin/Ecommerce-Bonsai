@extends('layouts.app')

@section('content')
    <style>
        .bg-custom-green {
            background-color: #90ac34;
        }

        .bg-custom-green-light {
            background-color: #90ac3410;
        }

        .bg-custom-green-50 {
            background-color: #90ac3420;
        }

        .bg-custom-green-100 {
            background-color: #90ac3430;
        }

        .text-custom-green {
            color: #90ac34;
        }

        .text-custom-green-dark {
            color: #7a9429;
        }

        .border-custom-green {
            border-color: #90ac34;
        }

        .border-custom-green-light {
            border-color: #90ac3450;
        }

        .hover-custom-green:hover {
            color: #90ac34;
        }

        .hover-bg-custom-green:hover {
            background-color: #7a9429;
        }

        .focus-custom-green:focus {
            border-color: #90ac34;
            box-shadow: 0 0 0 3px rgba(144, 172, 52, 0.1);
        }

        .gradient-custom-green {
            background: linear-gradient(135deg, #90ac34, #7a9429);
        }

        .gradient-custom-green-light {
            background: linear-gradient(135deg, #90ac3420, #90ac3430);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .modal-body {
            padding: 24px;
        }

        .close {
            color: #6b7280;
            float: right;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
            line-height: 1;
        }

        .close:hover {
            color: #ef4444;
        }

        .info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background-color: #3b82f6;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 8px;
        }

        .info-icon:hover {
            background-color: #2563eb;
            transform: scale(1.1);
        }

        .packaging-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .packaging-item {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
        }

        .packaging-item:hover {
            border-color: #90ac34;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(144, 172, 52, 0.15);
        }

        .packaging-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .packaging-item-info {
            padding: 12px;
            background-color: #f9fafb;
        }

        .packaging-item-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .packaging-item-desc {
            font-size: 12px;
            color: #6b7280;
        }
    </style>

    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-16">
                <!-- Bagian Gambar Bonsai yang Diperbaiki -->
                <div class="space-y-3">
                    <!-- Gambar Utama Bonsai -->
                    <div class="aspect-square overflow-hidden rounded-xl bg-gradient-to-b from-green-50 to-brown-50 shadow-lg border-custom-green-light"
                        style="border-width: 1px;">
                        <img src="{{ asset('storage/' . $product->image) }}"
                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-500 cursor-zoom-in"
                            alt="{{ $product->name }}" onclick="openImageModal(this.src)">
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
                        <a href="#"
                            class="text-dark hover-custom-green transition-colors">{{ $product->categories->categories_name }}</a>
                        /
                        {{ $product->name }}
                    </div>

                    <a href="#"
                        class="text-white bg-custom-green px-3 py-1 rounded-full text-sm font-medium inline-block mb-3">
                        {{ $product->categories->categories_name }}
                    </a>

                    <h4 class="text-2xl md:text-4xl font-bold mt-3 text-gray-800">{{ $product->name }}</h4>

                    <!-- Tampilan Rating dengan Styling Lebih Baik -->
                    <div class="flex items-center mb-4 bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="text-xl {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span
                            class="ml-3 text-gray-700 font-medium">{{ number_format($product->average_rating, 1) }}</span>
                        <span class="ml-2 text-gray-600 text-sm">({{ $product->total_reviews }} ulasan)</span>
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <h5 class="text-3xl font-bold text-custom-green">Rp {{ number_format($product->price) }}</h5>
                        @if ($product->stock > 0)
                            <span
                                class="bg-custom-green-100 text-custom-green-dark px-3 py-1 rounded-full text-sm font-medium">
                                📦 Stok: {{ $product->stock }}
                            </span>
                        @endif
                    </div>

                    <!-- Informasi Packaging dengan Info Icon -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <h6 class="font-semibold text-amber-800 mb-2 flex items-center gap-2">
                            <span>📦</span> Jaminan Packaging Aman
                            {{-- <span class="info-icon" onclick="openPackagingModal()" title="Lihat contoh packaging">
                                i
                            </span> --}}
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            @if (strtolower($product->categories->categories_name) === 'bonsai')
                                <div class="flex items-center gap-2">
                                    <span>🪵</span>
                                    <span class="text-amber-700">Dikemas dengan kayu berkualitas</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span>📦</span>
                                    <span class="text-amber-700">Pengemasan berkualitas tinggi</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <span>🛡️</span>
                                <span class="text-amber-700">Proteksi maksimal selama pengiriman</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>✅</span>
                                <span class="text-amber-700">Dijamin 100% aman sampai tujuan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>🎁</span>
                                <span class="text-amber-700">Kemasan premium & rapi</span>
                            </div>
                        </div>
                    </div>

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
                                    <button type="button" onclick="decreaseQuantity()"
                                        class="px-4 py-3 bg-custom-green-100 hover-bg-custom-green text-custom-green-dark font-bold transition-colors">-</button>
                                    <input type="number" name="quantity" id="quantity"
                                        class="py-3 px-4 w-20 text-center border-0 outline-none bg-white" value="1"
                                        min="1" max="{{ $product->stock }}" readonly>
                                    <button type="button" onclick="increaseQuantity()"
                                        class="px-4 py-3 bg-custom-green-100 hover-bg-custom-green text-custom-green-dark font-bold transition-colors">+</button>
                                </div>
                                <button
                                    class="btn-primary flex items-center gap-2 bg-custom-green hover-bg-custom-green px-6 py-3 text-white rounded-lg font-semibold transition-colors"
                                    type="submit">
                                    <span>🛒</span>
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mt-10">
                            <div
                                class="bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-300 text-red-700 px-6 py-4 rounded-lg shadow-sm">
                                <p class="font-bold text-center text-lg mb-1">Oops! Stok Habis 📦</p>
                                <div class="text-center">
                                    <p class="text-xs text-red-600 italic">Jangan khawatir, kami akan segera restock ✨</p>
                                    <p class="text-xs text-red-500 mt-1">Cek lagi nanti ya~ 💕</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div
                            class="mt-5 bg-custom-green-100 border border-custom-green text-custom-green-dark px-4 py-3 rounded-lg flex items-center gap-2">
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
                            <div
                                class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="w-12 h-12 rounded-full gradient-custom-green flex items-center justify-center mr-3 text-white font-bold">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $review->user->name }}</h4>
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span
                                                    class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                            <span
                                                class="ml-2 text-gray-500 text-sm">{{ $review->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($review->comment)
                                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg italic">"{{ $review->comment }}"</p>
                                @endif

                                @if ($review->image)
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Gambar Ulasan"
                                            class="max-w-xs rounded-lg border border-gray-200 shadow-sm">
                                    </div>
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
                        <div class="mt-10 gradient-custom-green-light p-6 rounded-lg shadow-md border-custom-green-light"
                            style="border-width: 1px;">
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
                                    <label for="comment" class="block text-gray-700 mb-2 font-medium">Ceritakan pengalaman
                                        Anda Setelah Membeli Produk kami</label>
                                    <textarea name="comment" id="comment" rows="4"
                                        class="w-full border border-gray-300 rounded-lg p-3 focus-custom-green outline-none transition-all"
                                        placeholder="Bagaimana pengalaman Anda dengan produk kami? Bagaimana dengan service yang kita berikan?"></textarea>
                                </div>

                                <button type="submit"
                                    class="btn-primary bg-custom-green hover-bg-custom-green text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
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

    <!-- Packaging Modal -->
    <div id="packagingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span>📦</span> Contoh Packaging Produk Kami
                </h2>
            </div>
            <div class="modal-body">
                <p class="text-gray-600 mb-4">Kami menggunakan berbagai jenis packaging berkualitas tinggi untuk memastikan produk Anda sampai dengan aman:</p>

                <div class="packaging-grid">
                    <!-- Kayu Packaging untuk Bonsai -->
                    <div class="packaging-item" onclick="openImageModal('https://image.indonetwork.co.id/f-webp/products/thumbs/600x600/2024/02/16/934192b2-1d20-4854-9ab7-54e44c4cf2fc.jpg')">
                        <img src="https://image.indonetwork.co.id/f-webp/products/thumbs/600x600/2024/02/16/934192b2-1d20-4854-9ab7-54e44c4cf2fc.jpg" alt="Packaging Kayu untuk Bonsai">
                        <div class="packaging-item-info">
                            <div class="packaging-item-title">Packaging Kayu Premium</div>
                            <div class="packaging-item-desc">Khusus untuk bonsai dan tanaman besar</div>
                        </div>
                    </div>

                    <!-- Kardus Premium -->
                    <div class="packaging-item" onclick="openImageModal('https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Kardus Premium">
                        <div class="packaging-item-info">
                            <div class="packaging-item-title">Kardus Premium</div>
                            <div class="packaging-item-desc">Untuk produk ukuran sedang</div>
                        </div>
                    </div>

                    <!-- Bubble Wrap Protection -->
                    <div class="packaging-item" onclick="openImageModal('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Bubble Wrap Protection">
                        <div class="packaging-item-info">
                            <div class="packaging-item-title">Proteksi Bubble Wrap</div>
                            <div class="packaging-item-desc">Perlindungan ekstra untuk produk fragile</div>
                        </div>
                    </div>

                    <!-- Foam Packaging -->
                    <div class="packaging-item" onclick="openImageModal('https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Foam Packaging">
                        <div class="packaging-item-info">
                            <div class="packaging-item-title">Foam Insert</div>
                            <div class="packaging-item-desc">Untuk produk dengan bentuk khusus</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 mb-2">✅ Jaminan Kami:</h3>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li>• Semua produk dikemas dengan standar internasional</li>
                        <li>• Asuransi pengiriman tersedia</li>
                        <li>• Garansi rusak/pecah akan diganti 100%</li>
                        <li>• Kemasan ramah lingkungan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content" style="max-width: 90vw; max-height: 90vh; padding: 0; background: transparent; box-shadow: none;">
            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                <span class="close" onclick="closeImageModal()" style="position: absolute; top: -50px; right: 0; color: white; font-size: 40px; z-index: 10001;">&times;</span>
                <img id="modalImage" src="" alt="Preview" style="max-width: 100%; max-height: 90vh; object-fit: contain; border-radius: 8px;">
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Quantity Controls dan Modal -->
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

        // Packaging Modal Functions
        function openPackagingModal() {
            document.getElementById('packagingModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closePackagingModal() {
            document.getElementById('packagingModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Image Modal Functions
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.classList.add('show');
            modalImg.src = src;
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const packagingModal = document.getElementById('packagingModal');
            const imageModal = document.getElementById('imageModal');

            if (event.target === packagingModal) {
                closePackagingModal();
            }
            if (event.target === imageModal) {
                closeImageModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePackagingModal();
                closeImageModal();
            }
        });
    </script>
@endsection
