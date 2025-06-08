@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-24">
        <div class="flex justify-center">
            <div class="w-full max-w-4xl">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="bg-primary text-white px-6 py-4">
                        <h4 class="text-xl font-semibold mb-0">Detail Pembayaran</h4>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <h5 class="text-lg font-medium">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h5>
                            <p class="text-gray-500 mt-1">Silakan selesaikan pembayaran Anda untuk melanjutkan</p>
                        </div>

                        <div class="order-summary mb-6 bg-gray-50 p-4 rounded-lg">
                            <h5 class="text-lg font-medium mb-3">Ringkasan Pesanan</h5>
                            <hr class="border-gray-200 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><strong>Total Item:</strong> {{ $order->details->sum('quantity') }}
                                    </p>
                                    <p class="mb-2"><strong>Subtotal:</strong> Rp
                                        {{ number_format($order->total_amount) }}</p>
                                    <p class="mb-2"><strong>Biaya Pengiriman:</strong> Rp
                                        {{ number_format($order->shipping_cost) }}</p>
                                    <p class="mb-2"><strong>Total Keseluruhan:</strong> Rp
                                        {{ number_format($order->grand_total) }}</p>
                                </div>
                                <div>
                                    <p class="mb-2"><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                                    <p class="mb-2"><strong>Telepon:</strong> {{ $order->phone_number }}</p>
                                    <p class="mb-2"><strong>Pengiriman:</strong> {{ $order->courier }}
                                        ({{ $order->courier_service }})</p>
                                    <p class="mb-2"><strong>Alamat:</strong> {{ $order->shipping_address }},
                                        {{ $order->city }},
                                        {{ $order->province }} {{ $order->postal_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button id="pay-button" class="btn-primary">
                                Bayar Sekarang
                            </button>
                            <p class="mt-3 text-sm text-gray-500">Anda akan diarahkan ke halaman pembayaran aman Midtrans
                            </p>
                        </div>

                        <div class="mt-6 text-center text-sm">
                            <p class="text-gray-600">Mengalami masalah?
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="text-blue-600 hover:text-blue-900 underline">Lihat
                                    detail pesanan</a> atau
                                <a href="#" class="text-blue-600 hover:underline">hubungi dukungan</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');

            payButton.addEventListener('click', function() {
                // Nonaktifkan tombol untuk mencegah klik berulang
                payButton.disabled = true;
                payButton.innerHTML = 'Memproses...';


                // Buka popup pembayaran Snap
                snap.pay('{{ $snapToken }}', {
                    // Callback opsional
                    onSuccess: function(result) {
                        /* Pembayaran berhasil, arahkan ke halaman sukses */
                        window.location.href =
                            '{{ route('checkout.success', ['order_id' => $order->id]) }}';
                    },
                    onPending: function(result) {
                        /* Pembayaran tertunda, arahkan ke halaman tertunda */
                        window.location.href =
                            '{{ route('checkout.pending', ['order_id' => $order->id]) }}';
                    },
                    onError: function(result) {
                        /* Pembayaran gagal, arahkan ke halaman gagal */
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
