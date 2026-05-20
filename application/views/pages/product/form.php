<main role="main" class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Formulir Produk</div>
                <div class="card-body">
                    <?= form_open_multipart($form_action, ['method' => 'POST']) ?>
                    <?= isset($input->id) ? form_hidden('id', $input->id) : '' ?>
                    <?= form_hidden('image', $input->image) ?>

                    <div class="mb-3">
                        <label for="title" class="form-label">Produk</label>
                        <?= form_input(['name' => 'title', 'value' => $input->title, 'class' => 'form-control', 'id' => 'title', 'required' => true, 'autofocus' => true]) ?>
                        <?= form_error('title') ?>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <?= form_input(['name' => 'slug', 'value' => $input->slug, 'class' => 'form-control', 'id' => 'slug', 'required' => true]) ?>
                        <?= form_error('slug') ?>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <?= form_input(['type' => 'number', 'name' => 'price', 'value' => $input->price, 'class' => 'form-control', 'id' => 'price', 'required' => true]) ?>
                        <?= form_error('price') ?>
                    </div>

                    <div class="mb-3">
                        <label for="id_category" class="form-label">Kategori</label>
                        <?= form_dropdown('id_category', getDropdownList('categories', ['id', 'title']), $input->id_category, ['class' => 'form-select', 'required' => true]) ?>
                        <?= form_error('id_category') ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <?= form_textarea(['name' => 'description', 'value' => $input->description, 'class' => 'form-control', 'id' => 'description', 'rows' => 4, 'required' => true]) ?>
                        <?= form_error('description') ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <br />
                        <div class="form-check form-check-inline">
                            <?= form_radio(['name' => 'is_available', 'value' => 1, 'checked' => $input->is_available == 1 ? true : false, 'class' => 'form-check-input', 'id' => 'stockIn']) ?>
                            <label class="form-check-label" for="stockIn">Tersedia</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= form_radio(['name' => 'is_available', 'value' => 0, 'checked' => $input->is_available == 0 ? true : false, 'class' => 'form-check-input', 'id' => 'stockOut']) ?>
                            <label class="form-check-label" for="stockOut">Kosong</label>
                        </div>
                        <?= form_error('is_available') ?>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <?= form_upload(['name' => 'image', 'class' => 'form-control', 'id' => 'image']) ?>

                        <?php if ($input->image): ?>
                            <img src="<?= base_url("images/products/$input->image") ?>" alt="" height="150" class="mt-2">
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const title = document.getElementById('title');
        const slug = document.getElementById('slug');

        if (title && slug) {
            title.addEventListener('input', function() {
                const titleValue = title.value;
                const slugValue = titleValue.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slug.value = slugValue;
            });
        }
    });
</script>