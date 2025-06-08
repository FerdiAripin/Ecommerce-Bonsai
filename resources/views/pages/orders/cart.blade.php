@extends('layouts.app')

@section('content')
    @php
        $subtotal = 0;
    @endphp
    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl md:text-4xl font-bold mb-5">Keranjang Belanja</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($cart->details->count() > 0)
                <form action="{{ route('cart.update') }}" method="POST" id="cart-form">
                    @csrf
                    @method('PUT')
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border border-gray-200">
                                    <th class="py-3 px-4 text-left"></th>
                                    <th class="py-3 px-4 text-left"></th>
                                    <th class="py-3 px-4 text-left">Produk</th>
                                    <th class="py-3 px-4 text-left">Harga</th>
                                    <th class="py-3 px-4 text-left">Jumlah</th>
                                    <th class="py-3 px-4 text-left">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart->details as $item)
                                    @php
                                        $subtotal += $item->quantity * $item->product->price;
                                    @endphp
                                    <tr class="border border-gray-200">
                                        <td class="py-3 px-4">
                                            <a href="{{ route('cart.remove', $item->id) }}"
                                                class="text-gray-500 mr-3 border p-2 flex items-center justify-center rounded-full w-12 h-12">✕</a>
                                        </td>
                                        <td class="py-3 px-4">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-20"
                                                alt="{{ $item->product->name }}">
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-green-600">{{ $item->product->name }}</span>
                                        </td>
                                        <td class="py-3 px-4">Rp. {{ number_format($item->product->price) }}</td>
                                        <td class="py-3 px-4">
                                            <input type="number" name="items[{{ $loop->index }}][quantity]"
                                                value="{{ $item->quantity }}" min="1"
                                                class="py-3.5 px-7 border md:w-4/12 outline-none">
                                            <input type="hidden" name="items[{{ $loop->index }}][id]"
                                                value="{{ $item->id }}">
                                        </td>
                                        <td class="py-2 px-4">Rp.
                                            {{ number_format($item->product->price * $item->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border border-gray-200">
                                    <td colspan="6" class="py-3 px-4">
                                        <div class="flex justify-end">
                                            <button type="submit" class="btn-primary py-2 px-3">Perbarui Keranjang</button>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>

                <div class="mt-5">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div>&nbsp;</div>
                        <div class="border border-gray-200">
                            <div class="p-5 md:p-10 border-b border-gray-200">
                                <h4 class="text-xl md:text-4xl font-semibold text-center">Total Keranjang</h4>
                            </div>
                            <div class="p-5 md:p-10">
                                <div class="px-5 py-2 border-b border-gray-200 grid grid-cols-2">
                                    <span>Subtotal</span>
                                    <span>Rp. {{ number_format($subtotal) }}</span>
                                </div>
                                <div class="px-5 py-2 border-b border-gray-200 grid grid-cols-2">
                                    <span>Total</span>
                                    <span>Rp. {{ number_format($subtotal) }}</span>
                                </div>
                                <div class="mt-5">
                                    <a href="{{ route('checkout.index') }}"
                                        class="btn btn-primary w-full py-4 inline-block text-center">Lanjut ke
                                        Pembayaran</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    Keranjang belanja Anda kosong. <a href="{{ route('home.products') }}" class="underline">Lanjutkan
                        belanja</a>.
                </div>
            @endif
        </div>
    </section>

@endsection
