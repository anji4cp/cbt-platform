# 🧠 CBT Platform  
**Multi School Computer Based Test System**

CBT Platform adalah sistem **Computer Based Test (CBT) multi-sekolah** berbasis web dan Android (APK) yang dirancang untuk pelaksanaan ujian online **aman, terkontrol, dan siap produksi**.

Sistem ini fokus pada **keamanan ujian**, **stabilitas server**, dan **kemudahan operasional sekolah**.

---

## 🎯 Tujuan Sistem

- Menyediakan platform ujian online terpusat untuk banyak sekolah
- Mendukung ujian berbasis PDF (Google Drive)
- Mengurangi kecurangan dengan kontrol device, waktu, dan akses
- Mendukung penggunaan **APK Android (kiosk / screen pinning)** untuk siswa

---

## 🧩 Peran & Hak Akses

### Super Admin
- Manajemen sekolah
- Monitoring aktivitas ujian
- Kontrol status sekolah (aktif / suspend / expired)
- Pembuatan admin sekolah

### Admin Sekolah
- Manajemen siswa & kelas
- Import siswa (Excel)
- Manajemen ujian & paket soal
- Token ujian
- Cetak kartu ujian
- Monitoring siswa
- Laporan nilai & export Excel

### Siswa
- Login berbasis **Server ID sekolah**
- Login akun siswa
- Token ujian (opsional)
- Ujian berbasis PDF
- Autosave jawaban
- Auto-submit saat waktu habis
- Akses dibatasi hanya dari **APK resmi**

---

## 🔐 Fitur Keamanan Utama

### 🔒 Keamanan Login
- Rate limit login (anti brute force)
- Validasi status sekolah (aktif / suspend / expired)
- Session scope per sekolah
- Login siswa hanya dari aplikasi resmi (APK)

### 📱 Keamanan Siswa (Anti-Cheat)
- Device lock (1 siswa = 1 device)
- Session terikat `device_id`
- Autosave server-side
- Auto-submit server-side saat waktu habis
- Minimal waktu submit (anti submit cepat)
- Validasi penuh di server (bukan hanya JavaScript)

### 🧠 Keamanan Ujian
- Paket soal ditentukan server (A, B, C, dst)
- Jawaban disimpan incremental (tidak overwrite)
- Penilaian otomatis server-side
- Redirect aman ke halaman finish

---

## ⏱️ Sistem Waktu Ujian

- Durasi ujian berbasis server (`started_at` & `ends_at`)
- Countdown hanya untuk UX (bukan acuan utama)
- Auto-submit berjalan walaupun:
  - Browser crash
  - APK force close
  - Koneksi terputus
- Validasi waktu dilakukan di setiap request (save & submit)

---

## 📄 Sistem Soal

- Soal berbasis PDF (Google Drive)
- Mendukung banyak paket soal:
  - Paket A, B, C, dst
- Kunci jawaban per paket
- Validasi jumlah kunci = jumlah soal
- PDF ditampilkan via Google Drive Preview (sandboxed)

---

## 🧾 Fitur Administrasi

### Admin Sekolah
- CRUD siswa
- Import siswa via Excel
- Hapus siswa per kelas / massal
- Manajemen ujian
- Token ujian
- Cetak kartu ujian (PDF A4)
- Laporan nilai + export Excel

### Super Admin
- Monitoring sekolah aktif
- Monitoring ujian berjalan
- Kontrol status sekolah
- Dashboard statistik

---

## 📱 Dukungan Android APK (Kiosk Mode)

CBT Platform dirancang untuk penggunaan:
- Android APK
- Screen pinning / kiosk mode
- Tanpa tab browser
- Tanpa akses URL lain

Login siswa **ditolak** jika tidak berasal dari aplikasi resmi.

---

## 🛠️ Teknologi

- **Backend**: Laravel 12
- **Database**: PostgreSQL / MySQL
- **Frontend**: Blade + Tailwind CSS
- **Auth**: Multi Guard (`web`, `student`)
- **Security**: RateLimiter, middleware custom
- **PDF Viewer**: Google Drive Preview
- **Client**: Web Admin & Android APK (Siswa)

---

## 🚀 Status Project

🟢 **Production Ready (CBT Sekolah)**

Sistem siap digunakan dengan asumsi:
- Server stabil
- APK kiosk digunakan untuk siswa

Cocok untuk:
- Ujian sekolah
- Tryout
- CBT internal
- Simulasi ANBK

---

## 📌 Catatan Pengembangan

Project ini dikembangkan:
- Secara mandiri
- Dengan pendekatan sistem CBT dunia nyata
- Fokus pada keamanan & stabilitas

Arsitektur dirancang agar:
- Mudah dikembangkan
- Mudah di-scale
- Aman untuk penggunaan produksi

---

## 📜 Lisensi & Tujuan

CBT Platform dikembangkan sebagai:
- Produk nyata
- Portofolio profesional
- Fondasi pengembangan sistem CBT skala besar
