<main role="main" class="container">
    <?php $this->load->view('layouts/_alert') ?>

    <div class="row">
        <div class="co-md-8" mx-auto>
            <div class="card">
                <div class="card-header h5">Formulis Registrasi</div>
                <div class="card-body">
                    <?= form_open('register', ['method' => 'POST']) ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <?= form_input(['type' => 'text', 'name' => 'name', 'value' => $input->name, 'class' => 'form-control', 'required' => true, 'autofocus' => true, 'placeholder' => 'Masukkan Nama Anda']) ?>
                        <?= form_error('name') ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <?= form_input(['type' => 'email', 'name' => 'email', 'value' => $input->email, 'class' => 'form-control', 'required' => true, 'placeholder' => 'Masukkan Email Anda']) ?>
                        <?= form_error('email') ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <?= form_password('password', '', ['class' => 'form-control', 'required' => true, 'placeholder' => 'Masukkan Password minimal 8 karakter']) ?>
                        <?= form_error('password') ?>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Konfirmasi Password</label>
                        <?= form_password('password_confirmation', '', ['class' => 'form-control', 'required' => true, 'placeholder' => 'Masukkan Konfirmasi Password']) ?>
                        <?= form_error('password_confirmation') ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</main>