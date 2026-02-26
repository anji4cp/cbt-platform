<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CBT Platform - Sistem Ujian Berbasis Komputer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%232563eb'/><text x='50' y='70' font-size='60' font-weight='bold' text-anchor='middle' fill='white'>C</text></svg>">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
        }
        .stat-card {
            backdrop-filter: blur(10px);
        }
        .download-btn img {
            transition: transform 0.2s;
        }
        .download-btn:hover img {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- ================= NAVBAR ================= --}}
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-semibold text-xl text-gray-800">CBT Platform</span>
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <a href="#fitur" class="text-gray-600 hover:text-blue-600 transition">Fitur</a>
                    <a href="#keunggulan" class="text-gray-600 hover:text-blue-600 transition">Keunggulan</a>
                    <a href="#testimoni" class="text-gray-600 hover:text-blue-600 transition">Testimoni</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->role === 'super_admin' ? route('superadmin.dashboard') : route('school.dashboard') }}"
                           class="px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            Login
                        </a>
                        <a href="{{ route('student.server.form') }}"
                           class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                            Mulai Ujian
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ================= HERO SECTION ================= --}}
    <section class="hero-gradient pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Sistem Ujian <br/>
                        <span class="text-yellow-300">Berbasis Komputer</span>
                    </h1>
                    <p class="text-lg text-blue-100 mb-8 leading-relaxed">
                        Platform CBT modern untuk sekolah Indonesia. Kelola ujian dengan mudah, 
                        aman, dan efisien. Dukung pembelajaran digital di era teknologi.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#download"
                           class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-lg">
                            📥 Download APK Ujian
                        </a>
                        @guest
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition border border-blue-200">
                               Login Admin
                            </a>
                        @endguest
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 stat-card">
                        <div class="text-center mb-6">
                            <div class="text-6xl mb-4">📱</div>
                            <h3 class="text-xl font-semibold text-white">Aplikasi Android</h3>
                            <p class="text-blue-200 text-sm mt-2">Ujian hanya dapat dilakukan melalui aplikasi Android</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-white mb-2">4.4+</div>
                                <div class="text-blue-200 text-xs">Android Version</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-white mb-2">10MB</div>
                                <div class="text-blue-200 text-xs">Ukuran APK</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FITUR SECTION ================= --}}
    <section id="fitur" class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Fitur Lengkap untuk Sekolah Anda
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Semua yang Anda butuhkan untuk mengelola ujian komputerisasi dalam satu platform
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="feature-card bg-gray-50 rounded-2xl p-8 transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📝</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Bank Soal Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola soal ujian dalam format digital dengan mudah. Import, edit, dan organize soal per mata pelajaran.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="feature-card bg-gray-50 rounded-2xl p-8 transition duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">⏱️</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Timer Otomatis</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem timer yang akurat dengan auto-submit ketika waktu habis. Siswa dapat melihat sisa waktu real-time.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="feature-card bg-gray-50 rounded-2xl p-8 transition duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🔒</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Token ujian, device lock, dan anti-cheating system untuk memastikan integritas ujian tetap terjaga.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="feature-card bg-gray-50 rounded-2xl p-8 transition duration-300">
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Laporan Nilai</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generate laporan nilai otomatis dengan berbagai format export. Monitoring perkembangan siswa per kelas.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="feature-card bg-gray-50 rounded-2xl p-8 transition duration-300">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">👥</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Multi-Role Access</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akses berbeda untuk Super Admin, Admin Sekolah, dan Siswa. Setiap role memiliki dashboard tersendiri.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="feature-card bg-gray-50 rounded-2xl p-8 transition duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Aplikasi Android</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Ujian hanya dapat dilakukan melalui aplikasi Android untuk memastikan keamanan dan stabilitas sistem ujian.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= KEUNGGULAN SECTION ================= --}}
    <section id="keunggulan" class="py-20 px-4 bg-gradient-to-br from-blue-600 to-indigo-700">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Kenapa Memilih CBT Platform?
                </h2>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto">
                    Solusi terbaik untuk transformasi digital ujian di sekolah Anda
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 flex gap-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-2">Mudah Digunakan</h3>
                        <p class="text-blue-100 leading-relaxed">
                            Interface yang intuitif dan user-friendly. Guru dan siswa dapat langsung menggunakan tanpa pelatihan panjang.
                        </p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 flex gap-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-2xl">🛡️</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-2">Data Aman</h3>
                        <p class="text-blue-100 leading-relaxed">
                            Enkripsi data dan backup otomatis. Informasi sekolah dan siswa tersimpan dengan aman.
                        </p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 flex gap-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-2">Performa Tinggi</h3>
                        <p class="text-blue-100 leading-relaxed">
                            Optimized untuk menangani ratusan siswa ujian bersamaan tanpa lag atau downtime.
                        </p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 flex gap-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-2">Harga Terjangkau</h3>
                        <p class="text-blue-100 leading-relaxed">
                            Berlangganan fleksibel dengan fitur lengkap. Cocok untuk sekolah dengan berbagai anggaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= TESTIMONI SECTION ================= --}}
    <section id="testimoni" class="py-20 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Apa Kata Mereka?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Testimoni dari sekolah-sekolah yang telah menggunakan CBT Platform
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <span class="text-yellow-400">★</span>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        "Sistem CBT yang sangat membantu kami dalam melaksanakan ujian semester. 
                        Siswa antusias dan proses berjalan lancar."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                            DS
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Dedi Susanto</div>
                            <div class="text-sm text-gray-500">Kepala Sekolah SMA Negeri 1</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <span class="text-yellow-400">★</span>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        "Fitur lengkap dan mudah digunakan. Laporan nilai otomatis sangat 
                        menghemat waktu kami dalam rekap nilai siswa."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold">
                            SA
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Siti Aminah</div>
                            <div class="text-sm text-gray-500">Guru SMA Negeri 2</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <span class="text-yellow-400">★</span>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        "Ujian jadi lebih seru dan tidak tegang. Sistemnya cepat dan 
                        tidak pernah error saat kami gunakan."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-semibold">
                            RA
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Rizky Abdullah</div>
                            <div class="text-sm text-gray-500">Siswa SMA Negeri 1</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= DOWNLOAD APK SECTION ================= --}}
    <section id="download" class="py-20 px-4 bg-gradient-to-br from-green-600 to-emerald-700">
        <div class="max-w-4xl mx-auto text-center">
            <div class="text-6xl mb-6">📱</div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Download Aplikasi Ujian CBT
            </h2>
            <p class="text-lg text-green-100 mb-8 max-w-2xl mx-auto">
                Ujian hanya dapat dilakukan melalui aplikasi Android. Download dan instal APK berikut pada device Android Anda.
            </p>

            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-8">
                <div class="grid md:grid-cols-3 gap-6 text-white">
                    <div>
                        <div class="text-3xl font-bold mb-2">📲</div>
                        <div class="font-semibold mb-1">Minimal Android</div>
                        <div class="text-green-100 text-sm">Android 4.4 (KitKat)</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">💾</div>
                        <div class="font-semibold mb-1">Ukuran File</div>
                        <div class="text-green-100 text-sm">~10 MB</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">🔐</div>
                        <div class="font-semibold mb-1">Keamanan</div>
                        <div class="text-green-100 text-sm">Verified & Safe</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="download-btn">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" 
                         alt="Download on Google Play" 
                         class="h-16 mx-auto">
                </a>
                <a href="#" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-green-600 font-semibold rounded-xl hover:bg-green-50 transition shadow-lg">
                    📥 Download APK Langsung
                </a>
            </div>

            <p class="text-green-200 text-sm mt-6">
                ⚠️ Jika download APK langsung, aktifkan "Install from Unknown Sources" di pengaturan Android Anda.
            </p>
        </div>
    </section>

    {{-- ================= CTA SECTION ================= --}}
    <section class="py-20 px-4 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">
                Siap Mengimplementasikan Ujian Digital?
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                Bergabunglah dengan puluhan sekolah yang telah mempercayakan ujian mereka pada CBT Platform
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg">
                    🔐 Login Admin Sekolah
                </a>
                <a href="#download"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-gray-50 transition border-2 border-blue-600">
                   📱 Download APK Ujian
                </a>
            </div>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-gray-900 text-gray-300 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">C</span>
                        </div>
                        <span class="font-semibold text-xl text-white">CBT Platform</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-md">
                        Platform Computer Based Test modern untuk mendukung transformasi digital 
                        pendidikan di Indonesia. Mudah, aman, dan terpercaya.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="#fitur" class="hover:text-white transition">Fitur</a></li>
                        <li><a href="#keunggulan" class="hover:text-white transition">Keunggulan</a></li>
                        <li><a href="#testimoni" class="hover:text-white transition">Testimoni</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-4">Akses Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="#download" class="hover:text-white transition">Download APK</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login Admin</a></li>
                        <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} CBT Platform. All rights reserved.</p>
                <p class="mt-2">Dibuat dengan ❤️ untuk pendidikan Indonesia</p>
            </div>
        </div>
    </footer>

    {{-- Smooth Scroll Script --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    </script>

</body>
</html>
