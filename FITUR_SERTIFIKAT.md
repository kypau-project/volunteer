# Fitur Pembuatan dan Pengelolaan Sertifikat

## 📋 Ringkasan Fitur

Sistem telah dilengkapi dengan fitur lengkap untuk membuat, mengelola, dan mendownload sertifikat digital. Berikut adalah panduan penggunaannya:

## 🔄 Alur Kerja

### 1. **Coordinator - Membuat Sertifikat (di halaman Manajemen Kehadiran)**

#### Syarat-syarat Pembuatan Sertifikat:

-   ✅ Event sudah selesai (tanggal akhir event sudah lewat)
-   ✅ Relawan sudah hadir (attended = true)
-   ✅ Relawan sudah check-out
-   ✅ Sertifikat belum pernah dibuat sebelumnya

#### Cara Membuat Sertifikat Individual:

1. Buka halaman "Events" → Pilih event → klik "Manajemen Kehadiran"
2. Cari relawan yang ingin diberi sertifikat
3. Pastikan status relawan sudah selesai (check-out selesai)
4. Klik tombol **"Buat Sertifikat"** di kolom Aksi
5. Sertifikat akan dibuat dan dapat langsung didownload

#### Cara Membuat Sertifikat Massal (Bulk):

1. Buka halaman "Events" → Pilih event → klik "Manajemen Kehadiran"
2. Pastikan event sudah selesai (tombol "Buat Semua Sertifikat" akan tampil jika syarat terpenuhi)
3. Klik tombol **"Buat Semua Sertifikat"** di bagian atas tabel
4. Semua relawan yang memenuhi syarat akan mendapat sertifikat secara otomatis
5. Sistem akan menampilkan jumlah sertifikat yang berhasil dibuat

### 2. **Volunteer - Melihat & Mendownload Sertifikat**

#### Akses Halaman Sertifikat:

1. Login sebagai volunteer
2. Di sidebar, klik **"My Certificates"** (atau "Sertifikat Saya")
3. Halaman akan menampilkan semua sertifikat yang telah diterima dalam bentuk kartu

#### Fitur di Halaman My Certificates:

-   📄 Tampil dalam format kartu dengan informasi:
    -   Nama acara
    -   Tanggal acara
    -   Jam kontribusi
    -   Tanggal penerbitan sertifikat
-   🔍 Fitur pencarian untuk mencari sertifikat berdasarkan nama acara
-   ⬇️ Tombol "Download Sertifikat" untuk mengunduh PDF
-   📱 Responsive design (bagus di mobile dan desktop)

#### Cara Mendownload Sertifikat:

1. Di halaman "My Certificates", cari sertifikat yang ingin didownload
2. Klik tombol **"Download Sertifikat"**
3. File PDF akan terunduh dengan nama: `Sertifikat-[nama-volunteer]-[nama-acara].pdf`

## 🔐 Keamanan & Otorisasi

-   **Volunteer** hanya dapat melihat dan mendownload sertifikat mereka sendiri
-   **Coordinator/Admin** dapat melihat dan mendownload sertifikat relawan di acara mereka
-   Unauthorized access akan menampilkan error 403

## 📄 Desain Template Sertifikat

Template sertifikat mencakup:

-   Desain profesional dengan warna brand (biru #3950A2)
-   Ornamen dekoratif di sudut sertifikat
-   Informasi detail:
    -   Nama relawan
    -   Nama acara
    -   Jam kontribusi
    -   Nomor sertifikat unik
    -   Tanggal penerbitan
    -   Tanda tangan digital (ruang untuk tanda tangan koordinator)
-   Format: A4 Landscape
-   Font yang elegan dengan Georgia serif

## 🗄️ Data yang Disimpan

Setiap sertifikat menyimpan:

-   `registration_id` - ID registrasi relawan di acara
-   `file_path` - Path file PDF yang tersimpan
-   `issued_at` - Tanggal penerbitan sertifikat
-   `created_at`, `updated_at` - Timestamp record

File PDF disimpan di: `storage/app/certificates/`

## 📊 Struktur Database

### Tabel: certificates

```
- id (primary key)
- registration_id (foreign key ke registrations)
- file_path (path ke file PDF)
- issued_at (datetime)
- created_at, updated_at (timestamp)
- unique constraint pada registration_id
```

## 🛠️ Komponen Sistem yang Digunakan

### Backend:

-   **Event Model**: Method `isFinished()` untuk cek event sudah selesai
-   **AttendanceManager Component**: Method `generateCertificate()` dan `generateAllCertificates()`
-   **MyCertificates Component**: Untuk menampilkan sertifikat volunteer
-   **CertificateController**: Download dan authorization

### Frontend:

-   **Template**: `certificates.template` (Blade template untuk PDF)
-   **Views**:
    -   `attendance-manager.blade.php` (UI coordinator)
    -   `my-certificates.blade.php` (UI volunteer)

### Routes:

-   `GET /coordinator/events/{event}/attendance` - Manajemen kehadiran
-   `GET /volunteer/certificates` - Halaman My Certificates
-   `GET /certificates/{certificate}/download` - Download sertifikat

## 📝 Contoh Nomor Sertifikat

Format: `CERT-[TANGGAL]-[ID]`

Contoh: `CERT-20251116-45`

## ⚠️ Catatan Penting

1. **Event harus selesai**: Sertifikat hanya dapat dibuat setelah tanggal akhir event berlalu
2. **Relawan harus check-out**: Jam kontribusi harus sudah tercatat
3. **Sertifikat tidak bisa dibuat ulang**: Jika sertifikat sudah ada, tidak bisa dibuat lagi
4. **PDF disimpan otomatis**: Saat dibuat, PDF langsung disimpan ke storage
5. **Download permanen**: Relawan dapat mendownload sertifikat kapan saja

## 🔄 Alur Teknis

```
Coordinator mengklik "Buat Sertifikat"
    ↓
Validasi: Event selesai? Relawan hadir? Check-out? Sertifikat belum ada?
    ↓
Buat record Certificate di DB
    ↓
Generate PDF menggunakan DomPDF dengan template
    ↓
Simpan PDF ke storage/app/certificates/
    ↓
Update file_path di record Certificate
    ↓
Tampilkan success message
    ↓
Relawan bisa lihat di "My Certificates"
    ↓
Relawan klik "Download" → File PDF diunduh
```

## 📱 Responsif Design

Halaman My Certificates menggunakan Bootstrap grid:

-   **Desktop** (lg): 3 kolom
-   **Tablet** (md): 2 kolom
-   **Mobile** (xs): 1 kolom

## 🎯 Fitur Bonus

-   Search sertifikat berdasarkan nama acara
-   Pagination untuk menampilkan sertifikat
-   Preview informasi sertifikat tanpa perlu download
-   Informasi lengkap (jam, tanggal, nomor sertifikat)
-   Icon dan visual yang user-friendly
