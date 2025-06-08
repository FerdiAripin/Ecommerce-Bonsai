@extends('layouts.app')

@section('title', 'Pembayaran Tertunda')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-12">
        <div class="flex justify-center">
            <div class="w-full max-w-4xl">
                <div class="bg-white shadow-md rounded-lg border border-yellow-400 overflow-hidden">
                    <div class="bg-yellow-400 text-gray-800 px-6 py-4">
                        <h4 class="text-xl font-semibold mb-0">Pembayaran Tertunda</h4>
                    </div>
                    <div class="p-6 text-center">
                        <div class="my-6">
                            <i class="fas fa-clock text-yellow-500 text-6xl"></i>
                        </div>

                        <h5 class="text-xl font-medium mb-2">Kami menunggu pembayaran Anda</h5>
                        <p class="mb-6 text-gray-600">Pesanan #{{ $order->id }} Anda telah dibuat, tetapi pembayaran
                            belum
                            selesai.</p>

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
                                            class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Tertunda</span>
                                    </p>
                                    <p class="mb-2"><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                                    <p class="mb-2"><strong>Telepon:</strong> {{ $order->phone_number }}</p>
                                    <p class="mb-2"><strong>Pengiriman:</strong> {{ $order->courier }}
                                        ({{ $order->courier_service }})</p>
                                </div>
                            </div>
                        </div>

                        @if ($order->payment)
                            <div class="payment-instructions mb-6 text-left bg-gray-50 p-4 rounded-lg">
                                <h5 class="text-lg font-medium mb-3">Instruksi Pembayaran</h5>
                                <hr class="border-gray-200 mb-4">

                                @if ($order->payment->payment_type == 'bank_transfer')
                                    <div class="bg-blue-50 text-blue-800 p-4 rounded mb-4">
                                        <p class="font-medium mb-2">Detail Rekening Bank:</p>
                                        <p>Bank: {{ strtoupper($order->payment->bank) }}</p>
                                        <p>Nomor Virtual Account: <strong
                                                class="text-lg">{{ $order->payment->va_number }}</strong></p>
                                        <p>Transfer dengan jumlah tepat Rp {{ number_format($order->grand_total) }}</p>
                                    </div>
                                    <p class="text-gray-600">Silakan selesaikan transfer bank ke nomor virtual account di
                                        atas.
                                        Pesanan Anda akan diproses setelah pembayaran diterima.</p>
                                @elseif($order->payment->payment_type == 'qris' || $order->payment->payment_type == 'gopay')
                                    <div class="text-center mb-4">
                                        @if ($order->payment->qr_code)
                                            <img src="{{ $order->payment->qr_code }}" alt="Kode QR Pembayaran"
                                                class="mx-auto max-w-xs">
                                            <p class="mt-3 text-gray-600">Pindai kode QR untuk menyelesaikan pembayaran</p>
                                        @else
                                            <div class="bg-blue-50 text-blue-800 p-4 rounded">
                                                <p>Silakan selesaikan pembayaran dengan
                                                    {{ $order->payment->payment_type == 'qris' ? 'Aplikasi QRIS' : 'Aplikasi GoPay' }}
                                                    Anda
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-blue-50 text-blue-800 p-4 rounded">
                                        <p>Silakan ikuti instruksi pembayaran yang disediakan oleh Midtrans.</p>
                                        <p>Jika Anda menutup halaman pembayaran, Anda dapat mencoba lagi di bawah ini.</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="payment-status mb-6 text-center">
                            <h6 class="text-gray-700 mb-2">Memeriksa status pembayaran...</h6>
                            <div
                                class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin spinner-payment-check">
                            </div>
                            <div class="payment-status-message mt-3"></div>
                        </div>

                        <div class="mt-6 space-x-3">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-primary">
                                Coba Bayar Lagi
                            </a>
                            <a href="{{ route('orders.show', $order->id) }}" class="text-primary">
                                Lihat Detail Pesanan
                            </a>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-500">
                                Mengalami masalah dengan pembayaran? <a href="#"
                                    class="text-blue-600 hover:underline">Hubungi
                                    dukungan kami</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Periksa status pembayaran setiap 5 detik
            const checkPaymentStatus = () => {
                fetch('{{ route('orders.payment-status', $order->id) }}')
                    .then(response => response.json())
                    .then(data => {
                        const statusMessage = document.querySelector('.payment-status-message');
                        const spinner = document.querySelector('.spinner-payment-check');

                        // Jika pembayaran berhasil, arahkan ke halaman sukses
                        if (data.payment_status === 'paid') {
                            statusMessage.innerHTML =
                                '<div class="bg-green-100 text-green-800 p-3 rounded">Pembayaran diterima! Mengarahkan ke halaman sukses...</div>';
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('checkout.success', ['order_id' => $order->id]) }}';
                            }, 2000);
                            return;
                        }

                        // Jika pembayaran gagal, tampilkan pesan
                        if (data.payment_status === 'failed' || data.transaction_status === 'deny' ||
                            data.transaction_status === 'cancel' || data.transaction_status === 'expire') {
                            statusMessage.innerHTML =
                                '<div class="bg-red-100 text-red-800 p-3 rounded">Pembayaran gagal atau kadaluarsa. Silakan coba lagi.</div>';
                            spinner.style.display = 'none';
                            return;
                        }

                        // Masih tertunda, lanjutkan pemeriksaan
                        setTimeout(checkPaymentStatus, 5000);
                    })
                    .catch(error => {
                        console.error('Error memeriksa status pembayaran:', error);
                        setTimeout(checkPaymentStatus, 5000);
                    });
            };

            // Mulai memeriksa status pembayaran
            checkPaymentStatus();
        });
    </script>
@endsection
