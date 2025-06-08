@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-br from-emerald-50 to-emerald-100 py-16 md:py-24">
        <div class="absolute inset-0 bg-white bg-opacity-50"></div>
        <div class="relative max-w-4xl mx-auto px-6 text-center">
            {{-- <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Artikel Terbaru
            </div> --}}
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $blog->title }}
            </h1>
            <div class="flex items-center justify-center text-gray-600 space-x-4">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                        {{ substr($blog->user->name, 0, 1) }}
                    </div>
                    <span class="font-medium">{{ $blog->user->name }}</span>
                </div>
                <span class="text-gray-400">•</span>
                <span>{{ $blog->created_at->format('M d, Y') }}</span>
                <span class="text-gray-400">•</span>
                <span>{{ $blog->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="py-12 md:py-16">
        <div class="max-w-4xl mx-auto px-6">
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                {{-- Featured Image --}}
                @if ($blog->image)
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                            class="w-full h-64 md:h-96 object-cover transition-transform duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                @else
                    <div
                        class="w-full h-64 md:h-96 bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-emerald-500 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-emerald-600 font-medium">Gambar Artikel</span>
                        </div>
                    </div>
                @endif

                {{-- Article Content --}}
                <div class="p-8 md:p-12">
                    {{-- Article Meta --}}
                    <div class="flex flex-wrap items-center gap-4 mb-8 pb-6 border-b border-gray-100">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ $blog->user->name }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $blog->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $blog->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Article Body --}}
                    <div class="prose prose-lg max-w-none text-gray-700">
                        {!! $blog->description !!}
                    </div>

                    {{-- Social Share --}}
                    <!-- Bagian Share -->
                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <h3 class="text-lg font-semibold text-gray-900">Bagikan Artikel</h3>
                            <div class="flex space-x-3 flex-wrap gap-2">
                                <button onclick="shareToTwitter()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                    Twitter/X
                                </button>
                                <button onclick="shareToFacebook()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    Facebook
                                </button>
                                <button onclick="shareToWhatsApp()"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.085" />
                                    </svg>
                                    WhatsApp
                                </button>
                                <button onclick="shareToTelegram()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                    </svg>
                                    Telegram
                                </button>
                                <button onclick="copyLink()"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Web Share API (untuk perangkat mobile) -->
                    <div class="mt-4 pt-4 border-t border-gray-100" id="nativeShare" style="display: none;">
                        <button onclick="nativeShare()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                            </svg>
                            Bagikan
                        </button>
                    </div>

                    <!-- Toast notification -->
                    <div id="toast"
                        class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300">
                        Link berhasil disalin!
                    </div>
                </div>

                <script>
                    // Konfigurasi konten yang akan dibagikan
                    const shareData = {
                        title: document.querySelector('h1').textContent,
                        text: document.querySelector('article p').textContent,
                        url: window.location.href
                    };

                    // Fungsi untuk membagikan ke Twitter/X
                    function shareToTwitter() {
                        const text = encodeURIComponent(`${shareData.title}\n\n${shareData.text}`);
                        const url = encodeURIComponent(shareData.url);
                        window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
                    }

                    // Fungsi untuk membagikan ke Facebook
                    function shareToFacebook() {
                        const url = encodeURIComponent(shareData.url);
                        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
                    }

                    // Fungsi untuk membagikan ke WhatsApp
                    function shareToWhatsApp() {
                        const text = encodeURIComponent(`${shareData.title}\n\n${shareData.text}\n\n${shareData.url}`);
                        window.open(`https://wa.me/?text=${text}`, '_blank');
                    }

                    // Fungsi untuk membagikan ke Telegram
                    function shareToTelegram() {
                        const text = encodeURIComponent(`${shareData.title}\n\n${shareData.text}`);
                        const url = encodeURIComponent(shareData.url);
                        window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank');
                    }

                    // Fungsi untuk menyalin link
                    async function copyLink() {
                        try {
                            await navigator.clipboard.writeText(shareData.url);
                            showToast();
                        } catch (err) {
                            // Fallback untuk browser yang tidak mendukung clipboard API
                            const textArea = document.createElement('textarea');
                            textArea.value = shareData.url;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textArea);
                            showToast();
                        }
                    }

                    // Fungsi untuk menampilkan toast notification
                    function showToast() {
                        const toast = document.getElementById('toast');
                        toast.classList.remove('translate-x-full');
                        setTimeout(() => {
                            toast.classList.add('translate-x-full');
                        }, 3000);
                    }

                    // Web Share API (untuk perangkat mobile)
                    function nativeShare() {
                        if (navigator.share) {
                            navigator.share(shareData)
                                .then(() => console.log('Berhasil dibagikan'))
                                .catch((error) => console.log('Error sharing:', error));
                        }
                    }

                    // Cek apakah perangkat mendukung Web Share API
                    if (navigator.share) {
                        document.getElementById('nativeShare').style.display = 'block';
                    }

                    // Event listener untuk keyboard shortcut
                    document.addEventListener('keydown', function(e) {
                        if (e.ctrlKey && e.key === 'c' && e.shiftKey) {
                            copyLink();
                            e.preventDefault();
                        }
                    });
                </script>
            </article>
        </div>
    </div>
@endsection

@push('addon-style')
    <style>
        .prose {
            line-height: 1.8;
            color: #374151;
        }

        .prose p {
            margin-bottom: 1.5em;
        }

        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4,
        .prose h5,
        .prose h6 {
            color: #059669;
            font-weight: 600;
            margin-top: 2em;
            margin-bottom: 1em;
        }

        .prose h1 {
            font-size: 2.25em;
        }

        .prose h2 {
            font-size: 1.875em;
        }

        .prose h3 {
            font-size: 1.5em;
        }

        .prose blockquote {
            border-left: 4px solid #059669;
            background: #f0fdf4;
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            border-radius: 0 8px 8px 0;
        }

        .prose a {
            color: #059669;
            text-decoration: none;
            font-weight: 500;
        }

        .prose a:hover {
            color: #047857;
            text-decoration: underline;
        }

        .prose ul,
        .prose ol {
            margin: 1.5em 0;
            padding-left: 1.5em;
        }

        .prose li {
            margin: 0.5em 0;
        }

        .prose code {
            background: #f3f4f6;
            padding: 0.25em 0.5em;
            border-radius: 4px;
            font-size: 0.875em;
            color: #059669;
        }

        .prose pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 2rem 0;
        }

        .prose img {
            border-radius: 8px;
            margin: 2rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        .prose th,
        .prose td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
            text-align: left;
        }

        .prose th {
            background: #f9fafb;
            font-weight: 600;
            color: #059669;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Loading animation for images */
        img {
            transition: opacity 0.3s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #059669;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #047857;
        }
    </style>
@endpush
