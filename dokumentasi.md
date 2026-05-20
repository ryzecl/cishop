# Dokumentasi Perubahan Modul Produk CIShop

Dokumentasi ini disusun ulang berdasarkan perubahan yang sedang ter-track oleh Git pada modul produk. Fokus perubahan ada pada perluasan flow produk dari halaman daftar saja menjadi flow tambah produk dengan validasi, upload gambar, kompres gambar, dan perubahan lokasi penyimpanan gambar.

## 1. Ringkasan File yang Berubah

Perubahan kode yang ter-track:

- `application/controllers/Product.php`
  - Menambahkan method `create()`.
  - Menambahkan callback validasi `unique_slug()`.
  - Merapikan chaining query pada `index()`.
- `application/core/MY_Model.php`
  - Mengubah eksekusi form validation menjadi `run(get_instance())` agar callback controller bisa dipanggil dari validasi model.
- `application/models/Product_model.php`
  - Menambahkan method `uploadImage()`.
  - Tetap menjadi pusat default value dan rule validasi produk.
- `application/views/pages/product/form.php`
  - Menambahkan view form tambah produk.
  - Mendukung multipart form untuk upload gambar.
  - Menambahkan auto-generate slug dari input nama produk.
- `application/views/pages/product/index.php`
  - Tombol tambah diarahkan ke route `product/create`.
  - Path gambar produk diubah dari `assets/images/products` ke `images/products`.
- Folder gambar produk
  - Gambar lama dipindahkan dari `assets/images/products/` ke `images/products/`.
  - Ada tambahan file gambar hasil upload di `images/products/`.

## 2. Flow Daftar Produk

Flow ketika admin membuka halaman daftar produk:

```text
User membuka /product
        |
        v
Product::index($page)
        |
        v
Product_model dipakai sebagai $this->product
        |
        v
Query produk:
select kolom produk dan kategori
join categories
order by products.id desc
paginate sesuai halaman
get result
        |
        v
Controller membuat total rows dan link pagination
        |
        v
layouts/app memuat pages/product/index
        |
        v
View menampilkan tabel produk, gambar, kategori, harga, stok, dan pagination
```

Method yang menangani flow ini:

```php
public function index($page = null)
{
    $data['title'] = 'Admin: Product';
    $data['content'] = $this->product->select(
        ['products.id', 'products.title as product_title', 'products.description', 'products.price', 'products.is_available', 'products.image', 'categories.title as category_title']
    )
        ->join('categories')
        ->orderBy('products.id', 'desc')
        ->paginate($page)->get();

    $data['total_rows'] = $this->product->count();
    $data['pagination'] = $this->product->makePagination(base_url('product'), 2, $data['total_rows']);
    $data['page'] = 'pages/product/index';

    $this->view($data);
}
```

Detail logic:

- `select()` memilih kolom yang dibutuhkan tabel produk.
- `products.title as product_title` mencegah bentrok dengan `categories.title`.
- `categories.title as category_title` dipakai untuk label kategori pada view.
- `join('categories')` membentuk relasi `products.id_category = categories.id`.
- `orderBy('products.id', 'desc')` menampilkan produk terbaru di atas.
- `paginate($page)` membatasi data sesuai konfigurasi `$perPage` pada `MY_Model`.
- `get()` mengeksekusi query dan mengembalikan array object.
- `count()` menghitung total produk untuk pagination.
- `makePagination(base_url('product'), 2, ...)` membuat link pagination dengan angka halaman pada URI segment ke-2.

## 3. Flow Tambah Produk

Perubahan terbesar ada pada penambahan `Product::create()`. Flow ini menangani dua kondisi: pertama kali form dibuka dan ketika form dikirim.

```text
User klik Tambah dari halaman produk
        |
        v
GET /product/create
        |
        v
Product::create()
        |
        v
Tidak ada POST, input diisi default value dari Product_model
        |
        v
View pages/product/form ditampilkan
```

Flow ketika form disubmit:

