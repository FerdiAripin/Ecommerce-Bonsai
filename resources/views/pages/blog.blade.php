@extends('layouts.app')

@section('content')
    <header class="bg-cover" style="background-image: url({{ asset('img/tropical.jpg') }})">
        <div class="h-[400px] flex flex-col items-center justify-center bg-black bg-opacity-40" style="z-index: 1">
            <h1 class="text-7xl font-medium text-center text-white md:w-[60%] mx-auto mb-10">
                Inspirasi Alam
            </h1>
        </div>
    </header>

    <div class="py-10 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            @if ($blogs->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Belum ada artikel yang tersedia saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($blogs as $blog)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            @if ($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <span>Ditulis Oleh {{ $blog->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                </div>

                                <h2 class="text-xl font-semibold text-gray-800 mb-2 hover:text-green-700 transition-colors">
                                    <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                                </h2>

                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit(strip_tags($blog->description), 120, '...') }}
                                </p>

                                <a href="{{ route('blogs.show', $blog->slug) }}"
                                    class="inline-flex items-center text-green-700 hover:text-green-600 font-medium transition-colors">
                                    Selengkapnya
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


@endsection

@push('addon-style')
    <style>
        .prose {
            line-height: 1.75;
        }

        .prose p {
            margin-bottom: 1.25em;
        }
    </style>
@endpush
