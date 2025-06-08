@extends('layouts.app')

@section('content')
    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-2xl md:text-3xl font-bold">Pesanan #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
                <div class="flex items-center space-x-4">
                    @if ($order->payment_status == 'paid')
                        <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                            class="bg-green-600 text-white text-sm px-4 py-2 rounded hover:bg-green-700 transition flex items-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Cetak Invoice
                        </a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="text-green-600 hover:text-green-800">
                        ← Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Informasi Pesanan</h3>
                            <p class="text-gray-600 mb-1">Tanggal Pesanan: {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                            <p class="text-gray-600 mb-1">
                                Status:
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'in_shipping') bg-blue-100 text-blue-800
                                @elseif($order->status == 'delivered') bg-blue-100 text-blue-800
                                @elseif($order->status == 'success') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                    @if ($order->status == 'pending')
                                        Menunggu Konfirmasi
                                    @elseif($order->status == 'in_shipping')
                                        Di Proses
                                    @elseif($order->status == 'delivered')
                                        Dalam Pengiriman
                                    @elseif($order->status == 'success')
                                        Selesai
                                    @elseif($order->status == 'cancelled')
                                        Dibatalkan
                                    @endif
                                    {{-- {{ ucfirst(str_replace('_', ' ', $order->status)) }} --}}
                                </span>
                            </p>
                            <p class="text-gray-600">
                                Status Pembayaran:
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'unpaid') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status == 'refunded') bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2">Detail Pengiriman</h3>
                            <p class="text-gray-600 mb-1">Penerima: {{ $order->recipient_name }}</p>
                            <p class="text-gray-600 mb-1">Telepon: {{ $order->phone_number }}</p>
                            <p class="text-gray-600 mb-1">Alamat: {{ $order->shipping_address }}</p>
                            <p class="text-gray-600">{{ $order->city }}, {{ $order->province }},
                                {{ $order->postal_code }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2">Pembayaran & Pengiriman</h3>
                            <p class="text-gray-600 mb-1">Metode Pembayaran:
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p class="text-gray-600 mb-1">Kurir: {{ strtoupper($order->courier) }}</p>
                            <p class="text-gray-600">Layanan: {{ $order->courier_service }}</p>

                            @if ($order->tracking_number)
                                <p class="text-gray-600 mt-2">Nomor Resi: {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Item Pesanan</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($order->details as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if ($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        class="h-10 w-10 mr-3" alt="{{ $item->product->name }}">
                                                @endif
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($item->price) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($item->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex justify-end">
                            <div class="w-full md:w-1/3">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span>Rp. {{ number_format($order->total_amount) }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Ongkos Kirim:</span>
                                    <span>Rp. {{ number_format($order->shipping_cost) }}</span>
                                </div>
                                <div class="flex justify-between font-semibold text-lg border-t border-gray-200 pt-2 mt-2">
                                    <span>Total:</span>
                                    <span>Rp. {{ number_format($order->grand_total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="p-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold mb-2">Catatan Pesanan</h3>
                        <p class="text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif

                @if ($order->payment && $order->payment_status != 'paid' && $order->status != 'cancelled')
                    <div class="p-6 border-t border-gray-200 bg-yellow-50">
                        <h3 class="text-lg font-semibold mb-2">Informasi Pembayaran</h3>

                        <p class="mb-4">Silakan selesaikan pembayaran Anda untuk memproses pesanan.</p>

                        <div class="flex flex-col items-center md:flex-row">
                            <button type="button" id="pay-button" class="btn-primary mb-2 md:mb-0 md:mr-4">
                                Bayar Sekarang
                            </button>
                            <span class="text-gray-600 text-sm">Pembayaran akan diproses secara aman via Midtrans</span>
                        </div>
                    </div>
                @endif
            </div>

            @if ($order->status != 'cancelled' && ($order->status == 'delivered' || $order->status == 'success'))
                <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Konfirmasi Penerimaan Pesanan</h3>

                    @if ($order->status == 'delivered')
                        <p class="text-gray-600 mb-4">Jika Anda telah menerima pesanan, silakan konfirmasi di bawah ini.</p>
                        <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">
                                Konfirmasi Pesanan Diterima
                            </button>
                        </form>
                    @else
                        <div class="bg-green-50 border-l-4 border-green-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        Anda telah mengkonfirmasi penerimaan pesanan ini pada
                                        {{ $order->updated_at->format('d M Y') }}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($order->status != 'cancelled' && $order->status != 'success' && $order->payment_status != 'paid')
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Batalkan Pesanan</h3>
                    <p class="text-gray-600 mb-4">Jika Anda ingin membatalkan pesanan ini, silakan klik tombol di bawah.</p>

                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif

            @if ($order->status == 'success')
                <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Beri Ulasan Produk</h3>

                    @foreach ($order->details as $item)
                        @if (!$item->product->reviews()->where('user_id', auth()->id())->where('order_id', $order->id)->exists())
                            <div class="border-b border-gray-200 pb-4 mb-4">
                                <div class="flex items-start mb-3">
                                    @if ($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            class="h-16 w-16 mr-4 object-cover rounded" alt="{{ $item->product->name }}">
                                    @endif
                                    <div>
                                        <h4 class="font-medium">{{ $item->product->name }}</h4>
                                        <p class="text-gray-600 text-sm">Rp. {{ number_format($item->price) }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                                    <div class="mb-3">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label class="block text-gray-700 mb-1">
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                        class="rating-input absolute opacity-0">
                                                    <span class="rating-star text-3xl text-gray-300">
                                                        ★
                                                    </span>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="comment-{{ $item->product->id }}"
                                            class="block text-gray-700 mb-1">Ulasan (opsional)</label>
                                        <textarea name="comment" id="comment-{{ $item->product->id }}" rows="3"
                                            class="w-full border border-gray-300 rounded p-2"></textarea>
                                    </div>

                                    <button type="submit" class="btn-primary">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="border-b border-gray-200 pb-4 mb-4">
                                <div class="flex items-start mb-3">
                                    @if ($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            class="h-16 w-16 mr-4 object-cover rounded" alt="{{ $item->product->name }}">
                                    @endif
                                    <div>
                                        <h4 class="font-medium">{{ $item->product->name }}</h4>
                                        <p class="text-gray-600 text-sm">Rp. {{ number_format($item->price) }}</p>
                                    </div>
                                </div>

                                @php
                                    $review = $item->product
                                        ->reviews()
                                        ->where('user_id', auth()->id())
                                        ->where('order_id', $order->id)
                                        ->first();
                                @endphp

                                <div class="bg-gray-50 p-4 rounded">
                                    <div class="flex items-center mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span
                                                class="text-xl {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                        @endfor
                                        <span
                                            class="ml-2 text-gray-600 text-sm">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                    @if ($review->comment)
                                        <p class="text-gray-700">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

@if ($order->status == 'pending')
    @push('addon-script')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const payButton = document.getElementById('pay-button');

                payButton.addEventListener('click', function() {
                    // Nonaktifkan tombol untuk mencegah banyak klik
                    payButton.disabled = true;
                    payButton.innerHTML = 'Memproses...';

                    // Buka popup pembayaran Snap
                    snap.pay('{{ $snapToken }}', {
                        // Callback opsional
                        onSuccess: function(result) {
                            /* Pembayaran berhasil, alihkan ke halaman sukses */
                            window.location.href =
                                '{{ route('checkout.success', ['order_id' => $order->id]) }}';
                        },
                        onPending: function(result) {
                            /* Pembayaran tertunda, alihkan ke halaman pending */
                            window.location.href =
                                '{{ route('checkout.pending', ['order_id' => $order->id]) }}';
                        },
                        onError: function(result) {
                            /* Kesalahan pembayaran, alihkan ke halaman gagal */
                            window.location.href =
                                '{{ route('checkout.failed', ['order_id' => $order->id]) }}';
                        },
                        onClose: function() {
                            /* Pelanggan menutup popup tanpa menyelesaikan pembayaran */
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang';
                            alert('Pembayaran dibatalkan. Silakan coba lagi.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endif

@if ($order->status == 'success')
    @push('addon-style')
        <style>
            .rating-input:checked~.rating-star {
                color: #facc15;
                /* Warna kuning Tailwind yellow-400 */
            }
        </style>
    @endpush
    @push('addon-script')
        <script>
            document.querySelectorAll('.rating-input').forEach(input => {
                input.addEventListener('change', function() {
                    const stars = this.closest('div').querySelectorAll('.rating-star');
                    const value = parseInt(this.value);

                    stars.forEach((star, index) => {
                        star.classList.toggle('text-yellow-400', index < value);
                        star.classList.toggle('text-gray-300', index >= value);
                    });
                });
            });
        </script>
    @endpush
@endif