```text
User submit form tambah produk
        |
        v
POST /product/create
        |
        v
Input POST dibaca dengan XSS filtering
        |
        v
Jika ada file image:
  buat nama file dari slug title + timestamp
  upload ke images/products
  kompres/resize gambar
  simpan nama file ke input->image
        |
        v
Validasi Product_model dijalankan
        |
        +-- gagal:
        |     tampilkan kembali form beserta input dan error
        |
        +-- berhasil:
              insert produk ke tabel products
              set flashdata success/error
              redirect ke /product
```

Kode utama:

```php
public function create()
{
    if (!$_POST) {
        $input = (object) $this->product->getDefaultValues();
    } else {
        $input = (object) $this->input->post(null, true);
    }

    if (!empty($_FILES) && $_FILES['image']['name'] != '') {
        $imageName = url_title($input->title, '-', true) . '-' . date('YmdHis');
        $upload = $this->product->uploadImage('image', $imageName);
        if ($upload) {
            $input->image = $upload['file_name'];
        } else {
            redirect(base_url('product/create'));
        }
    }

    if (!$this->product->validate()) {
        $data['title'] = 'Tambah Produk';
        $data['input'] = $input;
        $data['form_action'] = base_url('product/create');
        $data['page'] = 'pages/product/form';

        $this->view($data);
        return;
    }

    if ($this->product->create($input)) {
        $this->session->set_flashdata('success', 'Data berhasil disimpan!');
    } else {
        $this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
    }

    redirect(base_url('product'));
}
```

Catatan penting:

- Input POST dibaca melalui `$this->input->post(null, true)`, sehingga XSS filtering CodeIgniter aktif.
- File upload diproses sebelum validasi form. Jika upload gagal, request langsung redirect kembali ke form create.
- Jika tidak ada file yang diupload, field `image` tetap mengikuti value hidden dari form.
- Setelah insert berhasil atau gagal, user selalu diarahkan kembali ke halaman daftar produk.

## 4. Validasi Produk dan Callback Slug

Rule validasi tetap berada di `Product_model::getValidationRules()`.

Field yang divalidasi:

- `id_category`: wajib diisi.
- `slug`: wajib, di-trim, dan harus unik melalui `callback_unique_slug`.
- `title`: wajib dan di-trim.
- `description`: wajib dan di-trim.
- `price`: wajib, di-trim, dan numeric.
- `is_available`: wajib diisi.

Rule slug:

```php
[
    'field' => 'slug',
    'label' => 'Slug',
    'rules' => 'trim|required|callback_unique_slug'
]
```

Callback ada di `Product::unique_slug()`:

```php
public function unique_slug()
{
    $slug = $this->input->post('slug');
    $id = $this->input->post('id');
    $product = $this->product->where('slug', $slug)->first();

    if ($product) {
        if ($id == $product->id) {
            return true;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_message('unique_slug', '%s sudah digunakan!');
        return false;
    }

    return true;
}
```

Logic callback:

- Ambil `slug` dari POST.
- Ambil `id` dari POST jika form dipakai untuk edit.
- Cari produk dengan slug yang sama.
- Jika slug ditemukan dan id-nya sama dengan data yang sedang diedit, validasi tetap lolos.
- Jika slug ditemukan untuk produk lain, validasi gagal dan menampilkan pesan `%s sudah digunakan!`.
- Jika slug tidak ditemukan, validasi lolos.

Walaupun flow edit belum dibuat, callback sudah disiapkan agar reusable untuk create dan edit.

## 5. Perubahan `MY_Model::validate()`

Perubahan kecil di `MY_Model` sangat penting untuk callback validasi:

```php
return $this->form_validation->run(get_instance());
```

Sebelumnya validasi dijalankan tanpa parameter:

```php
return $this->form_validation->run();
```

Dampaknya:

- Rule validasi tetap didefinisikan di model.
- Callback `callback_unique_slug` tetap bisa diarahkan ke controller aktif.
- CodeIgniter mendapat instance controller melalui `get_instance()`.
- Method `Product::unique_slug()` bisa terpanggil saat `$this->product->validate()` dijalankan.

