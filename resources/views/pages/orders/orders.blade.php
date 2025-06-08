@extends('layouts.app')

@section('content')
    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl md:text-4xl font-bold mb-5">Pesanan Saya</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($orders->count() > 0)
                <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Pesanan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'in_shipping') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'delivered') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'success') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                            @if ($order->status == 'pending')
                                                Menunggu
                                            @elseif($order->status == 'in_shipping')
                                                Di Proses
                                            @elseif($order->status == 'delivered')
                                                Dalam Pengiriman
                                            @elseif($order->status == 'success')
                                                Selesai
                                            @elseif($order->status == 'cancelled')
                                                Dibatalkan
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp. {{ number_format($order->grand_total) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="text-green-600 hover:text-green-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg p-6 text-center">
                    <p class="text-gray-600 mb-4">Anda belum memiliki pesanan.</p>
                    <a href="{{ route('home.products') }}"
                        class="inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition rounded-lg">Mulai
                        Berbelanja</a>
                </div>
            @endif
        </div>
    </section>
@endsection
