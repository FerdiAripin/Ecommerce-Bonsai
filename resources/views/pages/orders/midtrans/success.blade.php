@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-10 md:py-24">
        <div class="flex justify-center">
            <div class="w-full max-w-4xl">
                <div class="bg-white shadow-md rounded-lg border border-primary overflow-hidden">
                    <div class="bg-primary text-white px-6 py-4">
                        <h4 class="text-xl font-semibold mb-0">Pembayaran Berhasil!</h4>
                    </div>
                    <div class="p-6 md:p-10 text-center">
                        <div class="my-6">
                            <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                        </div>

                        <h5 class="text-xl font-medium mb-2">Terima kasih atas pesanan Anda!</h5>
                        <p class="mb-6 text-gray-600">Pembayaran untuk Pesanan #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} telah berhasil diproses.
                        </p>

                        <div class="order-details my-6 text-left bg-gray-50 p-4 rounded-lg">
                            <h5 class="text-lg font-medium mb-3">Ringkasan Pesanan</h5>
                            <hr class="border-gray-200 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><strong>ID Pesanan:</strong> #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mb-2"><strong>Tanggal Pesanan:</strong>
                                        {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p class="mb-2"><strong>Metode Pembayaran:</strong>
                                        {{ ucfirst($order->payment->payment_type ?? $order->payment_method) }}</p>
                                    <p class="mb-2"><strong>Total Pembayaran:</strong> Rp
                                        {{ number_format($order->grand_total) }}</p>
                                </div>
                                <div>
                                    <p class="mb-2"><strong>Status:</strong> <span
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            Dibayar
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                                    <p class="mb-2"><strong>Telepon:</strong> {{ $order->phone_number }}</p>
                                    <p class="mb-2"><strong>Pengiriman:</strong> {{ $order->courier }}
                                        ({{ $order->courier_service }})</p>
                                </div>
                            </div>
                        </div>

                        <p class="mt-6 text-gray-600">Kami sedang memproses pesanan Anda. Anda akan menerima notifikasi
                            ketika pesanan dikirim.</p>

                        <div class="mt-6 space-x-3">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-primary">
                                Lihat Detail Pesanan
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn-primary">Daftar
                                Pesanan Saya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