Tanpa perubahan ini, callback yang ditulis di controller berisiko tidak dikenali saat validasi dipanggil dari model.

## 6. Upload dan Kompres Gambar Produk

Upload gambar ditangani oleh `Product_model::uploadImage($fieldName, $fileName)`.

Konfigurasi upload:

```php
$config = [
    'upload_path' => './images/products/',
    'file_name' => $fileName,
    'allowed_types' => 'jpg|png|jpeg|JPG|PNG|JPEG',
    'max_size' => 1024,
    'max_width' => 0,
    'max_height' => 0,
    'overwrite' => true,
    'file_ext_tolower' => true,
];
```

Detail logic:

- File disimpan ke `./images/products/`.
- Nama file dibuat dari slug nama produk dan timestamp, contoh `smartwatch-20260521010543.jpg`.
- Format yang diterima adalah JPG, JPEG, dan PNG, baik lowercase maupun uppercase.
- Maksimal ukuran file adalah `1024 KB`.
- Ekstensi file dipaksa lowercase melalui `file_ext_tolower`.
- Jika nama file sama, file lama bisa ditimpa karena `overwrite` bernilai `true`.

Setelah upload berhasil, gambar dikompres atau diresize dengan `image_lib`:

```php
$configResize = [
    'image_library'   => 'gd2',
    'source_image'    => './images/products/' . $uploadData['file_name'],
    'create_thumb'    => FALSE,
    'maintain_ratio'  => TRUE,
    'quality'         => '70%',
    'width'           => 500,
    'height'          => 500,
];
```

Dampaknya:

- Gambar diproses menggunakan library `gd2`.
- Rasio gambar dipertahankan.
- Kualitas gambar diturunkan ke `70%`.
- Target dimensi berada di batas `500 x 500`.
- Thumbnail tidak dibuat, file asli hasil upload langsung diproses.

Jika upload gagal:

- Error dari library upload disimpan ke flashdata `error`.
- Method mengembalikan `false`.
- Controller melakukan redirect ke `product/create`.

## 7. View Form Produk

File baru `application/views/pages/product/form.php` menjadi halaman form tambah produk.

Form dibuka dengan:

```php
<?= form_open_multipart($form_action, ['method' => 'POST']) ?>
```

Karena memakai `form_open_multipart()`, form sudah siap mengirim file gambar melalui `$_FILES`.

Field yang tersedia:

- Hidden `id`, hanya ada jika `$input->id` tersedia.
- Hidden `image`, menjaga nama gambar lama jika form nanti dipakai untuk edit.
- `title`, input nama produk.
- `slug`, input slug.
- `price`, input number untuk harga.
- `id_category`, dropdown kategori.
- `description`, textarea deskripsi.
- `is_available`, radio button `Tersedia` atau `Kosong`.
- `image`, input upload file.

Dropdown kategori:

```php
<?= form_dropdown('id_category', getDropdownList('categories', ['id', 'title']), $input->id_category, ['class' => 'form-select', 'required' => true]) ?>
```

View mengambil opsi kategori dari helper `getDropdownList('categories', ['id', 'title'])`. Value yang dikirim adalah `categories.id`, sedangkan label yang tampil adalah `categories.title`.

Preview gambar:

```php
<?php if ($input->image): ?>
    <img src="<?= base_url("images/products/$input->image") ?>" alt="" height="150" class="mt-2">
<?php endif; ?>
```

Jika `$input->image` ada, form menampilkan preview dari folder `images/products`.

Auto-generate slug:

```javascript
title.addEventListener('input', function() {
    const titleValue = title.value;
    const slugValue = titleValue.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    slug.value = slugValue;
});
```

Logic JavaScript:

- Setiap nama produk diketik, field slug otomatis diisi.
- Huruf diubah menjadi lowercase.
- Karakter selain huruf, angka, spasi, dan tanda hubung dihapus.
- Spasi diubah menjadi tanda hubung.
- Tanda hubung berulang dirapikan menjadi satu.

