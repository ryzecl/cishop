<main role="main" class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Formulir Kategori</div>
                <div class="card-body">
                    <?= form_open($form_action, ['method' => 'POST']) ?>
                    <?= isset($input->id) ? form_hidden('id', $input->id) : '' ?>
                    <div class="mb-3">
                        <label for="title" class="form-label">Kategori</label>
                        <?= form_input(['name' => 'title', 'value' => $input->title, 'class' => 'form-control', 'id' => 'title', 'required' => true, 'autofocus' => true]) ?>
                        <?= form_error('title') ?>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <?= form_input(['name' => 'slug', 'value' => $input->slug, 'class' => 'form-control', 'id' => 'slug', 'required' => true]) ?>
                        <?= form_error('slug') ?>
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