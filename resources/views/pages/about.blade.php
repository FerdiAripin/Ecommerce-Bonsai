@extends('layouts.app')

@section('content')
    <header class="bg-cover relative" style="background-image: url({{ asset('img/about-bg.jpg') }})">
        <div class="h-[400px] flex flex-col items-center justify-center bg-black bg-opacity-40" style="z-index: 1">
            <h1 class="text-7xl font-medium text-center text-white md:w-[60%] mx-auto mb-10 drop-shadow-lg">
                Tentang Kami
            </h1>
            <img src="{{ asset('img/bonsai-icon.png') }}" alt="Bonsai Icon"
                class="w-20 absolute bottom-6 right-6 opacity-70" />
        </div>
    </header>

    <div class="py-10 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 space-y-20">
            <section class="max-w-6xl mx-auto py-16 px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center text-justify">
                    <!-- Foto tumpang tindih -->
                    <div class="relative w-full h-96">
                        <img src="https://jatimnow.com/po-content/uploads/202302/img_20230215_112805.jpg" alt="Bonsai 1"
                            class="w-80 h-64 object-cover rounded-xl shadow-xl absolute top-0 left-0 z-20">
                        <img src="https://assetd.kompas.id/J5fgWu9O6Ila3xWlOfnFNkPaEtM=/1024x576/filters:watermark(https://cdn-content.kompas.id/umum/kompas_main_logo.png,-16p,-13p,0)/https%3A%2F%2Fkompas.id%2Fwp-content%2Fuploads%2F2021%2F02%2F20210221_120303_1613982451.jpg" alt="Bonsai 2"
                            class="w-90 h-64 object-cover rounded-xl shadow-xl absolute top-40 left-20 z-10">
                    </div>

                    <!-- Profil Perusahaan -->
                    <div>
                        <h2 class="text-4xl font-bold text-green-800 mb-4">Profil Ruang Bonsai</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Ruang Bonsai adalah sebuah usaha yang bergerak dalam bidang jual beli, edukasi, serta perawatan
                            tanaman bonsai. Usaha ini resmi berdiri sejak tahun 2019 dan berawal dari hobi pribadi
                            pendirinya yang mencintai seni merawat dan membentuk bonsai.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Dengan semangat untuk mengenalkan bonsai kepada masyarakat luas, Ruang Bonsai hadir sebagai
                            ruang apresiasi dan edukasi, serta ingin menjadikan bonsai sebagai bagian dari gaya hidup dan
                            seni yang menyatu dalam keseharian.
                        </p>
                    </div>
                </div>
            </section>
            <!-- Sejarah Timeline -->
            <section class="max-w-4xl mx-auto text-left relative">
                <h2 class="text-4xl font-semibold mb-12 text-green-800 text-center">Sejarah Ruang Bonsai</h2>
                <div class="relative">
                    <!-- Garis vertikal timeline -->
                    <div class="absolute left-4 top-0 bottom-0 w-1 bg-green-300"></div>

                    <!-- 2019 -->
                    <div class="flex items-start mb-14 relative">
                        <div class="flex flex-col items-center mr-6">
                            <div
                                class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                                <i class='bx bx-leaf text-xl'></i>
                            </div>
                            <div class="w-1 bg-green-300 flex-1"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-2 text-green-700">2019 - Awal Hobi</h3>
                            <p class="text-gray-700 max-w-xl">
                                Ruang Bonsai lahir dari kecintaan pendiri terhadap seni merawat dan membentuk bonsai yang
                                awalnya hanya sebagai hobi di waktu luang.
                            </p>
                        </div>
                    </div>

                    <!-- 2020 -->
                    <div class="flex items-start mb-14 relative">
                        <div class="flex flex-col items-center mr-6">
                            <div
                                class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                                <i class='bx bxs-store-alt text-xl'></i>
                            </div>
                            <div class="w-1 bg-green-300 flex-1"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-2 text-green-700">2020 - Usaha Jual Beli</h3>
                            <p class="text-gray-700 max-w-xl">
                                Kegiatan jual beli bonsai dilakukan secara kecil-kecilan dengan komunitas lokal yang
                                antusias terhadap tanaman hias ini.
                            </p>
                        </div>
                    </div>

                    <!-- 2021 -->
                    <div class="flex items-start mb-14 relative">
                        <div class="flex flex-col items-center mr-6">
                            <div
                                class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                                <i class='bx bx-briefcase-alt-2 text-xl'></i>
                            </div>
                            <div class="w-1 bg-green-300 flex-1"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-2 text-green-700">2021 - Usaha Terstruktur</h3>
                            <p class="text-gray-700 max-w-xl">
                                Berdasarkan antusiasme komunitas dan kecintaan yang mendalam, Ruang Bonsai resmi dibentuk
                                dan dikelola secara serius dan terstruktur.
                            </p>
                        </div>
                    </div>

                    <!-- 2022 -->
                    <div class="flex items-start mb-14 relative">
                        <div class="flex flex-col items-center mr-6">
                            <div
                                class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                                <i class='bx bx-book-reader text-xl'></i>
                            </div>
                            <div class="w-1 bg-green-300 flex-1"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-2 text-green-700">2022 - Edukasi dan Workshop</h3>
                            <p class="text-gray-700 max-w-xl">
                                Ruang Bonsai mulai mengadakan workshop dan sesi edukasi rutin untuk komunitas pecinta bonsai
                                agar seni ini bisa diwariskan dan dipelajari dengan benar.
                            </p>
                        </div>
                    </div>

                    <!-- 2023 -->
                    <div class="flex items-start mb-14 relative">
                        <div class="flex flex-col items-center mr-6">
                            <div
                                class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                                <i class='bx bx-globe text-xl'></i>
                            </div>
                            <div class="w-1 bg-green-300 flex-1"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-2 text-green-700">2023 - Ekspansi Digital</h3>
                            <p class="text-gray-700 max-w-xl">
                                Melalui platform digital dan media sosial, Ruang Bonsai menjangkau lebih banyak pecinta
                                tanaman di berbagai daerah, serta mempermudah akses pembelian dan konsultasi secara online.
                            </p>
                        </div>
                    </div>

                    <!-- 2024 -->
                    <div class="flex items-start mb-14 relative">
                        <div class="flex flex-col items-center mr-6">
                            <div
                                class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                                <i class='bx bx-wrench text-xl'></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-2 text-green-700">2024 - Layanan Perawatan Bonsai</h3>
                            <p class="text-gray-700 max-w-xl">
                                Ruang Bonsai memperluas layanannya dengan membuka Layanan Perawatan Bonsai. Layanan ini
                                ditujukan bagi para pemilik bonsai yang ingin memastikan tanamannya tetap sehat dan terawat.
                            </p>
                        </div>
                    </div>


            </section>

            <!-- Visi dan Misi -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                <div class="bg-[#ecf4d3] p-8 rounded-lg shadow-md">
                    <h3
                        class="text-3xl font-semibold text-green-700 mb-4 border-b-2 border-green-400 inline-block pb-2 flex items-center gap-3">
                        <i class='bx bx-target-lock text-green-700 text-4xl'></i> Visi
                    </h3>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Menjadi pusat komunitas bonsai terkemuka yang menginspirasi dan memfasilitasi pecinta bonsai dari
                        semua kalangan untuk menikmati dan melestarikan seni bonsai sebagai bagian dari gaya hidup hijau dan
                        budaya yang berkelanjutan.
                    </p>
                </div>
                <div class="bg-[#ecf4d3] p-8 rounded-lg shadow-md">
                    <h3
                        class="text-3xl font-semibold text-green-700 mb-4 border-b-2 border-green-400 inline-block pb-2 flex items-center gap-3">
                        <i class='bx bx-rocket text-green-700 text-4xl'></i> Misi
                    </h3>
                    <ul class="list-disc list-inside text-gray-700 text-lg leading-relaxed space-y-3">
                        <li>Menyediakan tanaman bonsai berkualitas dan perawatan terbaik untuk pelanggan.</li>
                        <li>Mengedukasi masyarakat tentang seni dan teknik merawat bonsai melalui workshop dan konsultasi.
                        </li>
                        <li>Membangun komunitas pecinta bonsai yang aktif dan saling mendukung.</li>
                        <li>Memperkenalkan bonsai sebagai bentuk seni dan gaya hidup yang ramah lingkungan dan estetis.</li>
                    </ul>
                </div>
            </section>

            <!-- Nilai-Nilai Perusahaan -->
            <section class="bg-[#ecf4d3] p-12 rounded-lg max-w-6xl mx-auto text-center">
                <h2 class="text-center text-3xl font-semibold text-green-900 mb-10">
                    Komitmen Kami
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                    <article>
                        <div class="bg-green-100 p-5 flex items-center justify-center rounded-full w-max h-max mx-auto">
                            <i class='bx bx-leaf text-4xl text-green-700'></i>
                        </div>
                        <p class="text-center text-xl font-semibold mt-5 mb-1 text-green-900">Keberlanjutan</p>
                        <p class="text-center text-green-700 text-sm">Kami berkomitmen untuk mendukung praktik ramah
                            lingkungan dalam setiap aspek bisnis kami.</p>
                    </article>
                    <article>
                        <div class="bg-green-100 p-5 flex items-center justify-center rounded-full w-max h-max mx-auto">
                            <i class='bx bxs-book text-4xl text-green-700'></i>
                        </div>
                        <p class="text-center text-xl font-semibold mt-5 mb-1 text-green-900">Edukasi</p>
                        <p class="text-center text-green-700 text-sm">Menyebarkan pengetahuan dan kecintaan terhadap bonsai
                            melalui edukasi yang mudah dipahami.</p>
                    </article>
                    <article>
                        <div class="bg-green-100 p-5 flex items-center justify-center rounded-full w-max h-max mx-auto">
                            <i class='bx bxs-heart text-4xl text-green-700'></i>
                        </div>
                        <p class="text-center text-xl font-semibold mt-5 mb-1 text-green-900">Passion</p>
                        <p class="text-center text-green-700 text-sm">Kami menjalankan usaha ini dengan cinta dan dedikasi
                            terhadap seni bonsai.</p>
                    </article>
                    <article>
                        <div class="bg-green-100 p-5 flex items-center justify-center rounded-full w-max h-max mx-auto">
                            <i class='bx bxs-user-voice text-4xl text-green-700'></i>
                        </div>
                        <p class="text-center text-xl font-semibold mt-5 mb-1 text-green-900">Komunitas</p>
                        <p class="text-center text-green-700 text-sm">Membangun komunitas yang saling mendukung dan berbagi
                            pengalaman bonsai.</p>
                    </article>
                </div>
            </section>

        </div>
    </div>
@endsection