Slug tetap divalidasi di server, sehingga JavaScript hanya membantu input user dan bukan satu-satunya sumber kebenaran.

## 8. Perubahan View Daftar Produk

Tombol tambah pada daftar produk sekarang masuk ke flow CodeIgniter:

```php
href="<?= base_url('product/create') ?>"
```

Sebelumnya tombol tambah masih mengarah ke file HTML statis. Dengan perubahan ini:

- Klik tombol `Tambah` membuka `Product::create()`.
- Form yang ditampilkan adalah `pages/product/form`.
- Submit form kembali ke `product/create`.

Path gambar produk juga berubah:

```php
src="<?= $row->image ? base_url('images/products/' . $row->image) : 'https://ui-avatars.com/api/?name=' . urlencode($row->product_title) ?>"
```

Dampaknya:

- Gambar produk sekarang dibaca dari `images/products/`.
- Jika produk tidak punya gambar, fallback tetap memakai UI Avatars berdasarkan nama produk.
- Perubahan ini konsisten dengan lokasi upload pada `Product_model::uploadImage()`.

Bagian yang masih placeholder di view daftar:

- Form pencarian belum mengirim keyword yang diproses controller.
- Tombol reset masih mengarah ke `/admin-product.html`.
- Tombol edit masih mengarah ke `/admin-product-form.html`.
- Form delete belum punya action ke route delete.

## 9. Perubahan Lokasi File Gambar

Git mencatat pemindahan file gambar:

```text
assets/images/products/console.jpg      -> images/products/console.jpg
assets/images/products/laptop.jpg       -> images/products/laptop.jpg
assets/images/products/power-bank.jpg   -> images/products/power-bank.jpg
assets/images/products/smartphone.png   -> images/products/smartphone.png
assets/images/products/watch.png        -> images/products/watch.png
```

Ada juga tambahan gambar baru di folder tujuan:

```text
images/products/smartwatch-20260521010543.jpg
images/products/smartwatch-7-20260521011655.jpg
```

Perubahan lokasi ini membuat source gambar daftar produk dan lokasi upload produk baru berada di folder yang sama, yaitu:

```text
images/products/
```

## 10. Status Fitur Setelah Perubahan

Fitur yang sudah tersambung:

- Halaman daftar produk melalui `/product`.
- Query daftar produk dengan join kategori.
- Pagination daftar produk.
- Tombol tambah dari daftar produk ke `/product/create`.
- Form tambah produk.
- Validasi input tambah produk.
- Validasi slug unik.
- Upload gambar produk.
- Kompres atau resize gambar produk.
- Insert produk baru ke tabel `products`.
- Flash message success atau error setelah insert.
- Path gambar produk menggunakan `images/products`.

Fitur yang belum selesai atau masih placeholder:

- Search produk belum diproses oleh controller.
- Reset search masih mengarah ke HTML statis.
- Edit produk belum memiliki method controller dan route dinamis.
- Delete produk belum memiliki method controller dan route dinamis.
- Form produk sudah disiapkan agar bisa dipakai edit, tetapi flow edit belum dibuat.

## 11. Kesimpulan Teknis

Perubahan ini menggeser modul produk dari sekadar halaman daftar menjadi awal CRUD yang lebih lengkap. Flow create sudah berjalan dari tombol tambah, render form, validasi input, validasi slug unik, upload gambar, kompres gambar, insert database, sampai redirect kembali ke daftar produk.

Perubahan pada `MY_Model::validate()` menjadi bagian penting karena validasi tetap didefinisikan di model, tetapi callback slug berada di controller. Dengan `run(get_instance())`, validasi model dan callback controller bisa bekerja dalam satu flow.

Folder gambar produk juga distandarkan ke `images/products/`, sehingga gambar lama, gambar baru hasil upload, dan path render di view memakai lokasi yang konsisten.
