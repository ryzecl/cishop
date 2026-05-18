# Dokumentasi Pengembangan Modul Kategori (CIShop)

Sebagai Software Engineer, saya telah melakukan serangkaian perbaikan dan pengembangan pada modul **Kategori** untuk memastikan fungsionalitas CRUD (Create, Read, Update, Delete) berjalan dengan standar yang baik.

Berikut adalah rincian perubahan logika dan teknis yang telah diterapkan:

---

## 1. Controller: `Category.php`

Controller ini bertindak sebagai otak yang mengatur alur data antara Model dan View.

### A. Fitur Pencarian (`index`)
```php
$keyword = $this->input->get('keyword', true);
if ($keyword) {
    $this->category->like('title', $keyword);
}
```
**Penjelasan Logic:**
- Mengambil kata kunci dari URL (metode GET).
- Jika ada kata kunci, model akan menambahkan kondisi `LIKE` pada query database untuk mencari judul kategori yang sesuai.

### B. Validasi & Penyimpanan (`create` & `edit`)
```php
if (!$this->category->validate()) {
    // Tampilkan Form (Kondisi saat pertama buka atau input salah)
} else {
    // Simpan Data (Kondisi saat input valid)
}
```
**Penjelasan Logic:**
- Menggunakan `!$this->category->validate()`. Jika validasi gagal (termasuk saat halaman pertama kali dimuat tanpa data POST), maka aplikasi akan menampilkan view formulir.
- Jika validasi berhasil, data dari `$input` akan disimpan ke database.

### C. Validasi Slug Unik (`unique_slug`)
```php
if ($category) {
    if ($id == $category->id) return true; // Jika mengedit data sendiri, abaikan
    $this->form_validation->set_message('unique_slug', '%s sudah digunakan!');
    return false;
}
```
**Penjelasan Logic:**
- Callback ini memastikan tidak ada dua kategori dengan slug yang sama.
- Khusus untuk fitur **Edit**, sistem akan mengabaikan pengecekan jika slug tersebut milik data yang sedang diedit itu sendiri (agar bisa menyimpan tanpa harus mengganti slug).

### D. Keamanan Penghapusan (`delete`)
```php
if (!$_POST) {
    redirect(base_url('category'));
}
```
**Penjelasan Logic:**
- Mengharuskan metode POST untuk menghapus data. Ini mencegah penghapusan data secara tidak sengaja melalui akses URL langsung (metode GET).

---

## 2. View: `form.php` (Formulir Kategori)

### A. Otomatisasi Slug (JavaScript)
```javascript
title.addEventListener('input', function() {
    const slugValue = title.value.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '') // Hapus karakter non-alfanumerik
        .replace(/\s+/g, '-')        // Ganti spasi dengan dash (-)
        .replace(/-+/g, '-');        // Cegah double dash
    slug.value = slugValue;
});
```
**Penjelasan Logic:**
- Menggunakan Event Listener `input` pada kolom Kategori.
- Setiap kali pengguna mengetik, JavaScript akan memproses teks tersebut menjadi format *URL-friendly* (huruf kecil, tanpa simbol, spasi diganti `-`) dan mengisinya secara otomatis ke kolom Slug.

---

## 3. View: `index.php` (Daftar Kategori)

### A. Pencarian & Reset
- Form pencarian menggunakan metode `GET` agar hasil pencarian bisa di-*bookmark* atau di-*share*.
- Tombol "Eraser" (Reset) mengarah kembali ke `base_url('category')` untuk membersihkan filter pencarian.

### B. Aksi Baris Data
- **Edit**: Menggunakan link `<a>` biasa karena hanya bersifat mengambil data (GET).
- **Delete**: Menggunakan `form_open` dan tombol submit di dalam form. Ini adalah standar keamanan untuk memastikan aksi manipulasi data (Delete) dilakukan via POST.

---

## 4. Struktur Data & Helper
- Menggunakan **CodeIgniter Form Helper** (`form_open`, `form_input`, `form_hidden`) untuk menghasilkan HTML yang konsisten dan aman dari serangan CSRF (jika diaktifkan).
- Pesan error validasi ditampilkan menggunakan `form_error()` yang terintegrasi dengan library Form Validation CodeIgniter.

---

**Dibuat oleh:** Antigravity (Software Engineer AI)
**Status:** Stabil & Teruji
