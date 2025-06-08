<!-- resources/views/pages/orders/failed.blade.php -->
@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-12">
        <div class="flex justify-center">
            <div class="w-full max-w-4xl">
                <div class="bg-white shadow-md rounded-lg border border-red-400 overflow-hidden">
                    <div class="bg-red-600 text-white px-6 py-4">
                        <h4 class="text-xl font-semibold mb-0">Pembayaran Gagal</h4>
                    </div>
                    <div class="p-6 text-center">
                        <div class="my-6">
                            <i class="fas fa-times-circle text-red-500 text-6xl"></i>
                        </div>

                        <h5 class="text-xl font-medium mb-2">Kami tidak dapat memproses pembayaran Anda</h5>
                        <p class="mb-6 text-gray-600">Maaf, terjadi masalah dengan pembayaran untuk Pesanan
                            #{{ $order->id }}.</p>

                        <div class="order-details my-6 text-left bg-gray-50 p-4 rounded-lg">
                            <h5 class="text-lg font-medium mb-3">Ringkasan Pesanan</h5>
                            <hr class="border-gray-200 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                                    <p class="mb-2"><strong>Tanggal Pesanan:</strong>
                                        {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p class="mb-2"><strong>Metode Pembayaran:</strong>
                                        {{ ucfirst($order->payment->payment_type ?? $order->payment_method) }}</p>
                                    <p class="mb-2"><strong>Total Jumlah:</strong> Rp
                                        {{ number_format($order->grand_total) }}</p>
                                </div>
                                <div>
                                    <p class="mb-2"><strong>Status:</strong> <span
                                            class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Gagal</span>
                                    </p>
                                    <p class="mb-2"><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                                    <p class="mb-2"><strong>Telepon:</strong> {{ $order->phone_number }}</p>
                                    <p class="mb-2"><strong>Pengiriman:</strong> {{ $order->courier }}
                                        ({{ $order->courier_service }})</p>
                                </div>
                            </div>
                        </div>

                        <div class="error-details mb-6 text-left bg-gray-50 p-4 rounded-lg">
                            <h5 class="text-lg font-medium mb-3">Apa yang terjadi?</h5>
                            <p class="mb-3 text-gray-600">Pembayaran tidak berhasil karena salah satu alasan berikut:</p>
                            <ul class="text-gray-600 space-y-1">
                                <li>• Saldo tidak mencukupi di akun Anda</li>
                                <li>• Kartu ditolak oleh bank</li>
                                <li>• Detail pembayaran tidak benar</li>
                                <li>• Transaksi kehabisan waktu</li>
                                <li>• Pembayaran dibatalkan</li>
                            </ul>
                        </div>

                        <div class="mt-6 space-x-3">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-primary">Lihat
                                Detail Pesanan</a>
                            <a href="{{ route('orders.cancel', $order->id) }}"
                                class="inline-block px-5 py-2.5 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition"
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batalkan
                                Pesanan</a>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-500">Mengalami masalah dengan pembayaran? <a href="#"
                                    class="text-blue-600 hover:underline">Hubungi dukungan
                                    kami</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
