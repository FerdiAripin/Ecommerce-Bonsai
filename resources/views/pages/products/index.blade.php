@extends('layouts.app')

@section('content')
    <header class="bg-cover" style="background-image: url({{ asset('img/banner2.jpg') }})">
        <div class="h-[400px] flex flex-col items-center justify-center bg-black bg-opacity-30" style="z-index: 1">
            <h1 class="text-7xl font-medium text-center text-white md:w-[60%] mx-auto mb-10">
                Produk Pilihan
            </h1>
        </div>
    </header>

    <section class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Filter Section -->
            <div class="mb-10 flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search -->
                <div class="w-full md:w-1/3">
                    <form action="{{ route('home.products') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search products..."
                                value="{{ request('search') }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm text-gray-700 transition" />
                            <button type="submit" class="absolute right-3 top-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Category and Sort Filters -->
                <div class="w-full md:w-2/3 flex flex-col md:flex-row gap-4 justify-end">
                    <!-- Category Filter -->
                    <form action="{{ route('home.products') }}" method="GET" class="w-full md:w-auto">
                        <select name="category" onchange="this.form.submit()"
                            class="w-full md:w-48 bg-white border border-gray-300 text-sm text-gray-400 py-2 px-4 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 transition">
                            <option value="all"
                                {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>
                                All Categories
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->categories_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Sort Filter -->
                    <form action="{{ route('home.products') }}" method="GET" class="w-full md:w-auto">
                        <select name="sort" onchange="this.form.submit()"
                            class="w-full md:w-48 bg-white border border-gray-300 text-sm text-gray-400 py-2 px-4 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 transition">
                            <option value="">Default Sorting</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                        </select>
                    </form>
                </div>

            </div>

            <!-- Products Grid -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <p class="text-gray-500 text-lg">No products found matching your criteria.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
