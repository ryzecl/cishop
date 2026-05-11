 <main role="main" class="container">
     <?php $this->load->view('layouts/_alert') ?>

     <div class="row">
         <div class="col-md-10 mx-auto">
             <div class="card">
                 <div class="card-header">
                     <span>Kategori</span>
                     <a
                         href="<?= base_url('category/create') ?>"
                         class="btn btn-sm btn-secondary">Tambah</a>
                     <div class="float-end">
                         <form action="<?= base_url('category') ?>" method="GET">
                             <div class="input-group">
                                 <input
                                     type="text"
                                     name="keyword"
                                     value="<?= $this->input->get('keyword') ?>"
                                     class="form-control form-control-sm"
                                     placeholder="Cari" />
                                 <button type="submit" class="btn btn-sm btn-primary">
                                     <i class="fas fa-search"></i>
                                 </button>
                                 <a
                                     href="<?= base_url('category') ?>"
                                     class="btn btn-sm btn-primary">
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
                                 <th>#</th>
                                 <th>Kategori</th>
                                 <th>Slug</th>
                                 <th></th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php $no = 0;
                                foreach ($content as $row): $no++; ?>
                                 <tr>
                                     <td><?= $no ?></td>
                                     <td><?= $row->title ?></td>
                                     <td><?= $row->slug ?></td>
                                     <td>
                                         <?= form_open(base_url("category/delete/$row->id"), ['method' => 'POST']) ?>
                                         <a
                                             href="<?= base_url("category/edit/$row->id") ?>"
                                             class="btn btn-sm text-info">
                                             <i class="fas fa-edit"></i>
                                         </a>
                                         <button
                                             type="submit"
                                             class="btn btn-sm text-danger"
                                             onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');">
                                             <i class="fas fa-trash"></i>
                                         </button>
                                         <?= form_close() ?>
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