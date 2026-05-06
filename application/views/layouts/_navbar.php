<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
        <div class="container-fluid container-lg">
            <a class="navbar-brand h1" href="index.html">CIShop</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            id="dropdown-1"
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                            Manage
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown-1">
                            <li>
                                <a class="dropdown-item" href="/admin-category.html">Kategori</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/admin-product.html">Produk</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/admin-order.html">Order</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/admin-users.html">Pengguna</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="/cart.html" class="nav-link"><i class="fas fa-shopping-cart"></i> Cart (0)</a>
                    </li>
                    <?php if (!$this->session->userdata('is_login')) : ?>
                        <li class="nav-item">
                            <a href="<?= base_url('login') ?>" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('register') ?>" class="nav-link">Register</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item dropdown">
                            <a
                                id="dropdown-2"
                                class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <?= ucwords($this->session->userdata('name')) ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown-2">
                                <li>
                                    <a class="dropdown-item" href="/profile.html">Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/orders.html">Orders</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>