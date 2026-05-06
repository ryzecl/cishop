<main role="main" class="container">
    <?php $this->load->view('layouts/_alert'); ?>

    <div class="row">
        <div class="co-md-8" mx-auto>
            <div class="card">
                <div class="card-header h5">Formulir Login</div>
                <div class="card-body">
                    <?= form_open('login', ['method' => 'POST']) ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <?= form_input(['type' => 'email', 'name' => 'email', 'value' => $input->email, 'class' => 'form-control', 'required' => true, 'placeholder' => 'Masukkan Email Anda']) ?>
                        <?= form_error('email') ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <?= form_password('password', '', ['class' => 'form-control', 'required' => true, 'placeholder' => 'Masukkan Password']) ?>
                        <?= form_error('password') ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</main>