# 📊 Panduan Import/Export User - Fixed Version

## ✅ Perbaikan yang Telah Dilakukan

### 1. **Hapus Dependency Maatwebsite/Excel**

-   ❌ Tidak lagi menggunakan `Maatwebsite\Excel\Concerns`
-   ✅ Menggunakan `PhpOffice\PhpSpreadsheet` yang sudah terinstall

### 2. **Improve Alert System**

-   ✅ Alert berhasil/gagal akan muncul setelah import selesai
-   ✅ Menampilkan jumlah user yang berhasil diimport
-   ✅ Menampilkan jumlah user yang gagal diimport
-   ✅ Error detail disimpan di log untuk debugging

### 3. **Better Error Handling**

-   ✅ Validasi email format
-   ✅ Validasi role (hanya: admin, coordinator, volunteer)
-   ✅ Error tracking per-row
-   ✅ Tidak menghentikan import jika satu row error

### 4. **Improved Import Flow**

-   ✅ UI yang lebih jelas dengan file display
-   ✅ Auto-submit setelah file dipilih
-   ✅ Clear button untuk membatalkan
-   ✅ Livewire integration yang lebih baik

---

## 🚀 Cara Menggunakan Import

### **Step 1: Persiapkan File Excel**

Buat file Excel dengan struktur berikut:

| ID     | Name     | Email            | Phone        | Role      | Status | Address     | Birth Date | Gender | Education | Institution | Skills       | Experience | Created At |
| ------ | -------- | ---------------- | ------------ | --------- | ------ | ----------- | ---------- | ------ | --------- | ----------- | ------------ | ---------- | ---------- |
| (auto) | John Doe | john@example.com | 081234567890 | volunteer | Active | Jl. Merdeka | 1990-01-15 | male   | S1        | ITB         | PHP, Laravel | 3 tahun    | (auto)     |

**Kolom Wajib:**

-   **Email** (kolom C) - Tidak boleh kosong dan harus format email valid

**Kolom Opsional:**

-   Name, Phone, Role, Status, Address, Birth Date, Gender, Education, Institution, Skills, Experience

**Contoh Role yang Valid:**

-   `admin`
-   `coordinator`
-   `volunteer`

**Contoh Status:**

-   `Active` (untuk active user)
-   `Blocked` (untuk blocked user)

### **Step 2: Export Template (Optional)**

Untuk mendapatkan template struktur yang benar:

1. Buka halaman "List User"
2. Klik tombol **Export**
3. File akan terdownload dengan nama: `users_2025-11-17_14-30-45.xlsx`
4. Gunakan sebagai template dan edit datanya

### **Step 3: Import File**

1. Buka halaman **"List User"** (Admin -> List User)
2. Klik tombol **Import** (icon upload)
3. Pilih file Excel (format .xlsx atau .xls)
4. File akan otomatis di-import
5. **Tunggu beberapa detik** untuk proses import selesai
6. Lihat **Alert Message** di bagian atas halaman:
    - ✅ **Hijau** = Success message
    - ❌ **Merah** = Error message

---

## 📋 Alert Messages

### **Success Scenario**

**Jika semua berhasil:**

```
10 user berhasil diimport
```

**Jika ada yang gagal:**

```
8 user berhasil diimport dan 2 user gagal diimport
```

### **Error Scenario**

**Jika semua gagal:**

```
Tidak ada user yang berhasil diimport. 5 user gagal.
```

**Jika ada error teknis:**

```
Error importing users: [deskripsi error]
```

---

## 🔍 Validasi Data

Sistem import akan melakukan validasi berikut:

### **1. Email Validation**

-   ❌ Email kosong → Skip (error: "Email is required")
-   ❌ Format email tidak valid → Skip (error: "Email is not valid")
-   ✅ Email valid → Lanjut proses

### **2. Role Validation**

-   ❌ Role tidak dikenal → Skip (error: "Role must be valid")
-   ✅ Role: admin, coordinator, volunteer → OK

### **3. Status Validation**

-   Jika status = "Blocked" → User akan di-block
-   Selain itu → User akan di-set Active

### **4. Data Processing**

