<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($title) ? $title : "CIShop" ?> - Codeigniter E-Commerce</title>

    <link
        rel="stylesheet"
        href="/assets/libs/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/libs/fontawesome/css/all.min.css" />
</head>

<body>
    <!-- Navbar -->
    <?php $this->load->view('layouts/_navbar') ?>
    <!-- End Navbar -->

    <!-- Content -->
    <?php $this->load->view($page); ?>
    <!-- End Content -->

    <script src="assets/libs/jquery/jquery-4.0.0.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>

</html>