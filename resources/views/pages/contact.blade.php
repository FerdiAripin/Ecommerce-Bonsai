@extends('layouts.app')

@section('content')
    <header class="bg-cover" style="background-image: url({{ asset('img/contact-bg.jpg') }})">
        <div class="h-[400px] flex flex-col items-center justify-center bg-black bg-opacity-30" style="z-index: 1">
            <h1 class="text-7xl font-medium text-center text-white md:w-[60%] mx-auto mb-10">
                Hubungi Kami
            </h1>
        </div>
    </header>

    <div class="py-10 md:py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <h2 class="text-lg md:text-3xl font-semibold mb-10">Kirim Kami Pesan</h2>
                    <div class="flex items-center gap-5 mb-10">
                        <div
                            class="w-20 h-20 bg-[#ecf4d3] text-slate-900 flex items-center justify-center p-3 rounded-full">
                            <i class="bx bx-phone text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-medium">WhatsApp</h5>
                            <p class="text-gray-700">6285932538234</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 mb-10">
                        <div
                            class="w-20 h-20 bg-[#ecf4d3] text-slate-900 flex items-center justify-center p-3 rounded-full">
                            <i class="bx bx-envelope text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-medium">Email</h5>
                            <p class="text-gray-700">email@bonsai.com</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 mb-10">
                        <div
                            class="w-20 h-20 bg-[#ecf4d3] text-slate-900 flex items-center justify-center p-3 rounded-full">
                            <i class="bx bx-map-pin text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-medium">Alamat</h5>
                            <p class="text-gray-700">Tasikmalaya No. 127</p>
                        </div>
                    </div>
                </div>
                <div>
                    <form action="{{ route('contact.send') }}" method="post">
                        @csrf
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-success-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="input" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm mb-2">Email</label>
                            <input type="email" id="email" name="email" class="input" required>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 text-sm mb-2">No Handphone</label>
                            <input type="tel" id="phone" name="phone" class="input" required pattern="[0-9]+"
                                inputmode="numeric" title="Masukkan hanya angka">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 text-sm mb-2">Pesan</label>
                            <textarea name="message" id="message" cols="30" rows="5" class="input" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