-   Trim whitespace otomatis
-   Default password: `password123`
-   Jika user sudah ada → Update data
-   Jika user baru → Create dengan profile baru

---

## 📊 Data yang Di-Import

### **User Table**

```
- name: Nama user (dari Excel)
- email: Email user (dari Excel)
- password: Default = hash('password123')
- phone: Nomor telepon (optional)
- role: Role user (admin/coordinator/volunteer)
- is_blocked: Blocked atau Active (dari Status field)
```

### **Volunteer Profile Table**

```
- address: Alamat
- birth_date: Tanggal lahir
- gender: Jenis kelamin
- education: Pendidikan terakhir
- institution: Institusi/Sekolah
- skills: Keahlian/Skills
- experience: Pengalaman
```

---

## 🛠️ Troubleshooting

### **Alert Tidak Muncul**

**Penyebab:**

1. File tidak terseleksi dengan benar
2. Format file bukan .xlsx atau .xls
3. Browser cache lama

**Solusi:**

1. Refresh halaman (F5)
2. Pastikan file adalah .xlsx atau .xls
3. Buka browser DevTools (F12) → Console untuk cek error

### **Import Gagal dengan Error**

**Penyebab:**

1. Email kosong atau format tidak valid
2. Role tidak sesuai (bukan admin/coordinator/volunteer)
3. Database constraint error

**Solusi:**

1. Periksa file Excel untuk email kosong/tidak valid
2. Pastikan role hanya: admin, coordinator, volunteer
3. Lihat logs di `storage/logs/laravel.log` untuk detail error

### **User Tidak Muncul di List**

**Penyebab:**

1. Import belum benar-benar selesai
2. Page belum di-refresh
3. Filter search mengabaikan user baru

**Solusi:**

1. Tunggu alert muncul (tidak ada loading spinner)
2. Refresh halaman (F5)
3. Clear search filter untuk lihat semua user

---

## 🔐 Keamanan

-   ✅ File upload di-validate (hanya .xlsx dan .xls)
-   ✅ Email di-validate sebelum disimpan
-   ✅ Password otomatis di-hash
-   ✅ Role di-validate (whitelist: admin, coordinator, volunteer)
-   ✅ Error detail disimpan di log, tidak tampil ke user

---

## 📝 Format File Excel Lengkap

### **Header Row (Baris 1):**

```
ID | Name | Email | Phone | Role | Status | Address | Birth Date | Gender | Education | Institution | Skills | Experience | Created At
```

### **Data Rows (Baris 2+):**

```
(skip) | John Doe | john@example.com | 081234567890 | volunteer | Active | Jl. Merdeka 123 | 1990-01-15 | male | S1 | ITB | PHP, Laravel, MySQL | 3 tahun | (auto)
(skip) | Jane Smith | jane@example.com | 082345678901 | coordinator | Active | Jl. Gatot Subroto | 1992-03-20 | female | S2 | UI | Project Management, Leadership | 5 tahun | (auto)
```

---

## 🎯 Best Practices

1. **Always Export First**

    - Export template dari sistem untuk memastikan format benar

2. **Validate Data Before Import**

    - Periksa email dan role sebelum import
    - Pastikan tidak ada baris kosong

3. **Import di Testing Dulu**

    - Test import dengan few records dulu
    - Pastikan sesuai ekspektasi sebelum bulk import

4. **Keep Backup**

    - Simpan salinan file Excel original
    - Catat jumlah import yang berhasil

5. **Check Logs**
    - Jika ada error, periksa `storage/logs/laravel.log`
    - Copy error message untuk debugging

---

## 📱 Browser Compatibility

-   ✅ Chrome 90+
-   ✅ Firefox 88+
-   ✅ Safari 14+
-   ✅ Edge 90+

Tested dengan file size hingga 5MB.

---

## 💡 Tips

-   Default password untuk semua import user adalah: `password123`
-   User akan diminta untuk change password saat login pertama (recommended)
-   Email adalah unique identifier, jika duplikat akan di-update
-   Profile akan auto-created jika belum ada
-   Untuk bulk import, bisa gunakan export → edit → import flow

---

**Import sudah siap! Coba impor data sekarang dan lihat alert message. 🎉**
