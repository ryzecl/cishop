<main role="main" class="container">
    <?php $this->load->view('layouts/_alert') ?>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span>Produk</span>
                    <a
                        href="/admin-product-form.html"
                        class="btn btn-sm btn-secondary">Tambah</a>
                    <div class="float-end">
                        <form action="">
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    placeholder="Cari" />
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="/admin-product.html" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            foreach ($content as $row): $no++; ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td>
                                        <p>
                                            <img
                                                src="<?= $row->image ? base_url('assets/images/products/' . $row->image) : 'https://ui-avatars.com/api/?name=' . urlencode($row->product_title) ?>"
                                                alt="<?= $row->product_title ?>"
                                                class="me-2" width="70" height="70" />
                                            <?= $row->product_title ?>
                                        </p>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-primary text-decoration-none"><i class="fas fa-tags"></i> <?= $row->category_title ?></span>
                                    </td>
                                    <td>Rp <?= number_format($row->price, 0, ',', '.') ?></td>
                                    <td><?= $row->is_available ? 'Tersedia' : 'Tidak Tersedia' ?></td>
                                    <td>
                                        <form action="">
                                            <a
                                                href="/admin-product-form.html"
                                                class="btn btn-sm text-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button
                                                type="submit"
                                                class="btn btn-sm text-danger"
                                                onclick="return confirm('Are you sure?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <?= $pagination ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</main>