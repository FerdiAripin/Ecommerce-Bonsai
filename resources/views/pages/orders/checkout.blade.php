@extends('layouts.app')

@section('content')
    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl md:text-4xl font-bold mb-5">Pembayaran</h2>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Informasi Pengiriman -->
                    <div>
                        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                            <h3 class="text-xl font-semibold mb-4">Informasi Pengiriman</h3>

                            <div class="mb-4">
                                <label for="recipient_name" class="block text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="recipient_name" name="recipient_name"
                                    value="{{ old('recipient_name', auth()->user()->name) }}" class="input">
                                @error('recipient_name')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phone_number" class="block text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" class="input">
                                @error('phone_number')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="province_id" class="block text-gray-700 mb-2">Provinsi</label>
                                <select id="province_id" name="province_id" class="input">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" id="province_name" name="province" value="{{ old('province') }}">
                                @error('province')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="city_id" class="block text-gray-700 mb-2">Kota/Kabupaten</label>
                                <select id="city_id" name="city_id" disabled class="input">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                <input type="hidden" id="city_name" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="postal_code" class="block text-gray-700 mb-2">Kode Pos</label>
                                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                    class="input">
                                @error('postal_code')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="shipping_address" class="block text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea id="shipping_address" name="shipping_address" rows="3" class="input">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="block text-gray-700 mb-2">Catatan Pesanan (Opsional)</label>
                                <textarea id="notes" name="notes" rows="2" class="input">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold mb-4">Metode Pengiriman</h3>

                            <div class="mb-4">
                                <label for="courier" class="block text-gray-700 mb-2">Pilih Kurir</label>
                                <select id="courier" name="courier" class="input">
                                    <option value="">Pilih Kurir</option>
                                    @foreach ($shippingMethods as $method)
                                        <option value="{{ $method['code'] }}"
                                            {{ old('courier') == $method['code'] ? 'selected' : '' }}>
                                            {{ $method['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('courier')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="courier_service" class="block text-gray-700 mb-2">Layanan Pengiriman</label>
                                <select id="courier_service" name="courier_service" disabled class="input">
                                    <option value="">Pilih Layanan</option>
                                </select>
                                @error('courier_service')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="shipping_cost" class="block text-gray-700 mb-2">Biaya Pengiriman (Rp)</label>
                                <input type="number" id="shipping_cost" name="shipping_cost" readonly
                                    value="{{ old('shipping_cost', 0) }}"
                                    class="w-full p-3 border border-gray-300 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-600">
                                @error('shipping_cost')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Pesanan -->
                    <div>
                        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                            <h3 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h3>

                            <div class="border-b pb-4 mb-4">
                                @foreach ($cart->details as $item)
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <span class="font-medium">{{ $item->product->name }}</span>
                                            <span class="text-gray-500"> × {{ $item->quantity }}</span>
                                        </div>
                                        <span>Rp. {{ number_format($item->product->price * $item->quantity) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-b pb-4 mb-4">
                                <div class="flex justify-between mb-2">
                                    <span>Subtotal</span>
                                    <span>Rp. {{ number_format($cart->total_amount) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ongkir</span>
                                    <span id="display-shipping">Rp. 0</span>
                                </div>
                            </div>

                            <div class="flex justify-between font-semibold text-lg">
                                <span>Total</span>
                                <span id="display-total">Rp. {{ number_format($cart->total_amount) }}</span>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold mb-4 text-center">Konfirmasi Pembayaran</h3>

                            <div class="mb-4">
                                <label for="payment_method" class="block text-gray-700 mb-2">Konfirmasi Pembayaran dengan Midtrans</label>
                                <select id="payment_method" name="payment_method" class="input">
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method['code'] }}"
                                            {{ old('payment_method') == $method['code'] ? 'selected' : '' }}>
                                            {{ $method['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-4 btn-primary">
                                Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const provinceNameInput = document.getElementById('province_name');
            const cityNameInput = document.getElementById('city_name');
            const courierSelect = document.getElementById('courier');
            const serviceSelect = document.getElementById('courier_service');
            const shippingCostInput = document.getElementById('shipping_cost');
            const postalCodeInput = document.getElementById('postal_code');

            // Config -
            const config = {
                originCityId: 469, // ID kota asal pengiriman
                defaultWeight: 1000 // Berat default dalam gram (1kg) 
            };

            // Function to load provinces
            function loadProvinces() {
                fetch('/rajaongkir/provinces')
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat provinsi');
                        return response.json();
                    })
                    .then(data => {
                        if (data.rajaongkir && data.rajaongkir.results) {
                            provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                            data.rajaongkir.results.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.province_id;
                                option.textContent = province.province;
                                option.dataset.provinceName = province.province;
                                provinceSelect.appendChild(option);
                            });

                            // Set selected province if there's old input
                            const oldProvinceId = "{{ old('province_id') }}";
                            if (oldProvinceId) {
                                provinceSelect.value = oldProvinceId;
                                const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
                                provinceNameInput.value = selectedOption.dataset.provinceName;
                                loadCities(oldProvinceId);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading provinces:', error);
                        alert('Gagal memuat data provinsi. Silakan refresh halaman.');
                    });
            }

            // Function to load cities
            function loadCities(provinceId) {
                if (!provinceId) {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    citySelect.disabled = true;
                    cityNameInput.value = '';
                    resetShipping();
                    return;
                }

                fetch(`/rajaongkir/cities?province_id=${provinceId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat kota');
                        return response.json();
                    })
                    .then(data => {
                        if (data.rajaongkir && data.rajaongkir.results) {
                            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                            data.rajaongkir.results.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.city_id;
                                option.textContent = `${city.type} ${city.city_name}`;
                                option.dataset.cityName = `${city.type} ${city.city_name}`;
                                citySelect.appendChild(option);
                            });
                            citySelect.disabled = false;

                            // Set selected city if there's old input
                            const oldCityId = "{{ old('city_id') }}";
                            if (oldCityId) {
                                citySelect.value = oldCityId;
                                const selectedOption = citySelect.options[citySelect.selectedIndex];
                                cityNameInput.value = selectedOption.dataset.cityName;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                        alert('Gagal memuat data kota. Silakan coba lagi.');
                        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        citySelect.disabled = true;
                        resetShipping();
                    });
            }

            // Function to calculate shipping cost
            function calculateShipping() {
                const cityId = citySelect.value;
                const courier = courierSelect.value;

                // Pastikan semua data yang diperlukan tersedia
                if (!cityId || !courier) {
                    resetShipping();
                    return;
                }

                // Hitung berat masih menggunakan default
                const weight = 5000; // dalam gram

                // Validasi berat minimal 1kg (1000 gram)
                const validWeight = Math.max(weight, 1000);

                // Tampilkan loading state
                serviceSelect.innerHTML = '<option value="">Memuat layanan...</option>';
                serviceSelect.disabled = true;

                fetch('/rajaongkir/cost', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            origin: config.originCityId,
                            destination: cityId,
                            weight: validWeight,
                            courier: courier
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results.length > 0) {
                            const services = data.rajaongkir.results[0].costs;

                            if (services && services.length > 0) {
                                serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';

                                services.forEach(service => {
                                    if (service.cost && service.cost.length > 0) {
                                        const option = document.createElement('option');
                                        option.value = service.service;
                                        option.textContent =
                                            `${service.service} - ${service.description} (${service.cost[0].etd} hari) - Rp ${service.cost[0].value.toLocaleString('id-ID')}`;
                                        option.dataset.cost = service.cost[0].value;
                                        serviceSelect.appendChild(option);
                                    }
                                });

                                serviceSelect.disabled = false;

                                // Set selected service if there's old input
                                const oldService = "{{ old('courier_service') }}";
                                if (oldService) {
                                    serviceSelect.value = oldService;
                                    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                                    if (selectedOption && selectedOption.dataset.cost) {
                                        shippingCostInput.value = selectedOption.dataset.cost;
                                        updateTotal();
                                    }
                                }
                            } else {
                                serviceSelect.innerHTML =
                                    '<option value="">Tidak ada layanan tersedia</option>';
                                resetShipping();
                            }
                        } else {
                            serviceSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
                            resetShipping();
                        }
                    })
                    .catch(error => {
                        console.error('Error calculating shipping:', error);
                        serviceSelect.innerHTML = '<option value="">Error memuat layanan</option>';
                        resetShipping();
                        alert('Gagal memuat layanan pengiriman. Silakan coba lagi.');
                    });
            }

            // Function to reset shipping options
            function resetShipping() {
                serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                serviceSelect.disabled = true;
                shippingCostInput.value = 0;
                updateTotal();
            }

            // Function to update total display
            function updateTotal() {
                const subtotal = {{ $cart->total_amount }};
                const shippingCost = parseInt(shippingCostInput.value) || 0;
                const total = subtotal + shippingCost;

                document.getElementById('display-shipping').textContent = 'Rp ' + shippingCost.toLocaleString(
                    'id-ID');
                document.getElementById('display-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            // Event listeners
            provinceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                provinceNameInput.value = selectedOption ? selectedOption.dataset.provinceName : '';
                loadCities(this.value);
                resetShipping();
            });

            citySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                cityNameInput.value = selectedOption ? selectedOption.dataset.cityName : '';
                calculateShipping();
            });

            courierSelect.addEventListener('change', function() {
                calculateShipping();
            });

            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.cost) {
                    shippingCostInput.value = selectedOption.dataset.cost;
                    updateTotal();
                } else {
                    resetShipping();
                }
            });

            // Initialize
            loadProvinces();
            updateTotal();
        });
    </script>
@endsection
