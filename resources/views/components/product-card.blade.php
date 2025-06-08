<a href="{{ route('home.products.detail', $product->id) }}">
    <img src="{{ asset('storage/' . $product->image) }}" class="h-[450px] w-full object-cover" alt="">
    <div class="py-5">
        <h4 class="text-base md:text-lg mb-2">{{ $product->name }}</h4>
        <p class="text-sm text-gray-500 mb-1">{{ $product->categories->categories_name }}</p>
        <p class="text-sm">Rp. {{ number_format($product->price) }}</p>
        <div class="flex items-center mb-2">
            @for ($i = 1; $i <= 5; $i++)
                <span
                    class="text-lg {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
            @endfor
            <span class="ml-2 text-gray-600 text-sm">({{ $product->total_reviews }})</span>
        </div>
    </div>
</a>
